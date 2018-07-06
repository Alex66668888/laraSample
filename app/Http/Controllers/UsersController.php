<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class UsersController extends Controller{
  public function create(){
    return view('users.create');
  }

  public function show(User $user){
    return view('users.show', compact('user'));
  }

  /**
   * 用于处理用户创建的相关逻辑
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function store(Request $request){
    $this->validate($request,[
      'name' => 'required|min:3|max:50',
      //unique:users 针对users表做邮箱的唯一性验证
      'email' => 'required|email|unique:users|max:255',
      'password' => 'required|confirmed|min:6'
    ]);

    //如果需要获取用户输入的所有数据:$data = $request->all();
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
    ]);

    //显示提示信息
    session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
    return redirect()->route('users.show',[$user]);


  }





}