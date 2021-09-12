<?php

namespace App\Notifications\Bdy;

use App\Notifications\Channels\BdyWechatNoticeChannel;
use EasyWeChat\Kernel\Messages\File;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\MiniprogramNotice;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\TextCard;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Work\GroupRobot\Messages\Markdown;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Skpower\Bdynotice\BdyNoticeType;

class BdyWechatNoticeNotification extends Notification
{
    use Queueable;

    public $type;
    public $message;

    const TYPE_TEXT = 'text'; //文本类型
    const TYPE_IMG = 'image'; //图片类型
    const TYPE_VOICE = 'voice'; //语音类型
    const TYPE_VIDEO = 'video'; //视频类型
    const TYPE_FILE = 'file'; //文件类型
    const TYPE_CARD = 'textcard'; //卡片类型
    const TYPE_NEW = 'news'; //图文类型
    const TYPE_MARKDOWN = 'markdown'; //markdown类型
    const TYPE_MINIGRAM = 'miniprogram_notice'; //小程序类型

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,$message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [BdyWechatNoticeChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    //处理企信发送的类型
    public function toWechatRobot()
    {

        switch ($this->type) {
            case BdyNoticeType::TYPE_TEXT:
                //文本类型
                $message = $this->message;

                break;
            case BdyNoticeType::TYPE_IMG:
                //图片类型
                $message = new Image($this->message);

                break;
            case BdyNoticeType::TYPE_VOICE:
                //语音类型
                $message = new Voice($this->message);

                break;
            case BdyNoticeType::TYPE_VIDEO:
                //语音类型
                $message = new Video($this->message);

                break;
            case BdyNoticeType::TYPE_FILE:
                //文件类型
                $message = new File($this->message);

                break;
            case BdyNoticeType::TYPE_CARD:
                //卡片类型
                $message = new TextCard($this->message);

                break;
            case BdyNoticeType::TYPE_NEW:
                //图文类型
                $message = new NewsItem($this->message);

                break;
            case BdyNoticeType::TYPE_MARKDOWN:
                //markdown类型
                $message = new Markdown($this->message);

                break;
            case BdyNoticeType::TYPE_MINIGRAM:
                //小程序类型
                $message = new MiniprogramNotice($this->message);

                break;
            default:
                throw new \Exception('请传递指定的Type类型!');
                break;
        }

        return ['message' => $message, 'type' => $this->type];
    }
}
