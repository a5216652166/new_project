<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac();

function checkrd($rd){
	if($rd == '')return false;
	if(preg_match('/[\xA1-\xA9]/', $rd))return false;//������֤
	return true;
}
$lstr = "";
if(isset($_POST['Submit'])){
	$rdtf = true;
	$str = $_POST['domaininput'];
	//if(!checkrd($str))die("�ļ���ʽ�����⣡");
	//�ָ��ַ���Ȼ��ʼ����
	$rows=preg_split('/\n/',$_POST["domaininput"]);
	$newfp=array();
	$dname="@";//Ĭ��
	foreach($rows as $row)
	{
		if(trim($row)=="")continue;  //ɾ���ļ��е����п���
		$row = str_replace('��',";",$row);
		$do = explode(';',$row);
		$row=preg_split('/[\t+|\s]/',trim($do[0]));
		/**********ȥ������********/
		$newarray=array();
		foreach ($row as $value){
			if($value=="")continue;
			$newarray[]=trim($value);
		}
		$row=$newarray;
		/**************��������Ҫ������***************/
		if($row[0]=="IN"){
			$v["dname"]=$dname;
			$v["dtype"]=$row[1];
			$v["dys"]=$v["dtype"]=="MX"||$v["dtype"]=="V6"?$row[2]:0;
		}else{
			$v["dname"]=$dname=$row[0];
			$v["dtype"]=$row[2];
			$v["dys"]=$v["dtype"]=="MX"||$v["dtype"]=="V6"?$row[3]:0;
		}
		if($row[2]=="TXT"){
			$tt=array_slice($row,3);
			$v["dvalue"]=implode(" ", $tt);
		}else{
			$v["dvalue"]=array_pop($row);
		}
		$v["dmarks"]=trim($do[1]);
		$newfp[]=$v;    //��������������
	}
	//д�����ݿ�
	foreach($newfp as $myre){
		foreach($_POST['dacl'] as $dacl)
		{
			$sql="insert into drecord (ddomain,dname,dys,dtype,dvalue,dacl,dis,disapp,dupdate,remarks)values(".$_POST['domain'];
			$sql=$sql.",'".$myre["dname"]."','".$myre["dys"]."','".$myre["dtype"]."','".$myre["dvalue"]."','".$dacl;
			$sql=$sql."','1','0',datetime('now','localtime'),'".$myre["dmarks"]."')";
			//echo $sql;
			$db->query($sql);
			$sql="update domain set domainnum=domainnum+1 where domainid=".$_POST['domain'];
			$db->query($sql);
		}
	}
	$sql="update domain set domainisapp='0' where domainid=".$_POST['domain'];
	$db->query($sql);
	writelog($db,'��������',"��������");
	$db->close();
	showmessage('��������ɹ�','record.php?domainid='.$_POST['domain']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����������������</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>
<script language="javascript">
var N_RegExp=/\r?\n+/;
var F_RegExp=/\t+|\s+/;
function showerror(msg){
  $("#error").html(msg);
}
function testip(realip,onlyip){
  var ip = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    var url= /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?\.$/;
	var ipv6=/^([\da-fA-F]{1,4}:){6}((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^::([\da-fA-F]{1,4}:){0,4}((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([\da-fA-F]{1,4}:):([\da-fA-F]{1,4}:){0,3}((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([\da-fA-F]{1,4}:){2}:([\da-fA-F]{1,4}:){0,2}((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([\da-fA-F]{1,4}:){3}:([\da-fA-F]{1,4}:){0,1}((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([\da-fA-F]{1,4}:){4}:((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$|^([\da-fA-F]{1,4}:){7}[\da-fA-F]{1,4}$|^:((:[\da-fA-F]{1,4}){1,6}|:)$|^[\da-fA-F]{1,4}:((:[\da-fA-F]{1,4}){1,5}|:)$|^([\da-fA-F]{1,4}:){2}((:[\da-fA-F]{1,4}){1,4}|:)$|^([\da-fA-F]{1,4}:){3}((:[\da-fA-F]{1,4}){1,3}|:)$|^([\da-fA-F]{1,4}:){4}((:[\da-fA-F]{1,4}){1,2}|:)$|^([\da-fA-F]{1,4}:){5}:([\da-fA-F]{1,4})?$|^([\da-fA-F]{1,4}:){6}:$/;
	if(onlyip){
	return ip.test(realip);
	}else{
	return ip.test(realip)||url.test(realip)||ipv6.test(realip);
	}
}
function checklogin(){
  $("#error").html("");
  if(document.domaininput.domain.value == ''){
  	alert("��ѡ������");
  	return false;
  }
  if(document.domaininput.domaininput.value == ''){
  	alert("������Ҫ����ļ�¼");
  	document.domaininput.domaininput.select();
  	return false;
  }else{
    var obj=document.domaininput.domaininput;
    var values=obj.value.split(N_RegExp);
	for(i in values){
	var realrow=$.trim(values[i]).split(";")[0].split(F_RegExp);//��Ϊ���ź�����ע��
	var rownumber=parseInt(i)+1;//�к�
	/*************��������********/
	if(realrow[2]!='TXT'){
		if(realrow.length>5){
		find(obj,values[i]);showerror("��"+(rownumber)+"�в�������̫�࣬��"+realrow.length+"��");
		return false}
	}
    if(realrow.length<3){find(obj,values[i]);showerror("��"+(rownumber)+"�в�������̫�٣���"+realrow.length+"��");return false}
	/************IN**************/
	if(!realrow[0].match(/in/i)&&!realrow[1].match(/in/i)){find(obj,values[i]);showerror("��"+(rownumber)+"��ȱ�ٲ���IN");return false}
	/**************ip6***************/
	var realip=$.trim(realrow[realrow.length-1]);
	var rowtype=realrow[0]=="IN"?realrow[1]:realrow[2];
	var rowtypes="AMX";
	/********************A��¼**********/
	if(rowtype=="A"&&!testip(realip,true)){
	find(obj,values[i]);
	showerror("�����IP:"+realrow[realrow.length-1]);
	return false;
	}
	if(rowtype=="TXT"){	
	find(obj,values[i]);
	return true;
	}
	/******************���м�¼**************/
	if(!testip(realip)){
	find(obj,values[i]);
	showerror("�����IP:"+realrow[realrow.length-1]);
	return false;
	};
	}
  }
	var t = 1;
	var box = document.getElementsByTagName('input');
	for (var i = 0; i < box.length; i++){
		if (box[i].checked)
			t = 0;	
	}
  
  if (t == 1){
 	 alert("������ѡ��һ����·");
	 return false;
  } 
	return true;
}
function find(obj,text){
   if(!$.browser.msie)return;
   var rg=obj.createTextRange();
  if(rg.findText(text)){
  rg.select();
  } 
}
</script>
</head>

<body>
<div class="nav">&nbsp;��ǰλ��:&gt;&gt; ��������</div>
<ul class="tab-menu">

    <li><a href="domain.php">��������</a></li>
	<li><a href="domain_add.php">�������</a></li>   
    <li><a href="domaingroup.php">����ת������</a></li>
	<li  class="on"><span>��������</span></li>
    <li><a href="domain_output.php">��������</a></li>
	<!--li><a href="checkzone.php">���������¼</a></li-->
    <li><a href="domain.php?ac=app">Ӧ�����õ�ϵͳ</a></li> 
	</ul>
<div class="content">
<form id="domaininput" name="domaininput" method="post" action="domain_input.php" onsubmit="return checklogin();">

          <table width="700" class="s s_form">
		   <tr>
          <td colspan="2" class="caption">������������</td>
        </tr>
            <tr>
              <td>ѡ��Ҫ���뵽��������</td>
              <td><label>
                <select name="domain" id="domain">
                <?$q=$db->query("select * from domain");
                while($r=$db->fetchAssoc($q))
                {?>
                <option value="<?echo $r['domainid']?>"><?echo $r['domainname']?></option>
                <?}?>
                </select>
              </label>(;�ź����Ǽ�¼�ı�ע��)</td>
            </tr>
         
            <tr>
              <td>�������ݣ�</td>
              <td>���磺www3 IN A 192.66.88.99<br>
			  <? if ($lstr == ""){?>
                <textarea name="domaininput" cols="60" rows="25" id="domaininput"></textarea>
			  <? } else {//�����¼�д���?>
				<div style="height:400px; overflow-y:auto "><? echo $lstr;?></div>
			  <? }?>
			  <div id ="error" style="color:red;"></div>
              </td>
            </tr>

            <tr>
              <td>��ѡ��Ҫ�������·��</td>
              <td>
             <input type="checkbox" name="dacl[]" id="dacl" value="ANY" /> 
             ͨ��
			  <?$q=$db->query("select * from setacl");
			  while($r=$db->fetchAssoc($q))
                {?>
                <input type="checkbox" name="dacl[]" id="dacl" value="<?echo $r['aclident']?>" /><?echo $r['aclident']?>
                <?}?>
             
                <label>
                
                </label></td>
            </tr>
             <tr>
          <td  colspan="2" class="footer" >
          <? if ($lstr == "") {?><input type="submit" name="Submit" value="��������" /> <? }?>
</td>

        </tr>
          </table>  
      
      </form>

<? $db->close();
if ($lstr != "")
echo '<script language="javascript"> alert("��¼��ʽ�д�")</script>';
?>
</div><div class="push"></div></div>
<? include "../copyright.php";?>
</body>
</html>
