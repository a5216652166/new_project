<?php
include("../mail/mailchecker.php");
header('Content-Type:text/html;charset=GB2312');
$mail=new email_validation_class($_GET['mail']);
if($mail->check())
{
	echo "<img src='../images/yes.gif' />��ϲ�����ʼ�����";
}
else 
{
	echo "<img src='../images/no.gif' />�ʼ������ã�����������ʼ�";
}
?>
