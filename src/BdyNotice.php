<?php


namespace Skpower\Bdynotice;


use App\Notifications\Bdy\BdyWechatNoticeNotification;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

class BdyNotice
{
    public $config;
    public $easyconf;
    public $app;
    const SKPOWER_PATH  = __DIR__;

    /**
     * BdyNotice constructor.
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->setEasyconf();
    }

    //获取easywechat的配置
    public function setEasyconf(){
        if ($this->config->get('bdynotice.sandbox')) {
            //调试模式
            $config = $this->config->get('bdynotice.work');
            $this->easyconf = $config;
            if (empty($this->easyconf) || empty($this->easyconf['corp_id']) || empty($this->easyconf['agent_id']) || empty($this->easyconf['secret'])) {
                throw new \Exception('请完善 [config/bdynotice.php] 配置',403);
            }

            //初始化easywechat
            $this->app = \EasyWeChat\Factory::work($this->easyconf);
            return $this->app;
        } else {
            throw new \Exception('仅供内部使用!');
        }

    }

    //获取token
    public function getToken(){
        $token = $this->app->access_token->getToken();
        return $token;
    }

    //处理发送的消息类型
    public function sendNotice($type,$message,$userIds){
        Notification::send($userIds, new BdyWechatNoticeNotification($type, $message));
    }

    //上传临时素材
    public function uploadMedia($type,$path){

        $mediaId = '';

        switch ($type) {
            case BdyNoticeType::TYPE_IMG:
                //图片类型
                $res = $this->app->media->uploadImage($path);

                break;
            case BdyNoticeType::TYPE_VOICE:
                //声音类型
                $res = $this->app->media->uploadVoice($path);

                break;
            case BdyNoticeType::TYPE_VIDEO:
                //视频类型
                $res = $this->app->media->uploadVideo($path);

                break;
            case BdyNoticeType::TYPE_FILE:
                //普通文件
                $form = [ //可选 发送时,中文文件名不显示或被过虑可传此参数
                    'filename' => '企业微信操作手册.pdf'
                ];
                $res = $this->app->media->uploadFile($path,$form = []);

                break;
            default:
                break;
        }

        if (!empty($res['errcode'])) {
            throw new \Exception('文件上传企微失败:' . $res['errmsg']);
        }
        $mediaId = $res['media_id'];
        return $mediaId;
    }

    //获取素材
    public function getMedia($mediaId){
        $res = $this->app->media->get($mediaId);
        return $res;
    }

}
