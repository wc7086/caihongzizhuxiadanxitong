<?php
if(!defined('IN_CRONLITE'))exit();
include_once TEMPLATE_ROOT.'faka/inc/waphead.php';
?>
<div style="height: 50px"></div>
<div class="menux"><div align="center">联系我们</div></div>

<div class="baoliao" style="padding:20px;">
	<div class="user_top gekai">
		<br>
		<br>
	   <span style="font-size: 16px; white-space: normal;" class="ziti"><?php echo $conf['gg_search'];?></span>
		<p class="ziti">
                <span style="color: #cccccc">-------------------------------------------------------------</span>
                <br>
                <br>
                <img src="assets/faka/images/qq_s.png" width="22" title="">&ensp;QQ客服：<a target="blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $conf['kfqq']?>&amp;Site=qq&amp;Menu=yes"><?php echo $conf['kfqq']?></a>
				<br><br>
                <span style="color: #cccccc">-------------------------------------------------------------</span>
				<?php if(!empty($conf['kfwx'])){?><br>
                <br>
                <img src="assets/faka/images/qq_wx.png" width="22" title="">&ensp;微信客服：<?php echo $conf['kfwx']?>
				<br><br>
                <span style="color: #cccccc">-------------------------------------------------------------</span><?php }?>
		</p>
	</div>
</div>

<!--div class="m_user" style="height:100px">
    <a href="#">返回顶部</a>
</div-->

<?php include TEMPLATE_ROOT.'faka/inc/wapfoot.php';?>
</body>
</html>