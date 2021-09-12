@extends('skpower::layout.app')
@section('content')
    <div id="app">

        <template>
            <div class="demo-split">
                <Split v-model="split1">
                    <div slot="left" class="demo-split-pane">
                        <template>
                            <i-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-position="top">
                                <form-item label="发文内容" prop="text">
                                    <i-input v-model="formValidate.text" placeholder="请输入要发送的文本内容"
                                             />
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
                                    <i-button @click="value8 = true" type="primary" style="margin-left: 8px">定时发送</i-button>
                                    <drawer title="PHP" placement="bottom" :closable="false" v-model="value8" height="55">
                                <pre style="text-align: left;">
                                        暂未开发
                                </pre>
                                    </drawer>
                                    <i-button type="primary" @click="getText()" style="margin-left: 8px">获取毒鸡汤文案
                                    </i-button>
                                    <Modal
                                            v-model="modal7"
                                            title="随机文案"
                                            @on-ok="copyText"
                                            ok-text="点击复制"
                                    >
                                        <p>${testText}</p>
                                    </Modal>
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
                    text: '',
                    user_id: [],
                },
                ruleValidate: {
                    text: [
                        {required: true, message: '请填写发送内容!', trigger: 'blur'}
                    ],
                    user_id: [
                        {required: true, type: 'array', min: 1, message: '请至少选择一个员工', trigger: 'change'},
                    ]
                },
                testText:'',
                modal7:false,
            },
            created: function () {
                this.getMmeberList();
            },
            methods: {
                handleSubmit(name) {
                    this.$refs[name].validate((valid) => {
                        if (valid) {
                            var url = "{{url('skpower/text/send')}}";
                            this.$Loading.start();
                            this.$http.post(url, {
                                text: this.formValidate.text,
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
                    this.formValidate.text = '';
                    this.formValidate.user_id = [];
                },

                getText(){
                    var url = "{{url('skpower/text/get_text')}}";
                    this.$Loading.start();
                    this.$http.get(url, {params: {act: 'list'}}).then(function (res) {

                        if (res.body.code == 0) {
                            this.testText = res.body.data.text;
                            this.$Loading.finish();
                            this.modal7 = true;
                        }else{
                            this.$Loading.error();
                        }
                    });
                },

                //点击按钮复制
                copyText(){
                    const cinput = document.createElement('input');
                    cinput.value = this.testText;
                    document.body.appendChild(cinput);
                    cinput.select();
                    document.execCommand("copy");
                    
                    this.$Message.success('复制成功!');
                    cinput.remove();
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
