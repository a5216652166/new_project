<?
include ('../include/comm.php');
include ('../include/upgrade_inc.php');
checklogin();
checkac();
if(isset($_POST['updatedate'])){
	$sql="update setupdate set updateurl='".$_POST['updateurl']."',updatedate=".$_POST['updatedate']." where updateid=1";
	$db->query($sql);
	
	writelog($db,'�Զ���������',"�����Զ�����");
		showmessage('�Զ��������óɹ�','setupdate.php');

}
if(isset($_GET['ac']))switch($_GET["ac"])
{
	case"del":
		$sql="delete from updatelog where updateid=".$_GET['id'];
		$db->query($sql);
		writelog($db,'�Զ���������',"ɾ���Զ�������־");
        break;
	case"all":
		$sql="delete from updatelog ";
		$db->query($sql);
        break;
        case"checkver":
        exec("/xmdns/sh/php/update checkver",$check_info,$error);
        if($error)
        {
        showmessage(array_pop($check_info));
        }else{
        echo array_pop($check_info);
        }
        exit;
        break;
        case"update":
        exec("/xmdns/sh/php/update",$update_info,$error);
        if($error)
        {
        showmessage(array_pop($update_info));
        }
        echo array_pop($update_info)."<script>window.location.reload();</script>";
        exit;
        break;
        default:
        sleep(10);
        exit;
        break;
}

$query=$db->query("select * from setupdate where updateid=1");
$row=$db->fetch($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�Զ���������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"  type="text/javascript"charset="utf-8" ></script>
<script src="/js/ximo_dns.js"  type="text/javascript" charset="utf-8"></script>
 <script language="javascript">
function checklogin(){
var check_config={
     url:document.autoupdate.updateurl.value.replace(/https?:\/\//,""),
     date:document.autoupdate.updatedate.value
    }	

	if(check_config.url == ''||check_config.url.split(":").length>2||(!checkip(check_config.url.split(":")[0])&&!checkurl(check_config.url.split(":")[0]))){	
			alert("��������ȷ�ĸ��µ�ַ");
			document.autoupdate.updateurl.select();
			return false;
		}
       if(check_config.url.indexOf(":")!=-1){
         var port=check_config.url.split(":").pop();
           if(Number(port).toString()!=port||port>65535||port<0){
			alert("��������ȷ�Ķ˿ں�");
			document.autoupdate.updateurl.select();
			return false;
                 }
                }
	if(check_config.date == ''||Number(check_config.date).toString()!=check_config.date||0>=check_config.date){	
			alert("��������ȷ�ĸ�������");
			document.autoupdate.updatedate.select();
			return false;
		}	
	return true;
}
//���汾
function checkver(){
	var versions=$("#versions").val();     //�������ֵ���洢���°汾��
	$("#vers").html("�����С�����ȴ�").addClass("load");
        $.get("?ac=checkver",{ver:versions},function(html){
	$("#vers").html(html).removeClass("load");
        });
}
//����
function checkupdate(){
	if($("#vers").html()=="�Ѿ������°汾"){
       alert("��ǰ�������°汾,�������!");
	}else{
	var versions=$("#versions").val();     //�������ֵ���洢���°汾��
	$("#vers").html("�����С�����ȴ�").addClass("load");
        $.get("?ac=update",{ver:versions},function(html){
	$("#vers").html(html).removeClass("load");
        });
	}
}
</script>
</head>

<body>
<div class="wrap">
			<div class="position">��ǰλ�ã�ϵͳ���� >> �Զ���������</div>
<div class="content">
<form id="autoupdate" name="autoupdate" method="post" onsubmit="return checklogin();" >
      <table width="768" class="s s_form" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#82C4E8">

        <tr>
          <td colspan="4" class="caption">�Զ���������</td>
        </tr>
         <tr>
            <td  class="title">��ǰ�汾��</td> 
            <td height="25" align="left" width="15%" bgcolor="#FFFFFF"><span id="ver"><?php echo $row['ver'];?></span>
			<input type="hidden" id="versions" name="ver" value="<?=$row['ver']?$row['ver']:"20.0.1";?>" />
			</td>
            <td class="title">���°汾��</td> 
            <td height="25" align="left" width="15%" bgcolor="#FFFFFF"><span id="vers"></span>
            <input type="hidden" id="versions" name="versions" value="<?php echo $row['version'];?>" /></td>

         </tr> 
         <tr>
         <td class="footer" colspan="4"> 
               &nbsp;&nbsp;<input type="button" class="button" name="check" id="check" value="���" onclick="checkver();" />&nbsp;&nbsp;
               <input type="button" class="button" name="update" id="update" value="��������" onclick="checkupdate();" />
            </td>
            </tr>
		<?php
		foreach($pkg_types as $k=>$v){
		$sql="select max(pkgDate) as pkgVersion from updatelog where pkgType=".$k." limit 1";
		$rs=$db->fetch($db->query($sql));
		?>
	    <tr>
          <td ><?=$v?>�汾��</td>
          <td height="25" colspan="3" align="left" bgcolor="#FFFFFF"><label>
          <?=date("YmdHis",strtotime($rs["pkgVersion"]?$rs["pkgVersion"]:"2011-6-1"));?>
          </label></td>
        </tr>
		<?php
		}
		?>
        <tr>
          <td >�Զ�����URL��ַ��</td>
          <td height="25" colspan="3" align="left" bgcolor="#FFFFFF"><label>
            <input name="updateurl" type="text" id="updateurl" value="<?echo $row['updateurl']?>" size="60" />
          </label></td>
        </tr>
         <tr>
           <td >�����Զ�����ʱ�䣺</td>
           <td height="25" colspan="3" align="left" bgcolor="#FFFFFF"><label>
             <input name="updatedate" type="text" id="updatedate" size="8" value="<?echo $row['updatedate'];?>" />
           ��</label></td>
         </tr>
        
        
        <tr>
          <td class="footer" colspan="4" >
            <input type="submit" name='submit' id='submit' />
			</td>
        </tr>
        </table>
		</form> &nbsp; 
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
          <?php $sql="select * from updatelog where update_type<>1 order by updateid desc";
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
</div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
