<?php
/*
 +-----------------------------------------------------
 * 	2010-3-23
 +-----------------------------------------------------
 *		
 +-----------------------------------------------------
 */

include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();

if(isset($_POST['Submit'])){
	checkac('Ӧ��');
   if ($_POST['yzfstate'] == 1){//����
       //����domain���е�domaintype�ֶ�
       $sql = 'update domain set domaintype=3, domainisapp=0 where domainid='.$_POST['domainid'];
       $db->query($sql);
       
       //��¼����
       $sql = 'select * from yzf where domainid='.$_POST['domainid'];
       $n = $db->num_rows($db->query($sql));
       if ($n == 1){//�м�¼
           $sql = "update yzf set ip='".$_POST['ip']."' where domainid=".$_POST['domainid'];
           $db->query($sql);
       } else {//û�м�¼
           $sql = "insert into yzf values(".$_POST['domainid'].",'".$_POST['ip']."')";
           $db->query($sql);
       }
   } else {//ͣ��
        //����domain���е�domaintype�ֶ�
       $sql = 'update domain set domaintype=0, domainisapp=0 where domainid='.$_POST['domainid'];
       $db->query($sql);
       
       //��¼����
       $sql = 'select * from yzf where domainid='.$_POST['domainid'];
       $n = $db->num_rows($db->query($sql));
       if ($n == 1){//�м�¼
           $sql = "update yzf set ip='".$_POST['ip']."' where domainid=".$_POST['domainid'];
           $db->query($sql);
       } else {//û�м�¼
           $sql = "insert into yzf values(".$_POST['domainid'].",'".$_POST['ip']."')";
           $db->query($sql);
       }
   }

    writelog($db,'��������',"����ת������");
	$db->close();
	showmessage('���óɹ�!','domain.php');
} else {
    $sql = "select domain.domaintype, yzf.* from domain,yzf where domain.domainid=".$_GET['domainid']." and yzf.domainid=".$_GET['domainid'];
    $res = $db->query($sql);
    $row = $db->fetchAssoc($res);
    $state = $row['domaintype'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��־����</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>

<script language="javascript">
function checkIp(obj)
{
	var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
	var reg = obj.match(exp);
	if(reg==null)
		return false;
	return true;
} 
function checklogin(){
var ips=document.yzf.ip.value;
    ch = new Array;
    ch = ips.split(";");
    if(ips!=""){
	for(var i=0;i<ch.length;i++){
	if(!checkIp(ch[i]))
		{
			alert("�����ַ��ʽ����ȷ��");
		    document.yzf.ip.select();
			return false;
		}
	}
}
}

</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �������� &gt;&gt; ��ת��</div>
<div class="content"> 
    <form id="yzf" name="yzf" method="post" action="yzf.php" onsubmit="return checklogin();">
    <table width="500" class="s s_form">
         <tr>
          <td colspan="2"class="caption">��ת��</td>
        </tr>
        <tr>
          <td>
          	ת������
          </td>
          <td>
             <input type="text" name="ip" id="ip" value="<?php echo $row['ip'];?>"/>
          </td>
        </tr>
        <tr>
          <td>״̬</td>
          <td>
            <input name="yzfstate" type="radio" value="1" <?php if($state == 3) echo 'checked="checked"';?> />����
            <input type="radio" name="yzfstate" value="0" <?php if($state != 3)echo 'checked="checked"';?> />ͣ��
          </td>
        </tr>
        <tr>
          <td colspan="2"class="footer">            
              <input type="submit" name="Submit" value="��������" />
              <input type="hidden" name="domainid" value="<?php echo $_GET['domainid']?>"/>
    		  
		  </td>
        </tr>      
    </table></form>
</div>
<?
   $db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>