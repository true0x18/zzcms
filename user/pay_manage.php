<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/gg.js"></script>
<?php
include("../inc/conn.php");
include("check.php");
?>
</head>
<body>
<div class="main">
<?php
include ("top.php");
?>
<div class="pagebody">
<div class="left">
<?php
include ("left.php");
?>
</div>
<div class="right">
<div class="admintitle">我的财务记录</div>
<?php
if( isset($_GET["page"]) && $_GET["page"]!="") 
{
    $page=$_GET['page'];
}else{
    $page=1;
}

$page_size=pagesize_ht;  //每页多少条数据
$offset=($page-1)*$page_size;
$sql="select * from zzcms_pay where username='".$username."'";
$rs = mysql_query($sql,$conn); 
$totlenum= mysql_num_rows($rs);  
$totlepage=ceil($totlenum/$page_size);  

$sql=$sql . " order by id desc limit $offset,$page_size";
$rs = mysql_query($sql,$conn); 
$row= mysql_num_rows($rs);//返回记录数
if(!$row){
echo "暂无信息";
}else{
?>

        
      <table width="100%" border="0" cellpadding="5" cellspacing="1">
        <tr> 
          <td width="42" align="center" class="border">序号 </td>
          <td width="144" class="border">摘要</td>
          <td width="123" class="border">记帐金额（元）</td>
          <td width="213" class="border">记帐时间</td>
          <td width="254" class="border">备注</td>
        </tr>
        <?php
$i=1;
while($row = mysql_fetch_array($rs)){
?>
         <tr class="bgcolor1" onMouseOver="fSetBg(this)" onMouseOut="fReBg(this)"> 
          <td width="42" align="center"><?php echo $i?></td>
          <td width="144"><?php echo $row["dowhat"]?></td>
          <td width="123"><?php echo $row["RMB"]?></td>
          <td width="213"><?php echo $row["sendtime"]?></td>
          <td width="254"><?php echo $row["mark"]?></td>
        </tr>
        <?php
$i=$i+1;
}
?>
      </table>
<div class="fenyei">
页次：<strong><font color="#CC0033"><?php echo $page?></font>/<?php echo $totlepage?>　</strong> 
      <strong><?php echo $page_size?></strong>条/页　共<strong><?php echo $totlenum ?></strong>条		 
          <?php  
if ($page!=1){
echo "<a href=?page=1>【首页】</a> ";
echo "<a href=?page=".($page-1).">【上一页】</a> ";
}else{
echo "【首页】【上一页】";
}
if ($page!=$totlepage){
echo "<a href=?page=".($page+1).">【下一页】</a> ";
echo "<a href=?page=".$totlepage.">【尾页】</a>";
}else{
echo "【下一页】【尾页】";
}
?>
</div>

<?php
}
?>
</div>
</div>
</div>
</body>
</html>