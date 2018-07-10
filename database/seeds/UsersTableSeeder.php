<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //使用该方法来创建 50 个假用户
      $users = factory(User::class)->times(50)->make();
      User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

      //对第一位用户的信息进行了更新，方便后面我们使用此账号登录
      $user = User::find(1);
      $user->name = 'Alex';
      $user->email = '1414818093@qq.com';
      $user->password = bcrypt('123456');
      $user->is_admin = true;
      $user->activated = true;
      $user->save();


    }
}
