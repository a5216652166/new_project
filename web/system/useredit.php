<?
include ('../include/comm.php');
checklogin();
checkac('�޸�');
if(isset($_POST['userrealname'])){
	$sql="update user set ";
	if($_POST['password']<>'')
	{
		$sql=$sql." password='".md5($_POST['password'])."',";
	}
	$sql=$sql." userrealname='".$_POST['userrealname']."',userdepart='".$_POST['userdepart']."',usermail='".$_POST[useremail]."',";
	$sql=$sql." role_id=$_POST[roleID],";
	$sql=$sql." userstate=".$_POST['userstate'].",usertelphone='".$_POST['usertelphone']."' where user_id=".$_POST['userid'];
	
	$db->query($sql);
	writelog($db,'�û�����',"�༭����Ա");
	$db->close();
	showmessage('����Ա�޸ĳɹ�','user.php');
}else 
{//��ȡ��Ϣ
	//echo "select * from [ user] where user_id=".$_GET['id'];
	$query=$db->query("select * from user where user_id=".$_GET['id']);
	while($row = $db->fetch_array($query))
	{
		$username=$row['username'];
		$userrealname=$row['userrealname'];
		$usergroup=$row['permission'];
		$userstate=$row['userstate'];
		$userdepart=$row['userdepart'];
		$usertelphone=$row['usertelphone'];
		$useremail=$row['usermail'];
		$userid=$row['user_id'];
		$roleID=$row['role_id'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�༭�û�</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>

<script language="javascript">
function checklogin(){
	if(document.useradd.username.value == ''){	
			alert("�������û���");
			document.useradd.username.select();
			return false;
		}
		if(document.useradd.userrealname.value == ''){	
			alert("��������ʵ����");
			document.useradd.userrealname.select();
			return false;
		}
		
		if(!document.useradd.password.value == ''){	
			
			if(!checkSpace(document.useradd.password.value) || document.useradd.password.value.length<3||_g("password").value.length>20)
			{
				alert("���볤����3-20֮�䣬�Ҳ��ܺ��������ַ�");
				document.useradd.password.select();
				return false;
			}
		}	

		if(document.useradd.password1.value != document.useradd.password.value){	
			alert("�������������벻��ȷ");
			document.useradd.password1.select();
			return false;
		}		
		if(document.useradd.useremail.value == ''){	
			alert("����������");
			document.useradd.useremail.select();
			return false;
		}	
		if(!isEmail(document.useradd.useremail.value )){	
			alert("��������ȷ�������ʽ");
			document.useradd.useremail.select();
			return false;
		}	
		if(document.useradd.userdepart.value == ''){	
			alert("�����벿��");
			document.useradd.userdepart.select();
			return false;
		}else{
			if(!checkname(document.useradd.userdepart.value))
			{
				alert("�������Ʋ��ܺ��������ַ���");
				document.useradd.userdepart.select();
				return false;
			}
		}	
		if(document.useradd.usertelphone.value == ''){	
			alert("��������ϵ�绰");
			document.useradd.usertelphone.select();
			return false;
		}	
		if(!checktelphone(document.useradd.usertelphone.value))
		{
			alert("��������ȷ����ϵ�绰");
			document.useradd.usertelphone.select();
			return false;
		}
	return true;
}
function isEmail(str){ 
res = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
var re = new RegExp(res); 
return !(str.match(re) == null); 
} 
function   checktelphone(str){   
  res   =   /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,12})(-(\d{3,}))?$/  ;  
 var re=new RegExp(res);
 return !(str.match(re)==null);
  }
</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �û�����&gt;&gt; �༭�û� </div>
<ul class="tab-menu">
    <li><a href="user.php">�û�����</a> </li>
    <li class="on"><span>�༭�û�</span></li> 
</ul>
<div class="content">
<form id="useradd" name="useradd" method="post" action="useredit.php" onsubmit="return checklogin();">
     <table  width="768" class="s s_form">
        <tr>
          <td colspan="2" class="caption">�༭�û�</td>
        </tr>
        <tr>
          <td>�û�����</td>
          <td>
            <input name="username" type="text" readonly=1 id="username" value="<? echo $username?>" />
            <span class="redtext">* �����ֶΣ��������Ļ�Ӣ����ĸ </span></td>
        </tr>
        <tr>
          <td>������</td>
          <td><input name="userrealname" type="text" id="userrealname" value="<? echo $userrealname?>" />
            <span class="redtext">* �����ֶΣ�Ϊ����Ա����ʵ���� </span></td>
        </tr>
        <tr>
          <td>���룺</td>
          <td>
            <input name="password" type="password" id="password" />
            <span class="redtext">* ������޸�������Ϊ��
            <input name="userid" type="hidden" id="userid" value="<? echo $userid?>" />
</span></td>
        </tr>
        <tr>
          <td>�ظ����룺</td>
          <td><input name="password1" type="password" id="password1" />
            <span class="redtext">*</span></td>
        </tr>
        <tr>
          <td>���䣺</td>
          <td>
            <input name="useremail" type="text" id="useremail" value="<? echo $useremail?>" />
            <span class="redtext">* ����Ա�ĳ�������</span></td>
        </tr>
        <tr>
          <td>���ţ�</td>
          <td>
            <input name="userdepart" type="text" id="userdepart" value="<? echo $userdepart?>" />
            <span class="redtext">* ����Ա���ڵĲ���</span></td>
        </tr>
         <tr>
          <td>��ϵ�绰��</td>
          <td>
            <input name="usertelphone" type="text" id="usertelphone" value="<? echo $usertelphone?>" />
            <span class="redtext">* ����Ա����ϵ�绰</span></td>
        </tr>
        <tr>
          <td>��ɫ��</td>
          <td>
            <select name="roleID" id="usergroup">
            	<?php
            	$sql="select * from role;";
            	$rs=$db->query($sql);
            	while($row=$db->fetch_array($rs))
            	{
            		?>
            		<option value=<?=$row['role_id']?> <?= $roleID==$row['role_id']?'selected':'' ?> ><?= $row['name'] ?></option>
            		<?php
            	}
            	?>
            <!--
              <option value="0" <? if($usergroup==0){echo "selected";}?>>�����û�</option>
              <option value="1" <? if($usergroup==1){echo "selected";}?>>���������û�</option>
              <option value="2" <? if($usergroup==2){echo "selected";}?>>����û�</option>
  <option value="3" <? if($usergroup==3){echo "selected";}?>>��־�û�</option>
              -->
              </select>
          </td>
        </tr>
        <tr>
          <td>״̬��</td>
          <td>
            <input name="userstate" type="radio" value="1" <? if($userstate==1){echo "checked=\"checked\"";}?> />
          ���� 
          <input type="radio" name="userstate" value="0"  <? if($userstate==0){echo "checked=\"checked\"";}?>  />
          ͣ��
         </td>
        </tr>
        
        <tr>
          <td colspan="2" class="footer">
		  <input type="submit" name="Submit" value="��������" /></td>
        </tr>       
      </table>
	 </form>
	</div>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
