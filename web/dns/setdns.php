<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
	checkac('Ӧ��');
	$_POST['dnssecip']=($_POST['dnssecip']!="")?$_POST['dnssecip'].";":"";
	$_POST['dnsthirdip']=($_POST['dnsthirdip']!="")?$_POST['dnsthirdip'].";":"";
	$sql="update setdns set dnsname='".$_POST['dnsname']."',dnsdomain='".$_POST['dnsdomain']."',dnstype='".$_POST['dnstype']."'";
	$sql=$sql.",dnsip='".$_POST['dnsip']."',dnsadmin='".$_POST['dnsadmin']."',dnskey='".$_POST['dnskey']."',dnsdatebase=".$_POST['dnsdatebase'].",";
	$sql=$sql."dnsforward='".$_POST['dnsforward']."',dnsmainip='".$_POST['dnsmainip']."',dnssecip='".$_POST['dnssecip']."',dnsthirdip='".$_POST['dnsthirdip'];
	$sql=$sql."',dnsrefresh=".$_POST['dnsrefresh'].",dnsretry=".$_POST['dnsretry'].",dnsexpire=".$_POST['dnsexpire'].",dnsttl=".$_POST['dnsttl'].",dnsupdate=datetime('now','localtime')";
	$sql=$sql." where dnsid=1";
	
	$db->query($sql);
	createdns($db,$binddir);
	writelog($db,'DNS����',"����".$_POST['dnstype']."�ɹ�!");
	$sql="update domain set domainisapp='0'";
	$db->query($sql);
	aclapp($db,$binddir); //����ݹ��ѯ��ַ Ӧ�õ���·
	showmessage($_POST['dnstype'].'���óɹ�','setdns.php');
		

}else 
{//��ȡ��Ϣ
	$query=$db->query("select * from setdns where dnsid=1");
	$row=$db->fetchAssoc($query);
	$row['dnssecip']=($row['dnssecip']!="")?substr($row['dnssecip'], 0, -1):"";
	$row['dnsthirdip']=($row['dnsthirdip']!="")?substr($row['dnsthirdip'], 0, -1):"";
}

function aclapp($db,$binddir){
global $logover,$logtemp,$logtemp1,$logtemp2,$logdns10,$logip10,$logurl,$logim,$sqlite3,$dnsdb,$logtimesh,$logback;
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
		$rc1 .= "forwarders {\n$dnsip\n};\n};\n";
	}
	else { //����DNS�޷�������ת��DNS���� 
		$dnsip = $row['addr'];
		$dnsip = str_replace(";", ";\n", $dnsip);
		$dnsip .= ";\n";
		
		$rc .= "include \"acl/".$row['ident']."\";\n";    //����ximoacl.conf������
		$rc1 .= "view \"view_".$row['ident']."\" {\n";    //����ximozone.conf������
		$rc1 .= "forwarders {\n$dnsip\n};\n};\n";
	}
}
//END    DNS��·ת��

//�����·��Ϣ
$sql="select * from setacl where aclis='1' order by aclpri";
$query=$db->query($sql);
$i=0;
while($row=$db->fetchAssoc($query))
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
//echo $rc1;
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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>DNS����</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>
<script language="javascript">

function checklogin(){
	var regipd=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\/(\d|1\d|2\d|3[0-2])$/;
	var regip=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
	if(document.setdns.dnsname.value == ''){	
			alert("������DNS��");
			document.setdns.dnsname.select();
			return false;
		}
		if(document.setdns.dnsdomain.value == ''){	
			alert("������DNS����");
			document.setdns.dnsdomain.select();
			return false;
		}
		if(document.setdns.dnsip.value == ''){	
			alert("������DNSip");
			document.setdns.dnsip.select();
			return false;
		}
		else
	    {
		 if(!checkip(document.setdns.dnsip.value) && !checkipv6(document.setdns.dnsip.value))
		  {
			alert("�����IP��ʽ����ȷ��");
		    document.setdns.dnsip.select();
			return false;
		   }
	    }
	
		if(document.setdns.dnskey.value == ''){	
			alert("������DNS��Կ");
			document.setdns.dnskey.select();
			return false;
		}
		if(document.setdns.dnsadmin.value == ''){	
			alert("������DNS����Ա");
			document.setdns.dnsadmin.select();
			return false;
		}
		if(document.setdns.dnsdatebase.value == ''){	
			alert("������DNS�����С");
			document.setdns.dnsdatebase.select();
			return false;
		}else if(!checkInt(document.setdns.dnsdatebase.value) || document.setdns.dnsdatebase.value<=0){
			alert("�����DNS�����С���Ϸ�");
			document.setdns.dnsdatebase.select();
			return false;
		}
		if(_g("dnssecip").value!=""){
		  var ip_array=_g("dnssecip").value.split(";");
		  for(var i=0;i<ip_array.length;i++){
			  if(!regipd.test(ip_array[i]) && !regip.test(ip_array[i])){
				  alert("���������ʹ�õ�ַ�ĸ�ʽ����ȷ��");
				  _g("dnssecip").select();
				  return false;
			  }
		  }
		}
		if(_g("dnsthirdip").value!=""){
		  var ip_array=_g("dnsthirdip").value.split(";");
		  for(var i=0;i<ip_array.length;i++){
			  if(!regipd.test(ip_array[i]) && !regip.test(ip_array[i])){
				  alert("���������ݹ��ѯ��ַ�ĸ�ʽ����ȷ��");
				  _g("dnsthirdip").select();
				  return false;
			  }
		  }
		}
		if(document.setdns.dnsrefresh.value == ''){	
			alert("������refresh");
			document.setdns.dnsrefresh.select();
			return false;
		}else if(!checkInt(document.setdns.dnsrefresh.value) || document.setdns.dnsrefresh.value<=0){
			alert("�����refresh���Ϸ�");
			document.setdns.dnsrefresh.select();
			return false;
		}
		if(document.setdns.dnsretry.value == ''){	
			alert("������retry");
			document.setdns.dnsretry.select();
			return false;
		}else if(!checkInt(document.setdns.dnsretry.value) || document.setdns.dnsretry.value<=0){
			alert("�����retry���Ϸ�");
			document.setdns.dnsretry.select();
			return false;
		}
		if(document.setdns.dnsexpire.value == ''){	
			alert("������expire");
			document.setdns.dnsexpire.select();
			return false;
		}else if(!checkInt(document.setdns.dnsexpire.value) || document.setdns.dnsexpire.value<=0){
			alert("�����expire���Ϸ�");
			document.setdns.dnsexpire.select();
			return false;
		}
		if(document.setdns.dnsttl.value == ''){	
			alert("������ttl");
			document.setdns.dnsttl.select();
			return false;
		}else if(!checkInt(document.setdns.dnsttl.value) || document.setdns.dnsttl.value<=0){
			alert("�����ttl���Ϸ�");
			document.setdns.dnsttl.select();
			return false;
		}
	return true;
}
function isEmail(str){ 
res = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
var re = new RegExp(res); 
return !(str.match(re) == null); 
} 
function   checktelphone(str){   
  res   =   /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,12})(-(\d{3,}))?$/  ;  
 var re=new RegExp(res);
 return !(str.match(re)==null);
  }
