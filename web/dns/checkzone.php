<?php
include ('../include/comm.php');
$pageaccess=2;
checklogin();
//checkaccess($pageaccess,$_SESSION['role']);
checkac('Ӧ��');

exec('/xmdns/sh/acl_check.sh');
exec('/xmdns/sh/checkzone /etc/namedb/master/'); //���м�����
exec('/xmdns/sh/acl_check.sh');

$zonename = "/etc/namedb/check/zone_name.t";
$zone = file($zonename);

$info = "/etc/namedb/check/info.log";
$ips = file($info);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>���������¼</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.STYLE1 {font-size:12px; color:#420505; margin-left:30px; font: "����";}
-->
</style></head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ���������¼</div>
<ul class="tab-menu">    
    <li><a href="domain.php">��������</a></li>
	<li><a href="domain_add.php">�������</a></li>
    <li><a href="domaingroup.php">����ת������</a></li>
	<li><a href="domain_input.php">��������</a></li>
    <li><a href="domain_output.php">��������</a></li>
	<!--li  class="on"><span>���������¼</span></li-->
    <li><a href="domain.php?ac=app">Ӧ�����õ�ϵͳ</a></li>   
</ul>
<div class="content"> 

  	<table width="768" class="s s_grid">
	  <tr>
          <td  colspan="5" class="caption" >���������¼</td>
        </tr>
  		<th>��·</th>
  		<th>��¼</th>
  		<th>����</th>
  		<th>���ȼ�</th> 
  		<th>�������</th>
  	
  	</tr>
	<? 
	for ($i=0; $i<sizeof($zone); $i++){
	?>
	<tr>
		<? 
		$cells = explode(' ', trim($zone[$i]));
			for ($j=0; $j<sizeof($cells); $j++){
		?>
		<td><?php echo $cells[$j]?></td>
		<? }?>
		<!-- ���ȼ� ������� -->
		<?php
		$yss = '';
		$jxs = '';
		if ( strpos( $ips[$i], '|') ){ //��"|"
			
			$ys = array();
			$jx = array();
			$aa=explode('|', trim($ips[$i]));
			foreach ($aa as $a){
				$rd = explode(' ', trim($a));
				$ys[] = $rd[0];
				$jx[] = $rd[1];	
			}
			$yss = implode('<br>', $ys);
			$jxs = implode('<br>', $jx);
		} else {
			$rd = explode(' ', trim($ips[$i]));
			$yss = $rd[0];
			$jxs = $rd[1];	
		}
		$yss = str_replace('#', '', $yss);
		?>
		<td><?php echo $yss?></td>
		<td><?php echo $jxs?></td>
	</tr>
	<? }?>
	</table><div class="push"></div>
</div></div>
<? include "../copyright.php";?>
</body>
</html>