<?
header('Content-Type:text/html;charset=GB2312');
error_reporting(0);
echo getstate($_GET['ip'],$_GET['mtype'],$_GET['murl']);


function getstate($ip,$type,$url){
	if($type=='url'){
		if(($a=file_get_contents( "http://".$url ))){
			return "����״̬";
		}else {
	      	return  "��ͨ״̬";
		}
	}
	if($type=='ping'){
		if(stristr($ip,":")){
		exec( "ping6 -c3 ".$ip, $ping);
		$ping = join( "<br>", $ping );
		if (stristr($ping,"100% packet loss") or $ping==NULL){
			return "��ͨ״̬";
		}else{
			return "����״̬";
		}
		
		}else{
		exec( "ping -c3 ".$ip, $ping);
		$ping = join( "<br>", $ping );
		if (stristr($ping,"100% packet loss")){
			return "��ͨ״̬";
		}else{
			return "����״̬";
		}
		}
	}
	if($type=='port'){
		$tmp = explode(" ",$url);
		$pt = $tmp[1];
		$port = $tmp[0];
		exec( "nmap -s$pt -p $port ".$ip, &$ping);
		$ping = join( "<br>", $ping );
		if (stristr($ping,"open")){
			return "����״̬";
		}else{
			return "��ͨ״̬";
		}
	}
	if($type=='server')
	{
		$tmp = explode(" ",$url);
		$pt = $tmp[1];
		$port = $tmp[0];
		exec( "nmap -s$pt -p $port ".$ip,&$ping);
		$ping = join( "<br>", $ping );
		if (stristr($ping,"open")){
			return "����״̬";
		}else{
			return "��ͨ״̬";
		}
	}
}
?>