<?php
//时区设置
date_default_timezone_set("UTC");
//屏蔽错误
error_reporting(0);


/**
 * 管理员session状态注销
 */
function session_un()
{
	unset($_SESSION["pass_manager_user"]);
}

/**
 * 验证码session状态注销
 */
function session_code()
{
    unset($_SESSION["authcode"]);
}

/**
 * 用户session注册
 */
function session_set_user($user,$key_value)
{
    $_SESSION["user"]=$user;
    $_SESSION["key"]=$key_value;
    $user = $user.rand(1000,9999);
	$_SESSION["pass_user"]=$user;
	return $user;
}

/**
 * 管理员session注册
 */
function session_set_admin($user)
{
    $_SESSION["admin"]=$user;
	$user = $user.rand(10000000000,99999999999);
    $_SESSION["pass_manager_user"]=$user;
	return $user;
}

// 
/**
 * 替换常用密码
 */
function pass_re($pass)
{
	$pass = str_replace("1111111","11111*1111",$pass);
	return $pass;
}
/**
 * 过滤参数
 */
function xss_safe($safe)
{
	$result = htmlspecialchars($safe);
	$result = str_ireplace("'","&#x27;",$result);
	return $result;
}

function xss_unsafe($safe)
{
	$result = html_entity_decode($safe);
	$result = str_ireplace("&#x27;","'",$result);
	return $result;
}

/**
 * 
 */
class MYsql_ex
{
    //私有的属性
    private static $dbcon=false;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $db;
    private $charset;
    private $link;

    function __construct($config=array())
    {
        $this->host = 'localhost';
        $this->port = '3306';
        $this->user = '账号';
        $this->pass = '密码';
        $this->db =   '数据库';
        $this->charset=isset($arr['charset']) ? $arr['charset'] : 'utf8';
        //连接数据库
        $this->db_connect();
        //选择数据库
        $this->db_usedb();
        //设置字符集
        $this->db_charset();
    }
    //连接数据库
    private function db_connect(){
        $this->link=mysqli_connect($this->host.':'.$this->port,$this->user,$this->pass);
        if(!$this->link){
        echo "数据库连接失败<br>";
        // echo "错误编码".mysqli_errno($this->link)."<br>";
        // echo "错误信息".mysqli_error($this->link)."<br>";
        exit;
        }
    }
    //设置字符集
    private function db_charset(){
        mysqli_query($this->link,"set names {$this->charset}");
    }
    //选择数据库
    private function db_usedb(){
        mysqli_query($this->link,"use {$this->db}");
    }
    //私有的克隆
    private function __clone(){
        die('clone is not allowed');
    }
    //公用的静态方法
    public static function getIntance(){
        if(self::$dbcon==false){
        self::$dbcon=new self;
        }
        return self::$dbcon;
    }
    //执行sql语句的方法
    public function query($sql){
        $res=mysqli_query($this->link,$sql);
        if(!$res){
        echo "sql语句执行失败<br>";
        // echo "错误编码是".mysqli_errno($this->link)."<br>";
        // echo "错误信息是".mysqli_error($this->link)."<br>";
    }
        return $res;
    }

    //用户注册
	public function insert_user($user,$pass,$key)
	{
        $tp_value=0;
        $register_time=time();
		$sql = "INSERT INTO `tp_user` (`user`, `pass`, `key_value`,`tp_value`,`register_time`) values (?,?,?,?,?)";
		$mysqli_stmt = $this->link->prepare($sql);
		$mysqli_stmt->bind_param("sssii",$user,$pass,$key,$tp_value,$register_time);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;
    }
    
    //用户登录
    public function select_user($user,$pass)
	{
		$sql = "select user,key_value  from `tp_user` where user=(?) and pass=(?)";
		$mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("ss",$user,$pass);
        $mysqli_stmt->bind_result($user,$key_value);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return array($user,$key_value);   
    }

    //管理员登录
    public function select_admin($user,$pass)
	{
		$sql = "select user_admin  from `works_admin` where user_admin=(?) and user_pass=(?)";
		$mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("ss",$user,$pass);
        $mysqli_stmt->bind_result($user_admin);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return $user_admin;   
    }

