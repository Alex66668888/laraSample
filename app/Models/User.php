<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * 只有包含在该属性中的字段才会被更新
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * 当我们需要对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
