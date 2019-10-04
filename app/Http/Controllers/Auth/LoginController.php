<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    /**
     * Authenticate User
     *
     * @param Request $request
     * @return array
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $type = $request->input('type');

        try {
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                $user = JWTAuth::toUser($token);
                //if($user->email == $credentials['email']){
                $user = new UserResource($user);
                if ($user->isActivated()) {
                    $role = $user->role;
                    if (!empty($type)) {
                        if ($role == $type)
                            return compact('token', 'user');
                    } else {
                        return compact('token', 'user');
                    }
                }
                //}
            }

            return response()->json('メールアドレスもしくはパスワードが正しくありません。', 401);
        } catch (JWTException $ex) {
            return response()->json('エラーが発生しました。!', 500);
        }
    }


    /**
     * Get user
     *
     * @param  void
     * @return User
     */
    public static function getUser()
    {
        try {
            $user = JWTAuth::parseToken()->toUser();
            if (!$user) {
                return $this->response->errorNotFound('User not found');
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $ex) {
            return $this->response->errorUnauthorized('Token is invalid');
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $ex) {
            return $this->response->errorUnauthorized('Token has expired');
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlackListedException $ex) {
            return $this->response->errorUnauthorized('Token is blacklisted');
        }

        return $user;
    }

    /**
     * Authenticate Any User Type
     *
     * @param Request $request
     * @return response
     */
    public function authenticateAny(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();
                //if($user->email == $credentials['email']){
                $user = new UserResource($user);
                return compact('token', 'user');
                //}
            }

            return response()->json('invalid email or password', 401);
        } catch (JWTAuthException $e) {
            return response()->json('server error', 500);
        }
    }


    /**
     * Get user
     *
     * @param  void
     * @return User
     */
    public function show(Request $request)
    {
        $data = $request->only('role');
        $user = $this->getUser();
        $role = $user->role;
        $user = new UserResource($user);

        if (!is_null($data['role']) && $data['role'] != $role) {
            return $this->response->errorUnauthorized("Invalid Credentials");
        }

        return response(compact('role', 'user'), 200);
    }

    /**
     * Refresh user token
     *
     * @param  void
     * @return User
     */
    public function refresh(Request $request)
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            throw new BadRequestHtttpException('Token not provided');
        }
        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }

        return response(compact('token'), 200);
    }
}
