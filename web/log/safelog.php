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
<title>��ȫ��־</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<style>
.s td{text-align:left;}
</style>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��ȫ��־ </div>
<div class="content">
<table width="768" class="s s_grid">
      <tr>
        <td class="caption">��ȫ��־</td>
      </tr>   
          <? exec("last",$ipconfig,$rc);
	if($rc==0)
	{
      $linenum=count($ipconfig)-1;
        $starnum =($page-1)*$pagesizenum;
      $pagem=$starnum+$pagesizenum;
      $ac="#FFFFFF";
      $a=0;
      $totalpage=ceil($linenum/$pagesizenum);
for($Tmpa=$starnum;$Tmpa<=$pagem;$Tmpa++){
	if($a==0){
		$ac="#e7f4ff";
		$a=1;
	}else 
	{$ac="#FFFFFF";
	$a=0;}
	if($ipconfig[$Tmpa]!=''){?>
        <tr>
        <td>
<?echo $ipconfig[$Tmpa];?>
</td></tr><?
	}
} 
	}
?>
</table>
<div align="center">
<br>
�� <?echo $linenum?> ����¼����ǰ��<?echo $page?>/<?echo $totalpage?> ҳ  ÿҳ<?echo $pagesizenum?>����¼
<br>
   <a href="?page=1">��ҳ</a> <?if($page>1){?><a href="?page=<?echo $page-1?>&keyword=<? echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <?if($page<$totalpage){?><a href="?page=<?echo $page+1?>&keyword=<? echo $keyword?>">��һҳ</a><?}else{?>��һҳ<?}?> <a href="?page=<?echo $totalpage?>&keyword=<? echo $keyword?>">ĩҳ</a> ������
      <select onchange="window.location='?page='+this.value" size="1" name="topage">
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