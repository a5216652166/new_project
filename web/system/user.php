<?
include ('../include/comm.php');
checklogin();
checkac();

if(isset($_GET['action'])&&$_GET['action']=='del'){
	checkac('ɾ��');
	$query=$db->query("select * from  user");
	$num=$db->num_rows($query);
	if($num<=1)
	{
		showmessage('����ɾ�����һ������Ա','user.php');
	}else {
	$query=$db->query("delete from  user where user_id=".$_GET['id']);
	//oplog('ɾ������Ա','ɾ��'.$_GET['username'].'����Ա');
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�û�����</title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
</head>
<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �û�����  </div>
<ul class="tab-menu">
    <li class="on"><span>�û�����</span> </li>
    <li><a href="useradd.php">����û�</a></li> 
</ul>

      <div class="content"><table width="813" class="s s_grid">
        <tr >
          <td colspan="8" class="caption">�û�����</td>
        </tr>
        <tr>
          <th>�û���</th>
          <th>
            ����
          </th>
          <th>�û���ɫ</th>
          <th>����</th>
          <th>���ʱ��</th>
          <th>״̬</th>
          <th>����</th>
          <th>����</th>
        </tr>
        <?
		$query=$db->query("select * from user ");
		while($row = $db->fetch_array($query))
		{
			$rolesql="select name from role where role_id=(select role_id from user where user_id=$row[user_id]);";
			$rolers=$db->query($rolesql);
			$roleName=$db->fetch_array($rolers);
		?>
        <tr bgcolor="#ffffff" onMouseOver="javascript:this.bgColor='#fdffc5';" onMouseOut="javascript:this.bgColor='#ffffff';">
          <td><? echo $row['username'];?></td>
          <td><label><? echo $row['userrealname'];?></label></td>
          <td><?=$roleName['name'] ?></td>
          <td><? echo $row['usermail'];?></td>
          <td><? echo $row['userupdate'];?></td>
          <td><? echo mystate($row['userstate']);?></td>
          <td><? echo $row['userdepart'];?></td>
          <td>
        <?
        if(getPri('�޸�'))
        {
        	?>    
          <a href="useredit.php?id=<? echo $row['user_id'];?>">�༭</a> 

			<?
		}
		?>

        <?
        if(getPri('ɾ��'))
        {
        	?>    
          <a href="user.php?id=<? echo $row['user_id'];?>&username=<?echo $row['username'];?>&action=del" onclick="javascript:return   confirm('���Ҫɾ����');">ɾ��</a>
          
			<?
		}
		?>
          </td>
        </tr>
		<?
		}
		//$db->free_result($query);
		$db->close();
		?>
      </table></div>
<div class="push"></div>
</div>
<? include "../copyright.php";?>
</body>
</html>
