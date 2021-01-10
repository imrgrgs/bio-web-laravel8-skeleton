<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use Throwable;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\LoginUserAPIRequest;

class AuthController extends AppBaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserAPIRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->sendError(
                __('messages.unauthorized', ['model' => __('models/users.singular')])
            );
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user =  auth()->user();
            if (!$user) {
                return $this->sendError(
                    __('messages.unauthorized', ['model' => __('models/users.singular')])
                );
            }
        } catch (Throwable $exception) {
            dd($exception);
            return $this->sendError(
                __('messages.unauthorized', ['model' => __('models/users.singular')])
            );
        }

        return $this->sendResponse(
            new UserResource($user),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        try {
            auth()->logout();
        } catch (JWTException $exception) {
            return $this->sendError(
                __('messages.unauthorized', ['model' => __('models/users.singular')])
            );
        }


        return $this->sendSuccess(__('messages.successfully_logged_out', ['model' => __('models/users.singular')]));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return $this->sendResponse(
            new UserResource([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
            ]),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }
}
