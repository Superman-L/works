<?php 
  session_start();
  if(empty($_SESSION["pass_user"])){
    header("Location:Login.html");    
}?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>个人中心</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
              
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
  <legend>个人中心</legend>
</fieldset>
 
<div class="layui-form-item">
  <div class="layui-inline">
    <label class="layui-form-label">用户名</label>
    <div class="layui-input-inline">
      <input type="text" name="user" lay-verify="required|user" autocomplete="off" class="layui-input" disabled value=<?php echo $_SESSION["user"]; ?>>
    </div>
  </div>
  <div class="layui-inline">
    <label class="layui-form-label">注册key</label>
    <div class="layui-input-inline">
      <input type="text" name="key" lay-verify="required|key" autocomplete="off" class="layui-input" disabled value=<?php echo $_SESSION["key"]; ?>>
    </div>
  </div>
</div>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 40px;">
  <legend>作品上传</legend>
</fieldset> 
<form class="layui-form">
<div class="layui-form-item" style="margin-top: 50px;margin-left: 20px;">
  <div class="layui-inline">
    <label class="layui-form-label">作品名称</label>
    <div class="layui-input-inline">
      <input type="text" name="works" id="works" autocomplete="off" class="layui-input">
    </div>
  </div>

  <div class="layui-inline">
    <label class="layui-form-label">作者</label>
    <div class="layui-input-inline">
      <input type="text" name="author" id="author" autocomplete="off" class="layui-input">
    </div>
  </div><br>

<div class="layui-upload-drag" id="test10" style="margin-top: 50px;margin-left: 5%;">
  <i class="layui-icon"></i>
  <p>点击上传，或将文件拖拽到此处</p>
  <div class="layui-hide" id="uploadDemoView">
    <hr>
    <img src="" alt="上传成功后渲染" style="max-width: 196px">
  </div>
</div>

<div class="layui-form-item" style="margin-top: 20px;">
      <div class="layui-input-block">
        <button type="Submit" class="layui-btn" lay-submit="" lay-filter="Submit">立即提交</button>
      </div>
</div>
</form>

<ul class="layui-nav layui-layout-right" style="background: #FFFFFF">
      <li class="layui-nav-item layui-hide layui-show-md-inline-block">
        <a href="index.php">
          <img src="/images/2.jpg" class="layui-nav-img">
          <span style="color: #000000;"><?php echo $_SESSION["user"];?><span>
        </a>
        <dl class="layui-nav-child">          
          <dd><a href="./logout.php">退出 </a></dd>
        </dl>
      </li>
      <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
        <a href="javascript:;">
          <i class="layui-icon layui-icon-more-vertical"></i>
        </a>
      </li>
</ul>
          
<script src="../js/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
<script>
layui.use(['form','element','layer','jquery','upload'], function(){
  $ = layui.jquery;
  element = layui.element;
  upload =layui.upload;
  layer = layui.layer;
  form = layui.form;
  layer = layui.layer
  
  upload.render({
    elem: '#test10'
    ,url: '/config/upload.php'
    ,accept:' images'  
    ,exts:'jpg|png|jpeg'
    ,done: function(res){
      if(res.code == 0){
      layer.msg('上传成功');
      layui.$('#uploadDemoView').removeClass('layui-hide').find('img').attr('src', res.data.rote);
      }else{
        layer.msg('上传失败');
      }
    }
  });

  form.on('submit(Submit)', function(data){
    data.field.action='upload_work';
    data=JSON.stringify(data.field);
    
    $.ajax({
      url:"/config/api/api.php"
      ,type:'POST'
      ,data:data
      ,success: function(data){
        if(data=='success')
        {
          layer.msg('你已上传成功，去首页看看吧！',{icon:1,time:1000});
        }
        else if(data=='参数错误')
        {
          layer.msg('参数不能为空',{icon:2,time:1000});
        }
        else if(data== '你还未上传呢')
        {
          layer.msg('你还没上传呢！',{icon:2,time:1000});
        }
        else{
          layer.msg('上传错误',{icon:2,time:1000});
        }
        }
      });
      return false;
    });
  
});
</script>

</body>
</html>