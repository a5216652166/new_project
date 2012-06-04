<?
include ('../include/comm.php');
$pageaccess=1;
checklogin();
checkac('修改');
if(isset($_POST['Submit'])){
	checkac_do(3);
	for($i=0;$i<sizeof($_POST['did']);$i++)
	{
		if($_POST['dvalue'][$i]!='')
		{
			$sql="update drecord set dname='".$_POST['dname']."',dtype='".$_POST['dtype']."',dvalue='".$_POST['dvalue'][$i]."'";
			$sql=$sql.",disapp='0',dys=".$_POST['dys'].",dacl='".$_POST['dacl'][$i]."',dupdate=datetime('now','localtime'),remarks='".$_POST['remarks']."' where did=".$_POST['did'][$i];
			$db->query($sql);
			if ( filter_var($_POST['dvalue'][$i], FILTER_VALIDATE_IP) ){
				$pageaccess = 0; 
			}
		}
	}
	if($pageaccess == 0){
		$dmain_del = $_POST['dname_old'].".".$_POST['domainname'].".";
		$del_sql = "delete from drecord where dvalue='".$dmain_del."' and dtype='PTR'";
		$db->query($del_sql);
	}
	$sql="update domain set domainisapp='0' where domainid=".$_POST['domainid'];
	$db->query($sql);
	writelog($db,'记录管理',"修改记录：".$_POST['dname'].".".$_POST['domainname']);
	$db->close();
	showmessage('域名记录修改成功!','record.php?domainid='.$_POST['domainid']);
}
$domainid=$_GET["domainid"];
$query=$db->query("select * from domain where domainid=".$domainid);
$row=$db->fetchAssoc($query);
$domainname=$row['domainname'];
$query=$db->query("select * from drecord where did=".$_GET['id']);
$row=$db->fetchAssoc($query);
/*$query=$db->query("select * from setacl");
$acl=$db->fetchAll($query);
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>记录修改设置</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script src="/js/ximo_dns.js"></script>

<script language="javascript">

function checklogin(){
	var t = 1;
	var input =$("input[name='dvalue[]']");	
	for (var i = 0; i < input.length; i++){		
		if (input[i].value != ""){
			t = 0;
		}
	}
	if(t==1)
	{
		alert("请至少输入一个记录值");
		return false;
	}else{
		if(document.record.dtype.value=='A'){
			for (var i = 0; i < input.length; i++){
				if (input[i].value != ""){
					if (!checkip(input[i].value)){
						alert("ipv4格式不正确");
                                                input[i].select();
						return false;
						break;
					}
				}
			}
		}else if(document.record.dtype.value=='AAAA' || document.record.dtype.value == 'A6'){
			for (var i = 0; i < input.length; i++){
			input[i].value=input[i].value.toUpperCase();
				if (input[i].value != ""){
					if (!checkipv6(input[i].value)){
						alert("ipv6格式不正确");
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
				input[i].value=input[i].value.toLowerCase();
					if (!checkip(input[i].value) && !checkipv6(input[i].value)){
						if(!checkurl(input[i].value) || !checkdomain(input[i].value)){
							alert("格式不正确");
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
			alert("请输入主机记录");
			document.record.dname.select();
			return false;
		}
		if(document.record.domainname.value ==''){	
			alert("请输入域名");
			document.record.domainname.select();
			return false;
		}
	if(document.record.dtype.value =='MX'){	
			if(document.record.dys.value ==''){	
			alert("请输入优先级");
			document.record.dys.select();
			return false;
		}
	}
	if(!document.record.remarks.value==''){
		var val= /^[\u0391-\uFFE5\w]+$/;
		if(!val.test(document.record.remarks.value)  )
		{
			alert("不允许输入特殊字符！");
			document.record.remarks.select();
			return false;
		}
	
	}
	//return true;
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
<div class="nav">&nbsp;当前位置:&gt;&gt; 域名设置 &gt;&gt;记录修改 </div>
<ul class="tab-menu">
	<li><a href="domain.php">域名管理</a></li>
    <li><a href="record.php?domainid=<?echo $domainid?>">记录管理 </a></li>    
    <li   class="on"><span>记录修改</span></li>
	<li><a href="domain_ptr.php?domainid=<?echo $domainid?>" onclick="javascript:return   confirm('真的要自动生成本域名的反向解析记录吗？');">自动生成本域名反向解析</a></li>

</ul>
<div class="content">
 <form id="record" name="record" method="post" action="record_mode.php" onsubmit="return checklogin();">
<table width="768"  class="s s_form">     
        <tr>
          <td colspan="2"class="caption"><?echo $domainname?>域名记录修改</td>
        </tr>
  
        <tr>
    
              <td>域名名称：</td>
              <td>
                <input name="domainname" type="text" id="domainname" size="40" value="<?echo $domainname?>" readonly />
              </td>
            </tr>
       
            <tr>
              <td>主机记录：</td>
              <td>
                <input name="dname" type="text" id="dname" value="<?echo $row['dname']?>" size="40" />
                <input name="domainid" type="hidden" id="domainid" value="<?echo $domainid?>" />
				<input name="dname_old" type="hidden" id="dname_old" value="<?echo $row['dname']?>" />
              </td>
            </tr>
            <tr>
              <td>记录类型：</td>
              <td>
                <select name="dtype" id="dtype" onchange="change()">
                <?for($i=0;$i<sizeof($dtype);$i++){?>
                <option value="<?echo $dtype[$i]?>" <?if($row['dtype']==$dtype[$i]){echo "selected";}?>><?echo $dtype[$i]?></option>
                <?}?>
                </select>
             </td>
            </tr>
			<tbody class="show" style="<?if($row['dtype']!="AAAA" && $row['dtype']!="A6" && $row['dtype']!="MX"){ ?>display:none;<? } ?>">
            <tr>
              <td>优先级：</td>
              <td>
                <input name="dys" type="text" id="dys" value="<?echo $row['dys']?>" size="5" />
             </td>
            </tr>
			</tbody>
            <?
              $sql = "select * from drecord where ddomain=".$domainid." and dname='".$row['dname']."' and dys=".$row['dys']." and dtype='".$row['dtype']."'";
              if ($_GET['ptr'] == 1){
                  $sql .= " and dvalue='".$row['dvalue']."'";
              }            
            $q=$db->query($sql); // 

            while($r=$db->fetchAssoc($q)){?>
            <tr>
              <td><?if($r['dacl']=='ANY'){echo "通用线路记录值：";}else{echo $r['dacl']."线路记录值：";}?></td>
              <td>
                <input name="dvalue[]" type="text"  value="<?echo $r['dvalue']?>" size="30" />
                <input name="dacl[]" type="hidden" value="<?echo $r['dacl']?>"  />
                <input name="did[]" type="hidden" value="<?echo $r['did']?>" />
              如果记录值为域名请在域名后加.</td>
            </tr>
            <?}?>
			<tr>
              <td>备注信息：</td>
              <td><input name="remarks" type="text" id="remarks" size="40" value="<?echo $row['remarks']?>" />
              </td>
            </tr>
        <tr>
          <td colspan="2" class="footer">
          <input type="submit" name="Submit" value="保存设置" />
</td>
        </tr>     
    </table> </form><div class="push"></div></div>
 <?
      $db->close();?></div>
<? include "../copyright.php";?>
</body>
</html>
