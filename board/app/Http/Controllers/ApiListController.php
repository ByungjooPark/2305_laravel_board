<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json([$board], 200);
    }

    function postlist(Request $req) {
        // 유효성 체크 필요

        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id','title');

        return response()->json([$arr], 404);
        //return $arr;
    }

    function putlist(Request $req, $id) {
        $arrData = [
            'code'  => '0'
            ,'msg'   => ''
        ];

        $data = $req->only('title', 'content');
        $data['id'] = $id;

        // 유효성체크
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:boards'
            ,'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
        } else {
            // 업데이트 처리
            $board = Boards::find($id);
            $board->title = $req->title;
            $board->content = $req->content;
            $board->save();
            $arrData['code'] = '0';
            $arrData['msg'] = 'Success';
        }

        return $arrData;
    }

    function deletelist($id) {
        $arrData = [
            'code'  => '0'
            ,'msg'   => ''
        ];
        $data['id'] = $id;
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:boards,id'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Error';
            $arrData['errmsg'] = 'id not found';
        } else {
            $board = Boards::find($id);
            if($board){
                $board->delete();
                $arrData['code'] = '0';
                $arrData['msg'] = 'Success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'Already Deleted';
            }
        }
        return $arrData;
    }
}
