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
  <title>查看图片</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
    <form class="layui-form">
    <div class="layui-inline" style="margin-top: 25px;margin-left: 30%;">
        <label class="layui-label">请输入图片路径：</label>
        <div class="layui-input-inline">
            <input type="text" name="img_rote" autocomplete="off" class="layui-input">
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="see_img" style="margin-left: 5px;">查看</button>
    </div>
</form>
<br />
    <div class="layui-body" style="margin-left: 15%;">
        <div style="height:100%;">
        
        <img id="imgMain" class="imgMain" src="../images/2.jpg" style="width:30%;" height="30%";></iframe>
    
        </div>
      </div>
               
          
<script src="../js/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
<script>
    layui.use(['element', 'layer', 'util','jquery'], function(){
    $ = layui.jquery;
    element = layui.element;
    layer = layui.layer;
    util = layui.util;
    form = layui.form;
    layer = layui.layer

//监听提交
form.on('submit(see_img)', function(data){
    var rote=data.field.img_rote;
    // rote.replace(/[<>&"]/g,function(c){return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;','\'':' '}[c];});
    $("#imgMain").attr("src",rote);
    return 1;
  });
});
</script>

</body>
</html>