    //查询注册key
    public function select_key($key)
	{
		$sql = "select `key_value` , `key_num`  from `key_register` where key_value=(?)";
		$mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$key);
        $mysqli_stmt->bind_result($key_value,$key_num);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return array($key_value,$key_num);   
    }

    //查询用户是否已注册
    public function select_users($user)
    {
        $sql = "select `user` from `tp_user` where user=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$user);
        $mysqli_stmt->bind_result($user_name);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return $user_name; 
    }

    //查询所有用户并按照id排序
    public function select_user_number()
    {
        $sql="SELECT * FROM	`tp_user` ORDER BY id ASC";
        $mysqli_stmt = $this->link->prepare($sql);
        // $mysqli_stmt->bind_param();
        $mysqli_stmt->bind_result($id,$user,$pass,$key_value,$tp_value,$register_time);
        $res = $mysqli_stmt->execute();
        $users=array();
        $datas=array();
		while($mysqli_stmt->fetch()){
            $users['id']=$id;
            $users['user']=$user;
            $users['key_value']=$key_value;
            if($tp_value==1){
                $tp_value='是';
            }else{
                $tp_value='否';
            }
            $users['tp_value']=$tp_value;
            $users['register_time']=date("Y-m-d H:i:s",$register_time);
            $datas[] = $users;
        }
		$mysqli_stmt->close();
		return $datas;
    }

    //修改注册key更改为已注册
    public function update_key($key)
	{
		$sql = "update `key_register` set `key_num` = 1 where key_value=(?)";
		$mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$key);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;   
    }

    //提交投票作品
    public function insert_works($works_name,$works_author,$works_route)
    {
        $works_number=0;
        $works_time=time();
        $sql="INSERT INTO `tp_works`(`works_name`,`works_author`,`works_route`,`works_number`,`works_time`)VALUES((?),(?),(?),?,?)";
        $mysqli_stmt = $this->link->prepare($sql);
		$mysqli_stmt->bind_param("sssii",$works_name,$works_author,$works_route,$works_number,$works_time);
        $res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;
    }

    //查询作品并按照投id排序
    public function select_works()
    {
        $sql="SELECT * from `tp_works` ORDER BY id ASC";
        $mysqli_stmt = $this->link->prepare($sql);
        //$mysqli_stmt->bind_param();
        $mysqli_stmt->bind_result($id,$works_name,$works_author,$works_route,$works_number,$works_time);
        $res = $mysqli_stmt->execute();
        $works=array();
        $datas=array();
		while($mysqli_stmt->fetch()){
            $works['id']=$id;
            $works['works_name']=$works_name;
            $works['works_author']=$works_author;
            $works['works_route']=$works_route;
            $works['works_number']=$works_number;
            $works['works_time']=date("Y-m-d H:i:s",$works_time);
            $datas[] = $works;
        }
		$mysqli_stmt->close();
		return $datas;
    }

    //查询作品票数
    public function select_works_number()
    {
        $sql="select works_route from `tp_works` ORDER BY works_number DESC";
        $mysqli_stmt=$this->link->prepare($sql);
        $mysqli_stmt->bind_result($works_route);
        $res=$mysqli_stmt->execute();
        $works=array();
        $datas=array();
        while($mysqli_stmt->fetch()){
            $works['works_route']=$works_route;
            $datas[] = $works;
        }
        $mysqli_stmt->close();
        return $datas;
    }

    //对作品进行投票
    public function update_works_number($id)
    {
        $sql="UPDATE `tp_works` SET works_number=works_number+1 where id=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("i",$id);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;
    }

    //通过搜索作者搜索信息
    public function search_works_author($works_author)
    {
        $sql="SELECT * FROM `tp_works` where works_author LIKE CONCAT('%',?,'%') ORDER BY id ASC";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$works_author);
        $mysqli_stmt->bind_result($id,$works_name,$works_author,$works_route,$works_number,$works_time);
        $res = $mysqli_stmt->execute();
        $works=array();
        $datas=array();
		while($mysqli_stmt->fetch()){
            $works['id']=$id;
            $works['works_name']=$works_name;
            $works['works_author']=$works_author;
            $works['works_route']=$works_route;
            $works['works_number']=$works_number;
            $works['works_time']=date("Y-m-d H:i:s",$works_time);
            $datas[] = $works;
        }
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return $datas;
    }

    //搜索用户
    public function search_users_user($users)
    {
        $sql="SELECT * FROM `tp_user` where user LIKE CONCAT('%',?,'%') ORDER BY id ASC";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$users);
        $mysqli_stmt->bind_result($id,$user,$pass,$key_value,$tp_value,$register_time);
        $res = $mysqli_stmt->execute();
        $users=array();
        $datas=array();
		while($mysqli_stmt->fetch()){
            $users['id']=$id;
            $users['user']=$user;
            $users['pass']=$pass;
            $users['key_value']=$key_value;
            if($tp_value==1){
                $tp_value='是';
            }else{
                $tp_value='否';
            }
            $users['tp_value']=$tp_value;
            $users['register_time']=date("Y-m-d H:i:s",$register_time);
            $datas[] = $users;
        }
		$mysqli_stmt->fetch();
		$mysqli_stmt->close();
		return $datas;
    }

    //修改作品表
    public function update_works($id,$works_name,$works_author,$works_route,$works_number,$works_time){
        $works_time=strtotime($works_time);
        $sql="UPDATE `tp_works` set works_name=(?),works_author=(?),works_route=(?) ,works_number=(?),works_time=(?) WHERE id=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("sssiii",$works_name,$works_author,$works_route,$works_number,$works_time,$id);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;
    }

    //修改用户表
    public function update_users($id,$user,$key_value,$key_num,$register_time){
        $register_time=strtotime($register_time);
        $sql="UPDATE `tp_user` SET user=(?),key_value=(?),tp_value=(?),register_time=(?) where id=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("ssiii",$user,$key_num,$key_num,$register_time,$id);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
		}
		return 1;
    }

    //删除作品
    public function del_work($id){
        $sql="DELETE FROM `tp_works` where id=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("i",$id);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
        }
		return 1;
    }

    //删除用户
    public function del_user($id){
        $sql="DELETE FROM `tp_user` where id=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("i",$id);
		$res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
        }
		return 1;
    }

    //查找用户今日是否能投票
    public function select_user_tp_num($user){
        $sql="SELECT tp_value from `tp_user` where user=(?)";
        $mysqli_stmt=$this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$key_value);
        $mysqli_stmt->bind_result($tp_value);
        $res=$mysqli_stmt->execute();
        $mysqli_stmt->fetch();
        $mysqli_stmt->close();
        return $user;
    }

    //使用今日票数
    public function tp_works_user_value($user){
        $sql="UPDATE `tp_user` set tp_value=tp_value+1 WHERE user=(?)";
        $mysqli_stmt = $this->link->prepare($sql);
        $mysqli_stmt->bind_param("s",$user);
        $res = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		if (!$res) {
			echo "sql插入语句执行失败<br>";
            return 0;
        }
		return 1;
    }
}
