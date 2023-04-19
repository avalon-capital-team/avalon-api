<?php

namespace App\Models\Deposit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositStatus extends Model
{
    use HasFactory;
    /**
     * table
     *
     * @var string
     */
    protected $table = 'deposits_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'color',
        'icon',
    ];
}
