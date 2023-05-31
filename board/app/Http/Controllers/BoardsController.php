<?php
/*************************************
 * 프로젝트명   : laravel_borad
 * 디렉토리     : Controllers
 * 파일명       : BoardsController.php
 * 이력         : v001 0526 BJ.Park new
 *               v002 0530 BJ.Park 유효성 체크 추가
 *************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // v002 add
use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 로그인 체크
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }

        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // v002 add start
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // v002 add end

        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //DB::table('Boards')->where('id','=',$id)->update([
        //    'title' => $request->title
        //    ,'content' => $request->content
        //]);
        
        // v002 add start
        // ID를 리퀘스트객체에 머지
        $arr = ['id' => $id];
        //$request->merge($arr);
        $request->request->add($arr);
        // v002 add end

        // 유효성 검사 방법 1
        $request->validate([
            'id'        => 'required|integer' // v002 add
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);

        // 유효성 검사 방법 2
        //$validator = Validator::make(
        //    $request->only('id', 'title', 'content')
        //    ,[
        //        'id'        => 'required|integer'
        //        ,'title'    => 'required|between:3,30'
        //        ,'content'  => 'required|max:1000'
        //    ]
        //);

        //if($validator->fails()) {
        //    return redirect()
        //        ->back()
        //        ->withErrors($validator)
        //        ->withInput($request->only('title', 'content'));
        //}

        $result = Boards::find($id);
        $result->title = $request->title;
        $result->content = $request->content;
        $result->save();

        //return redirect('/boards/'.$id);
        return redirect()->route('boards.show', ['board' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Boards::destroy($id);

        $board = Boards::find($id)->delete();
        //$board->delete();
        return redirect('/boards');
    }
}
