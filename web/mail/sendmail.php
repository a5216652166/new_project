<?
date_default_timezone_set('PRC');
require_once('class.phpmailer.php');
set_mail_subject_ext();//�����ʼ���׺
function sendMail($host,$sendName,$sendPWD,$sendAd,$subject,$body,$recAd)
{
	$mail = new PHPMailer();
	$mail->Mailer="smtp";
	$mail->CharSet = "GB2312"; // ���ñ���
	$mail->IsSMTP();  // ʹ��SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = $host; 	   // ���� SMTP ������
	$mail->Port       = 25;                    // ���ö˿�
	$mail->Username   = $sendName; 	   			   // SMTP �û���
	$mail->Password   = $sendPWD;                    // SMTP ����
	
	$mail->Setfrom($sendAd);		//�����˵�ַ
	$mail->Subject    = $subject.MAIL_SUBJECT_EXT;			//����
	$mail->MsgHTML($body);    //����
	$mail->AddAddress($recAd);   //�ռ��˵�ַ
	if(!$mail->Send()) {
		//echo "�ʼ����͵ķ������û������������!";//echo "Mailer Error: " . $mail->ErrorInfo;
		//return false;
	} else {
		//echo "�ʼ�����ʧ�ܣ���ȷ��DNS������������ʼ�����!";
		//return true;
	}
}
//�����ʼ���׺
function set_mail_subject_ext(){
global $db;
$query=$db->query("select * from setdns where dnsid=1");
$info=$db->fetch($query);
define('MAIL_SUBJECT_EXT',"-".$info['dnsdomain']."-".$info['dnsip']);
}
?>
