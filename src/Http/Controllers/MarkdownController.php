<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class MarkdownController extends BaseController
{
    public function index(){
        $msg = 'this is markdown';
        $code = $this->codeText();
        return view('skpower::markdown.index',['msg'=>$msg,'title' => 'markdown消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{
            $message = $request->post('description');
            if (empty($message)) {
                throw new \Exception('请填写markdown文本');
            }

            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('markdown',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">您的会议室已预订,稍后会同步到`邮箱`
**事项详情**
**事项**: *开会*
**组织者**: *@skpower*
**参与者**:*@skpower*

**会议室**: 一号会议室
**日期**: 2021-9-7
**时间**: 上午9:00-12:00

**备注**: 请准时参会
如需修改会议信息,请点击 [修改会议信息](https://www.baidu.com)</span>' . "\n";
        $str .= '       ' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("markdown");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"markdown</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }
}
