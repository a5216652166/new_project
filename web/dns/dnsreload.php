<? include ('../include/comm.php');
include ('../mail/sendmail.php');
$time=date('Y-m-d H:i:s');
$pageaccess=2;
checklogin();
checkac();
exec( $rndccmd." status", &$dnsstatus );
$dnsstatus = join( "<br>", $dnsstatus );	
if($dnsstatus!=""){
		$mydns="DNS����������....";
		$dns=1;
}else 
{
	$mydns="DNS�Ѿ�ֹͣ����....";
	$dns=0;
}
if($_GET['op']=='reload'){
	checkac('Ӧ��');		  
//	exec( $rndccmd." reload", &$ping );
	exec( $rndccmd." stop" );
	exec( $named, &$ping);
	exec( $rndccmd." status", &$dnsstatus );
	$dnsstatus = join( "<br>", $dnsstatus );	
	if($dnsstatus!=""){
    $nowstatus=1;
	}else 
		{
			$nowstatus=0;
		}
//	$ping = join( "<br>", $ping );
	if($nowstatus==0){
		$s1='';
	}else{
		$s1="DNS����������ɣ�";
		$rowmail=$db->fetchAssoc($db->query("select * from setmail"));
		if($rowmail['dnShape']==1){
			$subject='DNS������';
			$body=$time.'ʱ������DNS�����Ѿ�������һ�Σ��ط����ʼ�֪ͨ��';
			$sendName=split('@',$rowmail['recMail']);
			sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);
		}
	}
	writelog($db,'DNS RELOAD����','����װ��DNS����');
	showmessage("DNS����������ɣ�",'dnsreload.php?s='.$s1);
} 
// ����DNS
if($_GET['op']=='start'){ 
	checkac('Ӧ��');
	//exec( $namedcmd, &$ping );
	// exec('/ximolog/startnamed.sh');
	exec($named);
	// echo "DNS������һ�������������,���Ժ�";
	writelog($db,'DNS��������','����DNS��������');
	$rowmail=$db->fetchAssoc($db->query("select * from setmail"));
	if($rowmail['dnShape']==1){
		$subject='DNS������';
		$body=$time.'ʱ������DNS�����Ѿ�������һ�Σ��ط����ʼ�֪ͨ��';
		$sendName=split('@',$rowmail['recMail']);
		sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
	}
	showmessage("DNS����������ɣ�",'dnsreload.php');
}
// ֹͣDNS
if($_GET['op']=='stop'){  
	checkac('Ӧ��');
	$rowmail=$db->fetchAssoc($db->query("select * from setmail"));
	if($rowmail['dnShape']==1){
		$subject='DNS������';
		$body=$time.'ʱ������DNS�����Ѿ���ֹͣһ�Σ��ط����ʼ�֪ͨ��';
		$sendName=split('@',$rowmail['recMail']);
		sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
	}
	exec( $rndccmd." stop", &$ping );
	$ping = join( "<br>", $ping );	
	exec( $killallcmd." named" );
	writelog($db,'DNSֹͣ����','ֹͣDNS��������');
	showmessage("DNS����ֹͣ��ɣ�",'dnsreload.php?s=DNS����ֹͣ��ɣ�');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta http-equiv="refresh" content="40" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<title>DNS����ʹ��</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.redtext{ color:red;}
<!--
body {
	background-color: #FFFFFF;
}
.STYLE1 {font-size:12px; color:#420505; margin-left:30px; font: "����";}
-->
</style>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; DNS������</div>
      <div class="content"><table width="768"  class="s s_form">
        <tr>
          <td class="caption">DNS������ʹ��</td>
        </tr>
        <tr >
          <td>��ǰDNS������״̬��<span class="redtext"><?echo $mydns?></span> 
          <?if($dns==1){?><a href="dnsreload.php?op=reload" onclick="javascript:return   confirm('��ȷ���Ƿ�����DNS���ã�');"><img src="../images/bt_01.gif" width="91" height="31" border="0" align="absmiddle" /></a> <a href="dnsreload.php?op=stop" onclick="javascript:return   confirm('��ȷ���Ƿ�ֹͣDNS��������');"><img src="../images/bt_02.gif" width="98" height="31" border="0" align="absmiddle" /></a><?}else{?> <a href="dnsreload.php?op=start" onclick="javascript:return   confirm('��ȷ���Ƿ�����DNS��������');"><img src="../images/bt_03.gif" width="92" height="31" border="0" align="absmiddle" /></a><?}?> </td>
        </tr>
        <tr>
          <td class="t_c"><?
          ?>
           <?if($_GET['s']=='DNS������һ������������ɣ����Ժ�!'&&$dns==1){?><img src="../images/dnsstatus1.jpg" width="218" height="211" /><?}
          if($_GET['s']=='DNS����������ɣ�'&&$dns==1){?><img src="../images/dnsstatus3.jpg" width="218" height="211" /><?}
          if($_GET['s']=='DNS����ֹͣ��ɣ�'&&$dns==0){?><img src="../images/dnsstatus4.jpg" width="218" height="211" /><?}
          if($_GET['s']=='DNS������һ������������ɣ����Ժ�!'&&$dns==0){?><img src="../images/dnsstatus2.jpg" width="218" height="211" /><?}
		  if($_GET['s']==''&&$dns==1){?> <img src="../images/dnsstatus1.jpg" width="218" height="211" /><?}?> 
		 <? if($_GET['s']==''&&$dns==0){?> <img src="../images/dnsstatus4.jpg" width="218" height="211" /><?}?>         
            </td>
          </tr>
      </table></div>
  
<?$db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>
