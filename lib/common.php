<?php
if(!defined("APP_NAME")){
   exit('非法载入！');
}
require 'Ben/lib/pay.php';
function isMobile(){ 
    //是否为移动端
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 
function sql_page($table,$by,$page,$max=5){
    if($page==1){
            $sql='SELECT * FROM '.$table.' ORDER BY '.$by.' DESC  LIMIT 0,'.$max; //ASC DESC
    }else{
            $sql='SELECT * FROM '.$table.' ORDER BY '.$by.' DESC  LIMIT '.(($page-1)*$max).','.$max;
    }
    return $sql;
}
function sql_page_where($table,$by,$page,$max=5,$condition,$conditionvalue){
    if($page==1){
            $sql='SELECT * FROM '.$table.' WHERE '.$condition.'="'.$conditionvalue.'" ORDER BY '.$by.' DESC  LIMIT 0,'.$max; //ASC DESC
    }else{
            $sql='SELECT * FROM '.$table.' WHERE '.$condition.'="'.$conditionvalue.'" ORDER BY '.$by.' DESC  LIMIT '.(($page-1)*$max).','.$max;
    }
    return $sql;
}
function get_hour($time){
    $hour=date('H',$time);
    if($hour>0 and $hour<4){
        return '凌晨';
    }elseif($hour>4 and $hour<=6){
        return '黎明';
    }elseif($hour>6 and $hour<=7){
        return '清晨';
    }elseif($hour>7 and $hour<=8){
        return '早晨';
    }elseif($hour>8 and $hour<=11){
        return '上午';
    }elseif($hour>11 and $hour<=13){
        return '中午';
    }elseif($hour>13 and $hour<=17){
        return '下午';
    }elseif($hour>17 and $hour<19){
        return '傍晚';
    }elseif($hour>19 and $hour<22){
        return '晚上';
    }else{
        return '午夜';
    }
}
function get_season($time){
    $yue=date('m',$time);
    if($yue>=3 and $yue <=5){
        return '春天';
    }elseif($yue>=6 and $yue <=8){
        return '夏天';
    }elseif($yue>=9 and $yue<=11){
        return '秋天';
    }else{
        return '冬天';
    }
}
function success($text,$url=null){
    if($url == null){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    }
    $data = file_get_contents('Ben/lib/success.html');
    $data = str_replace('{$url}', $url, $data);
    $data = str_replace('{$text}', $text, $data);
    echo $data;
}
function error($text,$url=null){
    if($url == null){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    }
    $data = file_get_contents('Ben/lib/error.html');
    $data = str_replace('{$url}', $url, $data);
    $data = str_replace('{$text}', $text, $data);
    echo $data;
    exit();
}
function isemail($text){
    if(preg_match('/(.*)@(.*)\.(.{0,4})/i',$text)){
        return true;
    }else{
        return false;
    }
}
function post($remote_server, $post_string){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta");
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
function post_else($remote_server, $post_string){
    $context = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded' .
                        '\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
                        '\r\n'.'Content-length:' . strlen($post_string) + 8,
            'content' =>$post_string)
        );
    $stream_context = stream_context_create($context);
    $data = file_get_contents($remote_server, false, $stream_context);
    return $data;
}
function import($name){
    require 'Ben/plugin/'.$name;
}
function nowurl(){
//取当前URL
    return  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
}
function issame($value1,$value2){
// 比较两个字符串是否相同  不区分大小写
    if(strtoupper($value1)==strtoupper($value2)){
        return true;
    }else{
        return false;
    }
}
function arraycheck($arr,$value){
//元素是否存在        $arr数组 $value元素值
  $num = count($arr);
  for($i=0;$i<$num;$i++){
     if($arr[$i] == $value){
     return true;
     }
  }
  return false;
}
function sendmail($toname,$title,$contents){
//发送邮件   $toname收信邮箱 $title标题 $contents内容
    global $Ben_config;
    require_once "mail.class.php";
    //******************** 配置信息 ********************************
    $smtpserver = $Ben_config['SMTP_SERVER'];//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = $Ben_config['SMTP_MAIL'];//SMTP服务器的用户邮箱
    $smtpemailto = $toname;//发送给谁
    $smtpuser =$Ben_config['SMTP_USER'];//SMTP服务器的用户帐号
    $smtppass = $Ben_config['SMTP_PASSWORD'];//SMTP服务器的用户密码
    $mailtitle = $title;//邮件主题
    $mailcontent = $contents;//邮件内容
    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ****************************
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
    if($state==""){
        return false;
    }else{
        return true;
    }
}
function dump($var, $echo=true, $label=null, $strict=true) {
//友好的变量输出
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
function str_center($str, $leftStr, $rightStr){ //取出中间文本
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}
function encrypt($string,$key='',$operation=false){ //加密字符串 flase表示加密 true表示解密
    $key=md5($key); 
    $key_length=strlen($key); 
      $string=$operation==true?base64_decode($string):substr(md5($string.$key),0,8).$string; 
    $string_length=strlen($string); 
    $rndkey=$box=array(); 
    $result=''; 
    for($i=0;$i<=255;$i++){ 
           $rndkey[$i]=ord($key[$i%$key_length]); 
        $box[$i]=$i; 
    } 
    for($j=$i=0;$i<256;$i++){ 
        $j=($j+$box[$i]+$rndkey[$i])%256; 
        $tmp=$box[$i]; 
        $box[$i]=$box[$j]; 
        $box[$j]=$tmp; 
    } 
    for($a=$j=$i=0;$i<$string_length;$i++){ 
        $a=($a+1)%256; 
        $j=($j+$box[$a])%256; 
        $tmp=$box[$a]; 
        $box[$a]=$box[$j]; 
        $box[$j]=$tmp; 
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256])); 
    } 
    if($operation==true){ 
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){ 
            return substr($result,8); 
        }else{ 
            return''; 
        } 
    }else{ 
        return str_replace('=','',base64_encode($result)); 
    } 
} 
function randString($len=6,$type='',$addChars='') {
//生成随机字符串  可以用于生成卡密
//$len 字符串长度
//$type 0 字母 1 数字 其它 混合
//$addchars 自定义额外字符
        $str ='';
        switch($type) {
            case 0:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 1:
                $chars= str_repeat('0123456789',3);
                break;
            case 2:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
                break;
            case 3:
                $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
                break;
        }
        if($len>10 ) {//位数过长重复字符串一定次数
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        if($type!=4) {
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len);
        }else{
            // 中文随机字
            for($i=0;$i<$len;$i++){
              $str.= self::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1,'utf-8',false);
            }
        }
        return $str;
}
function isUtf8($str) {
//是否为UTF8编码
        $c=0; $b=0;
        $bits=0;
        $len=strlen($str);
        for($i=0; $i<$len; $i++){
            $c=ord($str[$i]);
            if($c > 128){
                if(($c >= 254)) return false;
                elseif($c >= 252) $bits=6;
                elseif($c >= 248) $bits=5;
                elseif($c >= 240) $bits=4;
                elseif($c >= 224) $bits=3;
                elseif($c >= 192) $bits=2;
                else return false;
                if(($i+$bits) > $len) return false;
                while($bits > 1){
                    $i++;
                    $b=ord($str[$i]);
                    if($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
}
function filter($str){
if (empty($str)) return false;
    $str=addslashes($str);
    $str = str_replace( '<frame', "&lt;frame", $str);
    $str = str_replace( '</frame>', "&lt;/frame&gt;", $str);
    $str = str_replace( '<script>', "&lt;script&gt;", $str);
    $str = str_replace( '</script>', "&lt;/script&gt;", $str);
    $str = str_replace( '<style>', "&lt;style&gt;", $str);
    $str = str_replace( '</style>', "&lt;/style&gt;", $str);
    $str = str_replace( '<script', "&lt;script;", $str);
    $str = str_replace( '<?php', "&lt;?php", $str);
    $str = str_replace( '?>', "?&gt;", $str);
    $str = str_replace( '#', "{井号}", $str);
return $str;
}
function unfilter($str){
    $str=StripSlashes($str);
    $str = str_replace( "&lt;",'<',$str);
    $str = str_replace("&gt;", '>',$str);
    $str = str_replace( "{井号}",'#',$str);
    return $str;
}
function getcode($length) { //生成php随机数
    $pattern = '23456789ABCDEFGHIJKLOMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz '; //字符池
    for ($i = 0; $i < $length; $i++) {
        $key.= $pattern{mt_rand(0, 35) };
    }
    return $key;
}
function verfycode($save='verfycode',$length=4,$width=70,$height=25,$font='Ben/lib/Minecraftia.ttf'){
        $code = getcode($length); //获取随机字符串
        $_SESSION[$save] = $code;
        $img = imagecreate($width, $height);
        $bgcolor = imagecolorallocate($img, 240, 240, 240);
        $rectangelcolor = imagecolorallocate($img, 150, 150, 150);
        imagerectangle($img, 1, 1, $width - 1, $height - 1, $rectangelcolor); //画边框
        for ($i = 0; $i < $length; $i++) { //循环写字
            $codecolor = imagecolorallocate($img, mt_rand(50, 200) , mt_rand(50, 128) , mt_rand(50, 200));
            $angle = rand(-20, 20);
            $charx = $i * 15 + 8;
            $chary = ($height + 14) / 2 + rand(-1, 1);
            imagettftext($img, 15, $angle, $charx, $chary, $codecolor, $font, $code[$i]);
        }
        for ($i = 0; $i < 20; $i++) { //循环画线
            $linecolor = imagecolorallocate($img, mt_rand(0, 250) , mt_rand(0, 250) , mt_rand(0, 250));
            $linex = mt_rand(1, $width - 1);
            $liney = mt_rand(1, $height - 1);
            imageline($img, $linex, $liney, $linex + mt_rand(0, 4) - 2, $liney + mt_rand(0, 4) - 2, $linecolor);
        }
        for ($i = 0; $i < 100; $i++) { //循环画点
            $pointcolor = imagecolorallocate($img, mt_rand(0, 250) , mt_rand(0, 250) , mt_rand(0, 250));
            imagesetpixel($img, mt_rand(1, $width - 1) , mt_rand(1, $height - 1) , $pointcolor);
        }
        ob_clean();
        header('Content-type:image/png');
        imagepng($img);
}
function space($text){
    $text=str_replace(' ','',$text);
    return $text;
}