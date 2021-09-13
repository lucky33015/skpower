<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}}</title>
    <script type="text/javascript" src="{{ asset('static/skpower/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/skpower/js/vue.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/skpower/js/vue-resource.js') }}"></script>
    <!-- import stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('static/skpower/css/iview.css') }}">
    <!-- import iView -->
    <script type="text/javascript" src="{{ asset('static/skpower/js/iview.min.js') }}"></script>
    <style>
        .all{
            background: #f8f8f9;
            font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
        }

        .zhushi{
            color: green;
        }

        .bianliang{
            color: #2d8cf0;
        }

        .param{
            color: #ff9900;
        }
        .layout{
            border: 1px solid #d7dde4;
            background: #f5f7f9;
            position: relative;
            border-radius: 4px;
            overflow: hidden;
        }
        .layout-logo{
            width: 100px;
            height: 30px;
            border-radius: 3px;
            float: left;
            position: relative;
            top: 15px;
            left: 20px;
            text-align: center;
            line-height: 30px;
        }
        .layout-nav{
            width: 620px;
            margin: 0 auto;
            margin-right: 160px;
        }

        .layout-nav a{
            color: #ffffff;
            display:block;
            float: right;
            font-weight: lighter;
            font-family: "Helvetica";
            font-size: 15px;
        }

        .layout-footer-center{
            text-align: center;
            font-weight: lighter;
            font-family: "Helvetica";
        }
        .titlea{
            border: 2px dashed #dcdee2;
            height: 70px;
            text-align: center;
            font-size: 25px;
            line-height: 70px;
            font-family: "Helvetica";
            color: #515a6e;

        }

    </style>

</head>
<body>
<div id="app">
    <template>
        <div class="layout">
            <layout>
                <header :style="{position: 'fixed', width: '100%'}">
                    <i-menu mode="horizontal" theme="dark" active-name="1">
                        <div class="layout-logo"><img src="{{ asset('static/skpower/image/logo.png') }}" alt=""></div>
                        <div class="layout-nav">
                            <menu-item name="1">
                                    @if($title != '首页')
                                        <icon type="ios-keypad"></icon>
                                        <a href="{{url('skpower/index')}}">返回首页</a>
                                    @endif
                            </menu-item>
                            <menu-item name="2">
                                <icon type="ios-people"></icon>
                                <a href="{{url('skpower/member/index')}}">员工列表</a>
                            </menu-item>
                            <menu-item name="3">
                                <icon type="md-switch"></icon>
                                <a href="{{url('skpower/conf/update')}}">切换企业</a>
                            </menu-item>
                        </div>
                    </i-menu>
                </header>
                <content :style="{margin: '88px 20px 0', background: '#fff', minHeight: '820px'}">
                    <div class="all">
                        <div class="titlea">{{$title}}</div>
                        <Divider orientation="left" plain style="font-family: STXinwei;font-style: italic;" >Send-Input</Divider>
                        @yield('content')

                    </div>
                </content>
                <footer class="layout-footer-center"> @ skpower</footer>
            </layout>
        </div>
    </template>
</div>

</body>
</html>
@stack('scripts')
@stack('style')
