<?
include ('../include/comm.php');
$pageaccess=0;
checklogin();
checkac();
if(isset($_POST['netface']))
{
	checkac('���');
	//�������
	$dlease=(int)$_REQUEST[defaultrelease];
	$mlease=(int)$_REQUEST[maxrelease];
	$sql="insert into dhcp (dheth, dhrange, dhgateway, dhmask, dhdns1, dhdns2, dhwig1, dhwig2, dhstate,dhisapp,dhip,dhrange1,defaultrelease,maxrelease) values('";
	$sql .= $_POST['netface']."','".$_POST['dhrange']."','".$_POST['dhgateway']."','".$_POST['dhmask']."','".$_POST['dns1']."','".$_POST['dns2']."','".$_POST['wing1']."','".$_POST['wing2']."',".$_POST['dhstate'].",0,'".$_POST['dhip']."','".$_POST['dhrange1']."',$dlease,$mlease)";
	//echo $sql;
	$db->query($sql);
	//��Ӻ���
	$db->query('update isapp set dhcp=1');
	writelog($db,'��������','���DHCP');
	showmessage('������óɹ�','dhcp.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><? echo $system['xmtactype'];?></title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"  type="text/javascript"></script>
<script src="../js/ximo_dns.js"  type="text/javascript"></script>

<script type="text/javascript" >
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
<div class="position">&nbsp;��ǰλ��:&gt;&gt;���DHCP����</div>
<ul class="tab-menu">    
    <li><a href="dhcp.php">DHCP�����б�</a></li>
	<li class="on"><span>���DHCP����</span> </li>
	<li><a href="dhcpuser.php">DHCP�û��б�</a></li> 
</ul>
<div class="content">
    <form id="dhcp" name="dhcp" method="post" action="dhcp_add.php"  onsubmit="return checklogin()">
      <table width="587" class="s s_form">
        <tr>
          <td colspan="2" class="caption">���DHCP����</td>
        </tr>        
        <tr>
          <td>ѡ��ӿڣ�</td>
          <td><label>
            <select name="netface" id="netface">
            <?
			$query=$db->query("select * from netface;");
			//$query=$db->query("select * from network where netdhcp=1");
			while($row = $db->fetch_array($query))
			{
				?>
				<option value="<?echo $row['facename']?>"><?echo $row['facename']?></option>
				<?
				}
				?>
                        </select>
          </label></td>
        </tr>
        <tr>
          <td>�������Σ�</td>
          <td>
          <input class="{isip:true,required:true}"  name="dhip" type="text" id="dhip" size="20" />
            ��192.168.0.0
          </td>
        </tr>
        <tr>
          <td>�������룺</td>
          <td>
          <input name="dhmask" type="text" id="dhmask" size="20"   class="{isip:true,required:true}"   />
            ��255.255.255.0</td>
        </tr>
        <tr>
          <td>����·�ɣ�</td>
          <td>
          <input name="dhgateway" type="text" id="dhgateway" size="20"  class="{isip:true,required:true}"   />
            ��192.168.0.1</td>
        </tr>
        <tr>
          <td>��ַ�ط�Χ��</td>
          <td>
          <input  class="{isip:true,required:true}"  name="dhrange" type="text" id="dhrange" size="16" />
            -
              <input  class="{isip:true,required:true}"  name="dhrange1" type="text" id="dhrange1" size="16" />
            ��192.168.0.100 </td>
        </tr>      
        <tr>
          <td>DNS��</td>
          <td>
          <input name="dns1" type="text" id="dns1" size="20" class="{isip:true,required:true}"  /> 
          <input name="dns2" type="text" id="dns2" size="20"  class="{isip:true}" /></td>
        </tr>
        <tr>
          <td>WINS��</td>
          <td>
          <input name="wing1" type="text" id="wing1" size="20" class="{isip:true}" />
            <input name="wing2" type="text" id="wing2" size="20"  class="{isip:true}" /></td>
        </tr>
        <tr>
          <td>Ĭ����Լʱ�䣺</td>
          <td>
            <input class="{min:0}" name="defaultrelease" type="text" size="20" />��</td>
        </tr>
        <tr>
          <td>�����Լʱ�䣺</td>
          <td>
            <input class="{min:0}" name="maxrelease" type="text"  size="20" />��</td>
        </tr>
        <tr>
          <td>����״̬��</td>
          <td><label>
            <select name="dhstate" id="dhstate">
              <option value="1">����</option>
              <option value="0">�ر�</option>
                        </select>
          </label></td>
        </tr>
        <tr>
          <td colspan="2" class="footer">
            <input type="submit" name="Submit" value="��������" />
          </td>
        </tr>
      </table>
     </form>
	 <?$db->close();?>
</div>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
