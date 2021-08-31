<?php
header("content-type:text/html;charset=utf-8");
session_start();
if(empty($_SESSION["pass_manager_user"])){
  header("Location:Login.html");  
}?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>作品管理</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
<div style="padding: 15px;">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>作品管理</legend>
    </fieldset>
    <div class="layui-inline">
      <input class="layui-input" name="search_keyword" id="demoReload" placeholder="搜索 作者"  autocomplete="off" class="layui-input" style="width: 300px">
    </div>
    <button class="layui-btn layui-btn-normal" data-type="reload">搜索</button>
</div>
<table class="layui-hide" id="test" lay-filter="test"></table> 

<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit">更新</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
              
          
<script src="../js/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 --> 
 
<script>
layui.use(['form','element','layer','table','util'], function(){
  var $ = layui.jquery;
  element = layui.element;
  layer = layui.layer;
  form = layui.form;
  table = layui.table;

//搜索功能加重载表格
$('.layui-btn').click(function () {
    var inputVal = $('.layui-input').val();
    var action= 'works';
    table.reload('test', {
        url: '../config/api/search.php'
        ,method:"post"
        ,where: {
         works_author: inputVal
         ,action
    }
    });
});  
  
  table.render({
    elem: '#test'
    ,url:'/config/api/data.php?action=works_data'
    ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
    ,title: '用户数据表'
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field:'id', title:'ID', width:100, fixed: 'left', align:'center',unresize: true, sort: true}
      ,{field:'works_name', title:'作品名', width:200, align:'center', edit: 'text', sort: true}
      ,{field:'works_author', title:'作者', width:200, align:'center', edit: 'text', sort: true}
      ,{field:'works_route', title:'作品路径', width:200, align:'center', sort: true, edit: 'text'}
      ,{field:'works_number', title:'票数', width:200, align:'center', sort: true, edit: 'text'}
      ,{field:'works_time', title:'作品提交时间', width:200, align:'center', sort: true}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', align:'center', width:150, edit: 'text'}
    ]]
    ,page: true
  });
  
  //监听行工具事件
  table.on('tool(test)', function(obj){
    var data = obj.data;
    //console.log(obj)
    var id=data.id;
    if(obj.event === 'del'){
      layer.confirm('确定删除ID为'+data.id+'的数据?',
       function(index){
         var action='del_works';
         data.action=action;
         data=JSON.stringify(data);
         console.log(data);
          $.ajax({
            type:'POST',
            url:'/config/api/api.php',
            data:data,
            success:function(row){
              if(row=="success"){
                layer.closeAll("loading");
                layer.msg("删除成功！",{icon: 1});
                obj.del();
                layer.close(index);
              }else{
                layer.closeAll("loading");
                layer.msg("删除失败",{icon:2});
              }
            }
          });
      });
    } else if(obj.event === 'edit'){
      layer.confirm('是否修改ID为'+ data.id+'的数据',{
        btn:['是','否']
        ,yes:function(index,layero){
          //layer.msg('1');
          var action='update_works';
          data.action=action;
          data=JSON.stringify(data);
          $.ajax({
            type:'POST',
            url:'/config/api/api.php',
            data:data,
            success:function(row){
              if(row=="success"){
                layer.closeAll("loading");
                layer.msg("更新成功！",{icon: 1});
                table.reload('test');
              }else {
                layer.closeAll("loading");
                //debugger
                layer.msg("更新失败",{icon:2});
              }
              }
            ,error: function (){
              layer.msg("更新错误",{icon:4});
            }
            
          });
        },
        btn2:function(){
          layer.msg('已取消');
        }
        ,cancel: function(){
          
        }
      })
    }
  });

});


</script>

</body>
</html>