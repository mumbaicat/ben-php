<?php
function pay($type=1,$user='666qq.com',$money=1,$title="来自Ben框架捐助",$note='来自Ben框架捐助辣'){
$pay1=$user;
$pay2=$user;
if($type==1){
?>
<html>
<head>
<meta charset="utf-8">
<title>正在跳转...</title>
</head>
<body>
<form accept-charset="gb2312" id="alipaysubmit" name="alipaysubmit" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="POST">
  <input type="hidden" name="optEmail" value="<?php echo $pay1;?>">
  <input type="hidden" name="payAmount" value="<?php echo $money;?>">
  <input type="hidden" name="title" value="<?php echo $title;?>">
  <input type="hidden" name="memo" value="<?php echo $note;?>">
  <input type="hidden" value="submit" value="sending...click here...">
</form>
<script type="text/javascript">
   document.forms['alipaysubmit'].submit();
</script>
</body>
</html>
<?php
}
if($type==2){
$md5=md5($pay2."&".$money."&".$title);
?>
<html>
<head>
<meta charset="utf-8">
<title>正在跳转...</title>
</head>
<body>
<form id="alipaysubmit" action="https://www.tenpay.com/v2/account/pay/paymore_cft.shtml?data=<?php echo $pay2;?>%26<?php echo $money;?>%26<?php echo $title;?>&validate=<?php echo $md5;?>" method="post">
<input type="hidden" value="submit" value="sending...click here...">
</form>
<script type="text/javascript">
   document.forms['alipaysubmit'].submit();
</script>
</body>
</html>
<?php
}}
?>