</script>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; DNS����</div>


      <div class="content">
	  <form id="setdns" name="setdns" method="post" action="setdns.php" onsubmit="return checklogin();">
	  <table width="768" class="s s_form">
        <tr>
          <td colspan="2"class="caption">DNS����</td>
        </tr>
         <tr>
          <td>ע���DNS���ƣ�</td>
          <td>
            <input name="dnsname" type="text" id="dnsname" size="10" value="<?echo $row['dnsname']?>" />
          .
          <input name="dnsdomain" type="text" id="dnsdomain" value="<?echo $row['dnsdomain']?>" />
          </td>
        </tr>
        <tr>
          <td>ע���DNS IP��</td>
          <td>
            <input name="dnsip" type="text" id="dnsip" value="<?echo $row['dnsip']?>" size="30" />
          </td>
        </tr>
        <tr>
          <td>�����Ա���䣺</td>
          <td>
            <input name="dnsadmin" type="text" id="dnsadmin" value="<?echo $row['dnsadmin']?>" />
          ��Ϊ@�����������壬������.����@����root@domain.com��Ϊroot.domain.com</td>
        </tr>
        <tr>
          <td>DNS��������Կ��</td>
          <td>
            <input name="dnskey" type="text" id="dnskey" size="50" value="<?echo $row['dnskey']?>" />
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="redtext"> ������Ϊ����DNS���������ߴ�DNS������ʱ������Ҫ��д</td>
        </tr>
        <tr>
          <td>�������ݿ��С��</td>
          <td>
            <input name="dnsdatebase" type="text" id="dnsdatebase" size="10" value="<?echo $row['dnsdatebase']?>" />
          M <span class="redtext">(���������ʱ��Ҫ��д) </span></td>
        </tr>
         <tr>
          <td>&nbsp;</td>
          <td>��Ϊת���������ʹӷ�����ʱ��Ҫ��д</td>
        </tr>
        <tr>
           <td> ����ʹ�õ�ַ��</td>
           <td>
             <input name="dnssecip" type="text" id="dnssecip" value="<?echo $row['dnssecip']?>" size="60" />
           </td>
         </tr>
         <tr>
           <td>&nbsp;</td>
           <td class="redtext">������������ʹ�ñ�DNS��Ϊ��,����ÿ��ip��ip����;�Ÿ���</td>
         </tr>
         <tr>
           <td>����ݹ��ѯ��ַ��</td>
           <td>
             <input name="dnsthirdip" type="text" id="dnsthirdip" value="<?echo $row['dnsthirdip']?>" size="60" />
           </td>
         </tr>
         <tr>
           <td>&nbsp;</td>
           <td class="redtext">����������ʱΪ�գ�����ÿ��ip��ip����;�Ÿ��� </td>
         </tr>
         <tr>
          <td>Refresh��</td>
          <td>
            <input name="dnsrefresh" type="text" id="dnsrefresh" size="14" value="<?echo $row['dnsrefresh']?>" />
            ��
          ������������೤ʱ���������
          </td>
        </tr>
         <tr>
           <td>Retry��</td>
           <td>
             <input name="dnsretry" type="text" id="dnsretry" size="14" value="<?echo $row['dnsretry']?>" />
           �� ��������������������ʧЧ�ٴθ���ʱ��</td>
         </tr>
         <tr>
           <td>Expire��</td>
           <td>
             <input name="dnsexpire" type="text" id="dnsexpire" size="14" value="<?echo $row['dnsexpire']?>" />
           �� �����������������޷���������������������ݶ೤ʱ��ԭ����ʧЧ </td>
         </tr>
         <tr>
           <td>Minimum��</td>
           <td>
             <input name="dnsttl" type="text" id="dnsttl" size="14" value="<?echo $row['dnsttl']?>" />
           �� ����Դ��¼��û������TTLֵ����������Ϊ׼ </td>
         </tr>        
        
        <tr>
          <td colspan="2" class="footer">
            <input type="submit" name="Submit" value="��������" />
          </td>
        </tr>
      </table>
	  </form>
	  </div>
 
<?$db->close();?></div><div class="push"></div>
<? include "../copyright.php";?>
</body>
</html>
