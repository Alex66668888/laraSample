<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $user_ids = ['1','2','3'];
      //通过app()方法获取一个Faker容器实例
      $faker = app(Faker\Generator::class);

      $statuses = factory(Status::class)->times(100)->make()->each(function ($status) use ($faker, $user_ids) {
        //借助 randomElement 方法来取出用户 id 数组中的任意一个元素并赋值给微博的 user_id
          $status->user_id = $faker->randomElement($user_ids);
      });

      Status::insert($statuses->toArray());

    }
}
