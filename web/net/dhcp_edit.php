<?
include ('../include/comm.php');
$pageaccess=0;
checklogin();
checkac();
//checkaccess($pageaccess,$_SESSION['role']);
if(isset($_POST['netface']))
{
	checkac('�޸�');
	//��������
	$dlease=((int)$_REQUEST[defaultrelease]<=0)?86400:(int)$_REQUEST[defaultrelease];
	$mlease=((int)$_REQUEST[maxrelease]<=0)?86400:(int)$_REQUEST[maxrelease];
	$sql="update dhcp set dheth='".$_POST['netface']."', dhrange='".$_POST['dhrange']."',dhrange1='".$_POST['dhrange1']."',dhip='".$_POST['dhip']."',dhgateway='".$_POST['dhgateway']."',dhmask='".$_POST['dhmask']."',dhdns1='".$_POST['dns1']."',dhdns2='".$_POST['dns2']."',dhwig1='".$_POST['wing1']."',dhwig2='".$_POST['wing2']."',dhstate='".$_POST['dhstate']."',dhisapp=0,defaultrelease=$dlease,maxrelease=$mlease where dhid=".$_POST['dhid'];
	$db->query($sql);
	//���Ӻ���
	$db->query('update isapp set dhcp=1');
	writelog($db,'��������','�޸�DHCP');
	showmessage('�༭���óɹ�','dhcp.php');
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
		alert("��ѡ��ӿ�");
		document.dhcp.netface.select();
		return false;
	}
	
	if(document.dhcp.dhip.value == ''){
		alert("�������������");
		document.dhcp.dhip.select();
		return false;
	}else{
		if(!checkip(document.dhcp.dhip.value) && !checkipv6(document.dhcp.dhip.value))
		{
			alert("IP��ʽ����");
			document.dhcp.dhip.select();
			return false;
		}
	}
	
	if(document.dhcp.dhmask.value == ''){
		alert("��������������");
		document.dhcp.dhmask.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhmask.value))
		{
			alert("IP��ʽ����");
			document.dhcp.dhmask.select();
			return false;
		}
	}
	
	if(document.dhcp.dhgateway.value == ''){
		alert("����������·��");
		document.dhcp.dhgateway.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhgateway.value)  && !checkipv6(document.dhcp.dhgateway.value))
		{
			alert("IP��ʽ����");
			document.dhcp.dhgateway.select();
			return false;
		}
	}
	
	if(document.dhcp.dhrange.value == ''){
		alert("�������ַ�ط�Χ");
		document.dhcp.dhrange.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhrange.value)  && !checkipv6(document.dhcp.dhrange.value))
		{
			alert("IP��ʽ����");
			document.dhcp.dhrange.select();
			return false;
		}
	}
	if(document.dhcp.dhrange1.value == ''){
		alert("�뽫��ַ�ط�Χ��������");
		document.dhcp.dhrange1.select();
		return false;
	}
	else
	{
		if(!checkip(document.dhcp.dhrange1.value) && !checkipv6(document.dhcp.dhrange1.value))
		{
			alert("IP��ʽ����");
			document.dhcp.dhrange1.select();
			return false;
		}
	}
	if(document.dhcp.dns1.value!= ''){
	
		if(!checkip(document.dhcp.dns1.value) && !checkipv6(document.dhcp.dns1.value))
		{
			alert("ip��ʽ����");
			document.dhcp.dns1.select();
			return false;
		}
	}
	if(document.dhcp.dns2.value!= ''){
	
		if(!checkip(document.dhcp.dns2.value) && !checkipv6(document.dhcp.dns2.value))
		{
			alert("ip��ʽ����");
			document.dhcp.dns2.select();
			return false;
		}
	}
	if(document.dhcp.wing1.value!= ''){
	
		if(!checkip(document.dhcp.wing1.value) && !checkipv6(document.dhcp.wing1.value))
		{
			alert("ip��ʽ����");
			document.dhcp.wing1.select();
			return false;
		}
	}
	if(document.dhcp.wing2.value!= ''){
	
		if(!checkip(document.dhcp.wing2.value)  && !checkipv6(document.dhcp.wing2.value))
		{
			alert("ip��ʽ����");
			document.dhcp.wing2.select();
			return false;
		}
	}
	
	
	if(document.dhcp.defaultrelease.value == ''){
		alert("������Ĭ����Լʱ��");
		document.dhcp.defaultrelease.select();
		return false;
	}
	else
	{
		
		var exp=/^(0|([1-9]\d*))(\.\d+)?$/ ;
		var reg = document.getElementById('defaultrelease').value.match(exp);
				if(reg==null){
				alert('Ĭ����Լʱ������Ǵ���0��������');
				document.getElementById('defaultrelease').value='';
				return false;
			}
		
	
	}
	if(document.dhcp.maxrelease.value == ''){
		alert("�����������Լʱ��");
		document.dhcp.maxrelease.select();
		return false;
	}
	else
	{
		
		var exp=/^(0|([1-9]\d*))(\.\d+)?$/ ;
		var reg = document.getElementById('maxrelease').value.match(exp);
				if(reg==null){
				alert('�����Լʱ������Ǵ���0��������');
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
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; DHCP����:&gt;&gt;  DHCP�༭ </div>
<ul class="tab-menu">
    <li><a href="dhcp.php">DHCP�����б�</a></li>
    <li class="on"><span>�༭DHCP����</span></li>	  
</ul>
<div class="content">
     <form id="dhcp" name="dhcp" method="post" action="dhcp_edit.php" onsubmit="return checklogin();">
      <table width="600"class="s s_form">
        <tr>
          <td colspan="2" class="caption">�޸�DHCP����</td>
        </tr>
        
        <tr>
          <td>ѡ��ӿڣ�</td>
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
          <td>�������Σ�</td>
          <td><input name="dhip" type="text" id="dhip" value="<?echo $row['dhip'];?>" size="20"  class="ip"  />
            ��192.168.0.0 </td>
        </tr>
        <tr>
          <td>�������룺</td>
          <td><input name="dhmask" type="text" id="dhmask" value="<?echo $row['dhmask'];?>" size="20"  class="ip" />
            ��255.255.255.0</td>
        </tr>
        <tr>
          <td>����·�ɣ�</td>
          <td><input name="dhgateway" type="text" id="dhgateway" value="<?echo $row['dhgateway'];?>" size="20"  class="ip" />
            ��192.168.0.1</td>
        </tr>
        <tr>
          <td>��ַ�ط�Χ��</td>
          <td><input name="dhrange" type="text" id="dhrange" value="<?echo $row['dhrange'];?>" size="16"  class="ip" />
            -
              <input name="dhrange1" type="text" id="dhrange1" value="<?echo $row['dhrange1'];?>" size="16"  class="ip"  />
            ��192.168.0.100 </td>
        </tr>
        
        <tr>
          <td>DNS��</td>
          <td><input name="dns1" type="text" id="dns1" value="<?echo $row['dhdns1'];?>" size="20"  class="ip" /> 
          <input name="dns2" type="text" id="dns2" value="<?echo $row['dhdns2'];?>" size="20"  class="ip" />
          <input name="dhid" type="hidden" id="dhid" value="<?echo $row['dhid'];?>" /></td>
        </tr>
        <tr>
          <td>WINS��</td>
          <td><input name="wing1" type="text" id="wing1" value="<?echo $row['dhwig1'];?>" size="20"  class="ip"  />
            <input name="wing2" type="text" id="wing2" value="<?echo $row['dhwig2'];?>" size="20"  class="ip" /></td>
        </tr>
          <tr>
          <td>Ĭ����Լʱ�䣺</td>
          <td>
            <input name="defaultrelease" type="text" size="20" value="<?=$row['defaultrelease']?>" />��</td>
        </tr>
        <tr>
          <td>�����Լʱ�䣺</td>
          <td>
            <input name="maxrelease" type="text"  size="20"  value="<?=$row['maxrelease']?>"  />��</td>
        </tr>
        <tr>
          <td>����״̬��</td>
          <td>
            <select name="dhstate" id="dhstate">
              <option value="1" <?if($row['dhstate']==1){echo "selected";}?>>����</option>
              <option value="0" <?if($row['dhstate']==0){echo "selected";}?>>�ر�</option>
              </select>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="footer"><input type="submit" name="Submit" value="��������" /></td>
        </tr>
      </table>
      </form>
</div>

<?$db->close();?>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>