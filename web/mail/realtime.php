<?php
include ('../include/comm.php');
include ('../mail/sendmail.php');
$rowmail=$db->fetchAssoc($db->query("select * from setmail"));


if($rowmail['dnShape']==1)
{
	exec( $rndccmd." status", $dnsstatus );	
	$dnsstatus = join( "<br>", $dnsstatus );
	//var_dump($dnsstatus);
	if($dnsstatus!=""){
		echo "DNS����������....";
	}else 
	{
		$subject='DNS����״̬';
		$body='����DNS����Ŀǰ���ڹر�״̬���ط����ʼ�֪ͨ��';
		$sendName=split('@',$rowmail['recMail']);
		sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);
	}
}
?>