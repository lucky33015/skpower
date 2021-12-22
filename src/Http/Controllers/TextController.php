<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class TextController extends BaseController
{
    public function index(){
        $msg = 'this is text';
        $code = $this->codeText();
        return view('skpower::text.index',['msg'=>$msg,'title' => '文本消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{
            $message = $request->post('text');
            if (empty($message)) {
                throw new \Exception('发送内容不能为空');
            }

            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('text',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">"skpower...."</span>' . "\n";
        $str .= '       ' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("text");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"text</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }

    //获取测试发送文案
    public function getTestText(){
        $text = $this->getHttp();
        if ($text['success'] == true) {
            $res = $text['ishan'];
        }else{
            $res = '今天天气好好哦';
        }

        return $this->returnJosn('ok',['text' => $res]);

    }

    //postman
    public function getHttp(){
        $response = file_get_contents('https://api.ghser.com/hitokoto?type=json');
        return json_decode($response,true);
    }

}
