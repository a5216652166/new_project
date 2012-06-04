<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
//checkac('Ӧ��');
checkac_do(6);
$domainid=$_GET["domainid"];
$query=$db->query("select * from domain where domainid=".$domainid);
$row=$db->fetchAssoc($query);
$domainname=$row['domainname'];

/**
 *  ��ip��ַ����
 *  ipptr($ip)
 *  ������	ip��ַ
 *  ����ֵ�� һ�����飬ip��ַ����������֡�
 */
function ipptr($ip6){
	$full = "";
	$rst = "";
	if (filter_var($ip6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){//ipv6
		if (strpos($ip6, "::") === false){ //û��"::"
			$yus = explode(":", $ip6);
			
			foreach ($yus as $yu){
			
				//�ָ�ʡ�Ե�ǰ��0
				$zeros = 4 - strlen($yu) ;
				if ($zeros == 1)
					$full .= "0";
				else if ($zeros == 2)
					$full .= "00";
				else if ($zeros == 3)
					$full .= "000";
				
				$full .= $yu;
			}
		}
		else {								//�� "::"
			$yus = explode(":", $ip6);
			
			$num = 8 - count($yus) + 1;
			
			foreach ($yus as $yu){
				if ($yu == ""){ //�ָ� "::"
					//�ڶ��� $yu == "" ʱ, $num ���� 0��˵�� "::"  λ���׻�β��Ӧ�� 0000
					if ($num == 0)
						$yu .= "0000";
						
					for ($num; $num > 0; $num--){
						$yu .= "0000";
					}
					$full .= $yu;
				}
				else {//�ָ�ʡ�Ե�ǰ��0
					$zeros = 4 - strlen($yu) ;
					if ($zeros == 1)
						$full .= "0";
					else if ($zeros == 2)
						$full .= "00";
					else if ($zeros == 3)
						$full .= "000";
					
					$full .= $yu;
				}
			}
		}
		$fx = array_reverse(str_split($full, 1));
		$fx2 = implode(".", $fx);
		$rs[0] = strtolower(substr($fx2, 32).".ip6.arpa");
		$rs[1] = substr($fx2, 0, 31);
		return $rs;
	}
	else{//ipv4
		$ip=preg_split('/\./',$ip6);
        $rs[0]=$ip[2].".".$ip[1].".".$ip[0].".in-addr.arpa";
        $rs[1]=$ip[3];
        return $rs;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>���������Զ���������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �������� </div>
<ul class="tab-menu">
    <li><a href="record.php?domainid=<?echo $domainid?>">��¼���� </a></li>
    <li><a href="record_add.php?domainid=<?echo $domainid?>">��¼���</a></li>
    <li><a href="domain.php">��������</a></li>
	<li><a href="domain_ptr.php?domainid=<?echo $domainid?>" onclick="javascript:return   confirm('���Ҫ�Զ����ɱ������ķ��������¼��');">�Զ����ɱ������������</a></li>     
</ul>
<div class="content">
		  <table width="95%"  align="center" class="s s_grid">
		  <tr>
          <td  class="caption"><?echo $domainname?>������������Զ�����</td>
        </tr>
            <tr>
              <td>���ڽ�������<?echo $domainname?>�������⣺<br>
              <?$sql="select * from drecord where ddomain=".$domainid;
              $query=$db->query($sql);
              $valid_chars = "1234567890-_.";
              while ($row=$db->fetchAssoc($query)) {
              	//����������������
              	if (!stristr($row['dname'],"*"))
              	{
              		if ( filter_var($row['dvalue'], FILTER_VALIDATE_IP) )
              		{
              			//�ж�����IP,Ȼ�󿴷���治����
              			
              			//$ip=preg_split('/\./',$row['dvalue']);
              			//$myptrname=$ip[2].".".$ip[1].".".$ip[0].".in-addr.arpa";
              			
              			$rs = ipptr($row['dvalue']); //����IP
              			$myptrname = $rs[0];
              			$rcd = $rs[1];
              			$q=$db->query("select * from domain where domainname='".$myptrname."'");
              			$num=$db->num_rows($q);
              			if($num>0)
              			{
              				//�����ļ�����ֱ����ӽ�ȥ
              				$q=$db->query("select * from domain where domainname='".$myptrname."'");
              				$r=$db->fetchAssoc($q);
              				$mydomainid=$r['domainid'];
              				
              				//��ɾ���ɼ�¼���ظ���¼
              				$sql ="select count(*) as num from drecord where ddomain=$mydomainid and dname='".$rcd."' and dvalue='".$row['dname'].".".$domainname.".' and dacl='".$row['dacl']."'";
              				$q=$db->query($sql);
							$r=$db->fetchAssoc($q);
              				if($r['num']==0){
              					//�����¼
								$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
								$sql=$sql.",'".$rcd."',0,'PTR','".$row['dname'].".".$domainname.".','".$row['dacl'];
								$sql=$sql."','1','0',datetime('now','localtime'))";
								$db->query($sql);
								$n=$db->query("select count(*) cnt from drecord where ddomain=".$mydomainid)->fetch();
								$sql="update domain set domainnum=".$n['cnt']." where domainid=".$mydomainid;
								$db->query($sql);
								echo "����".$row['dacl']."��·���⣺".$rcd."    IN     PTR     ".$row['dname'].".".$domainname.".<br>";
							}
              			}else 
              			{
              				//�����ļ��������򴴽�
              				//������
              				$sql="select * from setdns where dnsid=1";
              				$q=$db->query($sql);
              				$r=$db->fetchAssoc($q);
              				$mydns=$r['dnsname'].".".$r['dnsdomain'].".";
              				$sql="insert into domain (domainname,domainadmin,domainsoa,domainserial,domainrefresh,domainretry,domainexpire,domainttl,domainis,domainisapp,domainupdate,domainnum)values(";
							$sql=$sql."'".$myptrname."','".$r["dnsadmin"]."','".$r['dnsname'].".".$r['dnsdomain']."',".createnewserial().",".$r['dnsrefresh'];
							$sql=$sql.",".$r['dnsretry'].",".$r['dnsexpire'].",".$r['dnsttl'].",'1','0',datetime('now','localtime'),0)";
							$db->query($sql);

							$id = $db->fetchAssoc($db->query("SELECT domainid FROM domain ORDER BY domainid DESC LIMIT 1"));
							for($i=2;$i<=6;$i++){
								$sql="insert into do_access (role_id,domain_id,privilege_id,status) values($_SESSION[role],$id[domainid],$i,1)";
								$db->query($sql);
							}
							if($_SESSION['role']!=1){
								for($i=2;$i<=6;$i++){
									$sql="insert into do_access (role_id,domain_id,privilege_id,status) values(1,$id[domainid],$i,1)";
									$db->query($sql);
								}
							}
							echo "����".$myptrname."��������<br>";
							//����NS��¼
							$q=$db->query("select * from domain where domainname='".$myptrname."'");
              				$r=$db->fetchAssoc($q);
              				$mydomainid=$r['domainid'];
              				//������·
              				$acl=$db->query("select * from setacl");
              				while($aclrow=$db->fetchAssoc($acl)){
              					//ÿ����·����һ��NS
	              				$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
								$sql=$sql.",'@',0,'NS','".$mydns."','".$aclrow['aclident'];
								$sql=$sql."','1','0',datetime('now','localtime'))";
								$db->query($sql);
								echo "����".$aclrow['aclident']."��·��¼��@    IN     NS     ".$mydns."<br>";
								$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
								$db->query($sql);
              				}
              				//ͨ��NS
              				$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
							$sql=$sql.",'@',0,'NS','".$mydns."','ANY";
							$sql=$sql."','1','0',datetime('now','localtime'))";
							$db->query($sql);
							echo "����ͨ����·��¼��@    IN     NS     ".$mydns."<br>";
							$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
							$db->query($sql);
              				//д�뷴���¼
							$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
							$sql=$sql.",'".$rcd."',0,'PTR','".$row['dname'].".".$domainname.".','".$row['dacl'];
							$sql=$sql."','1','0',datetime('now','localtime'))";
							$db->query($sql);
							$n=$db->query("select count(*) cnt from drecord where ddomain=".$mydomainid)->fetch();
							$sql="update domain set domainnum=".$n['cnt']." where domainid=".$mydomainid;
							$db->query($sql);
							echo "����".$row['dacl']."��·���⣺".$rcd."    IN     PTR     ".$row['dname'].".".$domainname.".<br>";
              				
              			}
              		}
              	}
              	
              }
              //��ɴ���
              writelog($db,'��������',"�Զ�����������".$domainname."����!");
			$db->close();
			//showmessage('�Զ�������������ɹ�!',0);
              ?>
              </td>
            </tr>
		  <tr><td><input type="button" onclick="javascript:history.go(-1)" value="����"></td></tr>
          </table></div><div class="push"></div></div>

<? $db->close();?>
<? include "../copyright.php";?>
</body>
</html>
