<?php
require('../include/comm.php');
checklogin();
checkac();
switch($_REQUEST['action'])
{
	case 'add':
	    checkac('���');
		if(trim($_REQUEST[roleName])<>'')
		{
			$sql0="select * from role where name='$_REQUEST[roleName]'";
			$query=$db->query($sql0);
			$num=$db->num_rows($query);
			if($num>=1){
				echo "<script language='javascript'>";
				echo "alert('�ý�ɫ���Ѵ��ڣ����������������');";
				echo "history.back(-1);";
				echo "</script>";
			}else{
				$sql= "insert into role (name) values('$_REQUEST[roleName]');";
				if($rs=$db->query($sql))
				{
					writelog($db,'��ɫ����',"��ӽ�ɫ".$_REQUEST['roleName']."�ɹ�");
					header("Location: editrole.php");
				}
				else 
				{
					writelog($db,'��ɫ����',"��ӽ�ɫ".$_REQUEST['roleName']."ʧ��");
					showmessage('���ʧ��','editrole.php');
				}
			}
		}
		else 
		{
			showmessage('�������ɫ��','editrole.php');
		}
		break;
	case 'del':
	    checkac('ɾ��');
			if($_REQUEST[roleID]==1){ 
				showmessage('����ɾ��"ϵͳ����Ա"��ɫ','editrole.php');
			}
			$sql="select count(*) as exist from user where role_id=$_REQUEST[roleID];";
			$rs=$db->query($sql);
			$row=$db->fetch_array($rs);
			if($row['exist']==0)
			{
				$oldrole=$db->fetch_array($db->query("select name from role where role_id=$_REQUEST[roleID];"));
				$sql1="delete from role where role_id=$_REQUEST[roleID]";
				$sql2="delete from access where role_id=$_REQUEST[roleID]";
				if($db->query($sql2) && $db->query($sql1))
				{
					writelog($db,'��ɫ����',"ɾ����ɫ".$oldrole['name']."�ɹ�");
					header("Location: editrole.php");
				}
				else
				{
					writelog($db,'��ɫ����',"ɾ����ɫ".$oldrole['name']."ʧ��");
					showmessage('ɾ��ʧ��','editrole.php');
				}
			}
			else 
			{
					writelog($db,'��ɫ����',"ɾ����ɫ".$oldrole['name']."ʧ��");
					showmessage('�㲻��ɾ���˽�ɫ������ɾ���˽�ɫ�µ������û�','editrole.php');
			}
		break;
	case 'rename':
	    checkac('�޸�');
		if(trim($_REQUEST[roleName])=='')
		{
		    die("{msg:'�������ɫ��'}");
		}
		$sql0="select * from role where name='".iconv("utf-8","gbk",$_REQUEST[roleName])."'";
		$query=$db->query($sql0);
		$num=$db->num_rows($query);
		if($num>=1){
			die("{msg:'�ý�ɫ���Ѵ���'}");
		}else{
		   $sql="update role set name='".iconv("utf-8","gbk",$_REQUEST[roleName])."' where role_id=$_REQUEST[roleID];";
		   if($rs=$db->query($sql))
		   {
			  writelog($db,'��ɫ����',"�޸Ľ�ɫ�ɹ�");
			  die("{success:true}");
		    }
		    else
		    {
			   writelog($db,'��ɫ����',"�޸Ľ�ɫʧ��");
			   die("{msg:'������ʧ��'}");
		    }
		}
		break;
		
}
?>
