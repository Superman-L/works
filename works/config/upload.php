<?php 
  session_start();
  if(empty($_SESSION["pass_user"])){
    header("Location:Login.html");    
}?>

<?php
if($_FILES["file"]["name"]){
// 允许上传的图片后缀
$allowedExts = array("jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
// echo $_FILES["file"]["size"];
$extension = trim(end($temp));     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 1048576)   // 小于 1m
&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        //文件名重命名
        $tmp_name=time()*2/3;
        $tem=md5($tmp_name).".".$extension;

        //判断目录是否存在，不存在就创建目录
        $tmp_time=time();
        $tmp_catalogue="../upload/".date("Ymd",$tmp_time)."/";
        if(!file_exists($tmp_catalogue)){
          mkdir($tmp_catalogue, 777);
        }

        if (file_exists($tmp_catalogue. $tem))
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], $tmp_catalogue . $tem);
            $_SESSION['work_rote']=$tmp_catalogue . $tem;
            echo "{\"code\":0,\"msg\":\"\",\"data\":{\"rote\":\"".$tmp_catalogue.$tem."\"}}";


        }
    }
}
else
{
    echo "非法的文件格式";
}
}