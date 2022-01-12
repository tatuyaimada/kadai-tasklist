<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでmessages/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $tasks = Task::all();
        
        return view('tasks.index',[
            'tasks' =>$tasks,
            ]);
    }

    // getでmessages/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[
            'task' =>$task,
            ]);
    }

    // postでmessages/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {   
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',
        ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    // getでmessages/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.show',[
            'task' =>$task,
            ]);
    }

    // getでmessages/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.edit',[
            'task' =>$task,
            ]);
    }

    // putまたはpatchでmessages/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {   
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',
        ]);
        
        $task = Task::findOrFail($id);
        
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    // deleteでmessages/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        $task->delete();
        
        return redirect('/');
    }
}