<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
$page = checkPage();
if($_GET['ac']=='del')
	{
		
		$sql="delete from mhostch where id=".$_GET['id'];
		$db->query($sql);
	
	}elseif(isset($_POST["id"])){
	$ids=implode(",",$_POST["id"]);
	$sql="delete from mhostch where id in (".$ids.")";
	$db->query($sql);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ת����¼</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<style>
.s td.foot{text-align:left;}
</style>
<script src="../js/jquery.js"></script>
<script src="../js/ximo_dns.js"></script>
<script>
function s_all(){ 
	var checkboxs = document.getElementsByName('id[]');
	for(var i=0;i<checkboxs.length;i++){
		checkboxs[i].checked = true;
	}
}
function c_all(){
	var checkboxs = document.getElementsByName('id[]');
	for(var i=0;i<checkboxs.length;i++){
		checkboxs[i].checked = false;
	}
}
function del(cs){
	fm = document.getElementById('delform');
	fm.action="mhostzh.php?"+cs;
	fm.submit();
}
</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ת����¼ </div>
<div class="content">
<form method="post" id="delform" name="delform" onsubmit="javascript:return confirm('���Ҫɾ����ת����ʷ��');">
<table width="80%" class="s s_grid">  
	<tr>
    <td  class="caption" colspan="5">ת����¼</td>
  </tr>
      <tr>
	    <th></th>
        <th>����ʱ��</th>
        <th>Դ��·</th>
        <th>ת������·</th>
        <th>����</th>
      </tr>
      <?
		
 $starnum = ($page-1)*$pagesizenum;
	  $sql = "select count(*) as a1 from mhostch";
        
        $query=$db->query($sql);
        $allnum=0;
        while($row=$db->fetchAssoc($query))
        {
        	$allnum=$row['a1'];
        }
        $totalpage=ceil($allnum/$pagesizenum);
        $sql ="select * from mhostch order by id desc"; 
	$sql=$sql." limit {$starnum},{$pagesizenum}";
$query = $db->query($sql);
while($row = $db->fetchAssoc($query))
{
?>
      <tr>
        <td><input type="checkbox" value="<?echo $row['id']?>" name="id[]"></td>
		<td><?echo $row['chtime']?></td>
        <td><?echo $row['chs']?></td>
        <td><?echo $row['chd'];?></td>
        <td><a href="?id=<?echo $row['id']?>&ac=del" onclick="javascript:return   confirm('���Ҫɾ����ת����ʷ��');">ɾ��</a></td>
      </tr>
      <?}
      $db->close();?>
    <tr><td class="foot" colspan="5"><img src="../images/jiantou.png"><a href="javascript:s_all();">ȫѡ</a> / <a href="javascript:c_all();">ȫ��ѡ</a> <a href="javascript:del('ac=pdel');">ɾ��ѡ����</a></td>
	</tr>
   </table>
   </form>
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
<? include "../copyright.php";?></body>
</html>
