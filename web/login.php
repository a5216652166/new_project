<?
//��½�ж�
include('include/comm.php');
include("include/login_lock.class.php");
$Lock=new login_lock();//��½����
if($Lock->is_lock()){
  $_SESSION["login_lock"]["time"]=time();
  showmessage('��¼�����������5��,��15���Ӻ�����',"index.php");
}
$username=$_POST['username'];
$password=md5($_POST['password']);
$sql = "select * from user where username='".$username."' and password='".$password."' and userstate=1 ";
$query=$db->query($sql);
$row = $db->fetchAssoc($query);
$myusergroup=$row['role_id'];
//$myusergroup=1;
/*
if(isset($_POST["vdcode"])&&$_POST["vdcode"]!=$_SESSION["vdcode"]){
Login_error_handler("��֤�����");
}
*/
if($myusergroup!='')
{
	//�ɹ���½
	$_SESSION['islogin']="1";
	$_SESSION['loginname']=$username;
	$_SESSION['loginip']=$_SERVER["REMOTE_ADDR"];
	$_SESSION['role']=$myusergroup;
	$db->query("insert into userlog (username,userip,userstate,addtime)values('".$username."','".$_SERVER['REMOTE_ADDR']."','�ɹ���½',datetime('now','localtime'))");
	$Lock->init_times();
	writelog($db,'��½����','�û�'.$username.'��½�ɹ�');
	Header ("Location:index.php");
}
else 
{
	//��½ʧ��
	$db->query("insert into userlog (username,userip,userstate,addtime)values('".$username."','".$_SERVER['REMOTE_ADDR']."','��½ʧ��',datetime('now','localtime'))");
	writelog($db,'��½����','�û�'.$username.'��½ʧ��');
	Login_error_handler("�û����������");
}
function Login_error_handler($error){
   global $Lock;
   $lost_times=$Lock->error_handler();
   showmessage(!$lost_times?$error.'�����ڵ�¼�����������5�Σ���15���Ӻ�������¼!':$error.'����½ʧ��,�㻹��'.$lost_times.'�λ���',"index.php");
}
?>