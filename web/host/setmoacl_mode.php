<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
//checkaccess($pageaccess,$_SESSION['role']);

checkac();
if(isset($_POST['Submit'])){
	$sql="select * from mhostacl where maclid<>".$_POST['mid']." and maclhostid=".$_POST['maclhostid'];
	$query=$db->query($sql);
	$num=$db->num_rows($query);
	if($num>0)
	{
		$db->close();
		showmessage('��������ز����Ѿ����ڣ������޸ģ�','setmoacl.php');
	}
$sql="update mhostacl set maclhostid=".$_POST['maclhostid'].",maclis='".$_POST['maclis']."',maclns='".$_POST['maclns']."',maclnd='".$_POST['maclnd']."',maclzh=0 where maclid=".$_POST['mid'];


	$db->query($sql);
	
	writelog($db,'������ز��Թ���',"�޸�������ز���");
		$db->close();
		showmessage('�޸�������ز��Գɹ�','setmoacl.php');		

}else 
{
	$sql="select * from mhostacl where maclid=".$_GET['id'];
	$query=$db->query($sql);
	$r=$db->fetchAssoc($query);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������ز�������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<style>
.title{background:#e7f4ff; width:25%; text-align:right;}
</style>
<script src="/js/jquery.js"  type="text/javascript"charset="utf-8" ></script>
<script src="/js/ximo_dns.js"  type="text/javascript" charset="utf-8"></script>


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

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt;��ز�������&gt;&gt;��ز����޸�</div>
<div class="content">
 <form id="macl" name="macl" method="post" action="setmoacl_mode.php" onsubmit="return checklogin();"> 
      <table width="768" class="s s_form">      
         <tr>
          <td colspan="4" class="caption">��ز����޸�</td>
        </tr>
         <tr>
           <td>���������</td>
           <td>
             <select name="maclhostid" id="maclhostid">
             <?$query=$db->query("select * from mhost");
             while($row=$db->fetchAssoc($query)){?>
             <option value="<?echo $row['mid']?>" <?if($r['maclhostid']==$row['mid']){echo "selected";}?>><?echo $row['mname']."(".$row['mip'].")"?></option>
             <?}?>
             </select>
             <input name="mid" type="hidden" id="mid" value="<?echo $_GET['id']?>" />
           </td>
           <td class="title">�Ƿ�����</td>
           <td>
             <select name="maclis" id="maclis">
               <option value="1" <?if($r['maclis']=="1"){echo "selected";}?>>����</option>
               <option value="0" <?if($r['maclis']=="0"){echo "selected";}?>>�ر�</option>
             </select>
          </td>
         </tr>
         <tr>
          <td><span class="graytext">��ͨ״̬Դ��·��</span></td>
          <td> 
           <?$query=$db->query("select * from setacl");
           $i=0;
             while($row=$db->fetchAssoc($query)){
             	$acl[$i][0]=$row['aclname'];
             	$acl[$i][1]=$row['aclident'];
             $i++;
             }?>
          <select name="maclns" id="maclns">
             <?for($t=0;$t<$i;$t++){?>
             <option value="<?echo $acl[$t][1]?>" <?if($r['maclns']==$acl[$t][1]){echo "selected";}?>><?echo $acl[$t][0]."(".$acl[$t][1].")"?></option>
             <?}?>
             </select></td>
          <td class="title">ת������·��</td>
          <td> <select name="maclnd" id="maclnd">
             <?for($t=0;$t<$i;$t++){?>
             <option value="<?echo $acl[$t][1]?>" <?if($r['maclnd']==$acl[$t][1]){echo "selected";}?>><?echo $acl[$t][0]."(".$acl[$t][1].")"?></option>
             <?}?>
             </select></td>
         </tr>
        
        
        
        <tr>
          <td colspan="4"class="footer">
            <input type="submit" name="Submit" value="��������" />
          </td>
        </tr>
      </table></form>    
	  </div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
