<?php 
include "include/comm.php";
include("include/login_lock.class.php");
$Lock=new login_lock();//��½����
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><? echo $system['dnstype'];?> --<? echo $system['dnssq'];?></title>
<link href="divstyle.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script language="javascript">
if(this.parent.location!=this.location)self.parent.location=this.location;
var time_lock,M,S,timer;
var run=function(){
if(!time_lock){
$("#clock_div").hide();
$("#loginform").show();
clearTimeout(timer);
return;
}
M=Math.floor(time_lock/60);
S=time_lock%60;
time_lock--;
$("#clock").html("<b>"+M+"</b>��<b>"+S+"</b>��");
timer=setTimeout(function(){run();},1000);
}
<?if($Lock->is_lock()){
echo "time_lock=".($Lock->data["time"]+15*60-time()).";";
$time=true;
}?>

function checklogin(){
	var obj = document.userlogin.username;
	var obj2=document.userlogin.password;

	if(obj.value == ''){	
			alert("�������û���");
			obj.select();
			return false;
		}
	if(obj2.value == ''){	
			alert("����������");
			obj2.select();
			return false;
		}	
	
	return true;
}
$(function(){
$("#username").focus();
run();
$("#vdcode").click(function(){
        $(this).attr("src","/code.php?t="+new Date().getTime());
}).attr("src","/code.php?t="+new Date().getTime());
})
</script>
<style type="text/css">
#login_wrapper{background:url(images/frame/login5000.gif);width:574px;height:305px;margin:100px auto 0 auto;}
.login_box{padding:160px 0 0 270px;}
#clock_div{color:red;padding-left:10px;text-align:left;font-size:18px;<?=$Lock->is_lock()?"":"display:none;"?>}
#loginform{<?=$Lock->is_lock()?"display:none;":""?>}
#loginform td{height:20px;}
</style>
</head>

<body>
<div id="login_wrapper">
<div class="login_box">
<div id="clock_div">��Ϊ��¼��������������,<br>�㻹��Ҫ�ȴ�:<p id="clock" style="text-align:center;color:#000;"></p></div>
<form id="userlogin" name="userlogin" method="POST" action="login.php" onSubmit="return checklogin();"><table id="loginform"><tr><td width="72" align="right"><span class="STYLE1">�û�����</span></td>
              <td width="120"><input name="username" type="text" id="username" class="inputclass" size="25" /></td>
              <td width="24">&nbsp;</td>
            </tr>
            <tr>
              <td align="right"><span class="STYLE1">��&nbsp;&nbsp;�룺</span></td>
              <td ><input name="password" type="password" class="inputclass" size="20" /></td>
              <td>&nbsp;</td>
            </tr>
			<?if($Lock->data["times"]){?>
			<td align="right"><span class="STYLE1">��֤�룺</span></td>
              <td ><input name="vdcode" class="inputclass" size="20" /></td>
              <td align="left"><img alt="����ˢ�£�" id="vdcode"></td>
			<?}?>
            <tr>
              <td align="right">&nbsp;</td>
              <td ><input type="image" name="imageField" src="images/bt_login2.gif" /></td>
              <td>&nbsp;</td>
            </tr>
</table></form></div>
</div>
      <table width="383" border="0" align="center" cellpadding="0" cellspacing="0">
      	<tr>
      		<?php 
      		$mg = file_get_contents($manager);
      		if ($mg == "1"){
      		?>
      		<td width="383" height="20" align="center"><a href="app/reg.php">����������</a></td>
      		<?php 
      		}
      		?>
      	</tr>
        <tr>
          <td><img src="images/line.gif" width="383" height="10"></td>
        </tr>
        <tr>
          <td align="center" class="graytext2"></td>
        </tr>
        <tr>
          <td height="40" align="center" class="graytext2">&nbsp;</td>
        </tr>
      </table>
<?$db->close();?>
</body>
</html>