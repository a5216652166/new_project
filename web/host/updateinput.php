<?
include ('../include/comm.php');
define("DB","/ximorun/ximodb/ximodb");
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
    checkac('Ӧ��');
	$ver=db_exec("select ver from setupdate where updateid=1");
	//���лָ�
	//�ϴ��ļ�����
	if($_FILES["updatefile"]["type"] == "application/octet-stream" || "application/x-gzip-compressed" == $_FILES["updatefile"]["type"])
	{
		$file_fix=end(explode(".",$_FILES["updatefile"]["name"] ));
		
		if ($_FILES["updatefile"]["error"] > 0 || ($file_fix!='xmpkg'))
			{ showmessage('����ϵͳ����������','updateinput.php');}
		else {
		  $pkgPath=UPGRADE_PATH. $_FILES["updatefile"]["name"];
		  move_uploaded_file($_FILES["updatefile"]["tmp_name"],$pkgPath);
		  $sh="/xmdns/sh/install_pkg ".$pkgPath." ".($ver?$ver:"20.0.0");
		  exec($sh,$info,$error);
                  //print_r($info);
		  if($error)
          {
          showmessage("��װ������ʱ��������:".iconv("utf8","gbk",array_pop($info)),'updateinput.php');
          }
          exec('awk \'BEGIN{FS="="}/=/{print}\' '.str_replace("xmpkg",'ini',$pkgPath),$pkg_info,$error);
          foreach($pkg_info as $v){
          list($k,$value)=explode("=",$v);
          $pkg_info[$k]=$value;
          };
          $pkg_content=iconv("utf8","gbk",$pkg_info["pkg_info"]);
          $pkgDate=date("Y-m-d H:i:s",$pkg_info["pkgDate"]);
          $pkgType=$pkg_info["pkgType"];
		  $pkgVersion=$pkg_info["pkgVersion"];		  
          $pkgSize=format_size(filesize($pkgPath));
          $sql="insert into updatelog (updatetime,updatecontent,updateresult,version,pkgDate,pkgType,pkgSize,update_type) values ('".date("Y-m-d H:i:s",time())."','$pkg_content',0,'$pkgVersion','$pkgDate','$pkgType','$pkgSize',1);";
          $result=$db->query($sql);
          $result=$db->query("update setupdate set ver='$pkgVersion'"); 
	      writelog($db,'����ϵͳ',"����ϵͳ");
		  $db->close();
		  showmessage('����ϵͳ�ɹ�','updateinput.php');
		}
	}	else 
		{
			  showmessage('���ϴ��ļ�����ϵͳ������,���飡'.$_FILES["updatefile"]["type"],'updateinput.php');
		}
	
		

}
if(isset($_GET['ac']))
{
	if($_GET['ac']=="del")
	{
	    checkac('ɾ��');
		$sql="delete from updatelog where updateid=".$_GET['id'];
		$db->query($sql);
		writelog($db,'ϵͳ����',"ɾ��ϵͳ������־");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ϵͳ����</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ximo_dns.js"></script>
<script language="javascript">

function checklogin(){
	

	if(document.sysupdate.updatefile.value == ''){	
			alert("�뵼�������ļ�");
			document.sysupdate.updatefile.select();
			return false;
		}
	
	return true;
}

</script>
</head>

<body>
<div class="wrap">
  <div class="position">&nbsp;��ǰλ��:&gt;&gt; ϵͳ����</div>
<div class="content">
<form action="updateinput.php" method="post" enctype="multipart/form-data" name="sysupdate" id="sysupdate" onsubmit="return checklogin();"> 
      <table width="90%"  class="s s_form">       
         <tr>
          <td colspan="2" class="caption">ϵͳ����</td>
        </tr>
         <tr>
           <td>ϵͳ��������</td>
           <td>
           <input name="updatefile" type="file" id="updatefile" />
           </td>
         </tr>
         <tr>      
          <td  colspan="2" class="whitebg">���������п���Ҫ�Ե�һ��ʱ�䣬�벻Ҫ����������������������ʧ�ܣ�</td>
        </tr>        
        <tr>
          <td class="footer" colspan="2">
            <input type="submit" name="Submit" value="����ϵͳ����" />
          </td>
        </tr>
      </table></form>&nbsp;
	  <table class="s s_grid" border="0" align="center" cellpadding="2" cellspacing="1" >
      <tr>
        <td colspan="9" class="caption">�Զ�������ʷ��¼  <a href="?ac=all" onclick="javascript:return   confirm('���Ҫ���������');">�������</a></td>
      </tr>
           <tr bgcolor="#F7FFE8">
             <th width="61" height="25" align="center">���</th>
			 <th width="203" align="center">����ʱ��</th>
             <th width="203" align="center">�汾��</th>
			 <th width="203" align="center">������</th>
             <th width="286" align="center">��������</th>
             <th width="197" align="center">���½��</th>
			 <th width="197" align="center">��������</th>
			 <th width="197" align="center">��С</th>
             <th width="81" align="center">ɾ��</th>
        </tr>
          <?php $sql="select * from updatelog where update_type=1 order by updateid desc";
          $query=$db->query($sql);
		  $i=0;
          while($row=$query->fetch())
          {
			  if($row['updateresult']==0)
				  $result="�ɹ�";
			  else
				  $result="ʧ��";
			  $i++;
			  ?>
          <tr bgcolor="#ffffff" onMouseOver="javascript:this.bgColor='#fdffc5';" onMouseOut="javascript:this.bgColor='#ffffff';">
            <td height="25" align="center"><?echo $i;?></td>
            <td height="25" align="center"><?echo $row['updatetime']?></td>
			<td align="center"><?=$row["version"]?></td>
			 <td align="center"><?=$pkg_types[$row["pkgType"]]?></td>
            <td height="25" align="center"><?echo $row['updatecontent']?></td>
            <td height="25" align="center"><?echo $result;?></td>
			<td align="center"><?=$row["pkgDate"]?></td>
			 <td align="center"><?=$row["pkgSize"]?></td>
            <td height="25" align="center"><a href="?ac=del&id=<?echo $row['updateid']?>" onclick="javascript:return   confirm('���Ҫɾ���Զ�������־��');">ɾ��</a></td>
          </tr>
          <?}?>
	</table>
	  </div>
<?$db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>
<?php
function db_exec($sql)
 {
   $sh="echo \"$sql;\"|sqlite3 ".DB;
   return `$sh`;
 }
?>