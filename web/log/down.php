<?php
include ('../include/comm.php');
checklogin();
checkac();
$file = $_REQUEST['file'];
$filename = basename($_REQUEST['file']);
if(!strstr($filename,".log")){
    $file=$logback.$filename;
}else{
    $file=$bindlogdir."logquery/".$filename;
}
if(pathinfo($file,PATHINFO_EXTENSION)!="log")
{
	$filename.=".log";
}
if(!is_file($file))
	showmessage($filename.'�ļ�������','logquery.php');

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\""); 
readfile($file);
?>
