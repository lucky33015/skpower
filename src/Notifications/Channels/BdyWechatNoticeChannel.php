<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Log;
use Skpower\Bdynotice\BdyNotice;
use Skpower\Bdynotice\BdyNoticeType;

class BdyWechatNoticeChannel
{
    /**
     * 发送通知
     * @param $user '消息接收者，最多支持1000个'
     * @param $notification
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function send($user, $notification)
    {

        $messageData = $notification->toWechatRobot();
        $message = $messageData['message'];
        //固定企业发送通知
        $app = app(BdyNotice::class)->setEasyconf();
        $res = $app->messenger->message($message)->toUser($user)->send();
        if ($res['errcode']) {
            Log::error('企信通知错误:' . $res['errmsg']);
            throw new \Exception($res['errmsg']);
        }

        return true;
    }
}
