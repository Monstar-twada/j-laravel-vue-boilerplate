<?php

namespace App\Http\Controllers\Auth;

//use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Controller;
use Password;
use Illuminate\Http\Request;
use App\Mail\UserForgotPassword;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    //use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Send Reset link Via Email
     *
     * @param Request $request
     * @return response
     */
    public function sendResetLink(Request $request)
    {

        $user = Password::broker()->getUser([  'email' => $request->only('email')]);

        if (is_null($user)) {
            return response()->json([
                'message' => 'Please enter a valid email address',
                'display' => 'notification|error',
            ],500);
        }
        \Mail::send(new UserForgotPassword($user));

        return response()->json([
            'message' =>
            'A link to the password reset page has been sent to the registered email address',
            'display' => 'notification|success',
        ],200);


    }

}
