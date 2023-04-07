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
    public function getBySponsorshipId(string $id)
    {
        return User::where('sponsor_id', $id)->get();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\User
     */
    public function getUsersType()
    {
        $data['manangers'] = User::where('type', 'mananger')->select('name', 'username', 'email')->get();
        $data['advisors'] = User::where('type', 'advisor')->select('name', 'username', 'email')->get();

        return $data;
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
     * * @throws \Exception
     */
    public function verifyCode(int $code, string $email)
    {
        return User::where('verification_code', $code)->where('email', $email)->first();
    }

    /**
     * Register new user
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest $request
     * @return \App\Models\User
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $sponsor = isset($validated['sponsor_username']) ? $this->findByUsername($request->sponsor_username) : null;

        $code = CodeVerifyHelper::generateCode();

        $user = User::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'verification_code' => $code,
            'sponsor_id' => ($sponsor) ? $sponsor->id : null
        ]);

        if (!$user) {
            throw new \Exception('Não foi possível se registrar. Tente novamente!');
        }

        $user->notify(new RegisterNotification($user->id));
        $user->notify(new VerifyCodeNotification($user->id, $code));

        return $user;
    }

    /**
     * Register user indicad
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest $request
     * @return \App\Models\User
     * @throws \Exception
     */
    public function setIndicatUsername($username, $user)
    {
        $sponsor = $this->findByUsername($username);
        if (!$sponsor) {
            throw new \Exception('Username do indicador nao existe. Tente novamente!');
        }

        $user->sponsor_id = $sponsor->id;
        $user->save();
        if (!$user) {
            throw new \Exception('Não foi possível cadastrar o indicador. Tente novamente!');
        }


        return $user;
    }
}
