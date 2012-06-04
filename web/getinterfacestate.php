<?php
require_once 'include/comm.php';
$state=array();
foreach ($netface as $if )
{
    //�ӿ�����
	$s=trim(`ethtool $if|grep 'Link detected'|cut -d: -f2`);	//�ӿ�����״̬
	$s2=$s;
	$s=$s=='yes'?'1':'0';
	$ip=trim(`ifconfig $if|grep -i 'inet addr'|cut -d: -f2|cut -d ' ' -f1`);//ip
	$ip=$ip!=''?$ip:iconv('GBK','UTF-8','��');
	$port=trim(`ethtool $if|grep -i  'Port:'|cut -d: -f2`);		//�ӿڽ�������
	$port=$port=='FIBRE'?'����':'˫��';
	$port=iconv('GBK','UTF-8',$port);
	$speed=trim(`ethtool $if|grep -i 'speed'|cut -d: -f 2|grep -i -v 'Unknown'`);
	$speed=$speed!=''?$speed:iconv('GBK','UTF-8','δ֪');
	$duplex=strtolower(trim(`ethtool $if|grep -i 'Duplex'|cut -d: -f2`));
	if($duplex=='full')
		$duplex='ȫ˫��';
	elseif($duplex=='half')
		$duplex='��˫��';
	else 
		$duplex='δ֪';
	$rx=trim(`ifconfig $if|grep 'RX bytes'|cut -d: -f 2| awk '{ print $1}'`);
	$tx=trim(`ifconfig $if|grep 'RX bytes'|cut -d: -f 3| awk '{ print $1}'`);
	$duplex=iconv('GBK','UTF-8',$duplex);
	$state[]=array('name'=>$if,'ip'=>$ip,'state'=>$s,'port'=>$port,'speed'=>$speed,'duplex'=>$duplex,'state2'=>$s2
	,'rx'=>$rx,'tx'=>$tx
	);
}
echo json_encode($state);
?>
