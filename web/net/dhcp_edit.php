<?
include ('../include/comm.php');
$pageaccess=0;
checklogin();
checkac();
//checkaccess($pageaccess,$_SESSION['role']);
if(isset($_POST['netface']))
{
	checkac('修改');
	//进行添加
	$dlease=((int)$_REQUEST[defaultrelease]<=0)?86400:(int)$_REQUEST[defaultrelease];
	$mlease=((int)$_REQUEST[maxrelease]<=0)?86400:(int)$_REQUEST[maxrelease];
	$sql="update dhcp set dheth='".$_POST['netface']."', dhrange='".$_POST['dhrange']."',dhrange1='".$_POST['dhrange1']."',dhip='".$_POST['dhip']."',dhgateway='".$_POST['dhgateway']."',dhmask='".$_POST['dhmask']."',dhdns1='".$_POST['dns1']."',dhdns2='".$_POST['dns2']."',dhwig1='".$_POST['wing1']."',dhwig2='".$_POST['wing2']."',dhstate='".$_POST['dhstate']."',dhisapp=0,defaultrelease=$dlease,maxrelease=$mlease where dhid=".$_POST['dhid'];
	$db->query($sql);
	//添加后处理
	$db->query('update isapp set dhcp=1');
	writelog($db,'网络设置','修改DHCP');
	showmessage('编辑设置成功','dhcp.php');
}
$sql = "select * from dhcp where dhid=".$_GET['id'];
$qy = $db->query($sql);
$row = $db->fetch_array($qy);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><? echo $system['xmtactype'];?></title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script language="javascript">
function checklogin(){
	
	if(document.dhcp.netface.value == ''){
		alert("请选择接口");
		document.dhcp.netface.select();
		return false;
	}
	
	if(document.dhcp.dhip.value == ''){
		alert("请输入分配网段");
		document.dhcp.dhip.select();
		return false;
	}else{
		if(!checkip(document.dhcp.dhip.value) && !checkipv6(document.dhcp.dhip.value))
		{
			alert("IP格式有误");
			document.dhcp.dhip.select();
			return false;
		}
	}
	
	if(document.dhcp.dhmask.value == ''){
		alert("请输入子网掩码");
		document.dhcp.dhmask.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhmask.value))
		{
			alert("IP格式有误");
			document.dhcp.dhmask.select();
			return false;
		}
	}
	
	if(document.dhcp.dhgateway.value == ''){
		alert("请输入网关路由");
		document.dhcp.dhgateway.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhgateway.value)  && !checkipv6(document.dhcp.dhgateway.value))
		{
			alert("IP格式有误");
			document.dhcp.dhgateway.select();
			return false;
		}
	}
	
	if(document.dhcp.dhrange.value == ''){
		alert("请输入地址池范围");
		document.dhcp.dhrange.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhrange.value)  && !checkipv6(document.dhcp.dhrange.value))
		{
			alert("IP格式有误");
			document.dhcp.dhrange.select();
			return false;
		}
	}
	if(document.dhcp.dhrange1.value == ''){
		alert("请将地址池范围输入完整");
		document.dhcp.dhrange1.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhrange1.value) && !checkipv6(document.dhcp.dhrange1.value))
		{
			alert("IP格式有误");
			document.dhcp.dhrange1.select();
			return false;
		}
	}
	if(document.dhcp.dns1.value!= ''){
	
		if(!checkip(document.dhcp.dns1.value) && !checkipv6(document.dhcp.dns1.value))
		{
			alert("ip格式有误");
			document.dhcp.dns1.select();
			return false;
		}
	}
	if(document.dhcp.dns2.value!= ''){
	
		if(!checkip(document.dhcp.dns2.value) && !checkipv6(document.dhcp.dns2.value))
		{
			alert("ip格式有误");
			document.dhcp.dns2.select();
			return false;
		}
	}
	if(document.dhcp.wing1.value!= ''){
	
		if(!checkip(document.dhcp.wing1.value) && !checkipv6(document.dhcp.wing1.value))
		{
			alert("ip格式有误");
			document.dhcp.wing1.select();
			return false;
		}
	}
	if(document.dhcp.wing2.value!= ''){
	
		if(!checkip(document.dhcp.wing2.value)  && !checkipv6(document.dhcp.wing2.value))
		{
			alert("ip格式有误");
			document.dhcp.wing2.select();
			return false;
		}
	}
	
	
	if(document.dhcp.defaultrelease.value == ''){
		alert("请输入默认租约时间");
		document.dhcp.defaultrelease.select();
		return false;
	}
	else
	{
		
		var exp=/^(0|([1-9]\d*))(\.\d+)?$/ ;
		var reg = document.getElementById('defaultrelease').value.match(exp);
				if(reg==null){
				alert('默认租约时间必须是大于0的整数！');
				document.getElementById('defaultrelease').value='';
				return false;
			}
		
	
	}
	if(document.dhcp.maxrelease.value == ''){
		alert("请输入最大租约时间");
		document.dhcp.maxrelease.select();
		return false;
	}
	else
	{
		
		var exp=/^(0|([1-9]\d*))(\.\d+)?$/ ;
		var reg = document.getElementById('maxrelease').value.match(exp);
				if(reg==null){
				alert('最大租约时间必须是大于0的整数！');
				document.getElementById('maxrelease').value='';
				return false;
			}
		
	
	}

	return true;
}
</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;当前位置:&gt;&gt; DHCP设置:&gt;&gt;  DHCP编辑 </div>
<ul class="tab-menu">
    <li><a href="dhcp.php">DHCP设置列表</a></li>
    <li class="on"><span>编辑DHCP设置</span></li>	  
