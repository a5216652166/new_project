<?php
/*********************************************
*
* �������� Ȩ�޹���ģ��
* �� �ߣ� ����
* Email�� haohetao@gmail.com
* �汾�� v1.0
*
*********************************************/

/********************************************
 * ���ܣ�����Ȩ�������û�����ģ��������Ȩ��
 * ������
 * $pri		Ȩ������
 * $user	�û���
 * $mod		ģ����
 * ˵����
 * ��������������ԣ������а���null,''�ȿ�ֵ,Ȩ��δ��Ȩ����false
 * �����������true
 * ���ӣ�getPriByUser('access','admin','ϵͳ����')
 * getPriByUser('�޸�','admin','���ػ�����')
 *******************************************/

function getPriByUser($pri,$user,$mod)
{
	if(!$pri or !$user or !$mod)
	{
		return false;
	}
	global $db;
	$sql="select access.status as status from access
	 where role_id=(select role_id from user where username='$user')
	  and privilege_id=(select privilege_id from  privilege where name='$pri')
	  and module_id=(select module_id from module where name='$mod');";
	$rs=$db->query($sql);
	//echo $sql;
	if($row=$db->fetch_array($rs))
	{
		if($row['status']==1)
		{
			return true;
		}
	}
	return false;
}

/********************************************
 * ���ܣ�����Ȩ�������û�����ģ��������Ȩ��
 * ������
 * $pri		Ȩ������
 * $user	��ɫ��
 * $mod		ģ����
 * ˵����
 * ��������������ԣ������а���null,''�ȿ�ֵ,Ȩ��δ��Ȩ����false
 * �����������true
 * ���ӣ�getPri('�鿴');
 *******************************************/
function getPriByRole($pri,$role,$mod)
{
	if(!$pri or !$role or !$mod)
	{
		return false;
	}
	global $db;
	$sql="select access.status as status from access
	 where role_id=(select role_id from role where name='$role')
	  and privilege_id=(select privilege_id from privilege where name='$pri')
	  and module_id=(select module_id from module where name='$mod');";
	//echo $sql;
	$rs=$db->query($sql);
	if($row=$db->fetch_array($rs))
	{
		if($row['status']==1)
		{
			return true;
		}
	}
	return false;	
}


/********************************************
 * ���ܣ�����Ȩ��������Ȩ��
 * ������
 * $pri		Ȩ������
 * ˵����
 * ��������������ԣ������а���null,''�ȿ�ֵ,Ȩ��δ��Ȩ����false
 * �����������true
 * �û�����session�ж�ȡ�������ڲ����и�����ģ��������ҳ�����Ķ�ȡ
 * ����һ��ģ��ʱͬʱ��ģ��id����ҳ�棬����getPri()���ܻ�õ�ǰ
 * ģ����Ϣ��
 * ����ɲο�����Դ����
 *******************************************/
function getPri($pri)
{
	if(!isset($_SESSION['moduleid']))
	{
		//echo 'û���ṩ����ģ����Ϣ,��Ȩ����';
		//return false;
		return true;			
	}
	if(!isset($_SESSION['loginanme']))
	{
		//echo 'û���ṩ�û���Ϣ,��Ȩ����';
		return false;
		//exit();			
	}
	$user=$_SESSION['loginanme'];
	$modid=$_SESSION['moduleid'];
	if(!$pri or !$user or !$modid)
	{
		return false;
	}
	global $db;
	$sql="select access.status as status
	 from access
	 where role_id=(select role_id 
	 from user where username='$user')
	  and privilege_id=(select privilege_id
	   from privilege where name='$pri')
	  and module_id=$modid;";
	$rs=$db->query($sql);

	if($row=$db->fetch_array($rs))
	{
		if($row['status']==1)
		{
			return true;
		}
	}
	return false;
}
//���ģ��Ȩ��
function checkac($pri=NULL)
{
	if($pri==null)
	{
		$pri='access';	
	}
	if($pri=='access')
	{
		if(isset($_REQUEST['moduleid']))
		{
			$_SESSION['moduleid']=$_REQUEST['moduleid'];
		}		
	}
	if(!getPri($pri))
	{
		echo <<<EOT
			<script type="text/javascript" >
				self.location.href='../../../../noaccess.php';
			</script>
EOT;
		exit;
		//header("Content-Type: text/html; charset=GB2312");
		//exit('Ȩ�޲���');
	}
	return true;
}
function checkac_do($pri)
{
	global $db;
	$sql = "select status from do_access where role_id=$_SESSION[role] and domain_id=$_REQUEST[domainid] and privilege_id=$pri";
	$rs=$db->query($sql);
	$row=$db->fetchAssoc($rs);
	//echo $sql;die();
	if($row['status']==0)
	{
		echo <<<EOT
			<script type="text/javascript" >
				self.location.href='../../../../noaccess.php';
			</script>
EOT;
		exit;
	}
	return true;
}