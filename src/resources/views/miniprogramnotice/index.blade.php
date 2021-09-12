@extends('skpower::layout.app')
@section('content')
    <div id="app">

        <template>
            <div class="demo-split">
                <Split v-model="split1">
                    <div slot="left" class="demo-split-pane">
                        <template>
                            <i-form ref="formValidate" :model="formValidate" :rules="ruleValidate"  label-position="top" >

                                <form-item label="小程序appid:" prop="app_id">
                                    <i-input v-model="formValidate.app_id" placeholder="请输入小程序appid"
                                    />
                                </form-item>

                                <form-item label="小程序消息标题:" prop="title">
                                    <i-input v-model="formValidate.title" placeholder="请输入要发送的小程序消息标题"
                                             />
                                </form-item>



                                <form-item label="小程序消息描述:" prop="description">
                                    <i-input v-model="formValidate.description" placeholder="小程序消息描述"
                                             />

                                </form-item>
                                <span style="color: green;">
                                    <p>
                                        *-需注意: 小程序的发送,一定要先去企微后台->应用管理->自建应用->应用主页设置->关联小程序;-*<br>
                                    </p>
                                    <p>
                                        *-PS:      因为仅仅是demo,所以,此处就不对content_item参数做表单处理了,会发送内置的内容,右侧数据格式可便于理解;-*<br>
                                    </p>
                                    <p>
                                        *-      关于报错: 如果没有发送成功,那么大概率就是<br>
                                        1:appid填错了,<br>
                                        2:自建应用没有关联到小程序<br>
                                        3:根据错误的提示,去查询企微的error-code码,这里异常抛出的一定是企微返回的错误-*
                                    </p>
                                        </span>
                                <br>

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
                </Split>
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
                formValidate: {
                    title: '',
                    app_id: '',
                    description: '',
                    user_id: [],
                },
                ruleValidate: {
                    app_id: [
                        {required: true, message: '请填写发送小程序appid!', trigger: 'blur'}
                    ],
                    title: [
                        {required: true, message: '请填写发送图文标题!', trigger: 'blur'},
                        {type: 'string', min: 4, message: '消息标题长度最少4个汉字', trigger: 'change'},
                        {type: 'string', max: 12, message: '消息标题长度最长12个汉字', trigger: 'change'},
                    ],
                    description: [
                        {type: 'string', min: 4, message: '消息标题长度最少4个汉字', trigger: 'change'},
                        {type: 'string', max: 12, message: '消息标题长度最长12个汉字', trigger: 'change'},
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
                handleSubmit(name) {
                    this.$refs[name].validate((valid) => {
                        if (valid) {
                            var url = "{{url('skpower/miniprogram_notice/send')}}";
                            this.$Loading.start();
                            this.$http.post(url, {
                                title: this.formValidate.title,
                                description: this.formValidate.description,
                                app_id: this.formValidate.app_id,
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
                    this.formValidate.title = '';
                    this.formValidate.app_id = '';
                    this.formValidate.description = '';
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
