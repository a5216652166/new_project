<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac('���');
if(isset($_POST['Submit'])){
	checkac_do(5);
	if ($_POST['dtype'] == 'A'){ //A
		foreach($_POST['dvalue'] as $dv){
                   if(trim($dv)=="")continue;
		   if ( !filter_var($dv, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
                   showmessage("ipv4 error",'record_add.php?domainid='.$_POST['domainid']);
		}
	} else if ($_POST['dtype'] == 'AAAA' || $_POST['dtype'] == 'A6'){  //AAAA   A6
		foreach($_POST['dvalue'] as $dv){
                   if(trim($dv)=="")continue;
		   if ( !filter_var($dv, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) )
                   showmessage("ipv6 error",'record_add.php?domainid='.$_POST['domainid']);
		}
	}
	//���ݿ����Ѵ���
	for($i=0;$i<sizeof($_POST['dacl']);$i++)
	{
		$sql = "select * from drecord where ddomain=".$_POST['domainid']." and dname='".$_POST['dname']."' and dtype='".$_POST['dtype']."' and dys=".$_POST['dys']." and dvalue='".$_POST['dvalue'][$i]."' and dacl='".$_POST['dacl'][$i]."'";
		
		$rs = $db->query($sql);			
		if ( $db->num_rows($rs) != 0 )
		{
                showmessage("���ݿ����Ѵ���",'record.php?domainid='.$_POST['domainid']);
		}
	}
	for($i=0;$i<sizeof($_POST['dacl']);$i++)
	{
 	  if($_POST['dvalue'][$i]=='')continue;
          $sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate,remarks)values(".$_POST['domainid'];
          $sql=$sql.",'".$_POST['dname']."',".$_POST['dys'].",'".$_POST['dtype']."','".$_POST['dvalue'][$i]."','".$_POST['dacl'][$i];
          $sql=$sql."','".$_POST['dis']."','0',datetime('now','localtime'),'".$_POST['remarks']."')";
          $db->query($sql);
          $sql="update domain set domainnum=domainnum+1 where domainid=".$_POST['domainid'];
          $db->query($sql);
	}
	$sql="update domain set domainisapp='0' where domainid=".$_POST['domainid'];
	$db->query($sql);
	writelog($db,'��¼����',"��Ӽ�¼��".$_POST['dname'].".".$_POST['domainname']);
	$db->close();
	showmessage('������¼��ӳɹ�!','record.php?domainid='.$_POST['domainid']);
	

}
$domainid=$_GET["domainid"];
$query=$db->query("select * from domain where domainid=".$domainid);
$row=$db->fetchAssoc($query);
$domainname=$row['domainname'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��¼�������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>
<script language="javascript">

function checklogin(){
	var t = 0;
	var input =$("input[name='dvalue[]']");	
	for (var i = 0; i < input.length; i++){		
		if (input[i].value != ""){
			t = t+1;
		}
	}
	if(t==0)
	{
		alert("����������һ����¼ֵ");
		return false;
	}else{
		if(document.record.dtype.value=='A'){
			for (var i = 0; i < input.length; i++){
				if (input[i].value != ""){
					if (!checkip(input[i].value)){
						alert("ipv4��ʽ����ȷ");
                                                input[i].select();
						return false;
						break;
					}
				}
			}
		}else if(document.record.dtype.value=='AAAA' || document.record.dtype.value == 'A6'){
			for (var i = 0; i < input.length; i++){
				if (input[i].value != ""){
					if (!checkipv6(input[i].value)){
						alert("ipv6��ʽ����ȷ");
                                                input[i].select();
						return false;
						break;
					}
				}
			}
		}else if(document.record.dtype.value=='TXT'){
		
		return true;
		
		}else{
			for (var i = 0; i < input.length; i++){
				if (input[i].value != ""){
					if (!checkip(input[i].value) && !checkipv6(input[i].value)){
						if(!checkurl(input[i].value) || !checkdomain(input[i].value)){
							alert("��ʽ����ȷ");
                                                        input[i].select();
							return false;
							break;
						}
					}
				}
			}
		}
	}
	if(document.record.dname.value ==''){
		alert("������������¼");
		document.record.dname.select();
		return false;
	}
	if(document.record.domainname.value ==''){
		alert("����������");
		document.record.domainname.select();
		return false;
	}
	if(document.record.dtype.value =='MX'){
		if(document.record.dys.value ==''){
			alert("���������ȼ�");
			document.record.dys.select();
			return false;
		}
	}
	if(!document.record.remarks.value==''){
		var val= /^[\u0391-\uFFE5\w]+$/;
		if(!val.test(document.record.remarks.value)  )
		{
			alert("���������������ַ���");
			document.record.remarks.select();
			return false;
		}
	
	}
	return true;
}
function change()
{
	if(document.record.dtype.value == "AAAA" || document.record.dtype.value == "A6" || document.record.dtype.value == "MX")
	{
		$(".show").show();
	}else{
		$(".show").hide();
	}
}
</script>
</head>

<body>
<div class="wrap">
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; �������� &gt;&gt;��¼��� </div>
<ul class="tab-menu">
	<li><a href="domain.php">��������</a></li>
    <li><a href="record.php?domainid=<?echo $domainid?>">��¼���� </a></li>    
    <li   class="on"><span>��¼���</span></li>
	<li><a href="domain_ptr.php?domainid=<?echo $domainid?>" onclick="javascript:return   confirm('���Ҫ�Զ����ɱ������ķ��������¼��');">�Զ����ɱ������������</a></li>

</ul>
<div class="content"> 
<form id="record" name="record" method="post" action="record_add.php" onsubmit="return checklogin();">
<table width="768" class="s s_form">
      
        <tr>
          <td colspan="2" class="caption"><?echo $domainname?>������¼���</td>
        </tr>      
        <tr>
          
              <td>�������ƣ�</td>
              <td >
                <input name="domainname" type="text" id="domainname" size="40" value="<?echo $domainname?>" readonly />           
              </td>
            </tr>
       
            <tr>
              <td>������¼��</td>
              <td>
                <input name="dname" type="text" id="dname" value="@" size="40" />
                <input name="domainid" type="hidden" id="domainid" value="<?echo $domainid?>" />
             </td>
            </tr>
            <tr>
              <td>��¼���ͣ�</td>
              <td>
                <select name="dtype" id="dtype" onchange="change()">
                <?for($i=0;$i<sizeof($dtype);$i++){?>
                <option value="<?echo $dtype[$i]?>"><?echo $dtype[$i]?></option>
                <?}?>
                </select>
             </td>
            </tr>
			<tbody class="show" style="display:none;">
            <tr>
              <td >���ȼ���</td>
              <td>
                <input name="dys" type="text" id="dys" value="0" size="5" />
             </td>
            </tr>
			</tbody>
            <tr>
              <td >ͨ����·��¼ֵ��</td>
              <td ><label>
                <input name="dvalue[]" type="text" size=30 id="dvalue" />
                <input name="dacl[]" type="hidden" id="dacl" value="ANY" />
              ����·Ϊ����ʹ��Ĭ����·����</label></td>
            </tr>
            <?$q=$db->query("select * from setacl");
            while($r=$db->fetchAssoc($q))
            {?>
            <tr>
              <td ><?echo $r['aclident']."��·��¼ֵ��"?></td>
              <td>
                <input name="dvalue[]" type="text" size=30 id="dvalue" />
                <input name="dacl[]" type="hidden" value="<?echo $r['aclident']?>" />
              �����¼ֵΪ���������������.</td>
            </tr>
            <?}?>
            
            
            <tr>
              <td >����״̬��</td>
              <td>
                <input name="dis" type="radio" value="1" checked="checked" />
                ����
                <input type="radio" name="dis" value="0" />
                ͣ�� </td>
            </tr>
           <tr>
              <td>��ע��Ϣ��</td>
              <td><input name="remarks" type="text" id="remarks" size="40" />
              </td>
            </tr>
         
        <tr>
          <td colspan="2"  class="footer">
          <input type="submit" name="Submit" value="��������" />
</td>
        </tr>    
    </table>  </form></div>
 <?
      $db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>
