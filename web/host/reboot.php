<? include ('../include/comm.php');
$pageaccess=2;
checklogin();
checkac();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����ϵͳ</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.STYLE1 {font-size:12px; color:#420505; margin-left:30px; font: "����";}
-->
</style></head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ����ϵͳ </div>
<div class="content">
<form id="reboot" name="reboot" method="post" action="reboot.php?reboot=yes">
      <table width="768"align="center" class="s s_grid ">
        <tr>
          <td class="caption">����ϵͳ</td>
        </tr>
        <tr>
          <td><label>
<?if($_GET['reboot']=='yes')
{$my="��������ϵͳ�����Ժ�!";}  
else
{$my="�Ƿ�ȷ������ϵͳ?";}    ?>         
<input type="submit" name="Submit" value="<?echo $my?>" onclick="javascript:return   confirm('��ȷ���Ƿ�����ϵͳ��');" <?if($_GET['d']=='1'){echo "disabled";}?> />
          </label>  
<?
if($_POST[Submit]!="")
{
    
    if($_GET['reboot']=="yes")
    {
        checkac('Ӧ��');
    	writelog($db,'����ϵͳ','����ϵͳ');
        //echo "���Ժ�ϵͳ�������������������Ժ��ٲ�����";
        exec("$reboot");
    }
}

?></td>
          </tr>
      </table>
    </form>
  </div>
<div class="push"></div>
<?$db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>
 