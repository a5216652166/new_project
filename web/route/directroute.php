<?
include ('../include/comm.php');
checklogin();
checkac();
if(isset($_REQUEST['action'])&&$_REQUEST['action']=='del')
{
	checkac('ɾ��');
	$db->query("delete from directroute where directid=$_REQUEST[directid];");
	$db->query("update isapp set staticroute=1");
	$db->query("update directroute set isapp=1");
}
if(isset($_REQUEST['action'])&&$_REQUEST['action']=='setstatic')
{
	checkac('Ӧ��');
	$row_q=$db->query("select * from directroute where state=1  order by directid");
	while($row_static=$db->fetch_array($row_q))
	{
		if($row_static['type']==1)
		{
			$str_metric=empty($row_static['metric'])?'':"metric $row_static[metric]";
			$sh_content.="ip route add $row_static[ipr] via $row_static[nexthop] $str_metric\n";
			$sh_content_del.="ip route del $row_static[ipr] via $row_static[nexthop] $str_metric\n";
		}
		else 
		{
			$obj_q=$db->query("select aclident from setacl where aclid=$row_static[obj]");
			if(($row_obj=$db->fetch_array($obj_q)) && !empty($row_obj['aclident']))
			{

				$file_con=file($binddir."acl/".$row_obj['aclident']);
				for($i=1;$i<count($file_con)-1;$i++)
				{
					$obj_ipr=trim($file_con[$i],"; \t\n\r\0\x0B");//ɾ���ļ��еĿհ׷��ͷָ���
				    if($obj_ipr != "")  
				    {   
						$str_metric=empty($row_static['metric'])?'':"metric $row_static[metric]";
						$sh_content.="ip route add $obj_ipr via $row_static[nexthop] $str_metric\n";
						$sh_content_del.="ip route del $obj_ipr via $row_static[nexthop] $str_metric\n";
				    }   
				}
/*				$obj_iprs=explode("\r\n",$row_obj['ipaddr']);				
				foreach($obj_iprs as $obj_ipr)
				{
					if(trim($obj_ipr)!='')
					{
						$str_metric=empty($row_static['metric'])?'':"metric $row_static[metric]";
						$sh_content.="ip route add $obj_ipr via $row_static[nexthop] $str_metric\n";
						$sh_content_del.="ip route del $obj_ipr via $row_static[nexthop] $str_metric\n";
					}				
				}*/
			}

		}
	}
	exec($fstatic_route_del);
	writeShell($fstatic_route, $sh_content);
	exec($fstatic_route);
	writeShell($fstatic_route_del, $sh_content_del);
	$db->query("update isapp set staticroute=0");
	$db->query("update directroute set isapp=0");	
	showmessage("Ӧ�óɹ�", "directroute.php");	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><? echo $system['xmtactype'];?></title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<style type="text/css" >
table.thin
{
	margin:10px auto;
	border-collapse:collapse;	
	border:1px solid #82C4E8;
}
table.thin td
{
	border:1px solid #82C4E8;
}
</style>
</head>

<?
//�ж��Ƿ���д���ļ�
$sql="select staticroute from isapp";
$query=$db->query($sql);
$row=$db->fetch_array($query);
$count=$row['staticroute'];
?>
<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��̬·�� </div>
<ul class="tab-menu">

    <li class="on"><span> ��̬·��</span></li>
	<li> <a href="directrouteadd.php">��Ӿ�̬·��</a> </li> 
	<?php if($count==1){?><li><a href="directroute.php?action=setstatic"> Ӧ��</a> </li><?php }?>
</ul>
   <div class="content"> <table width="90%" class="s s_grid" >
      <tr >
        <td colspan="7" class="caption">��̬·��</td>
      </tr>
      <tr>
        <th>���</th>

        <th>IP��</th>   
        <th>
          ISP��·
       </th>
        <th>��һ��</th>
        <th>·�����ȼ�</th>
        <th>����״̬</th>
        <th>����</th>
      </tr>
      <?
		$query=$db->query("select * from directroute order by directid ;");
		while($row = $db->fetch_array($query))
		{
			$bg="";
			if($row['isapp']==1)
			{
				$bg="bg_red";
			}
			if($row['state']==0)
			{
				$bg="off";
			}
		?>
      <tr class="<?echo $bg?>" onmouseover="javascript:this.bgColor='#fdffc5';" onmouseout="javascript:this.bgColor='<?echo $bg?>';">
      <td><?echo $row['directid']?></td>
        <?
        $rtrow=$db->fetch_array($db->query("select * from setacl where aclid=$row[obj];"));
        ?>      
	    <td><?echo $row['ipr']?></td>	      
        <td><?=$rtrow['aclident']?></td>
        <td><?echo $row['nexthop']?></td>
        <td><?php echo $row['metric']?></td>
        <td><?if($row['state']==0){echo "ͣ��״̬";}else{echo "����״̬";}?></td>
        <td><a href="directrouteadd.php?action=mod&directid=<?=$row['directid'];?>">�༭</a> <a href="?directid=<?=$row['directid'];?>&action=del" onclick="javascript:return   confirm('���Ҫɾ����');">ɾ��</a></td>
      </tr>
      <?
		}
		$db->free_result($query);
		$db->close();		
		?>
   </table>
</div>     
<div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
