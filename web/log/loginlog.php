<?
include ('../include/comm.php');
$pageaccess=3;
checklogin();
checkac();
$page = checkPage();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��½��־</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script src="../js/checkdays.js"></script>
<script src="../js/setdate.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��½��־ </div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
  <tr>
    <td><table width="768" class="s s_grid">
      <tr>
        <td colspan="5" class="caption">��½��־</td>
      </tr>
      <tr>
        <td colspan="5"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <form id="keyword" name="keyword" method="get" action="loginlog.php">
            <tr>
              <td class="bluebg" width="64" align="right">��ʼʱ��:</td>
              <td class="bluebg" width="106"><label>
                <input name="start" type="text" id="start" size="12" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly" value="<?echo $_GET['start']?>" title="˫����������ѡ��" />
              </label></td>
              <td class="bluebg" width="66" align="right">����ʱ��:</td>
              <td class="bluebg" width="102"><input name="end" type="text" id="end" size="12" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly" value="<?echo  $_GET['end']?>" title="˫����������ѡ��" /></td>
              <td class="bluebg" width="58" align="right">�û���:</td>
              <td class="bluebg" width="79"><input name="username" type="text" id="username" size="10" value="<?echo $_GET['username']?>" /></td>
              <td class="bluebg" width="55" align="right">״̬:</td>
              <td class="bluebg" width="98" align="left"><label>
                <select name="op">
                  <option value="" selected="selected">����</option>
                <option value="�ɹ���½" <? if($_GET['op']=='�ɹ���½'){echo "selected";}?>>�ɹ���½</option>
				<option value="��½ʧ��" <? if($_GET['op']=='��½ʧ��'){echo "selected";}?>>��½ʧ��</option>
                </select>
              </label></td>
              <td class="bluebg" width="119">
                <input type="submit" name="Submit" value="��ѯ" />
				<?
        $starnum = ($page-1)*$pagesizenum;
        $keyword="";
        $sqld=" 1<2 ";
        if($_GET['start']!='')
        {
        	$sqld=$sqld." and date(addtime)>='".$_GET['start']."'";
        	$keyword=$keyword."&start=".$_GET['start'];
        }
         if($_GET['end']!='')
        {
        	$sqld=$sqld." and date(addtime)<='".$_GET['end']."'";
        	$keyword=$keyword."&end=".$_GET['end'];
        }
         if($_GET['username']!='')
        {
        	$sqld=$sqld." and username='".$_GET['username']."'";
        	$keyword=$keyword."&username=".$_GET['username'];
        }
        if($_GET['op']!='')
        {
        	$sqld=$sqld." and userstate='".$_GET['op']."'";
        	$keyword=$keyword."&op=".$_GET['op'];
        }
        $sql = "select count(*) as mycount from userlog where".$sqld;
        $query=$db->query($sql);
        $allnum=0;
        while($row=$db->fetchAssoc($query))
        {
        	$allnum=$row['mycount'];
        }
        $totalpage=ceil($allnum/$pagesizenum);
        $sql1 ="select * from userlog where ".$sqld." order by addtime desc"; 
	$sql=$sql1." limit {$starnum},{$pagesizenum}";
	//echo $sql;
$query = $db->query($sql);
$i=0;    
$a=0;
?>
			
				<a href="downlogin.php?sql=<? echo $sql1;?>">�������</a></td>
            </tr>
          </form>
        </table></td>
        </tr>
      <tr align="center">
        <th width="10%"><strong>���</strong></th>
        <th width="25%"><strong>ʱ��</strong></th>
        <th width="15%"><strong>�û�</strong></th>
        <th width="30%"><strong>��½IP</strong></th>
        <th width="20%"><strong>״̬</strong></th>
        </tr>	
		<?	
while($row = $db->fetchAssoc($query))
{
	$i=$i+1;
	if($a==0){
		$ac="#e7f4ff";
		$a=1;
	}else 
	{$ac="#FFFFFF";
	$a=0;}


?>
      <tr>
        <td height="25" align="center" bgcolor="<?echo $ac?>" class="<? if($row['userstate']=='��½ʧ��'){echo "error";}else {echo "graytext";}?>"><? echo $row['logid']?> </td>
        <td height="25" align="center" bgcolor="<?echo $ac?>" class="<? if($row['userstate']=='��½ʧ��'){echo "error";}else {echo "graytext";}?>"><label><? echo $row['addtime']?> </label></td>
        <td align="center" bgcolor="<?echo $ac?>" class="<? if($row['userstate']=='��½ʧ��'){echo "error";}else {echo "graytext";}?>"><? echo $row['username']?> </td>
        <td align="center" bgcolor="<?echo $ac?>" class="<? if($row['userstate']=='��½ʧ��'){echo "error";}else {echo "graytext";}?>"><?=str_replace("::ffff:","",$row['userip'])?> </td>
        <td align="center" bgcolor="<?echo $ac?>" class="<? if($row['userstate']=='��½ʧ��'){echo "error";}else {echo "graytext";}?>"><? echo $row['userstate']?> </td>
        </tr><?
		}
		
		$db->close();
		
		?>
    </table></td>
  </tr>
  </table>
  <div align="center">
  <br>
    �� <?echo $allnum?> ����¼����ǰ��<?echo $page?>/<?echo $totalpage?> ҳ ��ʾ�� <? echo $starnum+1?>-<?echo $starnum+$i?> ����¼  
  <br> 
    <a href="?page=1<?echo $keyword?>">��ҳ</a> <?if($page>1){?><a href="?page=<?echo $page-1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <?if($page<$totalpage){?><a href="?page=<?echo $page+1?><?echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <a href="?page=<?echo $totalpage?><?echo $keyword?>">ĩҳ</a> ������
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
