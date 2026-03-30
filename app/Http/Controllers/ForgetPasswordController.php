<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\RecoveryToken;
use App\Services\PasswordResetService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    protected $passwordResetService;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService;
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgetPasswordRequest $request)
    {
        try {
            $this->passwordResetService->handleReset($request->validated()['email']);

            return redirect()->route('get.password.forget')->with('status', 'Password reset email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('status', $e->getMessage());
        }
    }

    public function showResetForm($token)
    {
        $recoveryToken = RecoveryToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if (! $recoveryToken) {
            return redirect()->route('login_web')->with(['status' => 'Invalid or expired token.']);
        }

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(UpdatePasswordRequest $request)
    {
        $recoveryToken = RecoveryToken::where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if (! $recoveryToken) {
            return redirect()->route('login_web')->with(['status' => 'Invalid or expired token.']);
        }

        // Update password
        $user = $recoveryToken->user;
        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate token
        $recoveryToken->used = true;
        $recoveryToken->save();

        return redirect()->route('login_web')->with('status', 'Your password has been reset successfully.');
    }
}
