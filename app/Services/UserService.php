<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetMailable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user=$user;
    }

    public function save($data)
    {
        $user = $this->user->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'token'    => md5(rand(1, 10) . microtime())]);
        $user['access_token'] = $user->createToken('MyApp')->accessToken;
        return $user;
    }
    
    /**
     * Save User
     *
     * @param $data
     * @return user
     */
    public function delete($id)
    {
        $user = $this->user->where('id', $id)->first();
        if (!empty($user)) {
            $user->delete();
        } else {
            abort(500);
        }
    }

    /**
     * Update User
     *
     * @param $data
     * @return user
     */
    public function update($data, $id)
    {
        return $this->user->where('id', $id)->update($data);
    }

    /**
     *
     * @param  $token
     * @param  $password
     * @return void
     */
    public function reset($token, $password)
    {
        return  $this->user->where('token', $token)->update(['password'=>Hash::make($password)]);
    }

    public function sendResetMail($data)
    {
        $user = User::where('email', $data['email'])->firstOrFail();
        if (!empty($user)) {
            Mail::to($data['email'])->send(new ResetMailable(['token' => $user->token, 'email' => $data['email']]));
        }
    }
}
