<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
$bindquerylog="/xmdns/var/logquery/dns_query.log";
$page = checkPage();
//���ɾ����־
if($_GET['op']=='deldns')
{
    checkac('ɾ��');
	//������ɾ���ļ�
	if($_GET['logfile']=="all")
	{
		exec("echo \"\" > /ximolog/dns_query.log;");
		$sql="select doname from dnslog";
		$sql2="delete  from dnslog";
	}else
	{
		$sql="select doname from dnslog where doname='".$_GET['logfile']."'";
		$sql2="delete from dnslog where doname='".$_GET['logfile']."'";
	}
	$newdb=new SQL($logbackdb);
	$query=$newdb->query($sql);
	while($row=$newdb->fetchAssoc($query))
	{		//ɾ���ļ�
		$bb="/bin/rm ".$bindlogdir."logback/".$row['doname'];
		exec($bb);		
	}
	$newdb->query($sql2);
	$newdb->close();
	writelog($db,'ɾ��DNS������־','ɾ��'.$_GET['logfile'].'DNS������־');
	showmessage('DNS������־������ɾ���ɹ�','logdel.php');
}
//������ɾ��������־
if($_GET['op']=='deldnsdate')
{
    checkac('ɾ��');
	//������ɾ���ļ�
	if($_GET['start']!=""&&$_GET['end']!='')
	{
	
		$sql="select doname from dnslog where logname>='".$_GET['start']."' and logname<='".$_GET['end']."'";
		$sql2="delete from dnslog  where logname>='".$_GET['start']."' and logname<='".$_GET['end']."'";
			$newdb=new SQL($logbackdb);
	$query=$newdb->query($sql);
	while($row=$newdb->fetchAssoc($query))
	{		//ɾ���ļ�
		$bb="/bin/rm ".$bindlogdir."logback/".$row['doname'];
		exec($bb);		
	}
	$newdb->query($sql2);
	$newdb->close();
	writelog($db,'ɾ��DNS������־','ɾ����'.$_GET['start'].'��'.$_GET['end'].'ʱ��ε�DNS������־');
	showmessage('DNS������־������ɾ���ɹ�','logdel.php');
	}else 
	{
		showmessage('ʱ������벻��ȷ','logdel.php');
	}

}
//���״̬��־
if($_GET['op']=='dnsstate')
{
    checkac('ɾ��');
	
		$bb="/usr/bin/true > ".$bindgenerallog;
		exec($bb);		
	writelog($db,'���DNS״̬��־','���DNS״̬��־');
	showmessage('DNS״̬��־��ճɹ�','logdel.php');

}
if($_GET['op']=='today')
{
	checkac('ɾ��');
		$bb="/usr/bin/true > ".$bindquerylog;
		exec($bb);		
	writelog($db,'���DNS������־','���DNS������־');
	showmessage('DNS������־��ճɹ�','logdel.php');

}
//������ɾ��������־
if($_GET['op']=='deloplog')
{
    checkac('ɾ��');
	//������ɾ���ļ�
	if($_GET['start2']!=""&&$_GET['end2']!='')
	{
		$sql="delete from dorecord  where date(addtime)>='".$_GET['start2']."' and date(addtime)<='".$_GET['end2']."'";
			
	$db->query($sql);	
	writelog($db,'ɾ�����������־','ɾ����'.$_GET['start2'].'��'.$_GET['end2'].'ʱ��εĹ��������־');
	showmessage('DNS���������־������ɾ���ɹ�','logdel.php');
	}else 
	{
		showmessage('ʱ������벻��ȷ','logdel.php');
	}

}
//������ɾ����½��־
if($_GET['op']=='dellogin')
{
    checkac('ɾ��');
	//������ɾ���ļ�
	if($_GET['start3']!=""&&$_GET['end3']!='')
	{
		$sql="delete from userlog  where date(addtime)>='".$_GET['start3']."' and date(addtime)<='".$_GET['end3']."'";
			
	$db->query($sql);	
	writelog($db,'ɾ�����������־','ɾ����'.$_GET['start3'].'��'.$_GET['end3'].'ʱ��εĹ����½��־');
	showmessage('DNS��½��־������ɾ���ɹ�','logdel.php');
	}else 
	{
		showmessage('ʱ������벻��ȷ','logdel.php');
	}

}

//���dns������־
if ($_GET['op']=='dnslogs') {
	checkac('ɾ��');
	exec("cat /dev/null >".$bindquerylog);
	writelog($db,'���DNS������־','���DNS������־');
	showmessage('DNS������־��ճɹ�','logdel.php');
}


