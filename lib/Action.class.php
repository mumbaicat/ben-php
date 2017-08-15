<?php
class Action{
    static $tpl_vars = array();
    public function success($text,$url=null){
        if($url == null){
            $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        }
        $data = file_get_contents('Ben/lib/success.html');
        $data = str_replace('{$url}', $url, $data);
        $data = str_replace('{$text}', $text, $data);
        echo $data;
    }
    public function error($text,$url=null){
        if($url == null){
            $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        }
        $data = file_get_contents('Ben/lib/error.html');
        $data = str_replace('{$url}', $url, $data);
        $data = str_replace('{$text}', $text, $data);
        echo $data;
        exit();
    }
    public function assign($name,$value){
        if($name!=null){
            @$this->tpl_vars[$name]=$value;
        }
    }
    public function display($method="Index",$class="Index"){
        if(!file_exists(APP_NAME."/views/".$class."/".$method.".html")){
            return false;
        }
        $filedata = file_get_contents(APP_NAME."/views/".$class."/".$method.".html");
        $match= '/\{\s*\$([a-zA-Z_][za-zA-Z0-9_]*)\s*\}/i';
        $replace = '<?php echo $this->tpl_vars["${1}"];?>';
        $newdata = preg_replace($match, $replace, $filedata);
        $match= '/\{\s*\%([a-zA-Z_][za-zA-Z0-9_]*)\s*\}/i';
        $replace = '<?php echo ${1};?>';
        $newdata = preg_replace($match, $replace, $newdata);
        if(!file_exists(APP_NAME."/cache/".$class)){
            mkdir(APP_NAME.'/cache/'.$class,0777,true);
        }
        file_put_contents(APP_NAME."/cache/".$class."/".$method.".cache", $newdata);
        include(APP_NAME."/cache/".$class."/".$method.".cache");

    }
    public function jump($url){
        header("Location: ".$url); 
    }
}