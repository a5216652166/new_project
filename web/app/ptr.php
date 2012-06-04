<?php
/*
 +-----------------------------------------------------
 * 	2010-2-25
 +-----------------------------------------------------
 *		
 +-----------------------------------------------------
 */

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


$domainid=$doid;
$query=$db->query("select * from domain where domainid=".$domainid);
$row=$db->fetchAssoc($query);
$domainname=$row['domainname'];

$sql="select * from drecord where ddomain=".$domainid;
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
				$sql ="select * from drecord where ddomain=$mydomainid and dname='".$rcd."' and dvalue='".$row['dname'].".".$domainname.".' and dacl='".$row['dacl']."'";
				$abcd=$db->query($sql);
				$num=$db->num_rows($abcd);
				if($num>0){
				$sql ="delete from drecord where ddomain=$mydomainid and dname='".$rcd."' and dvalue='".$row['dname'].".".$domainname.".' and dacl='".$row['dacl']."'";
				$db->query($sql);
				$sql="update domain set domainnum=domainnum-1 where domainid=".$mydomainid;
				$db->query($sql);				
				}
				//�����¼
				$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
				$sql=$sql.",'".$rcd."',0,'PTR','".$row['dname'].".".$domainname.".','".$row['dacl'];
				$sql=$sql."','1','0',datetime('now','localtime'))";
				$db->query($sql);
				$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
				$db->query($sql);
			}
			else 
			{
				//�����ļ��������򴴽�
				//������
				$sql="select * from setdns where dnsid=1";
				$q=$db->query($sql);
				$r=$db->fetchAssoc($q);
				$mydns=$r['dnsname'].".".$r['dnsdomain'].".";
				$sql="insert into domain (domainname, domainadmin, domainsoa, domainserial, domainrefresh, domainretry, domainexpire, domainttl, domainis, domainisapp, domainupdate, domainnum)values(";
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
				$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
				$db->query($sql);
				}
				//ͨ��NS
				$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
				$sql=$sql.",'@',0,'NS','".$mydns."','ANY";
				$sql=$sql."','1','0',datetime('now','localtime'))";
				$db->query($sql);
				$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
				$db->query($sql);
				//д�뷴���¼
				$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$mydomainid;
				$sql=$sql.",'".$rcd."',0,'PTR','".$row['dname'].".".$domainname.".','".$row['dacl'];
				$sql=$sql."','1','0',datetime('now','localtime'))";
				$db->query($sql);
				$sql="update domain set domainnum=domainnum+1 where domainid=".$mydomainid;
				$db->query($sql);
				
			}
		}
	}
}
//��ɴ���
writelog($db,'��������',"�Զ�����������".$domainname."����!");
?>