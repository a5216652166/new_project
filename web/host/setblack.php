<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['ip']))
{    
    checkac('Ӧ��');
    $sql = "select * from blacklist";
    $row = $db->fetchAssoc($db->query($sql));
    $oip = $row['ip'];
    $nip = $_POST['ip'];
    
    //�������ݿ���ļ�
    $sql = "update blacklist set blacklist='".$_POST['urls']."', ip='".$_POST['ip']."', state=".$_POST['state'];
    $db->query($sql);
    $bl = file_get_contents($bldir."hostURL_M");
    $bl .= $_POST['urls']."\r\n";
    file_put_contents($bldir."hostURL" ,$bl);
	if(isset($_POST['tishi'])){
		writeFile($black_tishi,stripslashes($_POST['tishi']));
	}
    exec("sed 's/$oip/$nip/g' ".$etcmaster."www.aaa_ANY > ".$etcmaster."www.aaa_ANY.bak");
    exec("cp ".$etcmaster."www.aaa_ANY.bak ".$etcmaster."www.aaa_ANY");
	
	if ($_POST['state'] == 1){//����
		exec('at now -f /xmdns/sh/dns_black_up');
	}
	else {//�ر�
		exec('at now -f /xmdns/sh/dns_black_down');
	}
	writelog($db,'��������������',"����ɹ�");
    showmessage('����ɹ�', 'setblack.php');
}
$row = $db->fetchAssoc($db->query("select * from blacklist"));
$c = file_get_contents($black_tishi);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��ַ������</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script src="../js/input.js" language="javascript"></script>
<script language="javascript">
function checklogin()
{
	
	if(document.blackurl.ip.value == '')
	{
		alert('������IP������');
		document.blackurl.ip.focus();
		return false;
	}
	else
	{
		if(!checkIp(document.blackurl.ip.value) && !checkurl(document.blackurl.ip.value))
		{
			alert("�����IP��������ʽ����ȷ��");
		    document.blackurl.ip.select();
			return false;
		}
	}
	var obj=document.blackurl.urls.value;
	var n=obj.indexOf("\r");
	if(n>0){
	 var values=obj.split("\r\n");
	}else{
		var values=obj.split("\n");
	}
    
	for(i in values){
		if(values[i]=="")continue;		
		if(!checkurl(values[i])){
			alert("�����������ʽ����ȷ��");
		    document.blackurl.urls.select();
			return false;
		}
	}
	return true;
}
function checkIp(obj)
{
	var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
	var reg = obj.match(exp);
	if(reg==null)
		return false;
	return true;
} 
function shows()
{
	if($("#aa").attr("alt")==0)
	{
		$(".show").show();
		$("#aa").attr("alt",1);
		$("#tishi").attr("disabled",false);
	}else{
		$(".show").hide();
		$("#aa").attr("alt",0);
		$("#tishi").attr("disabled",true);
	}
}
</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��ַ������ </div>
          <div class="content"><form method="post" action="setblack.php" name="blackurl" id="blackurl" onsubmit="return checklogin()">
<table width="768" class="s s_form">   
         <tr>
             <td  colspan="2" class="caption">��ַ������</td>
         </tr>
		 <tr>
        <td><? if ($_GET['type'] == 0)echo "������ַ��"; else echo "IP�����";?></td>
        <td>
        	ע�⣺һ����дһ��URL<br>
        	<textarea name="urls" cols="60" rows="15" id="urls"><? echo $row['blacklist'];?></textarea>
        </td></tr>
		<tr>
			<td>���������ַת����ipҳ�棺</td>
            <td>
              <input name="ip" type="text" id="ip" size="36" value="<?php echo $row['ip'];?>" />	<a href="#" id="aa" onclick="shows()" alt="0">�޸ı�����ʾҳ��</a>
            </td>
		</tr>
		<tbody class="show" style="display:none;">
		<tr>
        <td>������ʾҳ�棺</td>
        <td>
			<textarea id="tishi" rows="15" cols="60" name="tishi" disabled="false"><? echo $c;?></textarea>
        </td>
		</tr>
		</tbody>
		<tr>
			<td>״̬��</td>
            <td>
              ����<input type="radio" name="state" id="state1" value="1" <? if ($row['state'] == 1) echo "checked";?> />
			  �ر�<input type="radio" name="state" id="state2" value="0" <? if ($row['state'] != 1) echo "checked";?> />
            </td>
		</tr>
	  
	  <tr>
        <td colspan="2" class="footer">	        
	          <input type="hidden" id="typy" name="type" value="<? echo $_GET['type'];?>" />
	          <input type="submit" name="Submit" value="��������" />	      
        </td>
	  </tr>	  
      </table></form></div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>