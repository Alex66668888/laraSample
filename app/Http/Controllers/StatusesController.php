<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __construct(){
      //借助 Auth 中间件来为这两个动作添加过滤请求
      $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        return redirect()->back();
    }

    //Laravel 会自动查找并注入对应 ID 的实例对象 $status，如果找不到就会抛出异常
    public function destroy(Status $status){

      //做删除授权的检测，不通过会抛出 403 异常
      $this->authorize('destroy', $status);

      //调用 Eloquent 模型的 delete 方法对该微博进行删除
      $status->delete();

      session()->flash('success', '微博已被成功删除！');
      return redirect()->back();

    }

}
