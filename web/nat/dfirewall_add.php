<?
include ('../include/comm.php');
checklogin();
checkac();
if(isset($_POST['fwname']))
{
	$sql="insert into firewall (fwname,fwnumber,fwaction,fwprotol,fwport,fwsip,fwwk,fwdip,fwstate,fwisapp,checkall)values('".$_POST['fwname']."',";
	$sql=$sql.$_POST['fwnumber'].",".$_POST['fwaction'].",".$_POST['fwprotol'].",'".$_POST['protol']."','".$_POST['fwsip']."','".$_POST['fwwk']."','";
	$sql=$sql.$_POST['fwdip']."',".$_POST['fwstate'].",0,1)";

	$db->query($sql);
	$db->query("update isapp set firewall=1");
	writelog($db,'��������','��ӷ���ǽ����');
	showmessage("��ӳɹ�", "dfirewall.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><? echo $system['xmtactype'];?></title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script  type="text/javascript"  src="../js/jquery.js" ></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script language="javascript">
function checklogin(){
	if(document.rule.fwname.value == ''){	
		alert("�������������");
		document.rule.fwname.select();
		return false;
	}
	else
	{
		if(!isName(document.rule.fwname.value))
		{
			alert("��������ֻ���Ǻ��֣����֣���ĸ���»��ߣ�");
			document.rule.fwname.select();
			return false;
		}
	}
	
	if(document.rule.fwnumber.value == ''){	
		alert("�����������");
		document.rule.fwnumber.select();
		return false;
	}
	else
	{
		if(!checkInt(document.rule.fwnumber.value)|| document.rule.fwnumber.value<=0 ){
			alert("�������Ǵ����������");
			document.rule.fwnumber.select();
			return false;
		}
		
	}
	
	if(document.rule.fwsip.value == '')
	{
		alert("������ԴIP��");
		document.rule.fwsip.select();
		return false;
	}else{
		if(!isIpOrIpd(document.rule.fwsip.value))
		{
			alert("�����IP���ʽ����ȷ��");
		    document.rule.fwsip.select();
			return false;
		}		
	}
	
	if(document.rule.fwdip.value == '')
	{
		alert("������Ŀ��IP��");
		document.rule.fwdip.select();
		return false;
	}else{
		if(!isIpOrIpd(document.rule.fwdip.value))
		{
			alert("�����IP���ʽ����ȷ��");
		    document.rule.fwdip.select();
			return false;
		}		
	}

	if(document.rule.fwprotol.value == "0" || document.rule.fwprotol.value == "-1")
	{
		if(document.rule.protol.value == ""){
			alert("������˿ں�");
			document.rule.protol.select();
			return false;
		}else{
			var ports = document.rule.protol.value.split(",");
			for(var i = 0; i < ports.length; i++) {
				if(!isPort(ports[i])){
					alert("����Ķ˿ںŸ�ʽ����ȷ");
					document.rule.protol.select();
					return false;
				}
			}
		}
	}
	return true;
}
function change()
{
	if(document.rule.fwprotol.value == "0" || document.rule.fwprotol.value == "-1")
	{
		$("#protol").show();
	}else{
		$("#protol").hide();
	}
}
 
</script>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; �˿ڷ���ǽ&gt;&gt; ��ӷ���ǽ����</div>
<ul class="tab-menu">
    <li><a href="dfirewall.php">����ǽ����</a></li>
    <li class="on"><span>��ӷ���ǽ����</span></li>	  
</ul>
<div class="content">
<form id="rule" name="rule" method="post" action="dfirewall_add.php" onsubmit="return checklogin();">
      <table width="500" class="s s_form">
        <tr>
          <td colspan="2" class="caption">��ӷ���ǽ����</td>
        </tr>
        <tr>
          <td>�������ƣ�</td>
          <td>
            <input name="fwname" type="text" id="fwname" size="20" />
          </td>
        </tr>
        
        <tr>
          <td>������ţ�</td>
          <td><input name="fwnumber" type="text" id="fwnumber" size="20" />&nbsp;--����������������</td>
        </tr>
        <tr>
          <td>������</td>
          <td><select name="fwaction" id="fwaction">
            <option value="1">����</option>
            <option value="0">�ܾ�</option>
                      </select>          </td>
        </tr>
		<tr>
          <td height="22" align="right" bgcolor="#e7f4ff">���ڣ�</td>
          <td align="left" bgcolor="#FFFFFF">
			<select name="fwwk" id="fwwk">
				<option value='����'>����</option>
				<?
				$query=$db->query("select * from netface");		
				while($row1 = $db->fetch_array($query))
				{
				echo"<option value='".$row1['facename']."'>".$row1['facename']."</option>";
				}
				?>
            </select>
		  </td>
        </tr>
        <tr>
          <td>Э�����</td>
          <td><select name="fwprotol" id="select" onchange="change()">
          <? $query=$db->query("select * from portlist where plfather=0 order by plsort asc");

           while($row=$db->fetch_array($query)){?>
			 <optgroup label="<? echo $row['plname'];?>"></optgroup>
			<? 
				$q=$db->query("select * from portlist where plfather=".$row['plid']." order by plsort asc");
				while($r=$db->fetch_array($q)){?>
					<option value="<?echo $r['plid']?>" >&nbsp;&nbsp;<?echo $r['plname']. "  (".$r['plproto']." ".$r['plport'].")";?></option>
				<? }?>
			
			<?}?>
			<option value="0" >����(TCP)</option>
			<option value="-1" >����(UDP)</option>
          </select>
			<input style="display:none" name="protol" type="text" id="protol" size="15" />�˿�
		  </td>
        </tr>
        <tr>
          <td>ԴIP�飺</td>
          <td><input name="fwsip" type="text" id="fwsip" size="15" />
          ��:192.168.0.32/32 ���е�Ϊ0.0.0.0/0</td>
        </tr>
        <tr>
          <td>Ŀ��IP�飺</td>
          <td><input name="fwdip" type="text" id="fwdip" size="15" />
            ��:192.168.0.32/32 ���е�Ϊ0.0.0.0/0</td>
        </tr>
        <tr>
          <td>����״̬��</td>
          <td><select name="fwstate" id="fwstate">
             <option value="1">����</option>
			  <option value="0">�ر�</option>
              
          </select></td>
        </tr>
        <tr>
          <td colspan="2" class="footer">
			<input type="submit" value="��������" />
          </td>
        </tr>
      </table>
        </form>
</div>
</div>

<? include "../copyright.php";?>
</body>
</html>
