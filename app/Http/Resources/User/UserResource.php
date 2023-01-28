<?php

namespace App\Http\Resources\User;

use App\Http\Requests\Auth\RegisterRequest;
use App\Helpers\CodeVerifyHelper;
use App\Models\User;
use App\Notifications\Auth\VerifyCodeNotification;
use App\Notifications\Auth\RegisterNotification;


class UserResource
{
    /**
     * @param string $email
     *
     * @return \App\Models\User
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param string $username
     *
     * @return \App\Models\User
     */
    public function findByUsername(string $username)
    {
        return User::where('username', $username)->first();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\User
     */
    public function findById(string $id)
    {
        return User::where('id', $id)->first();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\User
     */
    public function verifyCode(int $code, string $email)
    {
        return User::where('verification_code', $code)->where('email', $email)->first();
    }

    /**
     * Register new user
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \App\Models\User
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $sponsor = isset($validated['sponsor_username']) ? $this->findByUsername($request->sponsor_username) : null;

        $code = CodeVerifyHelper::generateCode();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'password' => bcrypt($validated['password']),
            'verification_code' => $code,
            'sponsor_id' => ($sponsor) ? $sponsor->id : null
        ]);

        if (!$user) {
            throw new \Exception('Não foi possível se registrar. Tente novamente!');
        }

        // $user->notify(new RegisterNotification($user));
        // $user->notify(new VerifyCodeNotification($user->id, $code));

        return $user;
    }
}
