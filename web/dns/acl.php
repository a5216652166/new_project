<?
include ('../include/comm.php');
include ('../mail/sendmail.php');
$rowmail=$db->fetchAssoc($db->query("select * from setmail"));
$pageaccess=1;
checklogin();
checkac();
if(isset($_GET['ac']))
{
//*********************** DEL ***********************************************
	if($_GET['ac']=='del')
	{
		checkac('ɾ��');
		$sql="delete from setacl where aclid=".$_GET['id'];
		$db->query($sql);
		
		$sql="delete from drecord where dacl='".$_GET['aclname']."'";
		$db->query($sql);
		$query=$db->query("select * from domain ");
		while($row=$db->fetchAssoc($query))
		{
			//��������
			$sql="select * from drecord where ddomain=".$row['domainid'];
			$r=$db->num_rows($db->query($sql));
			$sql="update domain set domainnum=".$r." where domainid=".$row['domainid'];
			$db->query($sql);
		}
		//ɾ����·�ļ�
		unlink($binddir."acl/".$_GET['aclname']);
		unlink($binddir."zone/".$_GET['aclname']."_zone.conf");
		
		
		//������е���·��δӦ��
		$sql = "update setacl set aclisapp='0'";
		$db->query($sql);
		
		//��ӵ�ximoacl.conf��·����
		$sql="select * from setacl where aclis='1'";
		$rc="";
		$rc1="";
		$i=0;
		$query=$db->query($sql);
		while($row=$db->fetchAssoc($query))
		{
			$rc=$rc."include \"acl/".$row['aclident']."\";\n";
			$rc1=$rc1.createaclzone($binddir,$row['aclident'],$db);
			$i++;
		}
		$rc1=$rc1.createanyzone($binddir,$db);
		writeFile($binddir."ximoacl.conf",$rc);
		if($i>0){
		writeFile($binddir."ximozone.conf",$rc1);
		}else 
		{
			$rc1=createanyzone($binddir,$db);
			writeFile($binddir."ximozone.conf",$rc1);
		}
		$sql="update domain set domainisapp='0'";
		$db->query($sql);
		//ɾ���ļ�
		exec("/bin/rm ".$binddir."master/*_".$_GET['aclname']);
		exec("/bin/rm ".$binddir."slave/*_".$_GET['aclname']);
		writelog($db,'��·����',"ɾ����·".$_GET['aclname']);
		if($rowmail['checkLine']==1)
		{
			$subject='��·�������';
			$body='�ط����ʼ�֪ͨ:������·�����н�������·ɾ������·��ʶΪ��'.$_GET['aclname'];
			$sendName=split('@',$rowmail['recMail']);
			sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
		}
	}
//************************** APP *****************************************	
	if($_GET['ac']=='app')
	{
		checkac('Ӧ��');
		$sql="update setacl set aclisapp='1'";
		$db->query($sql);
		$sql="update forwarder set app=1";
		$db->query($sql);

		$rc=""; 	//���ximoacl.conf����
		$rc1="";	//���ximozone.conf����
		
		//DNS��·ת��
		/*
		 * forwarder���ݱ���
		 * zftype: 1 : ȫ��ת��DNS����;  0 : ����DNS�޷�������ת��DNS���� 
		 * state: 1 : ����; 0 : ͣ��
		 * app: 1 : Ӧ��; 0 : δӦ��
		 */
		$sql = "select * from forwarder where state=1";
		$query = $db->query($sql);
		while ($row = $db->fetchAssoc($query)){
			if ($row['zftype'] == 1){ //ȫ��ת��DNS����;
				$dnsip = $row['addr'];
				$dnsip = str_replace(";", ";\n", $dnsip);
				$dnsip .= ";";
				
				$rc .= "include \"acl/".$row['ident']."\";\n";    //����ximoacl.conf������
				//����ximozone.conf������
				$rc1 .= "view \"view_".$row['ident']."\" {\nmatch-clients { ".$row['ident']."; };\nallow-query { any; };\n";
				$rc1 .= "allow-recursion { any; };\nallow-transfer { any; };\nrecursion  yes;\n";
				if(is_set($row['addr'])&&$row['addr']<>"")$rc1 .= "forwarders {\n$dnsip\n};\n};\n";
			}
			else { //����DNS�޷�������ת��DNS���� 
				$dnsip = $row['addr'];
				$dnsip = str_replace(";", ";\n", $dnsip);
				$dnsip .= ";\n";
				
				$rc .= "include \"acl/".$row['ident']."\";\n";    //����ximoacl.conf������
				$rc1 .= "view \"view_".$row['ident']."\" {\n";    //����ximozone.conf������
				if(is_set($row['addr'])&&$row['addr']<>'')$rc1 .= "forwarders {\n$dnsip\n};\n};\n";
			}
		}
		//END    DNS��·ת��
		
		//�����·��Ϣ
		$sql="select * from setacl where aclis='1' order by aclpri";
		$query=$db->query($sql);
		$i=0;
		while($row=$db->fetchAssoc($query)) //ȡ��ϵͳ�е���·
		{
			$rc=$rc."include \"acl/".$row['aclident']."\";\n";
			
			$rc1=$rc1.createaclzone($binddir,$row['aclident'],$db);
			$i++;
		}
		
		if($i>0){//��·��������0������·
		    $rc1=$rc1.createanyzone($binddir,$db);
		}else {//û����·
			$rc1=createanyzone($binddir,$db);
		}
		//END    �����·��Ϣ
		
		//д���ļ�
		writeFile($binddir."ximoacl.conf",$rc);
		writeFile($binddir."ximozone.conf",$rc1);

		//��������ļ�
		
		//����time.sh
		$timesh = "";
		$strtop ="#!/bin/sh\n";
		$strtop .="a=/bin/awk\n";
		$strtop .="b=/bin/sort\n";
		$strtop .="c=/usr/bin/tail\n";
		$strtop .="log=`date +%y%m%d --date=\"-1 day\"`\n";

		$strtop .="\$a -F '[ #]' '{print $11,$6,$9}' $logback\$log > $logover\n";

		$timesh = $timesh.$strtop;
		$sql = "select aclident from setacl where aclisapp='1'";
		$rs = $db->query($sql);
		while($row = $db->fetchAssoc($rs)){
		$flag = $row['aclident'];
		$strmid="\$a '/view_$flag/' $logover>$logtemp\n";
		$strmid .="\$a '{a[$1]+=1};END{for(i in a){print \"$flag\" \",\" \",\" i \",\" \",\" a[i] \",\" \"'\$log'\"}}' $logtemp > $logtemp1\n";
		$strmid .="\$b -n -t, -k5  $logtemp1|\$c -n10 >>$logdns10\n";
		$strmid .="\$a '{a[$2]+=1};END{for(i in a){print \"$flag\" \",\" i \",\" \",\" a[i] \",\" \",\" \"'\$log'\"}}' $logtemp> $logtemp1\n";
		$strmid .="\$b -n -t, -k5 $logtemp1|\$c -n10 >>$logip10\n";
		$strmid .="x=0\n";                                                    
		$strmid .="while [ \$x -le 100 ]\n";                                     
		$strmid .="do\n";                                                     
		$strmid .="x=$((x+1))\n";                                             
		$strmid .="url=` grep $flag $logdns10|sed -n ''\$x'p'|awk -F ',' '{print $3}'`\n";
		$strmid .="echo \$url\n";
		$strmid .="awk '/^'\$url'/' $logtemp>$logtemp2\n";
		$strmid .="awk '{a[$2]+=1}END{for(i in a) print a[i]\",\" \"$flag,\" i \",\" \"'\$url'\" \",\" \"'\$log'\"}' $logtemp2 | sort -n|tail -n10>> $logurl\n";
		$strmid .="done\n";

		$timesh = $timesh.$strmid;
		}
		$strend ="\$a '/view_ANY/' $logover>$logtemp\n";
		$strend .="\$a '{a[$1]+=1};END{for(i in a){print \"ANY\" \",\" \",\" i \",\" \",\" a[i] \",\" \"'\$log'\"}}' $logtemp > $logtemp1\n";
		$strend .="\$b -n -t, -k5  $logtemp1|\$c -n10 >>$logdns10\n";
		$strend .="\$a '{a[$2]+=1};END{for(i in a){print \"ANY\" \",\" i \",\" \",\" a[i] \",\" \",\" \"'\$log'\"}}' $logtemp> $logtemp1\n";
		$strend .="\$b -n -t, -k4 $logtemp1|\$c -n10 >>$logip10\n";
		$strend .="x=0\n";                                                    
		$strend .="while [ \$x -le 100 ]\n";                                     
		$strend .="do\n";                                                     
		$strend .="x=$((x+1))\n";                                             
		$strend .="url=` grep ANY $logdns10|sed -n ''\$x'p'|awk -F ',' '{print $3}'`\n";
		$strend .="echo \$url\n";
		$strend .="awk '/^'\$url'/' $logtemp>$logtemp2\n";
		$strend .="awk '{a[$2]+=1}END{for(i in a) print a[i]\",\" \"ANY,\" i \",\" \"'\$url'\" \",\" \"'\$log'\"}' $logtemp2 | sort -n|tail -n10>> $logurl\n";
		$strend .="done\n";

		$strend .="rm $logtemp\n";
		$strend .="rm $logtemp1\n";

		$strend .="tm=`date +%y%m`\n";
		$strend .="dm=`date +%y%m --date=\"-1 day\"`\n";
		$strend .="if [ \$tm -ne \$dm ];then\n";
		$strend .="tm=\$dm\n";
		$strend .="fi\n";

		$strend .="echo -e \"create table IF NOT EXISTS dns\$tm(server,ip,domain,ipn int,domn int,time);\">$logim\n";
		$strend .="echo -e \".separator \\\",\\\"\" >> $logim\n";
		$strend .="echo -e \".import $logip10 dns\$tm\" >>$logim\n";
		$strend .="echo -e \".import $logdns10 dns\$tm\" >>$logim\n";

		$strend .="echo -e \"create table IF NOT EXISTS url\$tm(num int, flag varchar(10), ip varchar(30), domain varchar(30),time);\" >> $logim\n";
		$strend .="echo -e \".import $logurl url\$tm\" >> $logim\n";

		$strend .="cat $logim | $sqlite3 $dnsdb\n";
		$strend .="#rm -f $logim\n";
		$strend .="rm -f $logdns10\n";
		$strend .="rm -f $logip10\n";
		$strend .="rm -f $logtemp2\n";
		$strend .="rm -f $logurl\n";
		$strend .="rm -f $logover\n";
		$timesh = $timesh.$strend;

		writeFile($logtimesh, $timesh);
		system("chmod +x ".$logtimesh);

		//����ximokey.conf
		$keyconf = createkeyconf($db, $keydir='');
		writeFile($binddir."ximokey.conf",$keyconf);
		
		writelog($db,'��·����',"Ӧ����·��ϵͳ");
		if($rowmail['checkLine']==1)
		{
			$subject='��·�������';
			$body='������·�����н�������ϢӦ�õ���·ϵͳ�У��ط����ʼ�֪ͨ��';
			$sendName=split('@',$rowmail['recMail']);
			sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
		}
	}
//*************************** SD ******************************************	
	if($_GET['ac']=='sd')
	{
		checkac('Ӧ��');
		$sql="select * from setacl where aclisdefault=1";
		$query=$db->query($sql);
		$num=$db->num_rows($query);
		if($num>0){
			$db->close();
			showmessage('ֻ������һ����·ΪĬ��!','acl.php');
		}else 
		{
			//����Ĭ����·
			$sql="update setacl set aclisdefault='1' where aclid=".$_GET['id'];
			$db->query($sql);
			$sql="update setacl set aclisapp='0' where aclid=".$_GET['id'];
			$db->query($sql);
			writelog($db,'��·����',"����Ĭ����·");
			if($rowmail['checkLine']==1)
			{
				$subject='��·�������';
				$body='������·������������Ĭ����·���ط����ʼ�֪ͨ��';
				$sendName=split('@',$rowmail['recMail']);
				sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
			}
		}
	}
//*************************** CD **********************************************
	if($_GET['ac']=='cd')
	{
			checkac('Ӧ��');
			//ȡ��Ĭ����·
			$sql="update setacl set aclisdefault='0' where aclid=".$_GET['id'];
			$db->query($sql);
			$sql="update setacl set aclisapp='0' where aclid=".$_GET['id'];
			$db->query($sql);
			writelog($db,'��·����',"ȡ��Ĭ����·");
			if($rowmail['checkLine']==1)
			{
				$subject='��·�������';
				$body='������·������ȡ����Ĭ����·���ã��ط����ʼ�֪ͨ��';
				$sendName=split('@',$rowmail['recMail']);
				sendMail($rowmail['recSmtp'],$sendName[0],$rowmail['recPWD'],$rowmail['recMail'],$subject,$body,$rowmail['sendMail']);	  		
			}
	}
}

