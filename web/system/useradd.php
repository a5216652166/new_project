<?
include ('../include/comm.php');
checklogin();
checkac('���');
if(isset($_POST['username'])){
    
	//���ж��Ƿ����
	$query=$db->query("select * from user where username='".$_POST['username']."'");
	$num=$db->num_rows($query);
	if($num>=1)
	{
		$db->free_result($query);
		writelog($db,'�û�����',"�û���".$_POST['username']."�Ѵ���,��ӹ���Աʧ�ܡ�");
		$db->close();
		showmessage('�û����Ѵ���','2');
	}else {
		$sql="insert into user (username,password,userrealname,userdepart,usermail,userstate,usertelphone,userupdate,role_id) values('".$_POST['username']."','".md5($_POST['password'])."','".$_POST['userrealname']."','".$_POST['userdepart']."','".$_POST['useremail']."',".$_POST['userstate'].",'".$_POST['usertelphone']."',CURRENT_TIMESTAMP,$_REQUEST[roleID]);";
		//echo $sql;
		$db->query($sql);

		writelog($db,'�û�����',"���".$_POST['username']."����Ա");
		showmessage('����Ա��ӳɹ�','user.php');
		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����û�</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>

<script language="javascript">
//self.parent.frames['navFrame'].location='../nav.php?pos= ϵͳ���� -> �û����';
$(document).ready(function(){
	$("#userrealname").val("");
	$("#password").val("");
});
function checklogin(){
	

	if(document.useradd.username.value == ''){	
			alert("�������û���");
			document.useradd.username.select();
			return false;
		}else{
			var exp=/^[\u4E00-\u9FA5A-Za-z]+$/ ;
			var reg = document.useradd.username.value.match(exp);
				if(reg==null){
				alert('�û�����������ĸ���֣�');
				document.useradd.username.select();
				return false;
			}
		
		
		}
		if(document.useradd.userrealname.value == ''){	
			alert("��������ʵ����");
			document.useradd.userrealname.select();
			return false;
		}else{
			var exp=/^[\u4E00-\u9FA5A-Za-z]+$/ ;
			var reg = document.useradd.userrealname.value.match(exp);
				if(reg==null){
				alert('������������ĸ���֣�');
				document.useradd.userrealname.select();
				return false;
			}
		}
	if(document.useradd.password.value == ''){	
			alert("����������");
			document.useradd.password.select();
			return false;
		}else{
			if(!checkSpace(document.useradd.password.value) || document.useradd.password.value.length<3||_g("password").value.length>20 )
			{
				alert("���볤����3-20֮�䣬�Ҳ��ܺ��������ַ�");
				document.useradd.password.select();
				return false;
			}
		}	
		if(document.useradd.password1.value == ''){	
			alert("�������ظ�����");
			document.useradd.password1.select();
			return false;
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
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �û�����&gt;&gt; ����û� </div>
<ul class="tab-menu">
    <li><a href="user.php">�û�����</a> </li>
    <li  class="on"><span>����û�</span></li> 
</ul>
<div class="content">
<form id="useradd" name="useradd" method="post" action="useradd.php" onsubmit="return checklogin();" >
      <table width="768" class="s s_form">       
        <tr>
          <td colspan="2"class="caption">����û� </td>
        </tr>
        <tr>
          <td>�û�����</td>
          <td>
            <input name="username" type="text" id="username" />
            <span class="redtext">* �����ֶΣ��������Ļ�Ӣ����ĸ </span></td>
        </tr>
        <tr>
          <td>������</td>
          <td><input name="userrealname" type="text" id="userrealname" />
            <span class="redtext">* �����ֶΣ�Ϊ����Ա����ʵ���� </span></td>
        </tr>
        <tr>
          <td>���룺</td>
          <td>
            <input name="password" type="password" id="password"/>
            <span class="redtext">* �����ֶΣ�Ϊ����Ա�ĵ�½���� </span></td>
        </tr>
        <tr>
          <td>�ظ����룺</td>
          <td><input name="password1" type="password" id="password1" />
            <span class="redtext">*</span></td>
        </tr>
        <tr>
          <td>���䣺</td>
          <td>
            <input name="useremail" type="text" id="useremail" />
            <span class="redtext">* ����Ա�ĳ�������</span></td>
        </tr>
        <tr>
          <td>���ţ�</td>
          <td>
            <input name="userdepart" type="text" id="userdepart" />
            <span class="redtext">* ����Ա���ڵĲ���</span></td>
        </tr>
         <tr>
          <td>��ϵ�绰��</td>
          <td>
            <input name="usertelphone" type="text" id="usertelphone" />
            <span class="redtext">* ����Ա����ϵ�绰</span></td>
        </tr>
        <tr>
          <td>��ɫ��</td>
          <td>
            <select name="roleID" id="usergroup">
            	<?php
            	$sql="select * from role ;";
            	$rs=$db->query($sql);
            	while($row=$db->fetch_array($rs))
            	{
            		?>
            		<option value="<?=$row['role_id']?>" ><?= $row['name'] ?></option>
            		<?php
            	}
            	?>
            <!--
              <option value="0">�����û�</option>
              <option value="1">���������û�</option>
              <option value="2">����û�</option>
              <option value="3">��־�û�</option>
            -->
            </select>
          </td>
        </tr>
        <tr>
          <td>״̬��</td>
          <td>
            <input name="userstate" type="radio" value="1" checked="checked" />
          ���� 
          <input type="radio" name="userstate" value="0" />
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
