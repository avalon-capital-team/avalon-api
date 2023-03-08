<?php

namespace App\Models\Credit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditStatus extends Model
{
    use HasFactory;

    /**
    * table
    *
    * @var string
    */
    protected $table = 'credits_status';

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
