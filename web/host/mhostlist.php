<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
$page = checkPage();
if($_GET['ac']=='del')
	{
		
		$sql="delete from mhostapp where mappid=".$_GET['id'];
		$db->query($sql);
	
	}
	if($_GET['ac']=='all')
	{
		
		$sql="delete from mhostapp ";
		$db->query($sql);
	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��ؼ������</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"></script>
<script src="../js/ximo_dns.js"></script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��ؼ�� </div>
<div class="content"><table width="80%" class="s s_grid">
  <tr>
    <td  class="caption" colspan="4">��ؼ�� <a href="?ac=all" onclick="javascript:return confirm('���Ҫ���ȫ����¼��');">���ȫ��</a></td>
  </tr>
      <tr>
        <th>���ʱ��</th>
        <th>�����������IP/URL</th>
        <th>���״̬</th>
        <th>����</th>
        </tr>
      <?
		 $starnum = ($page-1)*$pagesizenum;
	  $sql = "select count(*) as a1 from mhostapp";
        
        $query=$db->query($sql);
        $allnum=0;
        while($row=$db->fetchAssoc($query))
        {
        	$allnum=$row['a1'];
        }
        $totalpage=ceil($allnum/$pagesizenum);
        $sql ="select * from mhostapp order by mappid desc"; 
	$sql=$sql." limit {$starnum},{$pagesizenum}";
$query = $db->query($sql);
while($row = $db->fetchAssoc($query))
{
	$bg="#ffffff";
	if($row['mappresult']=="0")
	{
		$bg="#fcdfdf";
	}
?>
      <tr class="<?=$row['mappresult']=="0"?"bg_red":""?>">
        <td bgcolor="<?echo $bg?>"><?echo $row['mapptime']?></td>
        <td  bgcolor="<?echo $bg?>"><?echo $row['mapphost']?></td>
        <td bgcolor="<?echo $bg?>"><?if($row['mappresult']==1){echo '����״̬��';}else{echo '��ͨ״̬';}?></td>
        <td bgcolor="<?echo $bg?>" ><a href="?id=<?echo $row['mappid']?>&ac=del" onclick="javascript:return   confirm('���Ҫɾ���˼����ʷ��');">ɾ��</a></td>
        </tr>
      <?}
      $db->close();?>
    </table>
  <div align="center"> 
  <br>
         �� <?echo $allnum?> ����¼����ǰ��<?echo $page?>/<?echo $totalpage?> ҳ ��ʾ�� <? echo $starnum+1?>-<?echo $starnum+$i?> ����¼
		 <br><a href="?page=1<?echo $keyword?>">��ҳ</a> <?if($page>1){?><a href="?page=<?echo $page-1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <?if($page<$totalpage){?><a href="?page=<?echo $page+1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <a href="?page=<?echo $totalpage?><?echo $keyword?>">ĩҳ</a> ������
      <select onchange="window.location='?page='+this.value+'<?echo $keyword?>'" size="1" name="topage">
        <? for( $i=1;$i<=$totalpage;$i++) 
        {?>
        <option value="<?echo $i?>" <?if($i==$page){echo "selected=selected";}?>><?echo $i?></option>
       <?}?>
      </select>
ҳ���� <?echo $totalpage?>ҳ
</div>
</div>
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
