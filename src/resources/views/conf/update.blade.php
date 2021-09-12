@extends('skpower::layout.app')
@section('content')
    <div id="app" class="conftop">
        <template>


            <i-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-position="top" >
                <form-item label="企业corp_id:" prop="corp_id">
                    <i-input v-model="formValidate.corp_id"
                    />
                </form-item>

                <form-item label="自建应用agent_id:" prop="agent_id">
                    <i-input v-model="formValidate.agent_id"
                    />
                </form-item>

                <form-item label="自建应用secret:" prop="secret">
                    <i-input v-model="formValidate.secret"
                    />
                </form-item>

                <form-item>
                    <i-button type="primary" @click="handleSubmit('formValidate')" >写入配置文件</i-button>
                    <i-button @click="handleReset('formValidate')" style="margin-left: 8px">重置
                    </i-button>
                </form-item>
            </i-form>

        </template>


    </div>


@endsection
@push('scripts')
    <script>
        new Vue({
            el: "#app",
            delimiters: ['${', '}'],
            data: {
                formValidate: {
                    corp_id: '',
                    agent_id: '',
                    secret: '',
                },
                ruleValidate: {
                    corp_id: [
                        {required: true, message: '请填写企业corp_id!', trigger: 'blur'}
                    ],
                    agent_id: [
                        {required: true, message: '请填写自建应用agent_id!', trigger: 'blur'}
                    ],
                    secret: [
                        {required: true, message: '请填写自建应用secret!', trigger: 'blur'}
                    ]
                }
            },
            created: function () {

            },
            methods: {
                handleSubmit(name) {
                    this.$refs[name].validate((valid) => {
                        if (valid) {
                            var url = "{{url('skpower/conf/update_ac')}}";
                            this.$http.post(url, {
                                corp_id: this.formValidate.corp_id,
                                secret: this.formValidate.secret,
                                agent_id: this.formValidate.agent_id,
                            }, {emulateJSON: true}).then(function (res) {
                                if (res.body.code == 0) {

                                    this.$Message.success(res.body.msg);

                                    setTimeout(function(){
                                        //跳转至首页
                                        window.location.href = "{{url('skpower/index')}}";
                                    },2000)
                                } else {
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

                jump_url(type) {
                    var url = "{{url('skpower/')}}" + "/" + type;
                    window.location.href = url;
                },

                cleanInput: function () {
                    this.formValidate.title = '';
                    this.formValidate.url = '';
                    this.formValidate.picurl = '';
                    this.formValidate.user_id = [];
                }

            }
        });
    </script>
@endpush
@push('style')
    <style>
        .noticetip{
            width: 100%;
            height: 40px;
            background: #dfdddd;
            line-height: 40px;
            margin-bottom: 15px;
            text-align: center;
            overflow: hidden;
        }
        .noticetip p{
            color: #3a763d;
        }

        .conftop {
            height:810px;
        }


    </style>
@endpush

