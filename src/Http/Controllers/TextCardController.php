<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class TextCardController extends BaseController
{
    public function index(){
        $msg = 'this is textcard';
        $code = $this->codeText();
        return view('skpower::textcard.index',['msg'=>$msg,'title' => '卡片消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{

            $title = $request->post('title');
            if (empty($title)) {
                throw new \Exception('请填写卡片标题');
            }

            $url = $request->post('url');
            if (empty($url)) {
                throw new \Exception('请填写卡片跳转url');
            }

            $description = $request->post('description');
            if (empty($description)) {
                throw new \Exception('请填写卡片描述');
            }


            $message['title'] = $title;
            $message['description'] = $description;
            $message['url'] = $url;


            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('textcard',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">[
                \'title\' => \'测试标题\',
                \'description\' => \'测试内容\',
                \'url\' => \'http://www.baidu.com\',
            ];</span>' . "\n";
        $str .= '       ' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("textcard");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"textcard</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }

}
