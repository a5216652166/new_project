<?
include ('../include/comm.php');
checklogin();
checkac();
if($_GET['op']=='start'){
	
	exec( "/etc/init.d/dhcpd start" );
	writeShell($setdhcp,"/etc/init.d/dhcpd start\n");
	exec($setdhcp);
	writelog($db,'DHCP START����','����DHCP����');
	showmessage("������ɣ�",'dhcpserver.php');
}
if($_GET['op']=='close'){		  
	writeShell($setdhcp,"");
	exec("/etc/init.d/dhcpd stop");
	writelog($db,'DHCP STOP����','�ر�DHCP����');
	showmessage("������ɣ�",'dhcpserver.php');
}
if($_GET['op']=='restart'){		  
	exec( "/etc/init.d/dhcpd restart" );
	writelog($db,'DHCP RESTART����','����DHCP����');
	showmessage("������ɣ�",'dhcpserver.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo $system['xmtactype'];?></title>
<link href="../ximo_dns.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"  type="text/javascript"></script>
<script src="../js/ximo_dns.js"  type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js" ></script>  
<script type="text/javascript" src="../js/jquery.metadata.js" ></script>  
<script type="text/javascript" src="../js/jquery.validate.js" ></script>
<script type="text/javascript" src="../js/jquery.validate.my.js" ></script>  


</head>

<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt;���DHCP����</div>
<div class="content">
    <form id="dhcpserver" name="dhcpserver" method="post" action="dhcpserver.php" >
      <table width="700" class="s s_form">
        <tr>
          <td  colspan="2" class="caption">DHCP����</td>
        </tr>
        
        <tr>
          <td>DHCP�������ã�</td>
          <td>		 
		    <?php
			exec("/etc/init.d/dhcpd status",$a,$status);
			if($status==0)
			{
			?>
			&nbsp;<a href="dhcpserver.php?op=restart" onclick="javascript:return   confirm('��ȷ���Ƿ�����DHCP����');"><img src="../images/dhcp_restart.gif" width="91" height="31" border="0"  /></a>
			&nbsp;
			<a href="dhcpserver.php?op=close" onclick="javascript:return   confirm('��ȷ���Ƿ�ر�DHCP����');"><img src="../images/dhcp_cl.gif" width="91" height="31" border="0" /></a>
			<?
				
			}else if($status==3){
				?>
			&nbsp;<a href="dhcpserver.php?op=start" onclick="javascript:return   confirm('��ȷ���Ƿ���DHCP����');"><img src="../images/dhcp_st.gif" width="91" height="31" border="0"  /></a>
			
			<?
			}else{
				?>
			&nbsp;<a href="dhcpserver.php?op=start" onclick="javascript:return   confirm('��ȷ���Ƿ���DHCP����');"><img src="../images/dhcp_st.gif" width="91" height="31" border="0"  /></a>
			
			<?
			}			
			
		  ?>
		 
        </td>
        </tr>
        
        <tr>
          <td  colspan="2"  class="whitebg">
		   <?php
			exec("/etc/init.d/dhcpd status",$a,$status);
			if($status==0)
			{
				echo "<img src=\"../images/dhcp_run.jpg\" width=\"218\" height=\"211\" />";
			}else if($status==3){
				echo "<img src=\"../images/dhcp_stop.jpg\" width=\"218\" height=\"211\" />";
			}else{
				echo "<img src=\"../images/dhcp_stop.jpg\" width=\"218\" height=\"211\" />";
			}			
			
		  ?>
		  </td>
        </tr>
      </table>
                </form>
 	 <?$db->close();?>
</div>
<div class="push"></div></div>

<? include "../copyright.php";?>
</body>
</html>
