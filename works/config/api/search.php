<?php

include_once '../config.php';

$action=$_POST['action'];
if(empty($action))
{
    die('参数错误');   
}

switch ($action) {
    case works:
        $works_author=$_POST['works_author'];

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->search_works_author($works_author);
        $json = array('code' => 0,'message' =>"",'data'=> $row);
        echo json_encode($json);
        break;

    case users:
        $users=$_POST['users'];

        $db_con=new MYsql_ex();
        if($db_con -> connect_errno){
            $db_con=$db_con->getIntance();
        }
        $row=$db_con->search_users_user($users);
        // $users_num=count($row);
        // for($i=0;$i<$users_num;$i++){
        //     if($row[$i]['tp_value']==1){
        //         $row[$i]['tp_value']='是';
        //     }else{
        //         $row[$i]['tp_value']='否';
        // }
        $json = array('code' => 0,'message' =>"",'data'=> $row);
        echo json_encode($json);
        break;
}