$query=$db->query("select * from setacl where aclisapp='0'");
$num=$db->num_rows($query);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��·����</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
</head>
<body>
<div class="wrap">
<ul class="tab-menu">
    <li class="on"><span>��·����</span> </li>
    <li> <a href="acl_add.php">��·���</a></li>
	<?if($num>0){?>
    <li><a href="acl.php?ac=app">Ӧ��</a></li>
	<?}?>
</ul>

     <div class="content"><table width="98%" class="s s_grid">
      	<tr>
          <td colspan="9"class="caption"><image src="/img/grid.gif"> ��·����</td>
        </tr>
     
            <tr>
              <th >��·����</th>
              <th>��·��ʶ</th>
              <th>��·״̬</th>
              <th>�ݹ�</th>
              <th>����</th>
              <th>��ȫ</th>
              <th>Ĭ��</th>
              <th>����</th>
              <th>����</th>
            </tr>
            <?
		$query=$db->query("select * from setacl order by aclpri asc");
		while($row = $db->fetchAssoc($query))
		{
			$bg="";
			if($row['aclisapp']=="0")
			{
				$bg="bg_red";
			}
		?>
            <tr class="<?echo $bg?>">
              <td ><?echo $row['aclname']?></td>
              <td ><?echo $row['aclident']?></td>
              <td ><?if($row['aclis']=="1"){echo "������";}else{echo "ͣ����";}?></td>
              <td ><?if($row['acldg']=="1"){echo "������";}else{echo "�ر���";}?></td>
              <td ><?if($row['aclcs']=="1"){echo "������";}else{echo "�ر���";}?></td>
              <td ><?if($row['aclsafe']=="1"){echo "������";}else{echo "�ر���";}?></td>
              <td  ><?if($row['aclisdefault']=='1'){echo "Ĭ��";}?></td>
              <td  ><?echo $row['aclabout']?></td>
              <td ><?if($row['aclisdefault']=='1'){?><a href="acl.php?ac=cd&id=<?echo $row['aclid']?>" onclick="javascript:return   confirm('���Ҫȡ������·ΪĬ����·��');">ȡ��Ĭ��</a><?}else{?><a href="acl.php?ac=sd&id=<?echo $row['aclid']?>" onclick="javascript:return   confirm('���Ҫ���ñ���·ΪĬ����·��');">����Ĭ��</a><?}?> | <a href="acl_mode.php?id=<?echo $row['aclid']?>">�޸�</a> | <a href="acl.php?ac=del&id=<?echo $row['aclid']?>&aclname=<?echo $row['aclident']?>" onclick="javascript:return   confirm('���Ҫɾ������·��');">ɾ��</a> | <a href="acl_ip.php?aclname=<?echo $row['aclident'];?>&id=<? echo $row['aclid']?>&type=<? echo $row['acltype']?>">����IP</a></td>
            </tr>
            <?}
      		?>
         
		<?php 	
		$query=$db->query("select * from forwarder where app=0");
		$num=$db->num_rows($query);
        
		if ($num <= 0){
		    $query = $db->query("select * from forwarder");
		    $n = $db->num_rows($query);
		    if ($n > 0){
		        $num = 1;
		    }
		}
		?>
      	
      </table></div></div>

<? include "../copyright.php";?>
</body>
</html>
