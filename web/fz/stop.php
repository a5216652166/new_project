<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
$page = checkPage();
if($_GET['ac']=='del')
	{
		checkac('ɾ��');
		$sql="delete from yztest where id=".$_GET['id'];
		$db->query($sql);
	
	}
	if($_GET['ac']=='all')
	{
		checkac('ɾ��');
		$sql="delete from yztest ";
		$db->query($sql);
	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ͣ���������</title>
<link href="../divstyle.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="28" align="left" background="../images/bg_topbg.gif">&nbsp;��ǰλ��:&gt;&gt; ͣ���������  <a href="?ac=all">���ȫ��</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="797" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="251" align="center" bgcolor="#99FF00" class="graybg">���ʱ��</td>
        <td width="251" height="30" align="center" bgcolor="#99FF00" class="graybg">�������</td>
        <td width="201" height="30" align="center" bgcolor="#99FF00" class="graybg">����IP</td>
        <td width="179" height="30" align="center" bgcolor="#99FF00" class="graybg">���״̬</td>
        <td width="64" align="center" bgcolor="#99FF00" class="graybg">����</td>
        </tr>
      <?
		 $starnum = ($page-1)*$pagesizenum;
	  $sql = "select count(*) as a1 from yztest";
        
        $query=$db->query($sql);
        $allnum=0;
        while($row=$db->fetchAssoc($query))
        {
        	$allnum=$row['a1'];
        }
        $totalpage=ceil($allnum/$pagesizenum);
        $sql ="select * from yztest order by id desc"; 
	$sql=$sql." limit {$starnum},{$pagesizenum}";
$query = $db->query($sql);
while($row = $db->fetchAssoc($query))
{
	$bg="#ffffff";
	if($row['yztestis']=="0")
	{
		$bg="#fcdfdf";
	}
?>
      <tr>
        <td align="center" bgcolor="<?echo $bg?>" class="graytext"><?echo $row['yztesttime']?></td>
        <td height="25" align="center" bgcolor="<?echo $bg?>" class="graytext"><?echo $row['yzhost']?></td>
        <td height="25" align="center" bgcolor="<?echo $bg?>" class="graytext"><?echo $row['yzhostip']?></td>
        <td height="25" align="center" bgcolor="<?echo $bg?>" class="graytext"><?if($row['yztestis']==1){echo '����״̬��';}else{echo '��ͨ״̬';}?></td>
        <td align="center" bgcolor="<?echo $bg?>" class="graytext"><a href="?id=<?echo $row['id']?>&ac=del" onclick="javascript:return   confirm('���Ҫɾ���˼����ʷ��');">ɾ��</a></td>
        </tr>
      <?}
      $db->close();?>
    </table></td>
  </tr>
  
    <tr>
          <td height="25" colspan="2" align="center" bgcolor="#FFFFFF" class="graybg">�� <?echo $allnum?> ����¼����ǰ��<?echo $page?>/<?echo $totalpage?> ҳ ��ʾ�� <? echo $starnum+1?>-<?echo $starnum+$i?> ����¼</td>
        </tr>
        <tr>
          <td height="25" colspan="2" align="center" bgcolor="#FFFFFF" class="graybg"><a href="?page=1<?echo $keyword?>">��ҳ</a> <?if($page>1){?><a href="?page=<?echo $page-1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <?if($page<$totalpage){?><a href="?page=<?echo $page+1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <a href="?page=<?echo $totalpage?><?echo $keyword?>">ĩҳ</a> ������
      <select onchange="window.location='?page='+this.value+'<?echo $keyword?>'" size="1" name="topage">
        <? for( $i=1;$i<=$totalpage;$i++) 
        {?>
        <option value="<?echo $i?>" <?if($i==$page){echo "selected=selected";}?>><?echo $i?></option>
       <?}?>
      </select>
ҳ���� <?echo $totalpage?>ҳ</td>
        </tr>
</table>
<? include "../copyright.php";?>
</body>
</html>
