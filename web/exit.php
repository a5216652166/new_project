<?
//�����½��cook
include ('include/comm.php');
checklogin();
writelog($db,'�˳�����','�û�'.$_SESSION['loginname'].'�˳��ɹ�');
unset($_SESSION['islogin']); 
unset($_SESSION['loginname']); 
unset($_SESSION['loginip']); 
//unset($_SESSION['reboot']); 
//  ���ַ������������� Session �ļ�
session_destroy();

$db->close();
showmessage('�˳��ɹ�!','index.php');

?>
