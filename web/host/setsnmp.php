<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if (isset($_POST['subcreat']))
{
	checkac('Ӧ��');
	
	$sql="update creatsnmpuser set user='".$_POST['username']."',psd='".$_POST['paswd']."',verify='".$_POST['verify']."',state=".$_POST['state']." where csnmpid=1";
	$db->query($sql);
	//����snmpd
	$rc="com2sec notConfigUser default ".$_POST['username']."\n";
	$rc.=<<<EOT
group otConfigGroup v1 notConfigUser
group otConfigGroup v2c notConfigUser
group tConfigGroup usm notConfigUser
#exec .1.3.6.1.4.1.2021.55 iostat /bin/sh /root/iostat.sh
view systemview included .1.3.6.1.2.1.1
view systemview included .1.3.6.1.2.1.25.1.1
view all included 1.3.6.1.2.1.1.5
view all included 1.3.6.1.2.1.1.3.0
view all included 1.3.6.1.4.1.2021.4.5.0
view all included 1.3.6.1.4.1.2021.4.6.0
view all included 1.3.6.1.4.1.2021.4.14
view all included 1.3.6.1.4.1.2021.10.1.3.1
view all included 1.3.6.1.4.1.2021.10.1.3.2
view all included 1.3.6.1.4.1.2021.10.1.3.3
view all included 1.3.6.1.4.1.2021.11.11.0
view all included 1.3.6.1.4.1.2021.11.9
view all included 1.3.6.1.4.1.2021.11.10
view all included 1.3.6.1.2.1.2.2.1.8
view all included 1.3.6.1.2.1.2.2.1.6 
view all included 1.3.6.1.2.1.4.20.1.1
view all included 1.3.6.1.2.1.2.2.1.10
view all included 1.3.6.1.2.1.2.2.1.16
access notConfigGroup "" any noauth exact all none none

EOT;

	//$rc .="createUser notConfigGroup MD5 \"".$_POST['verify']."\" DES ".$_POST['paswd']."\n";
	//$rc .="rouser ".$_POST['username']."\n";
	writeFile($snmpfile,$rc."\n");
	$a="#!/bin/bash\n";
	$a.="killall snmpd\n";
	if($_POST['state']==1)
	{
		$a.="snmpd -c $snmpdconf -p $snmpdpid\n";

	}
	writeFile($setsnmp,$a);
	chmod($setsnmp,0755);
	exec($setsnmp);
	writelog($db,'����SNMP',"����SNMP�û�");
	$db->close();
	if($_POST['state']==1){
		showmessage('SNMP�������óɹ�','setsnmp.php');
	}else{
		showmessage('SNMP���ùرճɹ�','setsnmp.php');
	}
}else 
{//��ȡ��Ϣ
	
		$query=$db->query("select * from creatsnmpuser where csnmpid=1");
		$row2=$db->fetchAssoc($query);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>SNMP����</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>

<script language="javascript">

function checklogin2(){
	if(document.createuser.username.value==''){	
			alert("�û�������Ϊ��");
			document.createuser.username.select();
			return false;
	}else{
		if(!checkSpace(document.createuser.username.value)){
			alert("�û����������������ַ�");
			document.createuser.username.select();
			return false;
		}
	}
/*
	if(document.createuser.paswd.value==''){	
			alert("���벻��Ϊ��");
			document.createuser.paswd.select();
			return false;
	}else{
		if(!checkSpace(document.createuser.paswd.value) || document.createuser.paswd.value.length < 8){	
			alert("���볤�ȱ������8,�Ҳ��ܺ��������ַ�");
			document.createuser.paswd.select();
			return false;
		}
	}
	
	if(!checkSpace(document.createuser.verify.value) || document.createuser.verify.value.length < 8){	
			alert("��֤�볤�ȱ������8,�Ҳ��ܺ��������ַ�");
			document.createuser.verify.select();
			return false;
	}
*/	
	return true;
}

</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; SNMP����</div>
<div class="content">
<form id="createuser" name="createuser" method="post" action="setsnmp.php" onsubmit="return checklogin2();">
  <table width="768"  class="s s_form">
        <tr>
          <td colspan="2"  class="caption">SNMP V2����</td>
        </tr>
         <tr>
           <td>�û�����</td>
           <td><label>
             <input name="username" type="text" id="username" value="<?echo $row2['user']?>" />
           </label></td>
         </tr>
<!--
         <tr>
          <td>��&nbsp;&nbsp;�룺</td>
          <td><input name="paswd" type="password" id="paswd" value="<?echo $row2['psd']?>"/>MD5��֤����,��ʼ����:12345678</td>
        </tr>
        <tr>
          <td>��&nbsp;&nbsp;Կ��</td>
          <td><input name="verify" type="text" id="verify" value="<?echo $row2['verify']?>" />DES��Կ,���ڼ��ܴ���</td>
        </tr>
-->
        <tr>
          <td>ʹ�����ӣ�</td>
          <td>�鿴��������snmpwalk -v 2c -c pub 192.168.12.123 1.3.6.1.2.1.1.5
		  <!--snmpwalk -v 3 -u notConfigGroup -l AuthNoPriv -a MD5 -A mmmmrrrrr -x DES -X savlamar 192.168.2.110 1.3.6.1.2.1.1.5-->
          </td>
        </tr>
        <tr>
          <td>״̬��</td>
          <td>
			<input name="state" type="radio" value="1"  <?if($row2['state']=="1"){echo "checked";}?> />
                ����
                <input type="radio" name="state" value="0" <?if($row2['state']=="0"){echo "checked";}?> />
�ر�</td>
		  </td>
        </tr>
        <tr>
          <td colspan="2" class="footer">
            <input type="submit" name="subcreat" value="��������" />
          </td>
        </tr>		
      </table></form>
	  </div>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
