<?php
	include("../include/comm.php");
	checklogin();
	checkac();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" /> 
<title>�Ϻ���Ĭ������������ϵͳ-6000��</title>
<link href="/ximo_dns.css" rel="stylesheet" type="text/css" />
<style>
.box_tab{width:681px;margin:10px auto;}
dl.state_panel dt{height:354px;}/***ҳ��״̬���**/
dl.state_panel embed{width:100%;height:100%;}
dl.tab_state_panel dt{border:solid #cecece;border-width:0 1px;height:25px;line-height:25px; text-align:center;}
dl.tab_state_panel dd{text-align:center;display:none}
dl.tab_state_panel dd.on{display:block;}
dl.tab_state_panel dd img{width:681px;height:354px;}
.caption{background:url(/images/bg1.jpg);color:#ffffff;height:28px;text-align:center; line-height:28px;}
</style>
<script src="/js/jquery.js" type="text/javascript"></script>
<script>
$(function(){
  $("input[name='tab']").click(function(){
    $("dd:visible").removeClass("on");
	$("dd").eq($(this).val()).addClass("on")
	.find("img").attr('src',function(){
    return (this.src.indexOf("?")== -1?this.src:this.src.split("?")[0])+"?"+new Date().getTime()});
		
  });
})
</script>
</head> 
 
<body>
<div class="wrap">
<div class="position">&nbsp;��ǰλ��:&gt;&gt; CPU���</div>
<div class="content"> 
	<div class="box_tab box">
	<div class="caption">CPU���</div>
		<dl class="tab_state_panel">
			<dt>
				<input type="radio" name="tab" value="0" checked>һСʱ
				<input type="radio" name="tab" value="1">��Сʱ
				<input type="radio" name="tab" value="2">һ����
				<input type="radio" name="tab" value="3">һ����
				<input type="radio" name="tab" value="4">һ����
				<input type="radio" name="tab" value="5">һ����
			</dt>
			<dd class="on"><img src="../hot/system/cpu-hour.png"></dd>
			<dd><img src="../hot/system/cpu-6h.png"></dd>
			<dd><img src="../hot/system/cpu-day.png"></dd>
			<dd><img src="../hot/system/cpu-week.png"></dd>
			<dd><img src="../hot/system/cpu-month.png"></dd>
			<dd><img src="../hot/system/cpu-year.png"></dd>
	    </dl>
	</div>
</div>
<div class="push"></div></div>
<?php  include ("../copyright.php")?>
</body>
</html> 