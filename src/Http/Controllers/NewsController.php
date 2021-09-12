<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;

class NewsController extends BaseController
{
    public function index(){
        $msg = 'this is news';
        $code = $this->codeText();
        return view('skpower::news.index',['msg'=>$msg,'title' => '图文消息', 'code' => $code]);
    }

    public function send(Request $request){
        try{

            $title = $request->post('title');
            if (empty($title)) {
                throw new \Exception('请填写图文标题');
            }
            $url = $request->post('url','');
            $picurl = $request->post('picurl','');

            $message['articles'] = [];

            $content['title'] = $title;
            if (!empty($url)) {
                $content['url'] = $url;
            }

            if (!empty($picurl)) {
                $content['picurl'] = $picurl;
            }

            array_push($message['articles'],$content);

            $userIds = $request->post('user_id');
            if (empty($userIds)) {
                throw new \Exception('请至少选择一个发送人');
            }

            //发送
            app(BdyNotice::class)->sendNotice('news',$message,$userIds);

            return $this->returnJosn('发送成功');
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    public function codeText(){
        $str = '';
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">数据格式-articles是一个索引的二维数组,可以一次发送多个图文消息</span>' . "\n";
        $str .=  '<span class="bianliang">$message</span> = <span class="param">[
                \'articles\' => [
                    [
                        \'title\' => \'测试图文标题\',
                        \'url\' => \'http://www.baidu.com\',
                        \'picurl\' => \'http://oss.cn//3cf8cf13ab5c985bd9a29312d87143ac.jpg\',
                    ],
                    [
                        \'title\' => \'测试图文标题2\',
                        \'url\' => \'http://www.qq.com\',
                        \'picurl\' => \'http://oss.cn//3cf8cf13ab5c985bd9a29312d87143ac.jpg\',
                    ],
                    [
                        \'title\' => \'测试图文标题3\',
                        \'url\' => \'http://www.sina.com\',
                        \'picurl\' => \'http://oss.cn//3cf8cf13ab5c985bd9a29312d87143ac.jpg\',
                    ]
                ]
            ];</span>' . "\n";
        $str .= '       ' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送内容</span>' . "\n";
        $str .= '<span class="bianliang">$message</span> = <span class="bianliang">$request</span>->post("news");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">获取发送员工user_id</span>' . "\n";
        $str .= '<span class="bianliang">$userIds</span> = <span class="bianliang">$request</span>->post("user_id");' . "\n";
        $str .= '       ' . "\n";
        $str .=  '//<span class="zhushi">发送</span>' . "\n";
        $str .= 'app(BdyNotice::class)->sendNotice(<span class="param">"news</span>",<span class="bianliang">$message</span>,<span class="bianliang">$userIds</span>);' . "\n";
        return $str;
    }

}
