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
<title>������־</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>

<script src="../js/checkdays.js"></script>
<script src="../js/setdate.js"></script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ������־ </div>
<div class="content">
<table width="780" class="s s_grid">
      <tr>
        <td colspan="5" class="caption">������־</td>
      </tr>
      <tr>
        <td colspan="5">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
            <form id="keyword" name="keyword" method="post" action="oplog.php"><tr>
              <td  class="bluebg" width="64" height="25" align="right">��ʼʱ��:</td>
              <td  class="bluebg" width="106"><label>
                <input name="start" type="text" id="start" size="12" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly" value="<?echo $_REQUEST['start']?>" alt="˫����������ѡ��" title="˫����������ѡ��" />
              </label></td>
              <td  class="bluebg" width="64" align="right">����ʱ��:</td>
              <td  class="bluebg" width="104"><input name="end" type="text" id="end" size="12" onclick="fPopCalendar(event,this,this)" onfocus="this.select()" readonly="readonly" value="<?echo  $_REQUEST['end']?>" title="˫����������ѡ��" /></td>
              <td  class="bluebg" width="58" align="right">�û���:</td>
              <td  class="bluebg" width="84"><input name="username" type="text" id="username" size="10" value="<?echo $_REQUEST['username']?>" /></td>
              <td  class="bluebg" width="50" align="right">��Ϊ:</td>
              <td  class="bluebg" width="88" align="left"><label>
                <select name="op">
                  <option value="" selected>����</option>
                  <?
                  $query=$db->query('select distinct dotype from dorecord');
                  while($row=$db->fetchAssoc($query))
                  {?>
                   <option value="<?echo $row['dotype']?>" <? if($row['dotype']==$_REQUEST['op']){echo "selected";}?>><?echo $row['dotype']?></option>
                   <?}?>
                </select>
              </label></td>
              <td  class="bluebg" width="129">
                <input type="submit" name="Submit" value="��ѯ" />
             <?
        $starnum = ($page-1)*$pagesizenum;
        $keyword="";
        $sqld=" 1<2 ";
        if($_REQUEST['start']!='')
        {
        	$sqld=$sqld." and date(addtime)>='".$_REQUEST['start']."'";
        	$keyword=$keyword."&start=".$_REQUEST['start'];
        }
         if($_REQUEST['end']!='')
        {
        	$sqld=$sqld." and date(addtime)<='".$_REQUEST['end']."'";
        	$keyword=$keyword."&end=".$_REQUEST['end'];
        }
         if($_REQUEST['username']!='')
        {
        	$sqld=$sqld." and username='".$_REQUEST['username']."'";
        	$keyword=$keyword."&username=".$_REQUEST['username'];
        }
        if($_REQUEST['op']!='')
        {
        	$sqld=$sqld." and dotype='".$_REQUEST['op']."'";
        	$keyword=$keyword."&op=".$_REQUEST['op'];
        }
        $sql = "select count(*) as mycount from dorecord where".$sqld;
        $query=$db->query($sql);
        $allnum=0;
        while($row=$db->fetchAssoc($query))
        {
        	$allnum=$row['mycount'];
        }
        $totalpage=ceil($allnum/$pagesizenum);
        $sql1 ="select * from dorecord where".$sqld." order by addtime desc"; 
	$sql=$sql1." limit {$starnum},{$pagesizenum}";
$query = $db->query($sql);
$i=0;    
		$a=0;
		?>
	
		 <a href="downop.php?sql=<? echo $sql1;?>">			
			�������</a></td>
            </tr></form>
          </table>
		   </td>
        </tr>
      <tr>
        <th width="10%"><strong>���</strong></th>
        <th width="25%"><strong>ʱ��</strong></th>
        <th width="15%"><strong>�û�</strong></th>
        <th width="20%"><strong>��Ϊ</strong></th>
        <th width="30%"><strong>��ϸ��¼</strong></th>
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
        <td><? echo $row['id']?></td>
        <td><? echo $row['addtime']?></td>
        <td><? echo $row['username']?></td>
        <td><? echo $row['dotype']?></td>  
        <td><? echo $row['param']?> </td>
      </tr><?
		}		
		$db->close();		
		?>
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
