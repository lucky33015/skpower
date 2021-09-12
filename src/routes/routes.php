<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'skpower','namespace' => '\Skpower\Bdynotice\Http\Controllers'], function () {
    //首页
    Route::get('/index', 'IndexController@index');
    //上传
    Route::post('/index/upload', 'IndexController@upload');
    //文本发送
    Route::get('/text', 'TextController@index');
    Route::post('/text/send', 'TextController@send');
    //随机毒鸡汤
    Route::get('/text/get_text', 'TextController@getTestText');
    //图片发送
    Route::get('/image', 'ImageController@index');
    Route::post('/image/send', 'ImageController@send');
    //语音发送
    Route::get('/voice', 'VoiceController@index');
    Route::post('/voice/send', 'VoiceController@send');
    //视频发送
    Route::get('/video', 'VideoController@index');
    Route::post('/video/send', 'VideoController@send');
    //文件发送
    Route::get('/file', 'FileController@index');
    Route::post('/file/send', 'FileController@send');
    //文本卡片发送
    Route::get('/text_card', 'TextCardController@index');
    Route::post('/text_card/send', 'TextCardController@send');
    //图文发送
    Route::get('/news', 'NewsController@index');
    Route::post('/news/send', 'NewsController@send');
    //markdown发送
    Route::get('/markdown', 'MarkdownController@index');
    Route::post('/markdown/send', 'MarkdownController@send');
    //小程序发送
    Route::get('/miniprogram_notice', 'MiniprogramNoticeController@index');
    Route::post('/miniprogram_notice/send', 'MiniprogramNoticeController@send');

    //前端接口提供发送人员工
    Route::get('/member/list', 'MemberController@list');
    //员工列表页面
    Route::get('/member/index', 'MemberController@index');
    //页面接口
    Route::get('/member/index/list', 'MemberController@indexList');
    //同步员工
    Route::post('/member/sync', 'MemberController@syncMember');
    //加入发送列表
    Route::post('/member/add', 'MemberController@addList');

    //配置页面
    Route::get('/conf/index', 'ConfigController@index');
    Route::post('/conf/write', 'ConfigController@writeConfig');

    //切换企业
    Route::get('/conf/update', 'ConfigController@update');
    Route::post('/conf/update_ac', 'ConfigController@updateAction');

    //测试文件下载
    Route::get('/test/down/{type}', 'IndexController@download');
});
