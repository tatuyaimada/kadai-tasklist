<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Task;
class TasksController extends Controller
{
    // getでmessages/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
         $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        // Welcomeビューでそれらを表示
        return view('welcome', $data);
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
       
       //* Auth::user()->user_id;  
       //*どこにどう入力したらいいか答えがみつからない。
       
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user_id;
        $task->user_id = \Auth::id();
        $task->save();
        
        
       

        return redirect('/');
    }
    // getでmessages/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.show',[
            'task' => $task,
        ]);
        
        
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        $task->show();
        }
        return redirect('/');
        
    }
    // getでmessages/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.edit',[
            'task' =>$task,
            ]);
            
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        $task->edit();
        }
        return redirect('/');
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
        
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        $task->update();
        }
        return redirect('/');
    }
    // deleteでmessages/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
        $task->delete();
        } 
        return redirect('/');
    }
}