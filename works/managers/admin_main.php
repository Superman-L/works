<?php
header("content-type:text/html;charset=utf-8");
session_start();
if(empty($_SESSION["pass_manager_user"])){
  header("Location:Login.html");  
}
include_once '../config/config.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>后台管理</title>
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
</head>
<body>
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo layui-hide-xs layui-bg-black">功能</div>
    <!-- 头部区域（可配合layui 已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <!-- 移动端显示 -->
      <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-header-event="menuLeft">
        <i class="layui-icon layui-icon-spread-left"></i>
      </li>
      
      <li class="layui-nav-item layui-hide-xs"><a href="">首页</a></li>
      <!-- <li class="layui-nav-item layui-hide-xs"><a href="">nav 2</a></li>
      <li class="layui-nav-item layui-hide-xs"><a href="">nav 3</a></li>
      <li class="layui-nav-item">
        <a href="javascript:;">nav groups</a>
        <dl class="layui-nav-child">
          <dd><a href="">menu 11</a></dd>
          <dd><a href="">menu 22</a></dd>
          <dd><a href="">menu 33</a></dd>
        </dl>
      </li> -->
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item layui-hide layui-show-md-inline-block">
        <a href="javascript:;">
          <img src="/images/1.jpg" class="layui-nav-img">
          <?php echo($_SESSION["admin"]);?>
        </a>
        <dl class="layui-nav-child">          
          <dd><a href="./logout.php">退出 </a></dd>
        </dl>
      </li>
      <!-- <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
        <a href="javascript:;">
          <i class="layui-icon layui-icon-more-vertical"></i>
        </a>
      </li> -->
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="javascript:;">管理</a>
          <dl class="layui-nav-child">
            <dd><a href="./users_information.php">用户管理</a></dd>
            <dd><a href="./works_information.php">作品管理</a></dd>
          </dl>
        </li>
        <li class="layui-nav-item layui-nav-itemed">
        <a class="" href="javascript:;">辅助功能</a>
        <dl class="layui-nav-child">
            <dd><a href="./see_img.php">查看图片</a></dd>
        </dl>
      </li>
        <!-- <li class="layui-nav-item"><a href="./see_img.html">查看图片</a></li> -->
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="height:100%;">
    
    <iframe id="iframeMain" class="iframeMain" src="./main.php" style="width:100%;" height="100%";></iframe>

    </div>
  </div>
  
  <div class="layui-footer" style="text-align:center;">
    <!-- 底部固定区域 -->
    本网站所有权由阿呆所有
  </div>
</div>
<script src="../js/layui/layui.js"></script>
<script>
//JS 
layui.use(['element', 'layer', 'util','jquery'], function(){
    $ = layui.jquery;
    element = layui.element;
    layer = layui.layer;
    util = layui.util;
    form = layui.form;
    layer = layui.layer

    $(document).ready(function(){
    $("dd>a").click(function(e) {
        e.preventDefault();
        $("#iframeMain").attr("src",$(this).attr("href"));
    });
});

  //头部事件
  util.event('lay-header-event', {
    //左侧菜单事件
    menuLeft: function(othis){
      layer.msg('展开左侧菜单的操作', {icon: 0});
    }
    ,menuRight: function(){
      layer.open({
        type: 1
        ,content: '<div style="padding: 15px;">处理右侧面板的操作</div>'
        ,area: ['260px', '100%']
        ,offset: 'rt' //右上角
        ,anim: 5
        ,shadeClose: true
      });
    }
  });
  
});


</script>
</body>
</html>
