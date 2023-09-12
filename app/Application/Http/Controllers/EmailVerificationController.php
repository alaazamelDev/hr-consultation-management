<?php

namespace App\Application\Http\Controllers;

use App\Domain\Models\CD\Customer;
use App\Http\Requests\EmailVerificationRequest;
use App\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use http\Env\Response;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class EmailVerificationController extends Controller
{
    private Otp $otp;

    #[Pure] public function __construct()
    {
        $this->otp = new Otp;
    }

    public function emailVerification(EmailVerificationRequest $request): JsonResponse
    {
        // get the otp from the request
        $otp = $request->otp;

        // get the customer email using the token
        $customerId = $request->user()->id;
        $customer = Customer::query()
            ->where('id', $customerId)
            ->first();
        $email = $customer->email;

        // validate the otp
        $otp2 = $this->otp->validate($email, $otp);

        // if the otp is not valid (expired or wrong)
        if (!$otp2->status) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 400);
        }

        // if the otp is valid, update the customer email_verified_at
        $customer->email_verified_at = Carbon::now();
        $customer->save();

        return response()->json([
            'message' => 'Email verified successfully'
        ]);
    }

    public function resendEmailVerification(Request $request): JsonResponse
    {
        $request->user()->notify(new EmailVerificationNotification());
        return response()->json([
            'message' => 'OTP resent successfully'
        ]);
    }
}
