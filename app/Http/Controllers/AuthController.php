<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginWebRequest;
use App\Http\Requests\RegisterWebRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\user_has_roles;
use App\Services\AddressService;
use App\Services\AssessmentService;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $assessmentService;

    protected $addressService;

    protected $invitationService;

    public function __construct(
        AssessmentService $assessmentService,
        AddressService $addressService,
        InvitationService $invitationService
    ) {
        $this->assessmentService = $assessmentService;
        $this->addressService = $addressService;
        $this->invitationService = $invitationService;
    }

    public function showRegistrationForm()
    {
        return view('dash.auth.register');
    }

    public function showRegistrationForm_web(Request $request)
    {
        $autoCapturedFromLink = $this->invitationService->hasIncomingCode($request);
        $journey = $this->invitationService->captureFromRequest($request);

        return view('pages.register', [
            'invitationState' => $this->invitationService->buildSnapshot($journey),
            'invitationAutoCaptured' => $autoCapturedFromLink,
            'invitationPublicEnabled' => $this->invitationService->publicEnabled(),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
        // return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function register_web(RegisterWebRequest $request)
    {
        $request->validated();

        $previousUrl = url()->previous();
        $postRegistrationDestination = $this->resolvePostRegistrationDestination($request, $previousUrl);
        $journey = $this->invitationService->resolveJourneyForRegistration(
            $request->input('invitation_journey_token'),
            $request->input('invitation_code'),
            $request->input('referral_source'),
            $request->input('referral_source_note')
        );

        try {
            DB::beginTransaction();

            $userData = [
                'first_name' => $request->first_name ?? '-',
                'last_name' => $request->last_name ?? '-',
                'email' => $request->email ?? '-',
                'phone' => $request->phone ?? '-',
                'company_name' => $request->company_name,
                'password' => Hash::make($request->password),
            ];

            $user = $this->createUser($userData);

            $userAddress = [
                'street_address' => $request->street_address ?? '-',
                //                'apartment_suite_unit' => $request->apartment_suite_unit ?? '-',
                'town_city' => $request->town_city ?? '-',
                'phone' => $request->phone ?? '-',
            ];

            $this->addressService->createAddress($userAddress, $user->id);

            // * If the final data is sent with the request, Come from assessment page

            $assessment = json_decode($request->input('final_data'), true);

            if ($request->filled('final_data') && ! empty($assessment)) {
                $this->assessmentService->createAssessment($assessment, $user->id);
            }

            if ($journey !== null) {
                $journey = $this->invitationService->attachRegisteredUser(
                    $journey,
                    $user,
                    $request->input('referral_source'),
                    $request->input('referral_source_note')
                );
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        // \Mail::to($user->email)->send(new \App\Mail\WelcomeUserMail($user));

        Auth::login($user);

        // * Request come from  the checkout page

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'user_id' => Auth::id(),
                'invitation' => $this->invitationService->buildSnapshot($journey),
            ]);
        }

        if ($postRegistrationDestination === 'checkout') {
            return redirect()->route('checkout.show')->with('success', 'Login successful!');
        }

        return redirect()->route('user-dashboard.dashboard')->with('success', 'Login successful!');
    }

    public function showLoginForm()
    {
        return view('dash.auth.login');
    }

    public function showLoginForm_web(Request $request)
    {
        $autoCapturedFromLink = $this->invitationService->hasIncomingCode($request);
        $journey = $this->invitationService->captureFromRequest($request);

        return view('pages.login', [
            'invitationState' => $this->invitationService->buildSnapshot($journey),
            'invitationAutoCaptured' => $autoCapturedFromLink,
            'invitationPublicEnabled' => $this->invitationService->publicEnabled(),
        ]);
    }

    public function postLoginWeb(LoginWebRequest $request)
    {
        $previousUrl = url()->previous();
        $postLoginDestination = $this->resolvePostLoginDestination($request, $previousUrl);

        $credentials = $request->validated();

        // Attempt authentication
        if (! Auth::attempt($credentials, $request->filled('remember'))) {
            return redirect()->back()
                ->with('error', 'Invalid credentials!')
                ->withInput();
        }

        $request->session()->regenerate();

        $this->invitationService->syncJourneyAfterLogin($request, Auth::user());

        $rolesId = [
            Role::ADMIN_ROLE,
            Role::NUTRITION_SPECIALIST_ROLE,
            Role::SALES_ROLE,
            Role::ACCOUNTANT_ROLE,
        ];

        if(user_has_roles::where('user_id', Auth::id())->whereIn('role_id', $rolesId)->exists()) {
            return redirect()->route('dashboard.home')->with('success', 'Login successful!');
        }

        // * If the final data is sent with the request, Come from assessment page

        $assessment = json_decode($request->input('final_data'), true);

        if ($request->filled('final_data') && ! empty($assessment)) {
            $this->assessmentService->createAssessment($assessment, Auth::id());
        }

        if ($postLoginDestination === 'checkout') {
            return redirect()->route('checkout.show')->with('success', 'Login successful!');
        }

        return redirect()->intended(route('user-dashboard.dashboard'))->with('success', 'Login successful!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }

    private function createUser($userData)
    {

        $user = User::create(attributes: [
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'company_name' => $userData['company_name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => $userData['password'],
        ]);

        return $user;
    }

    private function resolvePostRegistrationDestination(Request $request, string $previousUrl): string
    {
        if ($request->input('post_registration_destination') === 'checkout') {
            return 'checkout';
        }

        if (str_contains($previousUrl, '/checkout')) {
            return 'checkout';
        }

        $assessment = json_decode($request->input('final_data', ''), true);

        if (is_array($assessment) && ! empty($assessment)) {
            return 'checkout';
        }

        return 'default';
    }

    private function resolvePostLoginDestination(Request $request, string $previousUrl): string
    {
        if ($request->input('post_login_destination') === 'checkout') {
            return 'checkout';
        }

        if (str_contains($previousUrl, '/checkout') || str_contains($previousUrl, '/assessment')) {
            return 'checkout';
        }

        $assessment = json_decode($request->input('final_data', ''), true);

        if (is_array($assessment) && ! empty($assessment)) {
            return 'checkout';
        }

        return 'default';
    }

    public function showCompleteProfile()
    {
        return view('pages.complete-profile');
    }

    // public function completeProfile(Request $request)
    // {
    //     Auth::user()->update([
    //         'company_name' => $request->company_name,
    //     ]);

    //     $userAddress = [
    //         'street_address' => $request->street_address ?? '-',
    //         'apartment_suite_unit' => $request->apartment_suite_unit ?? '-',
    //         'town_city' => $request->town_city ?? '-',
    //         'phone' => Auth::user()->phone ?? '-',
    //         'postcode' => $request->postal_code ?? '-',
    //     ];

    //     $this->addressService->createAddress($userAddress, Auth::id());

    //     $subscriptionIds = session()->pull('subscription_ids', []);

    //     return redirect()->route('checkout.show', ['subscriptions' => $subscriptionIds])
    //         ->with('success', 'Profile completed successfully!');
    // }
}
