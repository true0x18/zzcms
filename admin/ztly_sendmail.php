<?php
set_time_limit(1800);
include("admin.php");
ob_end_clean();//终止缓冲。这样就不用等到有4096bytes的缓冲之后才被发送出去了。
echo str_pad(" ",256);//IE需要接受到256个字节之后才开始显示。
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="border" style="padding:10px"> 
<div style="padding:10px;background-color:#FFFFFF">
<?php
include("../inc/mail_class.php");
checkadminisdo("dl");
$id="";
if(!empty($_POST['id'])){
    for($i=0; $i<count($_POST['id']);$i++){
    $id=$id.($_POST['id'][$i].',');
    }
	$id=substr($id,0,strlen($id)-1);//去除最后面的","
}
if ($id==""){
echo "<script lanage='javascript'>alert('操作失败！至少要选中一条信息。');window.opener=null;window.open('','_self');window.close()</script>";
exit;
}

if (strpos($id,",")>0){
$sql="select * from zzcms_guestbook where saver is not null and  id in (". $id .")";
}else{
$sql="select * from zzcms_guestbook where saver is not null and id=".$id."";
}
$rs=mysql_query($sql);
$row=mysql_num_rows($rs);
if (!$row){		
echo "暂无信息";
}else{
while($row=mysql_fetch_array($rs)){
		$rsn=mysql_query("select username,sex,email,somane from zzcms_user where username='".$row["saver"]."'");
		$rown=mysql_num_rows($rsn);
		if ($rown){
		$rown=mysql_fetch_array($rsn);
			$fbr_email=$rown["email"];
			$somane=$rown["somane"];
			$sex=$rown["sex"];
			if ($sex==1) {
			$sex="先生";
			}elseif ($sex==0) {
			$sex="女士";
			}
		//=============== 发 信 ================
$smtp=new smtp(smtpserver,25,true,sender,smtppwd,sender);//25:smtp服务器的端口一般是25
//$smtp->debug = true; //是否开启调试,只在测试程序时使用，正式使用时请将此行注释
$to = $fbr_email; //收件人
$subject = "有人在".sitename."上给您留言";
$body="<table width='100%'><tr><td style='font-size:14px;line-height:25px'>".$somane.$sex. "：<br>&nbsp;&nbsp;&nbsp;&nbsp;您好！<br>有人在".sitename."上给您留言，以下是部分信息：<hr>";
$body=$body . "留&nbsp;言&nbsp;人：".$row["linkmen"]."<br>留言标题：".$row["title"]."<br>留言时间：".$row["sendtime"]."<br><a href='".siteurl."/user/login.php' target='_blank'><b>登录网站查看详情</b></a><br><br><br><br><a href='".siteurl."' target='_blank'><img src='".logourl."' border='0'></a>";
$body=$body . "</td></tr></table>";

$fp="../template/".siteskin."/email.htm";
$f= fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);
$strout=str_replace("{#body}",$body,$strout) ;
$strout=str_replace("{#siteurl}",siteurl,$strout) ;
$strout=str_replace("{#logourl}",logourl,$strout) ;
$body=$strout;

$send=$smtp->sendmail($to,sender,$subject,$body,"HTML");//邮件的类型可选值是 TXT 或 HTML 
if($send){
$msg= "成功发送到".$row["saver"]."(".$fbr_email.")<br>";
}else{
$msg= "失败发送到".$row["saver"]."(".$fbr_email.")<br>";
//echo "发送失败原因：".$this->smtp->logs;
}
		echo $msg;	
		}
		flush();  //不在缓冲中的或者说是被释放出来的数据发送到浏览器  
		sleep(5);			
	}
}		      
	echo '完成';
?>
</div>
</div>
<div style="text-align:center;padding:10px" class="border">
    <input name="Submit" type="button" class="buttons" onClick="parent.window.opener=null;parent.window.open('','_self');parent.window.close();" value="关 闭">
</div>
</body>
</html>