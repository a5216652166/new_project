<?php
/*
 +-----------------------------------------------------
 * 	2010-2-4
 +-----------------------------------------------------
 *		
 +-----------------------------------------------------
 */

include '../include/comm.php';
checklogin();
checkac();
if (isset($_GET)){
	$ac = $_GET['ac'];
	$id = $_GET['id'];
	if ($ac == "yes"){
		$sql = "select * from regroom where id=$id";
		$row = $db->fetchAssoc($db->query($sql));
		
		//��Ӽ�¼
		$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(";
		$sql .= $row['doid'].",'".$row['domain2']."',0,'".$row['rtype']."','".$row['ip']."','ANY',1,0,datetime('now','localtime'))";
		$db->query($sql);		
		$rr=$db->query("select * from aclips where regroom=".$row['id']);
		$n=1;
		while($res = $db->fetchAssoc($rr))
		{
			
			$db->query("insert into drecord(ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate)values(".$row['doid'].",'".$row['domain2']."',0,'".$row['rtype']."','".$res['ip']."','".$res['aclname']."',1,0,datetime('now','localtime'))");
			$db->query("update aclips set status=1 where regroom=".$row['id']);
			$n++;
		}
		$sql="update domain set domainnum=domainnum+".$n." where domainid=".$row['doid'];
		$db->query($sql);
		//���ɷ������
		$doid= $row['doid'];
		require 'ptr.php';
		$sql = "update regroom set state=1 where id =".$_GET['id'];
		$db->query($sql);
		showmessage('����ͨ��', 'app.php');
	}
	else if ($ac == "no"){
		$sql = "update regroom set state=2 where id=".$_GET['id'];
		$db->query($sql);
		$db->query("update aclips set status=2 where regroom=".$_GET['id']);
		showmessage('���벻ͨ��', 'app.php');
	}
	else {
		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��·����</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; �������</div>

     <div class="content"> <table width="768" class="s s_grid">
      	<tr>
          <td colspan="8" class="caption">�������</td>
        </tr>
            <tr>
              <th>ID</th>
              <th>��������</th>
              <th>����</th>
              <th>IP</th>
              <th>���뵥λ</th>
              <th>������</th>
              <th>����ʱ��</th>
              <th>����</th>
            </tr>
            <?
            /*
             * state 0:δ����
             * 		 1��ͨ��
             * 		 2��δͨ��
             */
		$query=$db->query("select * from regroom where state=0");
		while($row = $db->fetchAssoc($query))
		{
			$bg="#ffffff";
			$sq = "select domainname from domain where domainid=".$row['doid'];
			$r = $db->fetchAssoc($db->query($sq));
		?>
            <tr>
              <td><?echo $row['id']?></td>
              <td><?echo $row['domain2']?></td>
              <td><?echo $r['domainname']?></td>
              <td>
			   <?
				echo "ͨ�ã�".$row['ip']."<br>";
				$que=$db->query("select * from aclips where status=0 and regroom=".$row['id']);
				while($r = $db->fetchAssoc($que))
				{
					echo $r['aclname'].":".$r['ip']."<br>";
				}
				?>
			  </td>
              <td><?echo $row['com']?></td>
              <td><?echo $row['ownner']?></td>
              <td><?echo $row['time']?></td>
              <td><a href="mode.php?id=<?echo $row['id']?>">�޸�</a> | <a href="?ac=yes&id=<?echo $row['id']?>">ͨ��</a> | <a href="?ac=no&id=<?echo $row['id']?>">�ܾ�</a> | <a href="info.php?id=<?echo $row['id']?>" >��ϸ��Ϣ</a> </td>
            </tr>
        <?}
      	?>
      </table></div><div class="push"></div></div>

<? include "../copyright.php";?>
</body>
</html>