//��շ���ǽ��־
if ($_GET['op']=='firewall') {
	checkac('ɾ��');
	exec("cat /dev/null >".$ipfwlog);
	writelog($db,'��շ���ǽ��־','��շ���ǽ��־');
	showmessage('����ǽ��־��ճɹ�','logdel.php');
}
if ($_GET['op']=='safe') {
	checkac('ɾ��');
	exec("echo \"\"> /var/log/wtmp;");
	writelog($db,'��հ�ȫ��־','��հ�ȫ��־');
	showmessage('��ȫ��־��ճɹ�','logdel.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��½��־</title>
<link href="../divstyle.css" rel="stylesheet" type="text/css" />
<script src="../js/calendar.js"></script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="28" align="left" background="../images/bg_topbg.gif">&nbsp;��ǰλ��:&gt;&gt; ��־ɾ�� </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><table width="768" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#82C4E8">
      <tr>
        <td width="742" height="25" align="center" background="../images/abg2.gif" bgcolor="#D7F5F9" class="greenbg">��־ɾ��</td>
      </tr>
      <form id="dnsdel" name="dnsdel" method="get" action="logdel.php"><tr>
        <td height="25" align="left" bgcolor="#FFFFFF">
          ��ǰDNS������־��¼:
                                                <label>
                                                <select name="logfile" id="logfile">
                                                  <option value="all">������־</option>
                                                  <? $newdb=new SQL($logbackdb);
                                                  $query=$newdb->query('select * from dnslog order by addtime desc');
                                                  while($row=$newdb->fetchAssoc($query)){?>
                                                  <option value="<? echo $row['doname'];?>"><? echo $row['logname'].'������־';?></option>
                                                  <?}
                                                  $newdb->close();?>
                                                </select>
                                                </label>
                                                <label>
                                                <input name="op" type="hidden" id="op" value="deldns" />
                                                <input type="submit" name="Submit" value="ɾ��ѡ���DNS������־" onclick="javascript:return   confirm('Ҫɾ��ѡ���DNS������־��');" />
                                                </label>
                  </td>
      </tr></form>
     <form id="dnslogdel" name="dnslogdel" method="get" action="logdel.php"> <tr>
        <td height="25" align="left" background="../images/abg.gif" bgcolor="#D7F5F9" class="graybg">
          ������ɾ��DNS������־:
              <label>
              <input name="start" type="text" id="start" size="13" ondblclick="calendar()" value="<?echo $_GET['start']?>" title="˫����������ѡ��" />
              </label>
      <label>
 ��
 <input name="end" type="text" id="end" size="13" ondblclick="calendar()" value="<?echo $_GET['end']?>" title="˫����������ѡ��" />      
 <input name="op" type="hidden" id="op" value="deldnsdate" /><input type="submit" name="Submit2" value="ɾ����ʱ��ε���־�ļ�" onclick="javascript:return   confirm('Ҫɾ��ѡ������ڶ���DNS������־��');"/>
      </label>
                </td>
      </tr></form>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF">���dns״̬��־��&gt;&gt;<a href="logdel.php?op=dnsstate" onclick="javascript:return   confirm('Ҫ���DNS��״̬��־��');">��Ҫ���&lt;&lt;</a> <a href="?op=today">&gt;&gt;��յ�����־</a> </td>
      </tr><form id="oplog" name="oplog" method="get" action="logdel.php">
      <tr>
        <td height="25" align="left" background="../images/abg.gif" bgcolor="#D7F5F9" class="graybg">
          ������ɾ��������־:
              <label>
      <input name="start2" type="text" id="start2" size="13" ondblclick="calendar()" value="<?echo $_GET['start2']?>" title="˫����������ѡ��" />
      </label>
      <label> ��
        <input name="end2" type="text" id="end2" size="13" ondblclick="calendar()" value="<?echo $_GET['end2']?>" title="˫����������ѡ��" />
      <input name="op" type="hidden" id="op" value="deloplog" /> <input type="submit" name="Submit22" value="ɾ����ʱ��εĲ�����־" onclick="javascript:return   confirm('Ҫɾ����ʱ��εĲ�����־��');" />
      </label>
        </td>
      </tr></form>
      <form id="loginlog" name="loginlog" method="get" action="logdel.php"><tr>
        <td height="25" align="left" bgcolor="#FFFFFF">
          ������ɾ����½��־:
            <label>
      <input name="start3" type="text" id="start3" size="13" ondblclick="calendar()" value="<?echo $_GET['start3']?>" title="˫����������ѡ��" />
      </label>
      <label> ��
        <input name="end3" type="text" id="end3" size="13" ondblclick="calendar()" value="<?echo $_GET['end3']?>" title="˫����������ѡ��" />
      <input name="op" type="hidden" id="op" value="dellogin" /> <input type="submit" name="Submit222" value="ɾ����ʱ��εĵ�½��־" onclick="javascript:return   confirm('Ҫɾ����ʱ��εĵ�½��־��');" />
      </label>
        </td>
      </tr></form>
      
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF">���dns������־��<a href="logdel.php?op=dnslogs" onclick="javascript:return   confirm('Ҫ���DNS�Ľ�����־��');">��Ҫ���</a></td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF">��շ���ǽ��־��<a href="logdel.php?op=firewall" onclick="javascript:return   confirm('Ҫ��շ���ǽ��־��');">��Ҫ���</a></td>
      </tr>
	        <tr>
        <td height="25" align="left" bgcolor="#FFFFFF">��հ�ȫ��־��<a href="logdel.php?op=safe" onclick="javascript:return   confirm('Ҫ��հ�ȫ��־��');">��Ҫ���</a></td>
      </tr>

    </table></td>
  </tr>
</table>

<? include "../copyright.php";?>
</body>
</html>
