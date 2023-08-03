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
use Carbon\Carbon;

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
  public function getNewUsers()
  {
    $user = User::with([
      'compliance'
    ])->whereHas('compliance', function ($query) {
      $query->where('status_id', 1);
    })->get();

    return $user;
  }
  /**
   * @param int $id
   *
   * @return \App\Models\User
   */
  // 2. Crie o método no seu controlador para obter a contagem de usuários por mês.
  public function managerMonth()
  {
    // Defina os tipos de usuário que você deseja filtrar.
    $userType = ['user', 'mananger', 'advisor'];

    // Obter a data atual.
    $date = Carbon::now();

    // Criar um array para armazenar os resultados.
    $result = [];

    // Loop para obter os últimos 6 meses, a partir da data atual até 6 meses atrás.
    for ($i = 0; $i <= 5; $i++) {
      // Subtrair o número de meses do mês atual.
      $monthAgo = $date->copy()->subMonths($i);

      // Obter o primeiro dia do mês.
      $firstDayMonth = $monthAgo->copy()->startOfMonth();

      // Obter o último dia do mês.
      $lastDayMonth = $monthAgo->copy()->endOfMonth();

      // Loop para obter as contagens de usuários por tipo.
      foreach ($userType as $type) {
        // Consulta para obter a contagem de usuários do tipo específico que entraram no mês.
        $count = User::where('type', $type)
          ->whereBetween('created_at', [$firstDayMonth, $lastDayMonth])
          ->count();

        // Armazenar o resultado no array.
        $result[$type][$monthAgo->format('Y-m')] = $count;
      }
    }

    // Retornar os resultados para a visualização ou fazer outras operações.
    return $result;
  }


  /**
   * @param int $id
   *
   * @return \App\Models\User
   */
  public function getClients()
  {
    $user = User::with([
      'address' => function ($query) {
        $query->select('user_id', 'cep', 'street', 'neighborhood', 'city', 'state', 'number', 'complement');
      }, 'sponsor' => function ($query) {
        $query->select('id', 'name', 'username', 'email', 'phone', 'type');
      },'clients', 'userPlan' => function ($query) {
        $query->select('plan_id', 'coin_id', 'user_id', 'amount', 'income', 'acting', 'activated_at', 'payment_voucher_url', 'withdrawal_report');
      }, 'userPlan.dataPlan' => function ($query) {
        $query->select('id', 'name', 'rescue', 'porcent');
      }, 'userPlan.coin' => function ($query) {
        $query->select('id', 'name', 'symbol', 'price_brl', 'price_usd');
      }, 'plan' => function ($query) {
        $query->select('user_id', 'token', 'amount', 'income', 'acting', 'activated_at', 'payment_voucher_url', 'withdrawal_report', 'created_at');
      }, 'creditBalance' => function ($query) {
        $query->select('user_id', 'coin_id', 'balance_enable', 'balance_placed', 'income');
      }, 'credits' => function ($query) {
        $query->select('user_id', 'uuid', 'amount', 'base_amount', 'description', 'type_id', 'created_at');
      }, 'financial' => function ($query) {
        $query->select('user_id', 'data', 'type');
      },
    ])
      ->whereHas('userPlan', function ($query) {
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
      throw new \Exception('Não foi possível atualizar o cliente. Tente novamente!', 403);
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
      'name', 'email', 'username', 'phone', 'genre_id'
    ];

    foreach ($fillableFields as $field) {
      if (array_key_exists($field, $data)) {
        $user->$field = $data[$field];
      }
    }

    if (array_key_exists('address', $data) && is_array($data['address'])) {
      $user->address->update($data['address']);
    }

    $user->save();

    return $user;
  }
}
