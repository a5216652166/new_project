<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();
if(isset($_POST['Submit'])){
    checkac('���');
	if ($_POST['mtype'] == 'port')
		$url = $_POST['pt']." ".$_POST['tu']; //��������portʱ�� url���   �� �˿�+�ո�+T��U �� ; T:tcp , U:udp
	else if ($_POST['mtype'] == 'server')
		$url = $_POST['fw']." T";
	else 
		$url = $_POST['murl'];
		
	$sql="insert into mhost (mname,mip,mdate,mis,mtype,murl)values('".$_POST['mname']."','".$_POST['mip']."'";
	$sql=$sql.",".$_POST['mdate'].",'".$_POST['mis']."','".$_POST['mtype']."','".$url."')";
	$db->query($sql);
	
	writelog($db,'�����������',"��Ӽ������:".$_POST['mname'].$_POST['mip']);
		$db->close();
		showmessage('��Ӽ�������ɹ�','setmohost.php');
}
if(isset($_GET['ac']))
{
	if($_GET['ac']=='del')
	{
	    checkac('ɾ��');
		//ɾ��
		$db->query("delete from mhost where mid=".$_GET['id']);
		$db->query("delete from mhostacl where maclhostid=".$_GET['id']);
		writelog($db,'�����������',"ɾ���������");
	}
	elseif($_GET['ac']=='pdel')
	{
        checkac('ɾ��');
		if(isset($_POST["todel"])){
		$ids=implode(",",$_POST["todel"]);
		$db->query("delete from mhost where mid in (".$ids.")");
		$db->query("delete from mhostacl where maclhostid in (".$ids.")");
        writelog($db,'�����������',"ɾ���������");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�����������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<style>
.title{background:#e7f4ff; width:25%; text-align:right;}
td.foot{text-align:left}
</style>
<script src="/js/jquery.js"  type="text/javascript"charset="utf-8" ></script>
<script src="/js/ximo_dns.js"  type="text/javascript" charset="utf-8"></script>
<script language="javascript">
function s_all(){
	var checkboxs = document.getElementsByName('todel[]');
	for(var i=0;i<checkboxs.length;i++){
		checkboxs[i].checked = true;
		if (checkboxs[i].parentNode.parentNode.bgColor != "#fcdfdf")
			checkboxs[i].parentNode.parentNode.bgColor="#fdffc5";
	}
}
function c_all(){
	var checkboxs = document.getElementsByName('todel[]');
	for(var i=0;i<checkboxs.length;i++){
		checkboxs[i].checked = false;
		if (checkboxs[i].parentNode.parentNode.bgColor != "#fcdfdf")
			checkboxs[i].parentNode.parentNode.bgColor="#ffffff";
	}
}
function checkcolor(bx, color){
	if (bx.checked == true){
		if (bx.parentNode.parentNode.bgColor != "#fcdfdf")
			bx.parentNode.parentNode.bgColor="#fdffc5";
	}
	else{
		if (bx.parentNode.parentNode.bgColor != "#fcdfdf")
		bx.parentNode.parentNode.bgColor=color;
	}
}
function del(cs){
	fm = _g('delform');
	fm.action="?"+cs;
	fm.submit();
}
function checklogin(){

	if(document.mhost.mname.value == ''){	
		alert("������������");
		document.mhost.mname.select();
		return false;
	}
	else
	{
		if(!checkSpace(document.mhost.mname.value))
		{
			alert("ֻ������Ӣ����ĸ,���ֺ��»���");
			document.mhost.mname.select();
			return false;
		}
	}
	if(document.mhost.mtype[1].checked && document.mhost.murl.value != ''){	
		if(document.mhost.mip.value != ''){
			if(!checkip(document.mhost.mip.value) && !checkipv6(document.mhost.mip.value))
			{
				alert("�����IP��ʽ����");
				document.mhost.mip.select();
				return false;
			}
		}
		if(!checkurl(document.mhost.murl.value ))
		{
			alert("�����url��ʽ����");
			document.mhost.murl.select();
			return false;
		}	
	}
	if(document.mhost.mtype[1].checked && document.mhost.murl.value == ''){	
		alert("������URL��ط�ʽURL��ַ");
		document.mhost.murl.select();
		return false;
	}
	if(!document.mhost.mtype[1].checked){
		if(document.mhost.mip.value == ''){	
			alert("������������IP");
			document.mhost.mip.select();
			return false;
		}
		else
		{
			if(!checkip(document.mhost.mip.value) && !checkipv6(document.mhost.mip.value))
			{
				alert("�����IP��ʽ����");
				document.mhost.mip.select();
				return false;
			}
		}
	}
	
	if(document.mhost.mtype[3].checked && document.mhost.pt.value == ''){	
		alert("������˿ڼ�ⷽʽ�˿ں�");
		document.mhost.pt.select();
		return false;
	}
	else if(document.mhost.mtype[3].checked && !isPort(document.mhost.pt.value))
		{
			alert("����д��Ч�˿ں�");
			document.mhost.pt.select();
			return false;
		}
	return true;
}
</script>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; �����������</div>
<div class="content">
 <form id="mhost" name="mhost" method="post" action="setmohost.php" onsubmit="return checklogin();"> 
      <table width="778"    class="s s_form"  >
         <tr>
          <td  colspan="4"  class="caption"  >�����������</td>
        </tr>
         <tr>
           <td >������ƣ�</td>
           <td>
             <input name="mname" type="text" id="mname" />
           </td>
           <td  class="title" >�������IP��</td>
           <td  >
             <input name="mip" type="text" id="mip" size=30/>
          </td>
         </tr>
         <tr>
          <td >ping��ⷽʽ��</td>
          <td>
            <input name="mtype" type="radio" value="ping" checked="checked" />
            ����PING��ⷽʽ
          </td>
          <td  class="title" >���ʱ������</td>
          <td >
            <select name="mdate" id="mdate">
              <option value="5">5����</option>
              <option value="10">10����</option>
              <option value="30">30����</option>
              <option value="60">60����</option>
            </select>
          </td>
         </tr>
        <tr>
          <td >URL��ⷽʽ��</td>
          <td>
            <input type="radio" name="mtype" value="url" />
          ����URL��ʽ:
          <input name="murl" type="text" id="murl" size="36" />
          </td>
          <td class="title">�Ƿ�����</td>
          <td  >
            <input name="mis" type="radio" value="1" checked="checked" />
          ����
          <input name="mis" type="radio" value="0" /> 
          �ر�
		</td>
        </tr>
        
        <tr>
          <td>�����ⷽʽ��</td>
          <td>
		  <input type="radio" name="mtype" value="server" />
          ���÷����ⷽʽ:
            <select name="fw" id="fw">
              <option value="25">smtp</option>
              <option value="53">dns server</option>
			  <option value="80">http</option>
              <option value="109">pop2</option>
              <option value="110">pop3</option>
			  <option value="161">snmp</option>
			  
            </select>
          </td>
          <td></td>
          <td></td>
        </tr>
               <tr>
          <td>�˿ڼ�ⷽʽ��</td>
          <td>
            <input type="radio" name="mtype" value="port" />
          ���ö˿ڼ�ⷽʽ:
          <input name="pt" type="text" id="pt" size="6" />
		  <select name="tu" id="tu">
              <option value="T">tcp</option>
              <option value="U">udp</option>
            </select>
          </td>
          <td  ></td>
          <td  ></td>
        </tr>
        
        
        <tr>
          <td  colspan="4"  class="footer">
            <input type="submit" name="Submit" value="��������" />
         </td>
        </tr>
      </table></form>  &nbsp; 
<form method="post" id="delform">
 <table width="778"  class="s s_grid" >
	 <tr>
    <td class="caption" colspan="8" >�������״̬</td>
  </tr>
      <tr >
	    <th></th>
        <th>�������</th>
        <th >�������IP</th>
        <th>��ط�ʽ</th>
        <th>״̬</th>
        <th>���ʱ��</th>
        <th>����״̬</th>
        <th>����</th>
      </tr>
      <?
    
		$query=$db->query("select * from mhost order by mid desc");
while($row = $db->fetchAssoc($query))
{
?>
      <tr>
	    <td><input type="checkbox" name="todel[]" value="<?=$row['mid'];?>"/></td>
        <td><?echo $row['mname']?></td>
        <td><?echo $row['mip']?></td>
        <td><?if($row['mtype']=='url'){echo "URL:".$row['murl'];}else{echo $row['mtype'];}?></td>
        <td><a href="mohost.php?&ip=<?echo $row['mip']?>&mtype=<?echo $row['mtype']?>&murl=<?echo $row['murl']?>" target="_blank">����鿴</a></td>
        <td><?echo $row['mdate']?>����</td>
        <td><?if($row['mis']=="1"){echo "������";}else{echo "ͣ����";}?></td>
        <td><a href="setmohost.php?id=<?echo $row['mid']?>&amp;ac=del" onclick="javascript:return   confirm('���Ҫɾ���˼�������𣬱������µļ�ز���Ҳȫ��ɾ����');">ɾ��</a> | <a href="setmohost_mode.php?id=<?echo $row['mid']?>">�޸�</a></td>
      </tr>
      <?}
      $db->close();?>
	  <td class="foot" colspan="8"><img src="../images/jiantou.png"><a href="javascript:s_all();">ȫѡ</a> / <a href="javascript:c_all();">ȫ��ѡ</a> <a href="javascript:del('ac=pdel');">ɾ��ѡ����</a> </td>
    </table>
 </form>
</div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
