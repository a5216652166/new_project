<html>
<head>
<title> </title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen">
<link href="css/common.css" rel="stylesheet" type="text/css" media="screen">
<script language="javascript" src="js/function.js"></script>
<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<script language="JavaScript" type="text/javascript" src="js/div_menu.js"></script>
<script language="javascript" src="js/menu.js"></script>
</head>


<body id="menu">
<div class="top">
    <a href="javascript:d.openAll();" title="ȫ��չ��"><img src="img/openall.gif">ȫ��չ��</a>
    <a href="javascript:d.closeAll();" title="ȫ���ر�"><img src="img/closeall.gif">ȫ���ر�</a>
</div>
<div class="dtree">
<script >
        function confirmReboot() {
            return confirm('�Ƿ�ȷ��Ҫ����ϵͳ��');
            //main.php?mod=sys&act=reboot
        }

        d = new dTree('d');

        d.add(0,-1,'ϵͳ��ҳ','main.php?act=sysinfo','','mainframe');

        /* system infomation */
        d.add( 1000, 0, 'ϵͳ״̬', '', '', 'mainframe' );
        d.add( 1001, 1000, 'ͳ �� ͼ', 'main.php?mod=stats&action=graph', '', 'mainframe' );
        /* d.add( 1001, 1000, 'ͳ �� ͼ', '/dnsgraph.php', '', 'mainframe' ); */
        d.add( 1002, 1000, '��ѯͳ��', 'main.php?mod=stats&action=domain', '', 'mainframe' );
        d.add( 1003, 1000, '�������', 'main.php?mod=stats&action=net', '', 'mainframe' );
        d.add( 1004, 1000, '���ؼ��', 'main.php?mod=stats&action=cpu', '', 'mainframe' );
        d.add( 1005, 1000, '��־��¼', 'main.php?mod=log&action=dns', '', 'mainframe' );
        
        /* services menu */
        d.add( 1100, 0, '�������', '', '', 'mainframe' );
        d.add( 1101, 1100, '��������', 'main.php?mod=domain&action=setdomain', '', 'mainframe' );
        d.add( 1102, 1100, '����DNS', 'main.php?mod=sys&action=rebootdns" onclick="return confirm(\'�Ƿ�ȷ��Ҫ����DNS����\');"', '', 'mainframe' );
        d.add( 1103, 1100, '��·����', 'main.php?mod=view&action=list', '', 'mainframe' );
        d.add( 1104, 1100, 'DNS����', 'main.php?mod=sys&action=setdns', '', 'mainframe' );
        d.add( 1105, 1100, '�� ַ ��', 'main.php?mod=pool&action=list', '', 'mainframe' );
        /*d.add( 1106, 1100, '��ر���', 'main.php?mod=alert&action=alert', '', 'mainframe' );*/
        d.add( 1107, 1100, 'DHCP����', 'main.php?mod=dhcpd&action=list', '', 'mainframe' );
                        
        /* network config */
        d.add( 1200, 0, '��������', '', '', 'mainframe' );
        d.add( 1201, 1200, '��������', 'main.php?mod=ifconfig&action=general', '', 'mainframe' );
        d.add( 1202, 1200, '·������', 'main.php?mod=route&action=list', '', 'mainframe' );
        d.add( 1203, 1200, '��ȫ����', 'main.php?mod=firewall&action=list', '', 'mainframe' );
        d.add( 1204, 1200, 'SNMP����', 'main.php?mod=sys&action=snmp', '', 'mainframe' );
        /* d.add( 1205, 1200, 'NAT ת��', 'main.php?mod=firewall&action=nat', '', 'mainframe' ); */
                
        d.add( 1300, 0, 'ϵͳ����', '', '', 'mainframe' );
        /* system config */
        d.add( 1301, 1300, '��������', 'main.php?mod=sys&action=sethost', '', 'mainframe' );
        d.add( 1302, 1300, '�߼�����', 'main.php?mod=adv&action=ha', '', 'mainframe' );
        d.add( 1303, 1300, 'ʵ�ù���', 'main.php?mod=tool&action=whois', '', 'mainframe' );
        d.add( 1304, 1300, '���ݱ���', 'main.php?mod=backup', '', 'mainframe' );
        d.add( 1305, 1300, '����ϵͳ', 'main.php?mod=sys&action=update', '', 'mainframe' );
        d.add( 1306, 1300, '�û�����', 'main.php?mod=member&action=list', '', 'mainframe' );
        d.add( 1307, 1300, '����ϵͳ', 'main.php?mod=sys&act=reboot" onclick="return confirm(\'�Ƿ�ȷ��Ҫ����ϵͳ��\');"', '', 'mainframe' );
        d.add( 1308, 1300, '�޸�����', 'main.php?mod=member&action=upPass', '', 'mainframe' );
        document.write(d);
        d.openAll();
        //-->
</script>
</div>
</body>
</html>