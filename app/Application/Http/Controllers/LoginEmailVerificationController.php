<?php

namespace App\Application\Http\Controllers;


use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;
use PharIo\Manifest\Application;

class LoginEmailVerificationController extends Controller
{
    private $otp;

    public function __construct(Otp $Otp)
    {
        $this->otp = $Otp;
    }


    public function GetLoginOtp(Request $request)
    {
        /* generate otp */
        $otp = $this->otp->generate($request->mobile, 6, 15);

        /* you and send OTP via sms or email  */
        $smsOrEmailMessage = 'Use this code for login : ' . $otp->token;

        return response([
            'success' => $otp->status,
            'message' => $otp->message
        ]);
    }

    public function loginOtp(Request $request)
    {
        /* validate otp */
        $otp = $this->otp->validate($request->mobile, $request->otp);

        if ($otp->status) {
            // add your action code here
        }


        /* json response */
        return response([
            'success' => $otp->status,
            'message' => $otp->message
        ]);
    }
}
