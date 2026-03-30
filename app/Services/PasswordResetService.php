<?php

namespace App\Services;

use App\Models\RecoveryToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function handleReset($email)
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return true; // Indicate that no email was found (no throttling for invalid emails)
        }

        // Check valid, unused tokens
        $validTokens = RecoveryToken::where('user_id', $user->id)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->where('not_valid', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // If 3 or more valid tokens exist, check the oldest token's creation time
        if ($validTokens->count() >= 3) {
            $oldestToken = $validTokens->last(); // Last in descending order = oldest
            if (Carbon::parse($oldestToken->created_at)->diffInMinutes(Carbon::now()) < 15) {
                // Less than 15 minutes have passed since the oldest of the 3 tokens
                throw new \Exception('You have reached the limit of 3 reset requests. Please wait 15 minutes or use an existing link.');
            }
            // If 15 minutes have passed, we can proceed (old tokens will be invalidated below)
        }

        // Invalidate old valid tokens
        $this->invalidateExistingTokens($user);

        // Generate new token
        $token = Str::random(64);
        RecoveryToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);

        // Send email
        Mail::send('emails.reset-password', ['token' => $token, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Easy Cook - Password Reset');
        });

        return true;
    }

    private function invalidateExistingTokens($user)
    {
        $validTokens = $user->recoveryToken()->validToken()->get();
        foreach ($validTokens as $token) {
            $token->update(['not_valid' => true]);
        }
    }
}
