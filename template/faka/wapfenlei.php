<?php 
if(!defined('IN_CRONLITE'))exit();

$data=$DB->getAll("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$count = count($data);
include_once TEMPLATE_ROOT.'faka/inc/waphead.php';
?>
<div style="height: 50px"></div>

<?php if($conf['search_open']==1){?>
<div class="menux" style="background-color: #ffffff;">
  <form action="?" method="get"><input type="hidden" name="mod" value="wapso"/>
    <input name="kw" type="text" class="search_input" placeholder="请输入您要查询的商品名称关键词" required>
    <input type="submit" class="search_submit" style="background-color: #f44530" value="商品搜索">
  </form>
</div>
<?php }?>

<div class="menux"><div align="center">商品分类</div></div>

<div class="top">
<div class="m_nav"  style="background-color: #FFFFFF">
  <a href="./"><img src="assets/faka/images/fenleitubiao.png" height="44" width="44">
  <span>全部商品</span></a>
  <?php foreach($data as $row){ ?>
  <a href="./?cid=<?php echo $row['cid'] ?>"><img src="<?php echo $row['shopimg'] ?>" height="44" width="44" onerror="this.src='assets/faka/images/fenleitubiao.png'">
  <span><?php echo $row['name'] ?></span></a>
  <?php }?>
</div>
</div>

<div class="m_user" style="height:100px">
    <a href="#">返回顶部</a>
</div>

<?php include TEMPLATE_ROOT.'faka/inc/wapfoot.php';?>
</body>
</html>