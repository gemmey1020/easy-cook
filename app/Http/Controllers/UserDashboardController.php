<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\StoreAssessmentRequest;
use App\Models\Subscription;
use App\Services\AssessmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->comeFromAssessment($request);

        return view('user-dashboard.dashboard');
    }
       public function index(Request $request)
    {
         $this->comeFromAssessment($request);

        return view('user-dashboard.dashboard');
    }

    public function profile()
    {
        $user = auth()->user()->load([
            'addresses',
        ]);

        return view('user-dashboard.user-profile', [
            'addresses' => $user->addresses,
        ]);
    }

    public function userProfileUpdate(Request $request, $id)
    {
        if (auth()->id() != $id) {
            return redirect()->route('user-dashboard.profile')->withErrors(['error' => 'You are not authorized to update this profile.']);
        }

        auth()->user()->update($request->all());

        return redirect()->route('user-dashboard.profile')->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {

        $user = auth()->user()->load([
            'subscriptions',
        ]);

        return view('user-dashboard.orders_history', [
            'orders' => $user->subscriptions,
        ]);
    }

    public function subscriptions()
    {
        $user = auth()->user()->load([
            'subscriptions' => function ($query) {
                $query->whereNot('status', Subscription::STATUS_PENDING);
            },
        ]);

        $subscriptions = $user->subscriptions;

        if ($subscriptions->isNotEmpty()) {
            $user->subscriptions = GeneralHelper::transformUserSubscriptionsWithProgress($user->subscriptions);
        }

        return view('user-dashboard.subscriptions', [
            'subscriptions' => $user->subscriptions,
        ]);
    }

    public function mealsTracking()
    {
        $user = auth()->user()->load([
            'addresses',
            'assessments',
            'subscriptions' => function ($q) {
                $q->where('status', '!=', 'pending');
            },
            // وبنحمّل المكونات الفرعية بعد الفلترة
            'subscriptions.dailySelectedMeals',
        ]);

        $user->subscriptions = GeneralHelper::transformUserSubscriptionsWithProgress($user->subscriptions);

        return view('user-dashboard.meals_tracking', [
            'assessments' => $user->assessments,
            'addresses' => $user->addresses,
            'subscriptions' => $user->subscriptions,
            'dailySelectedMeals' => $user->subscriptions->flatMap(function ($subscription) {
                return $subscription->dailySelectedMeals;
            }),
        ]);
    }

    public function getMealsData(Request $request, $subscriptionId)
    {
        $subscription = auth()->user()
            ->subscriptions()
            ->with('dailySelectedMeals.variant.nutrition')
            ->with('dailySelectedMeals.variant.translations')
            ->findOrFail($subscriptionId);

        $meals = $subscription->dailySelectedMeals;
        // return($meals);
        // Apply filter based on request
        $filter = $request->input('filter_range', 'all');
        $today = now()->startOfDay();

        if ($filter === 'day') {
            $meals = $meals->filter(fn ($meal) => \Carbon\Carbon::parse($meal->day)->isSameDay($today));
        } elseif ($filter === 'week') {
            $startOfWeek = $today->copy()->startOfWeek(Carbon::SATURDAY);
            $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::FRIDAY);
            $meals = $meals->filter(fn ($meal) => \Carbon\Carbon::parse($meal->day)->between($startOfWeek, $endOfWeek));
        } elseif ($filter === 'month') {
            $meals = $meals->filter(fn ($meal) => \Carbon\Carbon::parse($meal->day)->isSameMonth($today));
        }

        return DataTables::of($meals)
            ->addColumn('date', function ($meal) {
                $day = \Carbon\Carbon::parse($meal->day);

                return '<div class="text-center"><div class="fw-bold">'.$day->format('M d, Y').'</div><small class="text-muted">'.$day->format('D').'</small></div>';
            })

            ->addColumn('meal', function ($meal) {
                $title = optional($meal->variant->translations->where('locale', 'en')->first())->title ?? 'No Title';
                $image = $meal->variant->image ?? 'https://via.placeholder.com/80x60?text=Meal';

                return '<div class="d-flex align-items-center justify-content-center">
                        <img src="'.$image.'" class="meal-image me-3" style="width:60px;height:40px;object-fit:cover;" />
                        <div class="text-start"><div class="meal-name">'.$title.'</div></div>
                    </div>';
            })

            ->addColumn('type', function ($meal) {
                $type = $meal->variant->meal_type ?? $meal->meal_type ?? 'N/A';

                return '<div >'.ucfirst($type).'</div>';
            })
            ->addColumn('macros', function ($meal) {
                $n = $meal->variant->nutrition;

                return '
            <div class="macros-container">
                <div class="macro-item"><div class="macro-value">'.($n->protein ?? 0).'g</div><div class="macro-label">Pro</div></div>
                <div class="macro-item"><div class="macro-value">'.($n->carb ?? 0).'g</div><div class="macro-label">Carb</div></div>
                <div class="macro-item"><div class="macro-value">'.($n->fat ?? 0).'g</div><div class="macro-label">Fat</div></div>
            </div>';
            })
            ->addColumn('calories', function ($meal) {
                $kcal = $meal->variant->nutrition->kcal ?? 0;

                return '<div class="d-flex align-items-center justify-content-center"><i class="fas fa-fire text-warning me-1"></i><strong>'.$kcal.'</strong></div>';
            })
            ->addColumn('status', function ($meal) {
                $status = strtolower($meal->status);
                $icon = $status === 'completed' ? 'check' : 'clock';
                $text = ucfirst($status);

                return '<span class="status-badge status-'.$status.'"><i class="fas fa-'.$icon.' me-1"></i>'.$text.'</span>';
            })
            ->rawColumns(['date', 'meal', 'type', 'macros', 'calories', 'status'])
            ->make(true);
    }

    public function macronutrients()
    {
        $user = auth()->user()->load([
            'addresses',
            'assessments',
            'subscriptions.dailySelectedMeals',
        ]);

        $user->subscriptions = GeneralHelper::transformUserSubscriptionsWithProgress($user->subscriptions);

        return view('user-dashboard.macro_nutrients', [
            'assessments' => $user->assessments,
            'addresses' => $user->addresses,
            'subscriptions' => $user->subscriptions,
        ]);
    }

    private function comeFromAssessment($request)
    {
        if ($request->input('final_data')) {
            $data = json_decode($request->input('final_data'), true);
            $validator = Validator::make($data, (new StoreAssessmentRequest)->rules());
            (new AssessmentService)->createAssessment($validator->validated(), auth()->id());
        }
    }

    public function assessment(){
        $user = auth()->user()->load([
            'assessments.notes',
        ]);

        return view('user-dashboard.assessment', [
            'user' => $user
        ]);
    }

}
