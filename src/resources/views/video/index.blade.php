@extends('skpower::layout.app')
@section('content')
    <div id="app">

        <template>
            <div class="demo-split">
                <split v-model="split1">
                    <div slot="left" class="demo-split-pane">
                        <template>
                            <i-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-position="top">
                                <form-item label="发送视频" prop="media_id">
                                    <upload
                                        ref="upload"
                                        type="drag"
                                        action="{{url('skpower/index/upload')}}"
                                        :format="['mp4']"
                                        :max-size="10240"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize"
                                        :on-success="handleSuccess"
                                        :data="{type:'video'}"
                                    >
                                        <div style="padding: 20px 0">
                                            <icon type="ios-cloud-upload" size="52" style="color: #3399ff"></icon>
                                            <p>点击上传或拖拽文件至此处</p>
                                        </div>
                                    </upload>
                                </form-item>

                                <form-item label="发送人" prop="user_id">
                                    <checkbox-group v-model="formValidate.user_id">
                                        <checkbox :label="item.user_id" v-for="item in member" :key="item.user_id">
                                            <icon type="md-person"></icon>
                                            ${item.name}
                                        </checkbox>
                                    </checkbox-group>
                                </form-item>

                                <form-item>
                                    <i-button type="primary" @click="handleSubmit('formValidate')">发送</i-button>
                                    <i-button @click="handleReset('formValidate')" style="margin-left: 8px">重置
                                    </i-button>
                                    <i-button type="primary" to="{{url('skpower/test/down',['video'])}}" style="margin-left: 8px">点击下载测试文件
                                    </i-button>
                                    <i-button @click="value8 = true" type="primary" style="margin-left: 8px">定时发送</i-button>
                                    <drawer title="PHP" placement="bottom" :closable="false" v-model="value8" height="55">
                                <pre style="text-align: left;">
                                        暂未开发
                                </pre>
                                    </drawer>
                                </form-item>
                            </i-form>

                        </template>

                    </div>
                    <div slot="right" class="demo-split-pane">
                            <row>
                                <col span="18" push="6">&lt?php</col>
                                <col span="6" pull="18">
                                    <pre style="text-align: left; margin-left: 30px;">
                                        {!! $code !!}
                                    </pre>
                                </col>

                            </row>

                    </div>
                </split>
            </div>
        </template>
    </div>


@endsection
@push('scripts')
    <script>
        new Vue({
            el: "#app",
            delimiters: ['${', '}'],
            data: {
                value8: false,
                member: {},
                split1: 0.5,
                uploadFile:'',
                formValidate: {
                    media_id: '',
                    user_id: [],
                },
                ruleValidate: {
                    media_id: [
                        {required: true, message: '没有获取到视频文件的media_id,请检查后重新上传!', trigger: 'blur'}
                    ],
                    user_id: [
                        {required: true, type: 'array', min: 1, message: '请至少选择一个员工', trigger: 'change'},
                    ]
                }
            },
            created: function () {
                this.getMmeberList();
            },
            methods: {
                handleSuccess (res, file) {
                    //失败
                    if (res.code == 1) {
                        this.$Message.error(res.msg);
                        return;
                    }
                    //成功获取media_id
                    this.formValidate.media_id = res.data.media_id;
                    this.uploadFile = res.data.path;
                    file.name = '企信media_id: ' + this.formValidate.media_id;
                },
                handleFormatError (file) {
                    this.$Message.error(file.name + ' 文件格式不正确,仅支持mp4格式.');
                },
                handleMaxSize (file) {
                    this.$Message.error(file.name + ' 超出文件大小限制,最大上传10M');
                },
                handleSubmit(name) {
                    this.$refs[name].validate((valid) => {
                        if (valid) {
                            var url = "{{url('skpower/video/send')}}";
                            this.$Loading.start();
                            this.$http.post(url, {
                                video: this.formValidate.media_id,
                                user_id: this.formValidate.user_id
                            }, {emulateJSON: true}).then(function (res) {
                                if (res.body.code == 0) {
                                    this.$Loading.finish();
                                    this.$Message.success(res.body.msg);
                                    this.cleanInput();
                                } else {
                                    this.$Loading.error();
                                    this.$Message.error(res.body.msg);
                                }
                            });
                        } else {
                            this.$Message.error('请完善表单!');
                        }
                    })
                },

                handleReset(name) {
                    this.$refs[name].resetFields();
                },

                jump_url: function (type) {
                    var url = "{{url('skpower/')}}" + "/" + type;
                    window.location.href = url;
                },

                getMmeberList: function () {
                    var url = "{{url('skpower/member/list')}}";
                    this.$http.get(url, {params: {act: 'list'}}).then(function (res) {

                        if (res.body.code == 0) {
                            //如果发送员工为空,那么跳转到员工列表进行配置
                            if(res.body.data.length == 0) {
                                this.$Message.error('您当前还未添加企信发送人,请去员工列表添加');
                                setTimeout(function(){
                                    //跳转至员工列表
                                    window.location.href = "{{url('skpower/member/index')}}";
                                },2000);
                                return;
                            }
                            this.member = res.body.data;
                        }else if(res.body.code == 403){
                            console.log(res.body.code);
                            this.jump_url('conf/index');
                        }
                    });
                },

                cleanInput: function () {
                    this.formValidate.media_id = '';
                    this.formValidate.user_id = [];
                }

            }
        });
    </script>
@endpush
@push('style')
    <style>
        .demo-split {
            height: 700px;
            border: 1px solid #dcdee2;
        }

        .demo-split-pane {
            padding: 10px;
        }
    </style>
@endpush
