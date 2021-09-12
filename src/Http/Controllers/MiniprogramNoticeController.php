<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class MiniprogramNoticeController extends BaseController
{
    public function index(){
        $msg = 'this is miniprogramnotice';
        $code = $this->codeText();
        return view('skpower::miniprogramnotice.index',['msg'=>$msg,'title' => '小程序消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{
            $appId = $request->post('app_id');
            if (empty($appId)) {
                throw new \Exception('请填写小程序appid');
            }

            $title = $request->post('title');
            if (empty($title)) {
                throw new \Exception('请填写小程序消息标题');
            }
            $message['appid'] = $appId;
            $message['title'] = $title;
            $message['emphasis_first_item'] = true;
            $description = $request->post('description', '');
            if (!empty($description)) {
                $message['description'] = $description;
            }
            $message['content_item'] = [
                [
                    "key" => "自定义key1",
                    "value"=>"自定义val1"
                ],
                [
                    "key"=>"自定义key2",
                    "value"=>"自定义val2"
                ],
                [
                    "key"=>"自定义key3",
                    "value"=>"自定义val3"
                ],
                [
                    "key"=>"自定义key4",
                    "value"=>"自定义val4"
                ]
            ];

            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('miniprogram_notice',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式-content_item是一个索引二维数组,最多支持10组元素</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">[
                \'appid\' => \'wx3**********645\',
                \'title\' => \'会议预定成功通知\',
                \'description\' => \'9月10日 10:00\',
                \'emphasis_first_item\' => true,
                "content_item" => [
                    [
                        "key" => "会议室",
                        "value"=>"402"
                    ],
                    [
                        "key"=>"会议地点",
                        "value"=>"15H-402会议室"
                    ]
                ]
            ];</span>' . "\n";
        $str .= '       ' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("miniprogram_notice");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"miniprogram_notice</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }

}
