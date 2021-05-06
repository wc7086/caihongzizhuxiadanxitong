<?php
/**
 * 分类管理
**/
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
adminpermission('shop', 1);

$numrows=$DB->getColumn("SELECT count(*) from pre_class");
?>
    <form name="classlist" id="classlist">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>排序操作</th><th style="min-width:150px">名称（<?php echo $numrows?>）</th><th>操作</th></tr></thead>
          <tbody id="classlisttbody">
<?php

$rs=$DB->query("SELECT * FROM pre_class WHERE 1 order by sort asc");
while($res = $rs->fetch())
{
	echo '<tr data-cid='.$res['cid'].'><td>
	<a class="btn btn-xs sort_btn" title="移到顶部" onclick="sort('.$res['cid'].',0)"><i class="fa fa-long-arrow-up"></i></a><a class="btn btn-xs sort_btn" title="移到上一行" onclick="sort('.$res['cid'].',1)"><i class="fa fa-chevron-circle-up"></i></a><a class="btn btn-xs sort_btn" title="移到下一行" onclick="sort('.$res['cid'].',2)"><i class="fa fa-chevron-circle-down"></i></a><a class="btn btn-xs sort_btn" title="移到底部" onclick="sort('.$res['cid'].',3)"><i class="fa fa-long-arrow-down"></i></a><a class="btn btn-xs sort_drag" title="拖动排序"><i class="fa fa-sort"></i></a><input type="hidden" name="sort['.$res['cid'].']" value="'.$res['sort'].'">
	</td><td><input type="text" class="form-control input-sm" name="name['.$res['cid'].']" value="'.$res['name'].'" placeholder="分类名称" required></td><td><span class="btn btn-primary btn-sm" onclick="editClass('.$res['cid'].')">修改</span>&nbsp;'.($res['active']==1?'<span class="btn btn-sm btn-success" onclick="setActive('.$res['cid'].',0)">显示</span>':'<span class="btn btn-sm btn-warning" onclick="setActive('.$res['cid'].',1)">隐藏</span>').'&nbsp;<a href="./shoplist.php?cid='.$res['cid'].'" class="btn btn-info btn-sm">商品</a>&nbsp;<span class="btn btn-sm btn-danger" onclick="delClass('.$res['cid'].')">删除</span>&nbsp;<div class="btn-group"><button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">更多 <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right text-left"><li><a href="list.php?cid='.$res['cid'].'">查看该分类的订单</a></li><li><a href="javascript:setBlockPay('.$res['cid'].')">设置禁用支付方式</a></li>'.($conf['classblock']>0?'<li><a href="javascript:setClass('.$res['cid'].')">设置不可售地区</a></li>':null).'</ul></div></td></tr>';
}
echo '<tr><td><span class="btn btn-primary btn-sm" onclick="saveAll()"><i class="fa fa-floppy-o"></i> 保存全部</span></td><td><input type="text" class="form-control input-sm" name="addname" placeholder="分类名称" required></td><td colspan="3"><span class="btn btn-success btn-sm" onclick="addClass()"><span class="glyphicon glyphicon-plus"></span> 添加分类</span>&nbsp;&nbsp;<a href="./classlist.php?my=classimg" class="btn btn-info btn-sm"><i class="fa fa-picture-o"></i> 修改分类图片</a></td></tr>';
?>

          </tbody>
        </table>
      </div>
	</form>