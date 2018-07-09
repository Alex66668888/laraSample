<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller{

  public function __construct(){
    $this->middleware('auth',[
      // 除了show、create、store方法外，其他都需要Auth中间件过滤
      'except' => ['show', 'create', 'store', 'index']
    ]);
    $this->middleware('guest',[
      //只让未登录用户访问注册页面
      'only' => ['create']
    ]);
  }

  /**
   * 所有用户列表
   * @return [type] [description]
   */
  public function index(){
    //$users = User::all();
    $users = User::paginate(10);
    return view('users.index',compact('users'));
  }


  /**
   * 用户注册页面
   * @return [type] [description]
   */
  public function create(){
    return view('users.create');
  }

  //由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user
  //会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应
  //的用户模型实例。
  public function show(User $user){
    //将用户对象 $user 通过 compact 方法转化为一个关联数组
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

    //用户注册成功自动登录
    Auth::login($user);

    //显示提示信息
    session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
    //等同于 return redirect()->route('users.show', [$user->id]);
    return redirect()->route('users.show',[$user]);


  }

  /**
   * 编辑资料页面
   * @param  User   $user [description]
   * @return [type]       [description]
   */
  public function edit(User $user){
    //这里 update 是指授权类里的 update 授权方法，$user 对应传参 update 授权方法的第二个参数
    $this->authorize('update', $user);
    return view('users.edit',compact('user'));
  }


  /**
   * 编辑资料post处理
   * @param  User    $user    自动解析用户 id 对应的用户实例对象
   * @param  Request $request 更新用户表单的输入数据
   * @return [type]           [description]
   */
  public function update(User $user,Request $request){
    $this->validate($request,[
      'name' => 'required|min:3|max:50',
      'password' => 'nullable|confirmed|min:6'
    ]);

    $this->authorize('update', $user);

    $data = [];
    $data['name'] = $request->name;
    if($request->password){
      $data['password'] = bcrypt($request->password);
    }
    $user->update($data);
    session()->flash('success','个人资料更新成功！');

    // $user->update([
    //   'name' => $request->name,
    //   'password' => bcrypt($request->password),
    // ]);

    return redirect()->route('users.show',$user->id);

  }

  /**
   * 删除会员操作
   * @param  User   $user [description]
   * @return [type]       [description]
   */
  public function destroy(User $user){
    $this->authorize('destroy', $user);
    $user->delete();
    session()->flash('success','成功删除用户！');
    return back();
  }




}