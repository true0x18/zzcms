<?php 
include("admin.php"); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/gg.js"></script>
<?php
//checkadminisdo("adminmanage");改密码要用此页，所以在DEL时判断
if (isset($_REQUEST["action"])){
$action=$_REQUEST["action"];
}else{
$action="";
}

if ($action=="del" ){
checkadminisdo("adminmanage");
mysql_query("delete from zzcms_admin where id='".$_GET["id"]."'");
echo  "<script>alert('删除成功');location.href='?'</script>";
}
$sql="select * from zzcms_admin order by id desc";
$rs = mysql_query($sql); 
?>
<script language="JavaScript" src="/js/gg.js"></script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td class="admintitle">管理员信息管理</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="center" class="border"><a href="adminadd.php">管理员添加</a></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
  <tr> 
    <td width="5%" align="center" class="border">ID</td>
    <td width="10%" align="center" class="border">用户名</td>
    <td width="10%" class="border">所属用户组</td>
    <td width="5%" align="center" class="border">登陆次数</td>
    <td width="10%" align="center" class="border">上次登陆IP</td>
    <td width="10%" align="center" class="border">上次登陆时间</td>
    <td width="10%" align="center" class="border">操 作</td>
  </tr>
 <?php
	while($row= mysql_fetch_array($rs)){
?>
  <tr onMouseOver="this.bgColor='#E8E8E8'" onMouseOut="this.bgColor='#FFFFFF'" bgcolor="#FFFFFF">
    <td align="center"><?php echo $row["id"]?></td>
    <td align="center"><?php echo $row["admin"]?></td>
    <td>
	
	<?php 
			$rsn=mysql_query("select groupname from zzcms_admingroup where id='".$row['groupid']."'");
			$r=mysql_num_rows($rsn);
			if ($r){
			$r=mysql_fetch_array($rsn);
			echo $r["groupname"];
			}
			 ?><br>
    <a href="admingroupmodify.php?id=<?php echo $row["groupid"]?>">查看此组权限</a></td>
    <td align="center"><?php echo $row["logins"]?></td>
    <td><?php echo $row["showloginip"]?></td>
    <td><?php echo $row["showlogintime"]?></td>
    <td align="center"><a href="adminmodify.php?admin=<?php echo $row["admin"]?>">修改权限</a> 
	 | <a href="adminpwd.php?admin=<?php echo $row["admin"]?>">修改密码</a> |   
	 <?php
$rsn2=mysql_query("select id from zzcms_admin where groupid=(select id from zzcms_admingroup where groupname='超级管理员')");
$rown2=mysql_num_rows($rsn2);//超级管理员数	 
	 
$rsn=mysql_query("select groupname from zzcms_admingroup where id=(select groupid from zzcms_admin where id=".$row["id"].")");
$rown=mysql_fetch_array($rsn);
if ($rown["groupname"]=='超级管理员' && $rown2 < 2){
echo "<span style='color:#666666' title='至少要保留1个“超级管理员”，添加新“超级管理员”后，才能删除老的'>删除</span>";
}else{
	 ?>
	 <a href="?action=del&id=<?php echo $row["id"] ?>" onClick="return ConfirmDel()">删除</a>
<?php
}
?>	  
	  </td>
  </tr>
  <?php 
  }
   mysql_close($conn); 
   ?>
</table>
</body>
</html>