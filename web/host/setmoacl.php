<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
    checkac('���');
	$sql="select * from mhostacl where maclhostid=".$_POST['maclhostid'];
	$query=$db->query($sql);
	$num=$db->num_rows($query);
	if($num>0)
	{
		$db->close();
		showmessage('��������ز����Ѿ�����,������ӣ�','setmoacl.php');
	}
$sql="insert into mhostacl (maclhostid,maclis,maclns,maclnd,maclzh)values(".$_POST['maclhostid'].",'".$_POST['maclis']."',";
$sql=$sql."'".$_POST['maclns']."','".$_POST['maclnd']."',0)";
	$db->query($sql);
	
	writelog($db,'������ز��Թ���',"���������ز���");
		$db->close();
		showmessage('����������ز��Գɹ�','setmoacl.php');
		

}
if(isset($_GET['ac']))
{
	if($_GET['ac']=='del')
	{
	    checkac('ɾ��');
		//ɾ��
		$db->query("delete from mhostacl where maclid=".$_GET['id']);
		writelog($db,'�����������',"ɾ����ز���");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������ز�������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<style>
.title{background:#e7f4ff; width:20%; text-align:right;}
</style>
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>

<script language="javascript">

function checklogin(){
	

	if(document.macl.maclhostid.value == ''){	
			alert("��ѡ��������");
			
			return false;
		}
	if(document.macl.maclns.value ==document.macl.maclnd.value){	
			alert("��ͨ״̬��·ת��������ͬ");
			
			return false;
		}
	
	return true;
}

</script>
</head>

<body><div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��ز�������</div>
<div class="content">
       <form id="macl" name="macl" method="post" action="setmoacl.php" onsubmit="return checklogin();"> 
      <table width="776" class="s s_form">
         <tr>
          <td colspan="4" class="caption">��ز�������</td>
        </tr>
         <tr>
           <td>���������</td>
           <td   align="left" ><label>
             <select name="maclhostid" id="maclhostid">
             <?$query=$db->query("select * from mhost");
             while($row=$db->fetchAssoc($query)){?>
             <option value="<?echo $row['mid']?>"><?echo $row['mname']."(".$row['mip'].")"?></option>
             <?}?>
             </select>
           </label></td>
           <td  class="title">�Ƿ�����</td>
           <td >
             <select name="maclis" id="maclis">
               <option value="1">����</option>
               <option value="0">�ر�</option>
             </select>
           </td>
         </tr>
         <tr>
          <td><span >��ͨ״̬Դ��·��</span></td>
          <td > 
           <?$query=$db->query("select * from setacl");
           $i=0;
             while($row=$db->fetchAssoc($query)){
             	$acl[$i][0]=$row['aclname'];
             	$acl[$i][1]=$row['aclident'];
             $i++;
             }?>
          <select name="maclns" id="maclns">
             <?for($t=0;$t<$i;$t++){?>
             <option value="<?echo $acl[$t][1]?>"><?echo $acl[$t][0]."(".$acl[$t][1].")"?></option>
             <?}?>
             </select></td>
          <td class="title">ת������·��</td>
          <td width="265" align="left" > <select name="maclnd" id="maclnd">
             <?for($t=0;$t<$i;$t++){?>
             <option value="<?echo $acl[$t][1]?>"><?echo $acl[$t][0]."(".$acl[$t][1].")"?></option>
             <?}?>
             </select></td>
         </tr>
        
        
        
        <tr>
          <td  colspan="4"   class="footer"><label>
            <input type="submit" name="Submit" value="��������" />
          </label></td>
        </tr>      </table></form>
 <table width="776" class="s s_grid">
  <tr>
    <td class="caption" colspan="4">��ز����б�</td>
  </tr>    <tr>
        <th width="279" >�������</th>
        <th width="287" >��ͨ״̬</th>
        <th width="65" >�Ƿ���</th>
        <th width="124">����</th>
      </tr>
      <?
		$query=$db->query("select * from mhostacl,mhost where mhost.mid=mhostacl.maclhostid order by mhostacl.maclid desc");
while($row = $db->fetchAssoc($query))
{
?>
      <tr>
        <td    ><?echo $row['mname']."(".$row['mip'].")"?></td>
        <td    ><?echo $row['maclns']."��·�л���->".$row['maclnd']."��·"?></td>
        <td    ><?if($row['maclis']=="1"){echo "������";}else{echo "ͣ����";}?></td>
        <td    ><a href="setmoacl_mode.php?id=<?echo $row['maclid']?>">�޸�</a> <a href="setmoacl.php?id=<?echo $row['maclid']?>&ac=del" onclick="javascript:return   confirm('���Ҫɾ���˼�ز�����');">ɾ��</a></td>
      </tr>
      <?}
      $db->close();?>
    </table>
 </div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
