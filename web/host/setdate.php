<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
	$mycmd=$mycmd."$setdate ".$_POST['mydate']."\n";
	//д��once�ļ�
	writeShell($onerunfile,$mycmd);
	exec("$setdate -s \"".$_POST['mydate']."\"");
	exec("$cleandate");
	writelog($db,'��������ʱ��',"��������ʱ��");
	$db->close();
	showmessage('��������ʱ��ɹ�','sethost.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script language="javascript">

function checklogin(){
	if(document.setdate.mydate.value == ''){	
			alert("����������ʱ��");
			document.setdate.mydate.select();
			return false;
	}else if(!checkdate(document.setdate.mydate.value)){
			alert("��������ȷ��ʱ���ʽ");
			document.setdate.mydate.select();
			return false;
        }
	
	return true;
}


</script>
</head>

<body>
<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ����ʱ������ </div>
<div class="content">
<form id="setdate" name="setdate" method="post" action="setdate.php" onsubmit="return checklogin();">
      <table width="768" class="s s_form">
        <tr>
          <td colspan="2" class="caption">����ʱ������</td>
        </tr>
         <tr>
          <td>��ǰϵͳʱ�䣺</td>
          <td><label class="greentext">
          <?=date("Y��n��j�� H:i:s")?>	&nbsp;&nbsp;&nbsp;</label></td>
        </tr>
         <tr>
           <td>ʱ�����ã�            </td>
           <td>
             <input name="mydate" type="text" id="mydate" />
            </td>
         </tr>
         <tr>
           <td>&nbsp;</td>
           <td>ʱ���ʽ�磺2009-06-13 14:23:00(������ЧΪһ������������Ч)! </td>
         </tr>        
        <tr>
          <td colspan="2" class="footer">
            <input type="submit" name="Submit" value="��������" />
          </td>
        </tr>
      </table>
	  </form></div>
<? $db->close();?>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
