<?php 
  session_start();
  if(empty($_SESSION["pass_user"])){
    header("Location:Login.html");    
}?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>用户投票</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>

<body>
  <div class="layui-form-item" hidden="hidden">
    <div class="layui-inline">
      <label class="layui-form-label">自动切换</label>
      <div class="layui-input-block">
        <input type="checkbox" name="switch" lay-skin="switch" checked="" lay-text="ON|OFF" lay-filter="autoplay">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label" style="width: auto;">时间间隔</label>
      <div class="layui-input-inline" style="width: 120px;">
        <input type="tel" name="interval" value="3000" autocomplete="off" placeholder="毫秒" class="layui-input demoSet">
      </div>
    </div>
  </div>
</div>

<div class="layui-carousel" id="test3" lay-filter="test4" style="margin-top: 5px;margin-left: 30%;">
  <div carousel-item="">
    <div><img width="778px" height="440px;" src=<?php echo($_SESSION["works_pm"][0]["works_route"]);?>></div>
    <div><img width="778px" height="440px;" src=<?php echo($_SESSION["works_pm"][1]["works_route"]);?>></div>
    <div><img width="778px" height="440px;" src=<?php echo($_SESSION["works_pm"][2]["works_route"]);?>></div>
  </div>
</div> 

<ul class="layui-nav layui-layout-right" style="background: #FFFFFF">
      <li class="layui-nav-item layui-hide layui-show-md-inline-block">
        <a href="Personal_Center.php">
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
<!-- <div align="center" style="margin-top: 5px;">投票前三展示</div> -->
 
          
<script src="../js/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
<script>
layui.use(['element', 'layer', 'util','jquery','carousel'], function(){
    $ = layui.jquery;
    element = layui.element;
    layer = layui.layer;
    util = layui.util;
    form = layui.form;
    layer = layui.layer;
    carousel=layui.carousel  

  $('.layui-btn').click(function () {
    
    var id=$(this).attr("id");
    var action='tp_works_num';
    var data={"id":""+id+"","action":""+action+""};
    
    data=JSON.stringify(data);
    console.log(data);
    $.ajax({
      url: '../config/api/api.php'
      ,method:"post"
      ,data:data
      ,success: function(e){
        if(e=='success'){
        
        layer.msg('恭喜你，投票成功',{icon:1,time:1000},function(){
          layer.close();
          location.reload();
        });
        }else{
          layer.msg('今日投票次数已上限！',{icon:4,time:1000},function(){
            layer.close();
            location.reload();
          });
        }      
      }
      ,error:function(){
        layer.msg("发生错误",{icon:4});
      }
    });
  

  });    

  
  //图片轮播
  carousel.render({
    elem: '#test3'
    ,width: '778px'
    ,height: '440px'
    ,interval: 5000
  });
  
  
  var $ = layui.$, active = {
    set: function(othis){
      var THIS = 'layui-bg-normal'
      ,key = othis.data('key')
      ,options = {};
      
      othis.css('background-color', '#5FB878').siblings().removeAttr('style'); 
      options[key] = othis.data('value');
      ins3.reload(options);
    }
  };

  //头部事件
  util.event('lay-header-event', {
    menuRight: function(){
      layer.open({
        type: 1
        ,content: '<div style="padding: 15px;">1111</div>'
        ,area: ['360px', '100%']
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

<?php
include_once '../config/config.php';

$db_con=new MYsql_ex();
if($db_con -> connect_errno){
    $db_con=$db_con->getIntance();
}
$row=$db_con->select_works();
$users_num=count($row);
// echo '<h2>用户信息<h2>';
echo "<br />";
echo '<div class="layui-row  layui-col-space1">';
for ($i=0; $i <$users_num;$i++) { 
   $works_name = $row[$i]['works_name'];
   $id = $row[$i]['id'];
   $works_author = $row[$i]['works_author'];
   $works_route = $row[$i]['works_route'];
   $works_number = $row[$i]['works_number'];

  echo '<div class="layui-col-md3" style="margin-top:2%;margin-left:5%;">
  <div class="grid-demo grid-demo-bg1" align="center">
  <img height="200" width="250" src="'.$works_route.'"><br>
  <table border="2" style="text-align: center;"><tr><td>
  编号：</td><td width=50px;>'.$id.'  </td><td width=80px;>作者：</td><td>'.$works_author.'</td></tr><br>
  <tr><td>作品名：</td><td colspan="3" width="150px;">'.$works_name.'</td></tr><br>
  <tr><td>目前票数：</td><td colspan="3">'.$works_number.'</td></tr></table><br>
  <button type="button" class="layui-btn layui-btn-radius" value="'.$id.'" id="'.$id.'" >投票'.$id.'号</button>
  </div>
  </div>';

}
echo "</div>";


$_SESSION["works_pm"]=$db_con->select_works_number();

