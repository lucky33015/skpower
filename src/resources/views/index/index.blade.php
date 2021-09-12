@extends('skpower::layout.app')
@section('content')
    <div id="app" class="indextop">
        <div>
            <template>
                <div>
                    <row class="sendrow">
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 1 }">
                            <div @click="jump_url('text')">
                                <span>文本消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 11, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('image')">
                                <span>图片消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('voice')">
                                <span>语音消息</span>
                            </div>
                        </i-col>
                    </row>
                    <br>
                    <br>
                    <row class="sendrow">
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 1 }">
                            <div @click="jump_url('video')">
                                <span>视频消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 11, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('markdown')">
                                <span>Markdown消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('file')">
                                <span>文件消息</span>
                            </div>
                        </i-col>
                    </row>
                    <br>
                    <br>
                    <row class="sendrow">
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 1 }">
                            <div @click="jump_url('news')">
                                <span>图文消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 11, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('text_card')">
                                <span>文本卡片消息</span>
                            </div>
                        </i-col>
                        <i-col class="sendcol" :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
                            <div @click="jump_url('miniprogram_notice')">
                                <span>小程序消息</span>
                            </div>
                        </i-col>
                    </row>
                </div>
                <Divider orientation="left" plain style="font-family: STXinwei;font-style: italic;">Corp Info</Divider>
                <row class="sendrowcorp" :wrap="false" style="margin-top: 16px">
                    <i-col flex="none">
                        <div style="padding: 0 30px"></div>
                    </i-col>
                    <i-col class="sendcolcorp" flex="auto">
                        <div class="corpdiv">
                            <p>corp_id:
                                <span >{{!empty($config['corp_id']) ? $config['corp_id'] : ''}} - [{{$corpStatus}}]
                                </span>
                            </p>
                            <p>agent_id:
                                <span >{{!empty($config['agent_id']) ? $config['agent_id'] : ''}} - [{{$agentStatus}}]
                                </span>
                            </p>
                            <p>secret:
                                <span >{{!empty($config['secret']) ? $config['secret'] : ''}} - [{{$secretStatus}}]
                                </span>
                            </p>
                        </div>

                    </i-col>
                    <i-col flex="none">
                        <div style="padding: 0 30px"></div>
                    </i-col>
                </row>

            </template>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        var vm = new Vue({
            el: "#app",
            delimiters: ['${', '}'],
            data: {},
            created: function () {
                console.log('init ok');
            },
            methods: {
                jump_url: function (type) {
                    var url = "{{url('skpower/')}}" + "/" + type;
                    window.location.href = url;
                }
            }
        });
    </script>
@endpush
@push('style')
    <style>
        .indextop {
            height: 700px;
        }

        .sendrow {
            height: 150px;
            text-align: center;
            line-height: 150px;
        }

        .sendcol {
            border: 2px dashed #dcdee2;
            font-size: 20px;
            color: #515a6e;
            font-family: "Helvetica";
        }

        .sendrowcorp {
            height: 50px;
            text-align: center;
            line-height: 50px;
        }

        .sendcolcorp {
            border: 2px solid #dcdee2;
            font-size: 20px;
            color: #515a6e;
            font-weight: lighter;
            font-family: "Helvetica";
            background: #515a6e;
            color: #ffffff;
        }

        .corpdiv p {
            float: left;
            width: 30%;
        }


    </style>
@endpush
