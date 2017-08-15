<?php
if(!defined("APP_NAME")){
   exit('非法载入！');
}
if(file_exists(APP_NAME)==false){
    mkdir(APP_NAME,0777,true);
    mkdir(APP_NAME.'/views',0777,true);
    mkdir(APP_NAME.'/res',0777,true);
    mkdir(APP_NAME.'/views/index',0777,true);
    mkdir(APP_NAME.'/model',0777,true);
    mkdir(APP_NAME.'/cache',0777,true);
    mkdir(APP_NAME.'/control',0777,true);
    $config = '<?php 
    //$Ben_config[\'DB_TYPE\']=\'mysql\';
    //$Ben_config[\'DB_SERVER\']=;
    //$Ben_config[\'DB_DB\']=;
    //$Ben_config[\'DB_USER\']=;
    //$Ben_config[\'DB_PASSWORD\']=;
    //$Ben_config[\'SMTP_SERVER\']=;
    //$Ben_config[\'SMTP_MAIL\']=;
    //$Ben_config[\'SMTP_USER\']=;
     //$Ben_config[\'SMTP_PASSWORD\']=;';
    file_put_contents(APP_NAME."/config.php",$config);
    $action ='<?php
class indexAction extends Action{
    public function index(){
        echo"<div style=margin-left:5%;margin-top:5%;><font size=7>:)</font><br>你的项目创建成功，进入 /'.APP_NAME.'/control/目录来编写你的代码,尽情挥发吧！XD</div>";
    }
}';
    file_put_contents(APP_NAME."/control/index.class.php",$action);
}