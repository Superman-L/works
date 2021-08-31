<?php
header("content-type:text/html;charset=utf-8");
session_start();
if(empty($_SESSION["pass_manager_user"])){
  header("Location:../../user/Login.html");  
}

include_once '../config.php';
$action=$_GET['action'];

switch($action){
    case users_data:
        $db_con=new MYsql_ex();
        if($db_con ->connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_user_number();
        $data = json_encode($row);
        $result="{\"code\":\"0\",\"data\":$data}";
        echo $result;
        break;

    case works_data:
        $db_con=new MYsql_ex();
        if($db_con ->connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->select_works();
        $data = json_encode($row);
        $result="{\"code\":\"0\",\"msg\":\"\",\"data\":$data}";
        echo $result;
}