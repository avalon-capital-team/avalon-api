<?php

namespace App\Http\Resources\User;

use App\Http\Requests\Auth\RegisterRequest;
use App\Helpers\CodeVerifyHelper;
use App\Models\Plan\Plan;
use App\Models\User;
use App\Models\User\UserAddress;
use App\Models\User\UserProfile;
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
    $users = User::get();

    return $users;
  }

  /**
   * @param int $id
   *
   * @return \App\Models\User
   */
  public function getClients()
  {
    $user = User::with(['address' => function ($query) {
      $query->select('user_id', 'cep', 'street', 'neighborhood', 'city', 'state', 'number', 'complement');
    }, 'sponsor' => function ($query) {
      $query->select('id', 'name', 'username', 'email', 'phone', 'type');
    }, 'userPlan' => function ($query) {
      $query->select('plan_id','coin_id','user_id', 'amount', 'income', 'acting', 'activated_at', 'payment_voucher_url', 'withdrawal_report');
    }, 'userPlan.dataPlan' => function ($query) {
      $query->select('id', 'name', 'rescue', 'porcent');
    }, 'userPlan.coin' => function ($query) {
      $query->select('id', 'name', 'symbol', 'price_brl', 'price_usd');
    }, 'plan' => function ($query) {
      $query->select('user_id','token', 'amount', 'income', 'acting', 'activated_at', 'payment_voucher_url', 'withdrawal_report');
    },])->whereHas('userPlan', function ($query) {
      $query->where('acting', 1);
    })->get();

    return $user;
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
      throw new \Exception('Não foi possível se registrar. Tente novamente!', 403);
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
      throw new \Exception('Username do indicador nao existe. Tente novamente!', 403);
    }

    $user->sponsor_id = $sponsor->id;
    $user->save();
    if (!$user) {
      throw new \Exception('Não foi possível cadastrar o indicador. Tente novamente!', 403);
    }


    return $user;
  }

  /**
   * Updata User Type
   *
   * @param  \App\Http\Requests\Auth\RegisterRequest $request
   * @return \App\Models\User
   * @throws \Exception
   */
  public function updateUserType(User $user, $type)
  {
    if (!$user) {
      throw new \Exception('Não foi possível cadastrar o indicador. Tente novamente!', 403);
    }

    $user->type = $type;
    $user->save();

    return $user;
  }

  /**
   * Updata User Type
   *
   * @param  \App\Http\Requests\Auth\RegisterRequest $request
   * @return \App\Models\User
   * @throws \Exception
   */
  public function updateUserSponsor(User $user, User $sponsor)
  {
    if (!$user) {
      throw new \Exception('Não foi possível cadastrar o indicador. Tente novamente!', 403);
    }

    $user->sponsor_id = $sponsor->id;
    $user->save();

    return $user;
  }

  public function updateUser(User $user, array $data)
  {
    $fillableFields = [
      'name', 'email', 'document_type', 'document', 'username', 'phone',
      'sponsor_id', 'status_id', 'birth_date', 'genre_id', 'verification_code'
    ];

    foreach ($fillableFields as $field) {
      if (array_key_exists($field, $data)) {
        $user->$field = $data[$field];
      }
    }

    if (array_key_exists('profile', $data) && is_array($data['profile'])) {
      $user->profile->update($data['profile']);
    }

    if (array_key_exists('address', $data) && is_array($data['address'])) {
      $user->address->update($data['address']);
    }

    if (array_key_exists('security', $data) && is_array($data['security'])) {
      $user->security->update($data['security']);
    }

    if (array_key_exists('compliance', $data) && is_array($data['compliance'])) {
      $user->compliance->update($data['compliance']);
    }

    if (array_key_exists('financial', $data) && is_array($data['financial'])) {
      // Considerando que 'financial' é uma relação one-to-many ou many-to-many.
      foreach ($data['financial'] as $financial) {
        // Aqui você deve encontrar a entrada de 'financial' correta e atualizá-la.
        // Vamos assumir que 'type' seja o identificador.
        $user->financial()->where('type', $financial['type'])->update($financial);
      }
    }

    if (array_key_exists('plan', $data) && is_array($data['plan'])) {
      // Considerando que 'plan' é uma relação one-to-many ou many-to-many.
      foreach ($data['plan'] as $plan) {
        // Aqui você deve encontrar a entrada do 'plan' correta e atualizá-la.
        // Vamos assumir que 'plan_id' seja o identificador.
        $user->plan()->where('plan_id', $plan['plan_id'])->update($plan);
      }
    }

    $user->save();

    return $user;
  }
}
