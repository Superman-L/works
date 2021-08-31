<?php
include_once '../config.php';

$json_string = file_get_contents('php://input');
$data = json_decode($json_string, true);
session_start();

switch($data['action']){
    case update_works:  //修改作品
        if(empty($data['id'])||empty($data['works_name'])||empty($data['works_author'])||empty($data['works_route'])||empty($data['works_number'])||empty($data['works_time'])){
            die('参数错误');
        }
        //xss过滤
        $id=xss_safe($data['id']);
        $works_name=xss_safe($data['works_name']);
        $works_author=xss_safe($data['works_author']);
        $works_route=xss_safe($data['works_route']);
        $works_number=xss_safe($data['works_number']);
        $works_time=xss_safe($data['works_time']);

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->update_works($id,$works_name,$works_author,$works_route,$works_number,$works_time);
        if($row==1){
            echo "success";
        }else{
            die('失败');
        }
        break;

    case del_works:     //删除作品
        if(empty($data['id'])){
            die('参数错误');
        }
        $id=xss_safe($data['id']);

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        
        $row=$db_con->del_work($id);
        if($row==1){
            echo "success";
        }else{
            die('失败');
        }
        break;

    case del_users:
        if(empty($data['id'])){
            die('参数错误');
        }
        $id=xss_safe($data['id']);

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        
        $row=$db_con->del_user($id);
        if($row==1){
            echo "success";
        }else{
            die('失败');
        }
        break;

    case tp_works_num:  //投票方法
        if(empty($data['id'])){
            die('参数错误');
        }
        $id=xss_safe($data['id']);

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        // session_start();
        $user=$_SESSION["user"];
        $row=$db_con->select_user_tp_num($user);
        if($row==0){
            if($db_con -> connect_errno)
            {
                $db_con=$db_con->getIntance();
            }
            $row=$db_con->update_works_number($id);
            if($row==1)
            {
                if($db_con -> connect_errno)
                {
                    $db_con=$db_con->getIntance();
                }
                $row=$db_con->tp_works_user_value($user);
                if($row == 1)
                {
                    echo "success";
                    break;
                }else
                {
                    die('失败');
                }
            }else
            {
                die('失败');
            }
            break;
        }else
        {
            die("你今天次数已用完！");
        }

    case upload_work:   //上传作品
        if(empty($data['works'])||empty($data['author']))
        {
            die('参数错误');
        }
        // session_start();

        $works_rote=$_SESSION['work_rote'];
        if(empty($works_rote))
        {
            die('你还未上传呢');
        }
        $works_name=xss_safe($data['works']);
        $works_author=xss_safe($data['author']);
        

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }

        $row=$db_con->insert_works($works_name,$works_author,$works_rote);
        if($row==1){
            echo "success";
            unset($_SESSION['work_rote']);
        }else{
            if(file_exists("../".$works_rote)){
                unlink("../".$works_rote);
            }
            unset($_SESSION['work_rote']);
            die('失败');
        }
        break;
    
    case admin_login:
        //判断传参是否为空
        if(empty($data['username'])||empty($data['password'])||empty($data['captcha'])){
            die('参数错误');
        }

        //xss过滤
        $user=xss_safe($data['username']);
        $pass=xss_safe($data['password']);
        $code=xss_safe($data['captcha']);


        //判断验证码是否正确
        if($_SESSION['authcode']!==$code){
            session_code();
            die('验证码错误');
        }
        session_code();

        //判断账户密码是否正确
        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_admin($user,$pass);
        if(empty($row)){
            die('账号或密码错误');
        }
        session_set_admin($row);

        echo 'ok';
        break;

    case  user_login:
        //判断传参是否为空
        if(empty($data['username'])||empty($data['password'])||empty($data['captcha'])){
            die('参数错误');
        }

        //xss过滤
        $user=xss_safe($data['username']);
        $pass=xss_safe($data['password']);
        $code=xss_safe($data['captcha']);

        session_start();
        //判断验证码是否正确
        if($_SESSION['authcode']!==$code){
            session_code();
            echo '<script>alert("验证码错误")</script>';
            die('验证码错误');
        }
        session_code();

        //判断账户密码是否正确
        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_user($user,$pass);
        if(empty($row[0])){
            die('账号或密码错误');
        }
        session_set_user($row[0],$row[1]);

        echo 'ok';
        break;

    case user_register:
        //判断传参是否为空
        if(empty($data['username'])||empty($data['password'])||empty($data['key'])||empty($data['captcha'])){
            die('参数错误');
        }

        //xss过滤
        $user=xss_safe($data['username']);
        $pass=xss_safe($data['password']);
        $key =xss_safe($data['key']);
        $code=xss_safe($data['captcha']);

        //判断验证码是否正确
        if($_SESSION['authcode']!==$code){
            session_code();
            die('验证码错误');
        }
        session_code();

        //判断密码的长度是否是32位
        if(strlen($pass)!==32)
        {
            die('error_pass');
        }

        //判断key是否正确
        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_key($key);
        if(empty($row[0])){
            die('error_key');
        }
        if($row[1]==1){
            die('error_key');
        }

        //判断用户名是否已注册
        //$db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_users($user);

        if($row){
            die('error_zhuce');
        }

        //注册用户
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->insert_user($user,$pass,$key);
        if($row==0)
        {
            die('数据错误');
        }

        //修改注册key为已注册
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->update_key($key);
        if($row==0)
        {
            die('失败');
        }
        echo 'success';
        break;
}