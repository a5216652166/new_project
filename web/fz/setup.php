<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
    checkac('���');
	$query=$db->query("select * from domain where domainid=".$_POST['domain']);
$row=$db->fetchAssoc($query);
$domainname=$row['domainname'];

	
			$sql="insert into drecord (ddomain,dmainname,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate,isfz,isch,dsource)values(".$_POST['domain'].",'".$domainname."'";
			$sql=$sql.",'".$_POST['dname']."',0,'".$_POST['dtype']."','".$_POST['dvalue']."','".$_POST['dacl'];
			$sql=$sql."','1','0',datetime('now','localtime'),1,0,'".$_POST['dacl']."')";
			$db->query($sql);
			$sql="update domain set domainnum=domainnum+1 where domainid=".$_POST['domain'];
			$db->query($sql);
	
	$sql="update domain set domainisapp='0' where domainid=".$_POST['domain'];
	$db->query($sql);
	$sql="select * from  drecord where  dmainname='".$domainname."' and dname='".$_POST['dname']."' and dtype='".$_POST['dtype']."' order by did desc";
	$e=$db->query($sql);
	$ee=$db->fetchAssoc($e);

$sql="insert into yz (yzname,yzip,yztime,yzis,yzreid) values('".$domainname."-".$_POST['dtype']."-".$_POST['dname']."','".$_POST['dvalue']."',".$_POST['yztime'].",1,".$ee['did'].")";	
$db->query($sql);	
		$db->close();
		showmessage('������¼��ӳɹ�!','setup.php');
		

}
if(isset($_GET['ac']))
{
	if($_GET['ac']=='del')
	{
		checkac('ɾ��');
		$sql="delete from drecord where did=".$_GET['id'];
		$db->query($sql);
		$sql="update domain set domainisapp='0' where domainid=".$_GET['domainid'];
		$db->query($sql);
		$sql="update domain set domainnum=domainnum-1 where domainid=".$_GET['domainid'];
			$db->query($sql);
		writelog($db,'��������',"ɾ����¼��".$_GET['dname2'].".".$_GET['ddomain']);
	}
	if($_GET['ac']=='stop')
	{
	    checkac('Ӧ��');
		$sql="update drecord set dis='0' where did=".$_GET['id'];
		$db->query($sql);
		$sql="update domain set domainisapp='0' where domainid=".$_GET['domainid'];
		$db->query($sql);
		writelog($db,'��������',"ͣ�ü�¼��".$_GET['dname2'].".".$_GET['domainname']);
	}
	if($_GET['ac']=='start')
	{
	    checkac('Ӧ��');
		$sql="update drecord set dis='1' where did=".$_GET['id'];
		$db->query($sql);
		$sql="update domain set domainisapp='0' where domainid=".$_GET['domainid'];
		$db->query($sql);
		writelog($db,'��������',"���ü�¼��".$_GET['dname2'].".".$_GET['domainname']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������</title>
<link href="../divstyle.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="28" align="left" background="../images/bg_topbg.gif">&nbsp;��ǰλ��:&gt;&gt; ��������</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <table width="768" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#82C4E8">
      <tr>
          <td height="25" colspan="2" align="center" bgcolor="#D7F5F9" class="greenbg">��������</td>
        </tr>
         <tr>
           <td height="25" colspan="2" align="center" bgcolor="#FFFFFF" class="graytext">
             <table width="100%" border="00" cellpadding="2" cellspacing="1" bgcolor="#0099FF">
             <form id="setup" name="setup" method="post" action="setup.php">  <tr>
                 <td width="7%" align="right" bgcolor="#F0FBFD">������</td>
                 <td width="19%" height="22" align="left" bgcolor="#FFFFFF"><label>
                     <select name="domain" id="domain">
                <?$q=$db->query("select * from domain");
                while($r=$db->fetchAssoc($q))
                {?>
                <option value="<?echo $r['domainid']?>" <?if($_POST['domain']==$r['domainid']){echo "selected";}?>><?echo $r['domainname']?></option>
                <?}?>
                </select>
                 </label></td>
                 <td width="11%" align="right" bgcolor="#F0FBFD">��¼���ͣ�</td>
                 <td width="25%" align="left" bgcolor="#FFFFFF"><label>
                   <select name="dtype" id="dtype">
                <?for($i=0;$i<sizeof($dtype);$i++){?>
                <option value="<?echo $dtype[$i]?>"><?echo $dtype[$i]?></option>
                <?}?>
                </select>
                 </label></td>
                 <td width="11%" align="right" bgcolor="#F0FBFD">��·��</td>
                 <td width="13%" align="left" bgcolor="#FFFFFF"><label>
                  <select name="dacl" id="dacl">
              <option value="ANY" <?if($_POST['dacl']=="ANY"){echo "selected";}?>>ͨ��</option>
               <?$q=$db->query("select * from setacl");
                while($r=$db->fetchAssoc($q))
                {?>
                <option value="<?echo $r['aclident']?>" <?if($_POST['dacl']==$r['aclident']){echo "selected";}?>><?echo $r['aclident']?></option>
                <?}?>
              </select>
                 </label></td>
                 <td width="14%" rowspan="2" align="center" bgcolor="#FFFFFF"><label>
                   <input type="submit" name="Submit" value="�ύ" />
                 </label></td>
               </tr>
               <tr>
                 <td align="right" bgcolor="#F0FBFD">������</td>
                 <td height="22" align="left" bgcolor="#FFFFFF"><label>
                   <input name="dname" type="text" size="15" />
                 </label></td>
                 <td align="right" bgcolor="#F0FBFD">��¼ֵ��</td>
                 <td align="left" bgcolor="#FFFFFF"><input type="text" name="dvalue" /></td>
                 <td align="right" bgcolor="#F0FBFD">���ʱ�䣺</td>
                 <td align="left" bgcolor="#FFFFFF"><select name="yztime" id="yztime">
                   <option value="5">5����</option>
                   <option value="10">10����</option>
                   <option value="30">30����</option>
                   <option value="60">60����</option>
                 </select></td>
               </tr> </form>
           </table>         </td>
          </tr>
         <tr>
           <td colspan="2" align="right" bgcolor="#FFFFFF" class="graytext">&nbsp;</td>
         </tr>
         <tr>
          <td colspan="2" align="center" bgcolor="#e7f4ff" class="graytext"><table width="100%" border="00" cellpadding="0" cellspacing="1" bgcolor="#006699">
            <tr>
              <td width="7%" height="22" align="center" bgcolor="#33CCCC" class="graybg">���</td>
              <td width="18%" align="center" bgcolor="#33CCCC" class="graybg">����</td>
              <td width="12%" align="center" bgcolor="#33CCCC" class="graybg">��¼����</td>
              <td width="11%" align="center" bgcolor="#33CCCC" class="graybg">����</td>
              <td width="23%" align="center" bgcolor="#33CCCC" class="graybg">��¼ֵ</td>
              <td width="7%" align="center" bgcolor="#33CCCC" class="graybg">��·</td>
              <td width="6%" align="center" bgcolor="#33CCCC" class="graybg">״̬</td>
              <td width="16%" align="center" bgcolor="#33CCCC" class="graybg">����</td>
            </tr><?
             $sql ="select * from drecord where isfz=1 order by dupdate desc"; 

$query = $db->query($sql);
while($row = $db->fetchAssoc($query))
{
	?>

            <tr>
              <td height="25" bgcolor="#FFFFFF"><?echo $row['did']?></td>
              <td bgcolor="#FFFFFF"><?echo $row['dmainname']?></td>
              <td bgcolor="#FFFFFF"><?echo $row['dtype']?></td>
              <td bgcolor="#FFFFFF"><?echo $row['dname']?></td>
              <td bgcolor="#FFFFFF"><?echo $row['dvalue']?></td>
              <td bgcolor="#FFFFFF"><?echo $row['dacl']?></td>
              <td bgcolor="#FFFFFF"><?if($row['dis']=='1'){echo '������';}else{echo 'ͣ����';}?></td>
              <td bgcolor="#FFFFFF"><?if($row['dis']=='1'){?><a href="?domainid=<?echo $row['ddomain']?>&dname2=<?echo $row['dname']?>&domainname=<?echo $row['dmainname']?>&id=<?echo $row['did']?>&ac=stop<?echo $keyword?>" onclick="javascript:return   confirm('���Ҫֹͣ��������¼��');">ͣ��</a><?}else{?><a href="?domainid=<?echo $row['ddomain']?>&dname2=<?echo $row['dname']?>&domainname=<?echo $row['dmainname']?>&id=<?echo $row['did']?>&ac=start<?echo $keyword?>" onclick="javascript:return   confirm('���Ҫ���ý�������¼��');">����</a><?}?>                | <a href="?ac=del&id=<?echo $row['did']?>&dname2=<?echo $row['dname']?>&ddomain=<?echo $row['dmainname']?>&domainid=<?echo $row['ddomain']?><?echo $keyword?>" onclick="javascript:return   confirm('���Ҫɾ������¼��');">ɾ��</a></td>
            </tr>
            <?}?>
          </table>            
           <label>           </label></td>
          </tr>
        

        
        <tr>
          <td height="25" colspan="2" align="center" bgcolor="#FFFFFF" class="graybg"><label>���úú��뵽 <a href="../dns/domain.php">�������� </a>����Ӧ�� </label></td>
        </tr>
      </table>
        
    </td>
  </tr>
</table>
<? include "../copyright.php";?>
</body>
</html>
