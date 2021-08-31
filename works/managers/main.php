<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>更新日志</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../js/layui/css/layui.css"  media="all">
  <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body style="padding: 15px;">
 
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 25px;">
  <legend>时间线</legend>
</fieldset>
请选择要计算的日期：
<div class="layui-inline">
  <input type="text" class="layui-input" id="test1" value="2025-12-31 23:59:59">
</div>
<blockquote class="layui-elem-quote" style="margin-top: 10px;">
  <div id="test2"></div>
</blockquote>
 
<br>
<div class="layui-progress">
    <div class="layui-progress-bar layui-bg-green" lay-percent="20%"></div>
  </div>
<br>

<ul class="layui-timeline">
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title">8月20日</h3>
      <p>
        增加辅助模块
        <br>可以输入路径查看相应图片      <i class="layui-icon"></i>
      </p>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title">8月18日</h3>
      <p>
        本网站后台管理功能包括用户、作品管理
        <br>其他需要的功能请自行开发。
        <br>建议参考网址：<a href="https://www.baidu.com">https://www.baidu.com</a> <i class="layui-icon"></i>
      </p>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title">8月1日</h3>
      <p>此时脑子刚出现开发此网站的想法</p>
      <ul>
        <li>Version：1.0         <i class="layui-icon"></i></li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <div class="layui-timeline-title">过去</div>
    </div>
  </li>
</ul>  
              
          
<script src="../js/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
<script>
layui.use(['util', 'laydate', 'layer'], function(){
  var util = layui.util
  ,laydate = layui.laydate
  ,$ = layui.$
  ,layer = layui.layer;
  
  //倒计时
  var thisTimer, setCountdown = function(y, M, d, H, m, s){
    var endTime = new Date(y, M||0, d||1, H||0, m||0, s||0) //结束日期
    ,serverTime = new Date(); //假设为当前服务器时间，这里采用的是本地时间，实际使用一般是取服务端的
     
    clearTimeout(thisTimer);
    util.countdown(endTime, serverTime, function(date, serverTime, timer){
      var str = date[0] + '天' + date[1] + '时' +  date[2] + '分' + date[3] + '秒';
      lay('#test2').html(str);
      thisTimer = timer;
    });
  };
  setCountdown(2025,12,31);
  
  laydate.render({
    elem: '#test1'
    ,type: 'datetime'
    ,done: function(value, date){
      setCountdown(date.year, date.month - 1, date.date, date.hours, date.minutes, date.seconds);
    }
  });
});
</script>

</body>
</html>