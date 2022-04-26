# skpower

**使用环境**

```
PHP >=7.3
Larvel-framework ^5.5 || ^6.0
Vue 2.x
Iview-UI 4.x (非 template/render 模式)
依赖
easywechat ^5.0
```



**使用步骤**

1.安装: 
```
composer require skpower/bdy-wechat-notice
```

2.前往app.php 注册BdyNoticeServiceProvider 服务提供者

3.执行命令  
```
php artisan vendor:publish
```
    然后选择 Skpower\Bdynotice\BdyNoticeServiceProvider 前面的序号, 进行资源发布

4.访问demo路由   http://xxx.com/skpower/index

5.只需配置企微后台的

```
corp_id
自建应用的agent_id
自检应用的secret
开箱即用
```



**资源发布内容**

```
public/satic 发布js和css  
config/bdynotice.php  发布配置文件
app/Notifications  发布laravel内置的消息通知类文件
```


**介入的企微能力**

```
1.消息推送能力 (详见文档: https://work.weixin.qq.com/api/doc/90000/90135/90250)
2.素材管理能力 (详见文档: https://work.weixin.qq.com/api/doc/90000/90135/91054)
3.通讯录管理能力 (用于同步企业员工,详见文档:https://work.weixin.qq.com/api/doc/90000/90135/90193)
```

**注意事项**

```
员工同步问题
因为依赖的是企微后台自建应用的能力,所以,自建应用的可见范围一定要选择企业的根部门,这样才能保证同步员工的时候,全量同步,在发送企信的时候,全量员工都能够接收到企信通知,如果某一个员工不在自建应用的可见范围内,那么是发送不出去的.
```


