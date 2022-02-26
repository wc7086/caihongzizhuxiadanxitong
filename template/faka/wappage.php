<?php
if(!defined('IN_CRONLITE'))exit();
include_once TEMPLATE_ROOT.'faka/inc/waphead.php';

$type=isset($_GET['type'])?intval($_GET['type']):0;
?>
<div style="height: 50px"></div>
<div class="menux"><div align="center"><?php if($type==1){?>帮助中心<?php }else{?>关于我们<?php }?></div></div>

<div class="baoliao" style="padding:20px;">
	<div class="user_top gekai">
		<br>
		<br>
	   <span style="font-size: 16px; white-space: normal;" class="ziti"><?php echo $type==1?$conf['template_help']:$conf['template_about']?></span>
	</div>
</div>

<!--div class="m_user" style="height:100px">
    <a href="#">返回顶部</a>
</div-->

<?php include TEMPLATE_ROOT.'faka/inc/wapfoot.php';?>
</body>
</html>