</ul>
<div class="content">
     <form id="dhcp" name="dhcp" method="post" action="dhcp_edit.php" onsubmit="return checklogin();">
      <table width="600"class="s s_form">
        <tr>
          <td colspan="2" class="caption">修改DHCP设置</td>
        </tr>
        
        <tr>
          <td>选择接口：</td>
          <td><label>
            <select name="netface" id="netface">
            <?
$query=$db->query("select * from netface ");
//$query=$db->query("select * from network where netdhcp=1");
while($row1 = $db->fetch_array($query))
{?>
<option value="<?echo $row1['facename']?>" <?if($row1['facename']==$row['dheth']){echo "selected";}?>><?echo $row1['facename']?></option>
<?}?>
              </select>
          </label></td>
        </tr>
        <tr>
          <td>分配网段：</td>
          <td><input name="dhip" type="text" id="dhip" value="<?echo $row['dhip'];?>" size="20"  class="ip"  />
            如192.168.0.0 </td>
        </tr>
        <tr>
          <td>子网掩码：</td>
          <td><input name="dhmask" type="text" id="dhmask" value="<?echo $row['dhmask'];?>" size="20"  class="ip" />
            如255.255.255.0</td>
        </tr>
        <tr>
          <td>网关路由：</td>
          <td><input name="dhgateway" type="text" id="dhgateway" value="<?echo $row['dhgateway'];?>" size="20"  class="ip" />
            如192.168.0.1</td>
        </tr>
        <tr>
          <td>地址池范围：</td>
          <td><input name="dhrange" type="text" id="dhrange" value="<?echo $row['dhrange'];?>" size="16"  class="ip" />
            -
              <input name="dhrange1" type="text" id="dhrange1" value="<?echo $row['dhrange1'];?>" size="16"  class="ip"  />
            如192.168.0.100 </td>
        </tr>
        
        <tr>
          <td>DNS：</td>
          <td><input name="dns1" type="text" id="dns1" value="<?echo $row['dhdns1'];?>" size="20"  class="ip" /> 
          <input name="dns2" type="text" id="dns2" value="<?echo $row['dhdns2'];?>" size="20"  class="ip" />
          <input name="dhid" type="hidden" id="dhid" value="<?echo $row['dhid'];?>" /></td>
        </tr>
        <tr>
          <td>WINS：</td>
          <td><input name="wing1" type="text" id="wing1" value="<?echo $row['dhwig1'];?>" size="20"  class="ip"  />
            <input name="wing2" type="text" id="wing2" value="<?echo $row['dhwig2'];?>" size="20"  class="ip" /></td>
        </tr>
          <tr>
          <td>默认租约时间：</td>
          <td>
            <input name="defaultrelease" type="text" size="20" value="<?=$row['defaultrelease']?>" />秒</td>
        </tr>
        <tr>
          <td>最大租约时间：</td>
          <td>
            <input name="maxrelease" type="text"  size="20"  value="<?=$row['maxrelease']?>"  />秒</td>
        </tr>
        <tr>
          <td>启用状态：</td>
          <td>
            <select name="dhstate" id="dhstate">
              <option value="1" <?if($row['dhstate']==1){echo "selected";}?>>开启</option>
              <option value="0" <?if($row['dhstate']==0){echo "selected";}?>>关闭</option>
              </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="footer"><input type="submit" name="Submit" value="保存设置" /></td>
        </tr>
      </table>
      </form>
</div>

<?$db->close();?>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
