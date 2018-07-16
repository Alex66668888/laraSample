<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Auth;

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

    //指明一个用户拥有多条微博
    public function statuses(){
        return $this->hasMany(Status::class);
    }

    /**
     * 将当前用户发布过的所有微博从数据库中取出
     * @return [type] [description]
     */
    public function feed(){
        // return $this->statuses()
        //             ->orderBy('created_at','desc');

        //通过 followings 方法取出所有关注用户的信息，再借助 pluck 方法将 id 进行分离并赋值给 user_ids
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        //将当前用户的 id 加入到 user_ids 数组中
        array_push($user_ids, Auth::user()->id);
        //使用 Laravel 提供的 查询构造器 whereIn 方法取出所有用户的微博动态并进行倒序排序；
        return Status::whereIn('user_id', $user_ids)
                              ->with('user')
                              ->orderBy('created_at', 'desc');
        //使用了 Eloquent 关联的 预加载 with 方法，预加载避免了 N+1 查找的问题

    }

    /**
     * 获取粉丝关系列表（看你作为博主有多少人关注了你）
     * @return [type] [description]
     */
    public function followers(){
        //第三个参数 user_id 是定义在关联中的模型外键名，而第四个参数 follower_id 则是要合并的模型外键名
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    /**
     * 获取用户关注人列表（看你作为粉丝你关注了多少人）
     * @return [type] [description]
     */
    public function followings(){
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * 进行关注
     * @param  [array] $user_ids 被关注的id数组
     * @return [type]           [description]
     */
    public function follow($user_ids){
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        //sync 方法会接收两个参数，第一个参数为要进行添加的 id，第二个参数则指明是否要移除其它不包含在
        //关联的 id 数组中的 id，true 表示移除,false 表示不移除，默认值为 true
        $this->followings()->sync($user_ids, false);
        //$user->followings()->attach([2, 3])方法也可以实现"关注"功能，但是不会过滤掉重复关注的数据
    }

    /**
     * 取消关注
     * @param  [array] $user_ids 被取消关注的id数组
     * @return [type]           [description]
     */
    public function unfollow($user_ids){
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        //detach 来对用户进行取消关注的操作
        $this->followings()->detach($user_ids);
    }

    /**
     * 当前登录的用户A是否关注了用户B
     * @param  [type]  $user_id [description]
     * @return boolean          [description]
     */
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }



}
