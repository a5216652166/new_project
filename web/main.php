<?php

$mods = array("domain", "stats", "log", "sys", "view", "pool", "dhcpd", "ifconfig", "route", "firewall", "adv", "tool", "backup", "member");
$lis = array(
	'domain' => array(),
	'stats' => array(),
	'log' => array(),
	'sys' => array(),
	'view' => array(),
	'pool' => array(),
	'dhcpd' => array(),
	'ifocnfig' => array(),
	'route' => array(),
	'firewall' => array(),
	'adv' => array(),
	'tool' => array(),
	'backup' => array(),
	'member' => array()
);

$stats = array(
	'domain' => 'dns/query.php',
	'net' => 'state/reallan.php',
	'list' => 'dns/setdns.php',
);
$member = array(
	'logout' => 'exit.php'
);
$log = array(
	'dns' => 'log/dnslog.php'
);
$view = array(
    'list'=> 'dns/acl.php'
	
	);
$domain = array(
'setdomain' => 'dns/domain.php'
);
$sys = array(
  'sethost' => 'host/sethost.php',
  'setdns' => 'dns/setdns.php'

 );
$map = array(
	'stats' => $stats,
	'member' => $member,
	'log' => $log,
	'domain' => $domain,
	'view' => $view,
	'sys' => $sys,
	'pool' => $pool,
	'dhcpd' => $dhcpd,
	'ifocnfig' => $ifconfig,
	'route' => $route,
	'firewall' => $frewall,
	'adv' => $adv,
	'tool' => $tool,
	'backup' => $backup,
	'member' => member
);

$mod = $_GET['mod'];
$action = $_GET['action'] ? $_GET['action'] : $_GET['act'];
if($mod != '')
	$file = $map[$mod][$action];
else
	$file = $action.'.php';
?>
<html>
<head>
<title> </title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen">
<link href="css/common.css" rel="stylesheet" type="text/css" media="screen">

<script language="javascript" type="text/javascript" src="js/jquery_002.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<!--script type="text/javascript" src="/img/prototype.js"></script-->
<script language="javascript" src="js/function.js"></script>
<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<script language="JavaScript" type="text/javascript" src="js/div_menu.js"></script>
<!--[if IE]>
<link href="css/ie.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->
</head>
 
<body id="mainframe" scroll="no">
<table height="100%" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="header">
            <?php if($mod == 'domain') {?>
            	<ul class="common_tab">
	            <li><a href="main.php?mod=domain&action=setdomain" class="on"><span>�����б�</span></a></li>
	            </ul>
            	<div class="common_help">�����б� </div>
            <?php } elseif($mod == 'stats'){ ?>
            	<ul class="common_tab">
	            <li><a href="main.php?mod=stats&action=graph" class="<?php if($action == 'graph') echo 'on'; else echo 'off';?>"><span>ͳ��ͼ</span></a></li>
	            <li><a href="main.php?mod=stats&action=domain" class="<?php if($action == 'domain') echo 'on'; else echo 'off';?>"><span>��ѯͳ��</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="<?php if($action == '') echo 'on'; else echo 'off';?>"><span>��·ͳ��</span></a></li>
	            <li><a href="main.php?mod=stats&action=net" class="<?php if($action == 'net') echo 'on'; else echo 'off';?>"><span>�������</span></a></li>
	            <li><a href="main.php?mod=stats&action=cpu" class="<?php if($action == 'cpu') echo 'on'; else echo 'off';?>"><span>���ؼ��</span></a></li>
	            </ul>
            	<div class="common_help">��·��ѯͳ��</div>            
            <?php } elseif($mod == 'log') {?>
            	<ul class="common_tab">
	            <li><a href="main.php?mod=domain&action=list" class="on"><span>��־��¼</span></a></li>
	            </ul>
            	<div class="common_help">��־��¼</div>  
            <?php } elseif($mod == 'sys') {?>
            	<ul class="common_tab">
	            <li><a href="main.php?mod=sys&action=sethost" class="on"><span>��������</span></a></li>
	            <li><a href="main.php?mod=sys&action=setdns" class="off"><span>DNS����</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="off"><span>SSH����</span></a></li>
	            <li><a href="main.php?mod=domain&action=search" class="off"><span>SNMP����</span></a></li>
	            <li><a href="main.php?mod=dimport&action=show" class="off"><span>����ϵͳ</span></a></li>
	           
 </ul>
            	<div class="common_help">��������</div>  
            <?php } elseif($mod == 'view') {?>
            	<ul class="common_tab">
	            <li><a href="main.php?mod=view&action=lists" class="on"><span>��·�б�</span></a></li>

				
	            </ul>
            	<div class="common_help">��·�б�</div>  
            
            <?php } elseif($mod == 'pool') {?>
			    <ul class="common_tab">
	            <li><a href="main.php?mod=domain&action=list" class="on"><span>��ַ���б�</span></a></li>
	            <li><a href="main.php?mod=domain&action=addRecord" class="off"><span>��ӵ�ַ��</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="off"><span>�������</span></a></li>
				
	            </ul>
            	<div class="common_help">��ַ���б�</div>  
            
            <?php } elseif($mod == 'dhcpd') {?>
            			    <ul class="common_tab">
	            <li><a href="main.php?mod=domain&action=list" class="on"><span>�����б�</span></a></li>
	            <li><a href="main.php?mod=domain&action=addRecord" class="off"><span>WAN�ӿ�</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="off"><span>LAN�ӿ�</span></a></li>
	            <li><a href="main.php?mod=domain&action=list" class="on"><span>MGMT�ӿ�</span></a></li>
	            <li><a href="main.php?mod=domain&action=addRecord" class="off"><span>DHCP����</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="off"><span>��ӵ�ַ��</span></a></li>
	            <li><a href="main.php?mod=domain&action=add" class="off"><span>����״̬</span></a></li>					
	            </ul>
            	<div class="common_help">�����б�</div>  
            <?php } elseif($mod == 'ifconfig') {?>
            
            <?php } elseif($mod == 'route') {?>
            
            <?php } elseif($mod == 'firewall') {?>
            
            <?php } elseif($mod == 'adv') {?>
            
            <?php } elseif($mod == 'tool') {?>
            
            <?php } elseif($mod == 'backup') {?>
            
            <?php } elseif($mod == 'member') {?>
            
            <?php } elseif($mod == '') {?>
            	<ul class="common_tab">
            	<li><a href="main.php?act=sysinfo" class="on"><span>ϵͳ��ҳ</span></a></li>
            	</ul>
            	<div class="common_help">ϵͳ������Ϣ</div>
            <?php }?>
            
        </td>
    </tr>
    <tr>
        <td class="mainer">
            <table height="100%" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td class="main_outer">
                        <iframe name="frameMain" src="<?php echo $file;?>" style="height: 100%; visibility: inherit; width: 100%; z-index: 1; overflow: auto;" frameborder="0" scrolling="yes"></iframe>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>
</body>
</html>