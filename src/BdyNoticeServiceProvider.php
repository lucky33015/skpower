<?php

namespace Skpower\Bdynotice;

use Illuminate\Support\ServiceProvider;

class BdyNoticeServiceProvider extends ServiceProvider
{

    //延迟加载
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bdynotice',function ($app) {
            return new BdyNotice($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //加载路由
        include __DIR__.'/routes/routes.php';

        //指定视图目录
        $this->loadViewsFrom(__DIR__ . '/resources/views','skpower');
        //资源发布
        $this->publishes([
            __DIR__ . '/config/bdynotice.php' => config_path('bdynotice.php'),//发布配置文件
            __DIR__ . '/Notifications' => base_path('app/Notifications'),//发布消息通知类
            __DIR__ . '/resources/js' => public_path('static/skpower/js'),//发布静态资源
            __DIR__ . '/resources/css' => public_path('static/skpower/css'),//发布静态资源

        ]);
    }

    //提供者提供的服务
    public function provides()
    {
        return [
            'bdynotice'
        ];
    }
}
