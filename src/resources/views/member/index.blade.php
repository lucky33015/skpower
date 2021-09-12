@extends('skpower::layout.app')
@section('content')
    <div id="app">
        <template>
            <i-button type="primary" @click="modal6 = true">同步员工</i-button>
            <Modal
                v-model="modal6"
                title="提醒"
                :loading="loading"
                @on-ok="syncMember">
                <p>重新同步员工的话,会清空发送列表的员工数据,是否确定同步?</p>
            </Modal>
            <i-button type="primary" >员工数量: ${syncCount} 人</i-button>
            <br/>
            <br/>
            <i-table stripe border :columns="columns12" :data="data6">
                <template slot-scope="{ row }" slot="name">
                    <strong>${ row.name }</strong>
                </template>
                <template slot-scope="{ row, index }" slot="action">
                    <i-button type="primary" size="small" style="margin-right: 5px" @click="add(index)">加入发送列表</i-button>
                </template>
            </i-table>
        </template>

    </div>


@endsection
@push('scripts')
    <script>
        var vm = new Vue({
            el:"#app",
            delimiters:['${','}'],
            data:{
                modal6: false,
                loading: true,
                columns12: [
                    {
                        title: '员工姓名',
                        slot: 'name'
                    },
                    {
                        title: '员工id',
                        key: 'user_id'
                    },
                    {
                        title: 'Action',
                        slot: 'action',
                        width: 300,
                        align: 'center'
                    }
                ],
                data6: [],
                syncCount:0,
            },
            created:function(){
                this.getMmeberList();
            },
            methods:{
                syncMember () {
                    setTimeout(() => {
                        this.modal6 = false;
                    }, 2000);
                    var url = "{{url('skpower/member/sync')}}";
                    this.$http.post(url, {}, {emulateJSON: true}).then(function (res) {
                        if (res.body.code == 0) {
                            this.data6 = res.body.data;
                            this.$Message.success(res.body.msg);
                        } else {
                            this.$Message.error(res.body.msg);
                        }
                    });
                },
                add (index) {
                    var url = "{{url('skpower/member/add')}}";
                    this.$http.post(url, {
                        id: index,
                    }, {emulateJSON: true}).then(function (res) {
                        if (res.body.code == 0) {
                            this.remove(index)
                            this.$Message.success(res.body.msg);
                        } else {
                            this.$Message.error(res.body.msg);
                        }
                    });
                },
                remove (index) {
                    this.data6.splice(index,1);
                },
                jump_url:function(type){
                    var url = "{{url('skpower/')}}" + "/" + type;
                    window.location.href = url;
                },
                getMmeberList: function () {
                    var url = "{{url('skpower/member/index/list')}}";
                    this.$http.get(url, {params: {act: 'list'}}).then(function (res) {

                        if (res.body.code == 0) {
                            this.data6 = res.body.data;
                            let sendMember = "{{$sendCount}}";
                            this.syncCount = this.data6.length + parseInt(sendMember);
                        }else if(res.body.code == 403){
                            console.log(res.body.code);
                            this.jump_url('conf/index');
                        }
                    });
                },
            }
        });
    </script>
@endpush
