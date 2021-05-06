<?php
/**
 * 商品销量排行
**/
include("../includes/common.php");
$title='商品销量排行';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
    <div class="col-xs-12 col-md-10 center-block" style="float: none;">
<?php
$thtime=date("Y-m-d").' 00:00:00';
$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
if($_GET['last']==1){
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM pre_orders A LEFT JOIN pre_tools B ON A.tid=B.tid WHERE A.addtime>='$lastday' AND A.addtime<'$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 20";
}else{
	$sql = "SELECT B.tid,B.name,count(A.id) num FROM pre_orders A LEFT JOIN pre_tools B ON A.tid=B.tid WHERE A.addtime>='$thtime' GROUP BY B.tid ORDER BY num DESC LIMIT 20";
}

?>
<div class="block">
     <div class="block-title"><h2>商品销量排行</h2></div>
<ul class="nav nav-tabs">
<li class="<?php echo $_GET['last']!=1?'active':null;?>" style="width:50%"><a href="shoprank.php"><center>今日销量排行</center></a></li>
<li class="<?php echo $_GET['last']==1?'active':null;?>" style="width:50%"><a href="shoprank.php?last=1"><center>昨日销量排行</center></a></li>
</ul>

      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th class="text-center">排名</th><th class="text-center">商品ID</th><th class="text-center">商品名称</th><th class="text-center">订单数量</th></thead>
          <tbody>
<?php
$rs=$DB->query($sql);
$i=1;
while($res = $rs->fetch())
{
echo '<tr><td class="text-center"><span class="badge badge-danger">'.$i.'</span></td><td class="text-center"><b>'.$res['tid'].'</b></td><td class="text-center">'.$res['name'].'</td><td class="text-center">'.$res['num'].'</td></tr>';
$i++;
}
?>
          </tbody>
        </table>
      </div>
    </div>
 </div>
</div>