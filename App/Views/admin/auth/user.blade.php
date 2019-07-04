@extends('layouts.admin')

@section('body')

<table class="layui-hide" id="test" lay-filter="test"></table>

<script type="text/html" id="toolbarDemo">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="add">添加管理员</button>
  </div>
</script>

<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script type="text/html" id="switchTpl">
  <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }}>
</script>
@endsection


@section('javascriptFooter')
<script>
layui.use('table', function(){
  var table = layui.table, form = layui.form;

  table.render({
    elem: '#test'
    ,url:'/admin/auth/get_all'
    ,method:'post'
    ,toolbar: '#toolbarDemo'
    ,page: true
    ,title: '用户数据表'
    ,cols: [[
      {field:'id', title:'ID', width:80, fixed: 'left', unresize: true, sort: true}
      ,{field:'uname', title:'用户名', width:120}
      ,{field:'display_name', title:'真实用户名', event:'edit_name'}
      ,{field:'created_at', title:'创建时间', }
      ,{field:'logined_at', title:'最近登录时间'}
      ,{field:'status', title:'状态', templet: '#switchTpl', unresize: true}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
    ]]
    ,defaultToolbar:[]
  });

    //头工具栏事件
    table.on('toolbar(test)', function(obj){
        switch(obj.event){
            case 'add':
                location.href="/admin";
            break;
        };
    });

    form.on('switch(status)', function(obj){
        let datajson = {key:'status', value:obj.elem.checked ? '1':'0'};

        $.post('/admin/auth/set/' + this.value ,datajson,function(data){
            if(data.code != 0) {
                layer.msg(data.msg);
                obj.elem.checked = !obj.elem.checked;
                form.render();
            }
        });
    });

  //监听行工具事件
    table.on('tool(test)', function(obj){
        var data = obj.data;
        event = obj.event;
        if(event === 'del'){
            layer.confirm('真的删除行么', function(index){
                $.post('/admin/auth/del/' + data.id ,'',function(data){
                    layer.close(index);
                    if(data.code != 0) {
                        layer.msg(data.msg);
                    } else {
                        obj.del();
                    }
                });


            });
        } else if(event === 'edit'){
            return ;
        } else if(event = 'edit_name') {
            layer.prompt({
                formType: 2
                ,value: data.display_name
            }, function(value, index){
                layer.close(index);
                let datajson = {key:'display_name', value:value};
                $.post('/admin/auth/set/' + data.id ,datajson,function(data){
                    if(data.code != 0) {
                        layer.msg(data.msg);
                    } else {
                        obj.update({
                          display_name: value
                        });
                    }
                });
            });
        }
    });

});
</script>
@endsection