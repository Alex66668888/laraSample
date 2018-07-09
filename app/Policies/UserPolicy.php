<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 用户更新授权策略
     * @param  User   $currentUser 默认为当前登录用户实例
     * @param  User   $user        要进行授权的用户实例
     * @return [type]              [description]
     */
    public function update(User $currentUser, User $user){
        return $currentUser->id === $user->id;
    }

    /**
     * 删除用户操作授权策略
     * @param  User   $currentUser [description]
     * @param  User   $user        [description]
     * @return [type]              [description]
     */
    public function destroy(User $currentUser, User $user){
        //只有当前用户拥有管理员权限且删除的用户不是自己时才显示链接
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }


}
