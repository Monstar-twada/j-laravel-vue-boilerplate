<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Entities\User;
use Illuminate\Http\Request;



class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function form($token)
    {
        if($token) {
            try {
                $reset_token = decrypt($token);
                $reset_token = preg_split("/^id=/", $reset_token);
            } catch(DecryptException $ex) {
                return redirect('not-found');
            }

            if(isset($reset_token[1])) {
                $user = User::find($reset_token[1]);
                if($user && $user->password_reset) {
                    return view('app');
                }
            }
        }
        return redirect('not-found');
    }

    /**
     * Reset Password
     *
     * @param Request $request
     * @return response
     */
    public function resetPass(Request $request)
    {
        $data = $request->all();
        if(isset($data['token'])) {
            try {
                $reset_token = decrypt($data['token']);
                $reset_token = preg_split("/^id=/", $reset_token);
            } catch(DecryptException $ex) {
                return response()->json([
                    'message' => 'Invalid Token',
                    'display' => 'notification|error',
                ],500);
            }

            if(isset($reset_token[1])) {
                $user = User::find($reset_token[1]);
                if($user && $user->password_reset) {
                        $this->resetPassword($user, $data['password']);
                        $user->password_reset = 0;
                        $user->password_reset_at = null;
                        $user->save();

                        return response()->json([
                            'message' => 'Successfully Reset Password',
                            'display' => 'notification|success',
                        ],200);
                }
            }
        }
        return response()->json([
            'message' => 'This link is no longer valid',
            'display' => 'notification|error',
        ],500);
    }
}
