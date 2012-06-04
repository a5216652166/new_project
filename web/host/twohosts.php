<?php
include ('../include/comm.php');
include ('../mail/sendmail.php');
checklogin();
checkac();
$mes="";
$queryOld=$db->query("select * from twohosts");
$rowOld=$db->fetchAssoc($queryOld);
$isapp="";
exec("service keepalived status",$isapp);
if(strstr($isapp[0], "pid")){
	$autoNew=0;
}else{
	$autoNew=1;
}
if ($_POST['Submit']) {
	$ripname1=$_POST['ripname1'];
	$ripname2=$_POST['ripname2'];
	$autport1=$_POST['autport1'];
	$autport2=$_POST['autport2'];
	$hostype1=$_POST['hostype1'];
	$hostype2=$_POST['hostype2'];
	$vIP1=$_POST['vIP1'];
	$vIP2=$_POST['vIP2'];
	$vroute1=$_POST['vroute1'];
	$vroute2=$_POST['vroute2'];
	$heartTime1=$_POST['heartTime1'];
	$heartTime2=$_POST['heartTime2'];
	$getapp=$_POST['autoNew'];
	if($getapp==0){
		$keepconf="";
	
			if($hostype1==0){
				$htye="MASTER";
				$priority=100;
			}else {
				$htye="BACKUP";
				$priority=99;
			}
			$keepconf.="vrrp_instance VI_1 {\n";
			$keepconf.="    state ".$htye."\n";
			$keepconf.="    interface ".$ripname1."\n";
			$keepconf.="    virtual_router_id ".$vroute1."\n";
			$keepconf.="    priority ".$priority."\n";
			$keepconf.="    advert_int ".$heartTime1."\n"; 
			$keepconf.="    authentication {\n";
			$keepconf.="        auth_type PASS\n";
			$keepconf.="        auth_pass 1111\n";
			$keepconf.="    }\n";
			$keepconf.="     virtual_ipaddress {\n";
			$keepconf.="        ".$vIP1."\n";
			$keepconf.="    }\n ";	
			$keepconf.="     notify_master /xmdns/sh/master.sh\n";
			$keepconf.="} \n";	
		if(writeFile("/etc/keepalived/keepalived.conf",$keepconf)){
			$mes.="���óɹ���";
		}else{
			$mes.="����ʧ��!";
		}
		exec("service keepalived restart");
		exec("service keepalived status",$isa);
		if(strstr($isa[0], "pid")){
			$mes.="�����ѿ���";
		}else{
			$mes.="�����޷���������";
		}
	}else{
		exec("service keepalived stop");
		$mes="���óɹ��������Ѿ��رգ�";
	}
	$sql="update twohosts set ripname1='".$ripname1."',hostype1=".$hostype1.",vIP1='".$vIP1."',vroute1=".$vroute1.",heartTime1=".$heartTime1;
	$db->query($sql);

	showmessage($mes,"twohosts.php");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>˫���ȱ�</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"  type="text/javascript"charset="utf-8" ></script>
<script src="/js/ximo_dns.js"  type="text/javascript" charset="utf-8"></script>
<script language="javascript">
function checkTwohost(){

			if(document.twohost.vIP1.value == ''){
			alert("����������IP");
			document.twohost.vIP1.select();
			return false;
		}
		else
		{
			if(!checkip(document.twohost.vIP1.value)  && !checkipv6(document.twohost.vIP1.value))
			{
				alert("��������IP����");
				document.twohost.vIP1.select();
				return false;
			}
		}
			if(document.twohost.vroute1.value == ''){
			alert("����������·��ID");
			document.twohost.vroute1.select();
			return false;
		}
		else
		{
			if(!checkInt(document.twohost.vroute1.value)||document.twohost.vroute1.value<=0)
			{
				alert("����������������");
				document.twohost.vroute1.select();
				return false;
				
			}
		}
		if(document.twohost.heartTime1.value == ''){
			alert("����������ʱ��");
			document.twohost.heartTime1.select();
			return false;
		}
		else
		{
			if(!checkInt(document.twohost.heartTime1.value)||document.twohost.heartTime1.value<=0)
			{
				alert("����������������");
				document.twohost.heartTime1.select();
				return false;
			}
		}
	
	return true;
}


</script>
</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; ˫���ȱ�</div>
<div class="content">
 <form id="twohost" name="twohost" method="post" action="twohosts.php" onsubmit="return checkTwohost()">
      <table  width="778"    class="s s_form" >
       <tr>
          <td  colspan="4"  class="caption" >˫���ȱ�</td>
        </tr>
        
         <tr>
          <td class="title">��ض˿ڣ�</td>
          <td>
             <select name="ripname1" id="ripname1">
			<!--<option value="0"> </option>-->
			<? $s = "select * from netface";
		   $res = $db->query($s);
		   while ($r = $db->fetchAssoc($res))
		{ 
        ?>
             <option value="<?echo $r['facename']?>" <?php if($rowOld['ripname1']==$r['facename']) echo "selected"; ?> ><?echo $r['facename']?></option>
             <?php } ?>
             </select>
           	</td>
     

        
         <tr>
           <td class="title">�����˿�״̬��</td>
           <td>         
           <select name="hostype1" id="hostype1">
           <option value="0" <?php if($rowOld['hostype1']==0) echo "selected"; ?> >������ʽ</option>
           <option value="1" <?php if($rowOld['hostype1']==1) echo "selected"; ?> >���÷�ʽ</option>
           </select>
           </td>
         </tr>
         <tr>
          <td class="title">����IP��</td>
          <td>
          <input name="vIP1" type="text" id="vIP1" value="<? echo $rowOld['vIP1']; ?>" />
</td>
        </tr>

        <tr>
          <td class="title">����·��ID��</td>
          <td><input name="vroute1" type="text" id="vroute1" value="<? echo $rowOld['vroute1']; ?>" />
          
</td>

        </tr>         
        

        <tr>
          <td class="title">����ʱ�䣺</td>
          <td><input name="heartTime1" type="text" id="heartTime1" value="<? echo $rowOld['heartTime1']; ?>" />
          ��&nbsp;&nbsp;
</td>
        </tr> 

                      <tr>
        <td>��<?php if ($autoNew==1) echo "�����ѹر�";else echo "�����ѿ���" ?>��</td>
          <td colspan="3">
          <input type="radio" name="autoNew" id="yautoNew" value="0" <?php if($autoNew==0) echo "checked"; ?> />����
          <input type="radio" name="autoNew" id="nautoNew" value="1" <?php if($autoNew==1) echo "checked"; ?> />�ر�
          </td>
        </tr>
        <tr>
          <td  colspan="4"  class="footer" >
            <input type="submit" name="Submit" value="��������" />
          </td>
        </tr>

      </table>
      <br>
      
      
      
      
      </form>
</div><div class="push"></div>
<? $db->close();?>
</div>
<? include "../copyright.php";?>
</body>
</html>
