<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Send OTP to user's email
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in user table with 10 minutes expiration
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP via email
        try {
            Mail::send('emails.forgot-password-otp', ['otp' => $otp, 'user' => $user], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Password Reset OTP - EduAcademy');
            });

            return response()->json([
                'message' => 'OTP has been sent to your email address',
                'success' => true
            ], 200);
        } catch (\Swift_TransportException $e) {
            // SMTP specific error
            \Log::error('SMTP Error sending OTP: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send OTP. Please check your email configuration.',
                'error' => config('app.debug') ? $e->getMessage() : 'SMTP configuration error'
            ], 500);
        } catch (\Exception $e) {
            // General error
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send OTP. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->otp) {
            return response()->json([
                'message' => 'Invalid OTP',
                'errors' => ['otp' => ['The OTP is incorrect']]
            ], 422);
        }

        // Check if OTP matches
        if ($user->otp != $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP',
                'errors' => ['otp' => ['The OTP is incorrect']]
            ], 422);
        }

        // Check if OTP is expired
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'message' => 'OTP has expired. Please request a new one.',
                'errors' => ['otp' => ['The OTP has expired']]
            ], 422);
        }

        return response()->json([
            'message' => 'OTP verified successfully',
            'success' => true
        ], 200);
    }

    /**
     * Reset password with OTP
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->otp) {
            return response()->json([
                'message' => 'Invalid OTP',
                'errors' => ['otp' => ['The OTP is incorrect']]
            ], 422);
        }

        // Check if OTP matches
        if ($user->otp != $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP',
                'errors' => ['otp' => ['The OTP is incorrect']]
            ], 422);
        }

        // Check if OTP is expired
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'message' => 'OTP has expired. Please request a new one.',
                'errors' => ['otp' => ['The OTP has expired']]
            ], 422);
        }

        // Update user password
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'message' => 'Password has been reset successfully',
            'success' => true
        ], 200);
    }
}
