<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * 指定与模型关联的表，默认情况下可不指定
     * @var string
     */
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

    /**
     * 用于生成用户的头像
     * @param  string $size 头像默认值
     * @return [type]       [description]
     */
    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //boot 方法会在用户模型类完成初始化之后进行加载
    public static function boot(){
        parent::boot();
        //creating 用于监听模型被创建之前的事件，created 用于监听模型被创建之后的事件
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }




}
