<?php
// Ben框架    php快速开发
// 版本：2.0(20150913)
// 作者：诺尘
// 联系方式：1272294450@qq.com
header("Content-type: text/html; charset=utf-8");
if(!defined("APP_NAME")){
   exit('非法载入！');
}
session_start();
// if(!isset($_SESSION)){
//     session_start();
// }
require "install.php";
try{
require APP_NAME."/config.php";
require "lib/common.php";
if(@issame($Ben_config['DB_TYPE'],'mysql')== true and  @$Ben_config['DB_SERVER']!=null){
    require "lib/mysql.class.php";
    if(@$Ben_config['DB_PORT']==null){
        $Ben_config['DB_PORT']=3306;
    }
    $DB = new mysql($Ben_config['DB_SERVER'],$Ben_config['DB_DB'],$Ben_config['DB_USER'],$Ben_config['DB_PASSWORD'],$Ben_config['DB_PORT']);
}
if(@issame($Ben_config['DB_TYPE'],'sqlite')== true and  @$Ben_config['DB_SERVER']!=null){
	require "lib/sqlite.class.php";
	$DB = new sqlite($Ben_config['DB_SERVER']);
}
require "lib/Action.class.php";
$file = dir(APP_NAME."/control");
while(($name=$file->read())==true){
   if($name!='.' && $name!='..'){
        require APP_NAME."/control/".$name;
   }
}
$file = dir(APP_NAME."/model");
while(($name=$file->read())==true){
   if($name!='.' && $name!='..'){
        require APP_NAME."/model/".$name;
   }
}

$url = explode('/', $_SERVER['REQUEST_URI']);
@$class = $url[2]."Action";
@$method = $url[3];
if($class=='Action'){
  $class="indexAction";
}
if($method==null){
  $method="index";
}
if(class_exists($class)==false){
    $debug=file_get_contents('Ben/lib/debug.html');
    exit(str_replace('{$message}','<h2>404</h2>并不存在'.$class.'控制层!',$debug));
}
for ($i=4; $i < count($url); $i++) { 
  if($i%2==0){
    $name = $url[$i];
    @$value = $url[$i+1];
    $_GET[$name]=$value;
  }
}
$object = new $class();
if(method_exists($object,$method)==false){
    $debug=file_get_contents('Ben/lib/debug.html');
    exit(str_replace('{$message}','<h2>404</h2>'.$class.'控制层并不存在'.$method.'方法!',$debug));
}
$object->$method();
}catch(Exception $e){
  $debug=file_get_contents('Ben/lib/debug.html');
  echo str_replace($debug,'{$message}',$e->getMessage());
}