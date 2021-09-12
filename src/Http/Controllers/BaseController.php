<?php


namespace Skpower\Bdynotice\Http\Controllers;


use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    //定义api格式返回
    public function returnJosn($msg = '', $data = [], $code = 0){
        $data = [
            'msg' => $msg,
            'data' => $data,
            'code' => $code,
        ];
        return response()->json($data);
    }

}