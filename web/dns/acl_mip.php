<?
include ('../include/comm.php');
$pageaccess=2;
checklogin();
//checkac();
/*
 *��·����setacl���ݱ���
 *type Ϊ 1 ��ʾ����ӵ���·
 *type Ϊ 0 ��ʾ��Ĭ�ϵ���·
 *
 *��/etc/namedb/acl Ŀ¼��
 *Ĭ����·IP����� XXX_M �ļ���
 *�û����IP����� XXX_ADD �ļ���
 *�����ļ���IP�������� XXX �ļ���
 *
 */

if(isset($_POST['Submit'])){
	if ($_POST['type'] == 0){ //Ĭ����·
	    $mip = "acl ".$_POST['aclident']." {\n";
	    $mip .= preg_replace("/\\r\\n/","\n",$_POST['aclcip']);
	    
	    $allip = $mip;
	    $mip .= "\n};";
	    
	    writeFile($binddir."acl/".$_POST['aclident']."_M", $mip);
	    $addip = file_get_contents($binddir."acl/".$_POST['aclident']."_ADD");
	    $allip .= $addip;
	    $allip .= "};";
	    writeFile($binddir."acl/".$_POST['aclident'], $allip);
	    showmessage('�޸ĳɹ�','../ip.php');
	}
}else 
{//��ȡ��Ϣ
	
	$areaflag=$_GET['aclname'];
	$newfp="";
	if ($_GET['type'] == 0){ //Ĭ����·
		$max=file($binddir."acl/".$areaflag."_M");
		$cnt = count($max);
		for($i=1;$i<$cnt-1;$i++)
		{
		    if(trim($max[$i]) != "")  //ɾ���ļ��е����п���
		    {   
		        $newfp.=$max[$i];    //��������������
		    }   
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��·IP����</title>
<link href="../divstyle.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="28" align="left" background="../images/bg_topbg.gif"> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="768" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#82C4E8">
      <form id="setacl" name="setacl" method="post" action="acl_mip.php" >  <tr>
        <td height="25" colspan="2" align="center" bgcolor="#e7f4ff" class="greenbg">�޸���·Ĭ��IP����</td>
      </tr>
      <tr>
        <td width="137" height="25" align="right" bgcolor="#e7f4ff" class="graytext">��·��ʶ��</td>
        <td width="620" height="25" align="left" bgcolor="#FFFFFF"><label><?echo $areaflag?>
              <input name="aclident" type="hidden" id="aclident" value="<?echo $areaflag?>" />
        </label></td>
      </tr>
      
<?php
	if ($_GET['type'] != 0){
?>
      <tr>
        <td height="25" align="right" bgcolor="#e7f4ff" class="graytext">IP�����</td>
        <td height="25" align="left" bgcolor="#FFFFFF">
        	<textarea name="aclip" cols="60" rows="15" id="aclip"><? echo $newfp?></textarea>
        </td>
      </tr>

      
<?}
 if ($_GET['type'] == 0){
?>
      <tr>
        <td height="25" align="right" bgcolor="#e7f4ff" class="graytext">�Զ���IP�����</td>
        <td height="25" align="left" bgcolor="#FFFFFF">
        	<textarea name="aclcip" cols="60" rows="10" id="aclcip"><? echo $newfp?></textarea>
        </td>
      </tr>
<? } ?>
      <tr>
        <td height="25" colspan="2" align="center" bgcolor="#FFFFFF" class="graybg">
	        <label>
	          <input type="hidden" id="typy" name="type" value="<? echo $_GET['type'];?>" />
	          <input type="submit" name="Submit" value="��������" />
	        </label>
        </td>
      </tr></form>
    </table></td>
  </tr>
</table>
 <?$db->close();?>
<? include "../copyright.php";?>
</body>
</html>
