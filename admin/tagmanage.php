<?php
include("admin.php");
if (isset($_GET['tabletag'])){
$_SESSION['tabletag']=$_GET['tabletag'];
}
if ($_SESSION['tabletag']==''){
showmsg('请选择类别');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<?php
checkadminisdo("zskeyword");
?>
<script language="JavaScript" src="/js/gg.js"></script>
<script language="JavaScript" src="/js/jquery.js"></script>
<script language="JavaScript" type="text/JavaScript">
function ConfirmDelBig(){
   if(confirm("确定要删除此关键词吗？"))
     return true;
   else
     return false;	 
}
function CheckForm(){  
if (document.form1.tag.value=="")
  {
    alert("关键词不能为空！");
	document.form1.tag.focus();
	return false;
  }
    if (document.form1.url.value=="")
  {
    alert("链接地址不能为空！");
	document.form1.url.focus();
	return false;
  } 
}
</script>
</head>
<body>
<?php
if (isset($_REQUEST['dowhat'])){
$dowhat=$_REQUEST['dowhat'];
}else{
$dowhat="";
}
switch ($dowhat){
case "addtag";
addtag();
break;
case "modifytag";
modifytag();
break;
default;
showtag();
}
function showtag(){
if (isset($_REQUEST['action'])){
$action=$_REQUEST['action'];
}else{
$action="";
}

if ($action=="px") {
$sql="Select * From ".$_SESSION['tabletag']."";
$rs=mysql_query($sql);
while ($row=mysql_fetch_array($rs)){
$xuhao=$_POST["xuhao".$row["id"].""];//表单名称是动态显示的，并于FORM里的名称相同。
	   if (trim($xuhao) == "" || is_numeric($xuhao) == false) {
	       $xuhao = 0;
	   }elseif ($xuhao < 0){
	       $xuhao = 0;
	   }else{
	       $xuhao = $xuhao;
	   }
mysql_query("update ".$_SESSION['tabletag']." set xuhao='$xuhao' where id=".$row['id']."");
}
}
if ($action=="del"){
$id=trim($_REQUEST["id"]);
if ($id<>""){
	$sql="delete from ".$_SESSION['tabletag']." where id='$id'";
	mysql_query($sql);
}    
echo "<script>location.href='?'</script>";
}
?>
<div class="admintitle">关键词设置</div>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="center" class="border">
      <input name="submit3" type="submit" class="buttons" onClick="javascript:location.href='?dowhat=addtag'" value="添加关键词">
      </td>
  </tr>
</table>
	<?php
	$sql="Select * From ".$_SESSION['tabletag']." order by xuhao asc";
	$rs=mysql_query($sql);
	$row=mysql_num_rows($rs);
	if (!$row){
	echo "暂无信息";
	}else{
?>
      <form name="form1" method="post" action="?action=px">
        
  <table width="100%" border="0" cellpadding="5" cellspacing="1" >
    <tr> 
      <td width="265" height="25" class="border">关键词</td>
      <td width="302" class="border">url</td>
      <td width="237" class="border">排序</td>
      <td width="170" height="25" class="border">操作选项</td>
    </tr>
    <?php
	while ($row=mysql_fetch_array($rs)){
?>
     <tr class="bgcolor1" onMouseOver="fSetBg(this)" onMouseOut="fReBg(this)">  
      <td width="265" height="22"><?php echo $row["keyword"]?><a name="B<?php echo $row["id"]?>"></a></td>
      <td width="302"><?php echo $row["url"]?></td>
      <td width="237" height="22"><input name="<?php echo "xuhao".$row["id"]?>" type="text" id="<?php echo "xuhao".$row["id"]?>" value="<?php echo $row["xuhao"]?>" size="4" maxlength="4"> 
       <input type="submit" name="Submit" value="更新序号"></td>
      <td class="docolor"> <a href="?dowhat=modifytag&id=<?php echo $row["id"]?>">修改名称</a> 
        | <a href="?action=del&id=<?php echo $row["id"]?>" onClick="return ConfirmDelBig();">删除</a></td>
    </tr>
    <?php
	}
	?>
  </table>
	  </form>
<?php
}
}
function addtag(){
if (isset($_REQUEST['action'])){
$action=$_REQUEST['action'];
}else{
$action="";
}


if ($action=="add"){
for($i=0; $i<count($_POST['tag']);$i++){
	$tag=str_replace("{","",trim($_POST['tag'][$i]));
	$url=addhttp(str_replace("{","",trim($_POST['url'][$i])));
	if ($tag!=''){
	$sql="select * from ".$_SESSION['tabletag']." where keyword='" . $tag . "'";
	$rs=mysql_query($sql);
	$row=mysql_num_rows($rs);
		if (!$row) {
		mysql_query("insert into ".$_SESSION['tabletag']." (keyword,url)VALUES('$tag','$url') ");
		//start写入缓存文件
		$sql= "select keyword,url from ".$_SESSION['tabletag']." order by xuhao asc";
		$rs=mysql_query($sql);
		$row=mysql_num_rows($rs);
		if ($row){
		$str="";
			while ($row=mysql_fetch_array($rs)){	
			$str=$str . $row["keyword"]."," . $row["url"].";\r\n";				
			}
		}else{
		$str="暂无信息";
		}//以上得到最新关键词写入str变量
		if ($_SESSION['tabletag']=='zzcms_tagzs'){
		$fpath="../cache/zskeyword.txt";
		}elseif ($_SESSION['tabletag']=='zzcms_tagzx'){
		$fpath="../cache/zxkeyword.txt";
		}
		$fp=fopen($fpath,"w+");//fopen()的其它开关请参看相关函数
		fputs($fp,$str);//写入文件
		fclose($fp);
		//end		
		}
	}
}	
echo "<script>location.href='?'</script>";
}else{	
?>
<div class="admintitle">添加关键词</div>
<script language="javascript">   
//动态增加表单元素。
 function AddElement(){   
//得到需要被添加的html元素。
var TemO=document.getElementById("add");   
//var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='tag[]' value='关键词'>");
if($.browser.msie) {
	var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='tag[]' value='关键词'>"); 
}else{
	var newInput = document.createElement("input");
	newInput.type = "text";
	newInput.name = "tag[]";
	newInput.size = "50";
	newInput.maxlength = "50";
	newInput.value = "关键词";
}
TemO.appendChild(newInput);

//var newInput = document.createElement("<input name='url[]' type='text' value='#' size='50' maxlength='255'>");
if($.browser.msie) {
	var newInput = document.createElement("<input type='text' size='50' maxlength='255' name='url[]' value='#'>"); 
}else{
	var newInput = document.createElement("input");
	newInput.type = "text";
	newInput.name = "url[]";
	newInput.size = "50";
	newInput.maxlength = "255";
	newInput.value = "#";
}
TemO.appendChild(newInput); 
var newline= document.createElement("hr"); 
TemO.appendChild(newline);  
}    
</script>

<form name="form1" method="post" action="?dowhat=addtag" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="border">
    <tr> 
      <td width="24%" align="right">&nbsp;</td>
      <td width="76%"> 
	  <div id="add">
	  <input name="tag[]" type="text" value="关键词" size="50" maxlength="50">
	  url： 
      <input name="url[]" type="text" id="url" value="#" size="50" maxlength="255">
	  <br>
	  </div>	  </td>
    </tr>
    <tr> 
      <td align="right">&nbsp;</td>
      <td><img src="image/icobigx.gif" width="23" height="11"> <a href="#" onClick='AddElement()'><img src='image/icobig.gif' border="0"> 添加</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> <input name="action" type="hidden" id="action" value="add"> <input name="add" type="submit" value=" 提交 ">      </td>
    </tr>
  </table>
</form>
<?php
}
}

function modifytag(){
if (isset($_REQUEST['action'])){
$action=$_REQUEST['action'];
}else{
$action="";
}
if (isset($_REQUEST['id'])){
$id=$_REQUEST['id'];
}else{
$id="";
}
if (isset($_POST['tag'])){
$tag=str_replace("{","",trim($_POST['tag']));
}else{
$tag="";
}
if (isset($_POST['url'])){
$url=addhttp(str_replace("{","",trim($_POST['url'])));
}else{
$url="";
}

if ($id==""){
echo "<script>location.href='?'</script>";
}

if ($action=="modify"){
	$sql="Select * from ".$_SESSION['tabletag']." where id='$id'";
	$rs=mysql_query($sql);
	$row=mysql_num_rows($rs);
	if (!$row){
		$FoundErr==1;
		$ErrMsg="<li>不存在！</li>";
		WriteErrMsg($ErrMsg);
	}else{
	mysql_query("update ".$_SESSION['tabletag']." set keyword='$tag',url='$url' where id='$id'");
	//start写入常量文件
		$sql= "select keyword,url from ".$_SESSION['tabletag']." order by xuhao asc";
		$rs=mysql_query($sql);
		$row=mysql_num_rows($rs);
		if ($row){
		$str="";
			while ($row=mysql_fetch_array($rs)){	
			$str=$str . $row["keyword"]."," . $row["url"].";\r\n";			
			}
		}else{
		$str="暂无信息";
		}//以上得到最新关键词写入str变量
		if ($_SESSION['tabletag']=='zzcms_tagzs'){
		$fpath="../cache/zskeyword.txt";
		}elseif ($_SESSION['tabletag']=='zzcms_tagzx'){
		$fpath="../cache/zxkeyword.txt";
		}
		$fp=fopen($fpath,"w+");//fopen()的其它开关请参看相关函数
		fputs($fp,$str);//写入文件
		fclose($fp);
		//end
	}	
	echo "<script>location.href='?#B".$id."'</script>";
}else{
$sql="Select * from ".$_SESSION['tabletag']." where id='$id'";
$rs=mysql_query($sql);
$row=mysql_fetch_array($rs);
?>
<div class="admintitle">修改关键词</div>
<form name="form1" method="post" action="?dowhat=modifytag" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="border">
    
    <tr> 
      <td width="30%" align="right">关键词：</td>
      <td width="70%"> <input name="tag" type="text" id="tag" value="<?php echo $row["keyword"]?>" size="50" maxlength="50"></td>
    </tr>
    <tr> 
      <td align="right">url：</td>
      <td><input name="url" type="text" id="url" value="<?php echo $row["url"]?>" size="50" maxlength="255"> 
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="id" type="hidden" id="id" value="<?php echo $row["id"]?>"> 
	  <input name="action" type="hidden" id="action" value="modify"> <input name="save" type="submit" id="save" value=" 修改 "> 
      </td>
    </tr>
  </table>
</form>
<?php
}
}
?>
</body>
</html>
<?php
mysql_close($conn);
?>