<?php


namespace Skpower\Bdynotice\Http\Controllers;


use Illuminate\Http\Request;
use Skpower\Bdynotice\BdyNotice;
use Skpower\Bdynotice\BdyNoticeType;

class IndexController extends BaseController
{
    //首页
    public function index(){
        //获取配置
        $config = config('bdynotice.work');

        $corpStatus = $config['corp_id'] ? 'OK' : 'Null';
        $agentStatus = $config['agent_id'] ? 'OK' : 'Null';
        $secretStatus = $config['secret'] ? 'OK' : 'Null';

        $notice = 'agent_id 配置项必须为 int 类型,  例: 1000001, 错误: "1000001"';

        if ($corpStatus != 'OK' || $agentStatus != 'OK' || $secretStatus != 'OK') {
            return redirect('skpower/conf/index');
        }

        //处理参数
        $config['agent_id'] = $this->substrCut($config['agent_id']);
        $config['secret'] = $this->substrCut($config['secret']);

        return view('skpower::index.index',['title' => '首页','config'=>$config, 'corpStatus' => $corpStatus ,'agentStatus' => $agentStatus, 'secretStatus' => $secretStatus, 'notice' => $notice]);
    }

    public function substrCut($user_name)
    {
        //获取字符串长度
        $strlen = mb_strlen($user_name, 'utf-8');

        //如果字符创长度小于2，不做任何处理
        if ($strlen < 2) {
            return $user_name;
        } else {
            //mb_substr — 获取字符串的部分
            $firstStr = mb_substr($user_name, 0, 1, 'utf-8');

            $lastStr = mb_substr($user_name, -1, 1, 'utf-8');

            //str_repeat — 重复一个字符串
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . '**' . $lastStr;
        }
    }

    //上传
    public function upload(Request $request){
        try {
            $file = $request->file('file');
            $type = $request->post('type');
            if (empty($type)) {
                throw new \Exception('请指定上传文件的类型');
            }
            $ext = $file->getClientOriginalExtension();
            $fileName = date("YmdHis",time()) . '.' . $ext;
            $res = $file->storeAs('bdy',$fileName);
            $path = str_replace('\\','/',storage_path('app')) . '/' .$res;
            //图片
            $mediaId = app(BdyNotice::class)->uploadMedia($type,$path);
            //删除本地的文件
            unlink($path);
            return $this->returnJosn('上传成功',['media_id' => $mediaId]);
        }catch(\Exception $e){
            return $this->returnJosn($e->getMessage(), [],1);
        }

    }

    //测试文件下载
    public function download($type){
        try{

            if (empty($type)) {
                throw new \Exception('请指定测试文件类型');
            }

            $fileName = "test";

            switch ($type) {
                case BdyNoticeType::TYPE_IMG:
                    //图片类型
                    $res = ".jpg";

                    break;
                case BdyNoticeType::TYPE_VOICE:
                    //声音类型
                    $res = ".amr";

                    break;
                case BdyNoticeType::TYPE_VIDEO:
                    //视频类型
                    $res = ".mp4";

                    break;
                case BdyNoticeType::TYPE_FILE:
                    //普通文件
                    $form = [ //可选 发送时,中文文件名不显示或被过虑可传此参数
                        'filename' => '企业微信操作手册.pdf'
                    ];
                    $res = ".docx";

                    break;
                default:
                    throw new \Exception('指定文件类型有误');
            }

            $file = $fileName . $res;

            $path = str_replace('\\','/',BdyNotice::SKPOWER_PATH) . '/storage/' . $file;

            return response()->download($path);

        }catch (\Exception $e){
            dd($e->getMessage());
        }
    }

}
