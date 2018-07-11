<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model{

  //指定可以进行正常更新的字段
  protected $fillable = ['content'];

  //指明一条微博属于一个用户
  public function user(){
    return $this->belongsTo(User::class);
  }




}
