<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class SessionsController extends Controller{

  public function __construct(){
    //用于指定一些只允许未登录用户访问的动作
    $this->middleware('guest',[
      'only' => ['create']
    ]);
  }

  /**
   * 用户登录页面
   * @return [type] [description]
   */
  public function create(){
    return view('sessions.create');
  }

  public function store(Request $request){

    $credentials = $this->validate($request,[
      'email' => 'required|email|max:255',
      'password' => 'required',
    ]);

    if(Auth::attempt($credentials,$request->has('remember'))){
      //该用户存在于数据库，且邮箱和密码相符合
      session()->flash('success','欢迎回来！');
      //使用了 Laravel 提供的 Auth::user() 方法来获取 当前登录用户 的信息，并将数据传送给路由。
      //intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，
      //当上一次请求记录为空时，跳转到默认地址上
      return redirect()->intended(route('users.show',[Auth::user()]));
    }else{
      session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
      return redirect()->back();
    }

  }

  public function destroy(){
    Auth::logout();
    session()->flash('success','您已成功退出！');
    return redirect('login');
  }




}
