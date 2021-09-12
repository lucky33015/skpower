<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class ImageController extends BaseController
{
    public function index(){
        $msg = 'this is image';
        $code = $this->codeText();
        return view('skpower::image.index',['msg'=>$msg,'title' => '图片消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{
            $message = $request->post('image');
            if (empty($message)) {
                throw new \Exception('发送内容不能为空');
            }
            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('image',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '<span class="zhushi">//文件上传至临时素材 </span>' . "\n";
        $str .=  '<span class="bianliang">$path</span> = <span class="param">"D://image/test.jpg"</span> //此处也支持网络图片路径(http://oss.com/test.jpg)' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取接口返回的media_id</span>' . "\n";
        $str .=  '<span class="bianliang">$mediaId</span> = app(BdyNotice::class)->uploadMedia(<span class="param">"image"</span>,$path);' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式-接收media_id</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">"3qCT4qHZbSHp6_xsvCZA5JsqvyRGiDPubOzSa0nHUgiQ"</span>' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("image");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"image</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }

}
