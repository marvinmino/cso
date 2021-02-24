<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use \Auth;
use App\Http\Resources\UserAuthResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserEmailRequest;
use Illuminate\Http\Response;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->userService->save($request->all());
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new UserAuthResource($user);
    }


    /**
     *
     * @param Request $request
     * @return void
     */
    public function resetEmail(UserEmailRequest $request)
    {
        try {
            $this->userService->sendResetMail($request->all());
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('User not Found', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('success', Response::HTTP_ACCEPTED);
    }

    /**
     *
     * @param Request $request
     * @return void
     */
    public function reset(UserResetPasswordRequest $request)
    {
        try {
            $this->userService->reset($request['token'], $request['password']);
            return $this->successResponse('success', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('User not Found', Response::HTTP_NOT_FOUND);
        }
    }
    

    public function show(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('User not Found', Response::HTTP_NOT_FOUND);
        }
        return new UserResource($user);
    }

    /**
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->delete();
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('User not Found', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @return void
     */
    public function index(Request $request)
    {
        $users = User::where('username', $request->username)->paginate(10);
        if (isset($request->username)) {
            $users = User::where('username', $request->username)->first;
        }
        return UserResource::collection($users);
    }

    /**
     *
     * @param UserUpdateRequest $request
     * @return void
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            $user = User::where('id', $request->id)->update([
                'username' => $request->username,
                'email'    => $request->email
                ]);
            return $this->successResponse('User updated', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @param UserUpdateRequest $request
     * @return void
     */
    public function updatePassword(UserResetPasswordRequest $request)
    {
        try {
            $user = User::where('id', Auth::user()->id)->update([
                'password' => $request->username]);
            return $this->successResponse('Password reset', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->errorResponse('System error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
