<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();

if ($_POST['submitNtp']) {
	checkac('�޸�');
	$gaptimes=$_POST['gaptimes'];
	$sql1="UPDATE 'sametime' SET 'gaptime' = $gaptimes";	
    $sh=file($crontab);
    foreach($sh as $k=>$row){
		if(strstr($row,$ntp))unset($sh[$k]);
    }
    $sh[]="0 0 */$gaptimes * * root $ntp\n";
    file_put_contents($crontab,implode("",$sh));
	if ($as=$db->query($sql1)) {
		$mesNtp="ʱ�������óɹ���";
	}
	else 
	{
		$mesNtp="ʱ��������ʧ�ܣ������ԣ�";
	}
	
	$timeIP=$_POST['localIP'];
	//$sameIP="/ximorun/ximorun/ntp";
	$a=$ntpdate." ".$timeIP."\n";

	writeShell($ntp,$a);
	$mesNtp.="ͬ��IP���óɹ���";
//	if(!writeShell($ntp,$a))
//	{
//		$mesNtp.="ͬ��IP���óɹ���";
//	}
//	else 
//	{
//		$mesNtp.="ͬ��IP����ʧ�ܣ������ԣ�";
//	}
	writelog($db,'ʱ��ͬ������',$mesNtp);
	showmessage($mesNtp,'setsametime.php');	
}

if ($_GET['testNtp']) {
	checkac('Ӧ��');
	$cmds=read_file($ntp);
	exec("/usr/sbin/ntpdate ".$_GET['time'],$sameerror,$interror);
	if($interror==0) {
		writelog($db,'ʱ��ͬ������','ͬ��ʱ��ɹ�');
		showmessage('ͬ��ʱ��ɹ�','setsametime.php');
	}
	else
	{
		writelog($db,'ʱ��ͬ������','ͬ��ʱ��ʧ��');
		showmessage('ͬ��ʱ��ʧ�ܣ�������','setsametime.php');
	}
		
}

$query1=$db->query("select * from sametime");
$row1=$db->fetchAssoc($query1);
$readtime=$row1['gaptime'];

$strIP=read_file($ntp);
preg_match("/((\d){1,3}.){3}(\d){1,3}/",$strIP,$arrIP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ʱ��ͬ������</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script language="javascript">

function checks(){
	if(document.gaptime.gaptimes.value == ''){	
		alert('������������');
		document.gaptime.gaptimes.select();
		return false;		
	}
	else
	{
		if(!checkInt(document.gaptime.gaptimes.value))
		{
			alert('���������֣�');
			document.gaptime.gaptimes.select();
			return false;
		}
	}
	
	if(document.gaptime.localIP.value == '')
	{
		alert('������IP��ַ');
		document.gaptime.localIP.select();
		return false;
	}
	else
	{
		if(!checkip(document.gaptime.localIP.value))
		{
			alert('IP��ַ��ʽ����');
			document.gaptime.localIP.select();
			return false;
		}
	}
	
	return true;
}
function checkip(ip){
	var reg = /^\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$/; 
	if (!reg.test(ip)) { 
		
	return false; 
	} 
	return true; 
}
function checkInt(str)
{
	var newPar=/^[0-9]*[1-9][0-9]*$/;
	if(!newPar.test(str))
	{
		return false;
	}
	

		return true;

}

</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ʱ��ͬ������</div>
     <div class="content"><form id="gaptime" name="gaptime" method="POST" action="setsametime.php" onsubmit="return checks();">
	  <table width="768" align="center" class="s s_form">
       <tr>
          <td colspan="2" class="caption">ʱ��ͬ������</td>
        </tr>  
         <tr>
          <td>���ü��ʱ�䣺</td>
          <td ><input name="gaptimes" type="text" id="gaptimes" value="<?php echo $readtime; ?>" />��&nbsp;&nbsp;&nbsp;</td>
          </tr>   
        <tr>
          <td >NTP������IP��</td>
          <td><input name="localIP" type="text" id="localIP" value="<?php echo $arrIP[0] ?>" />ipv4��ַ</td>
        </tr>
         <tr>
          <td colspan="2" class="footer"><input type="submit" name="submitNtp" value="����" onclick="return checks();" />&nbsp;<a href="setsametime.php?testNtp=1&time=<?php echo $arrIP[0]; ?>">ʱ��ͬ��</a>��������Ч��
        </td></tr>         
		
      </table>
        </form></div>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
