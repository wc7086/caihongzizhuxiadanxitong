<?php
if (!defined('IN_CRONLITE')) exit();
if(!$conf['invite_tid'])exit("<script language='javascript'>alert('当前站点未开启推广链接功能');window.location.href='./';</script>");

$shops = array();
$rs=$DB->query("SELECT A.*,B.name,B.shopimg FROM pre_inviteshop A LEFT JOIN pre_tools B ON A.tid=B.tid WHERE A.active=1 ORDER BY A.sort ASC");
while($res = $rs->fetch())
{
	$shops[] = $res;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title><?php echo $conf['sitename'] ?> - 推广链接生成</title>
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic ?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/oneui.css">
	<script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php echo $background_css?>
<style>
.alert-info {
    color: #001fff;
}
#list td{max-width:220px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}
</style>
</head>
<body style="background-color:#ffffff;">
<div style="padding-top:6px;">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
        <div class="block" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
            <a class="btn btn-block" href="./" id="tlet"><i class="fa fa-mail-reply-all"></i> 返回网站首页</a>
        </div>
        <div class="block" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                <li class="active" style="width:50%"><a href="#share" data-toggle="tab">
                        <center><i class="fa fa-share-alt"></i> 推广奖励</center>
                    </a></li>
                <li style="width:50%" class=""><a href="#query" data-toggle="tab">
                        <center><i class="fa fa-search"></i> 进度查询</center>
                    </a></li>
            </ul>
            <div class="block-content">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="share">
                        <div class="alert alert-info">
                            注：请选择下方的商品，填写好相关信息，然后点击生成您的专属推广链接，复制链接或者广告语发送到QQ好友/QQ群聊/微信好友/朋友圈/空间/贴吧/论坛等地方宣传。<br><br>
                            邀请他人完成正常访问后，您即可获得<font color="red">指定商品奖励</font>！<br><br>
                            邀请好友访问邀请的人数越多，领取的福利越好、越多！领取无上限！赶快生成您的专属『推广链接』把网站分享给更多人吧！
                        </div>
                         <div class="list-group">
                            <?php foreach ($shops as $row) {
							if(empty($row['shopimg']))$row['shopimg'] = 'https://ae01.alicdn.com/kf/H62814210ab734f578208b4e0276dd392k.png';
							?>
								<div class="list-group-item" style="padding:5px"><label class="css-input css-radio css-radio-primary" style="font-size: 14px;"><input type="radio" name="ctool" data-tid="<?php echo $row['tid'] ?>" data-type="<?php echo $row['type'] ?>" data-value="<?php echo $row['value'] ?>" value="<?php echo $row['id'] ?>"><span></span>
										<img src="<?php echo $row['shopimg'] ?>"  width="18px">&nbsp;<?php echo $row['name'] ?>
									</label>
								</div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
							<div class="hide" id="tidframe"></div>
                            <div class="input-group">
                                <div class="input-group-addon">查单ＱＱ</div>
                                <input type="text" name="query_qq" id="query_qq" class="form-control" placeholder="请输入用于查询订单的QQ账号" required="required">
                            </div>
                            <hr />

                            <div id="inputsname"></div>

                            <div id="alert_frame" class="alert alert-success" style="background: linear-gradient(to right, rgb(113, 215, 162), rgb(94, 209, 215)); font-weight: bold; color: white;display: none"></div>

							<div id="alert_invite" style="display: none" class="alert alert-success"></div>
                        </div>

                        <div class="form-group">
                            <input type="submit" name="submit" id="submit_buy" value="立即创建推广订单" class="btn btn-primary btn-block" style="background-color: #7266ba; border-color: #7266ba;">
                        </div>
						<div id="resulturl" style="display:none;">
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="query">
                        <div class="form-group">
                            <div class="alert alert-warning">
                                提示：输入获取推广信息时填写的联系QQ，即可查询进度<br>
                                注意：此页面仅查询推广进度，订单查询请返回首页进行查询
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        查单ＱＱ
                                    </div>
                                    <input type="text" name="qq" id="qq" class="form-control"
                                           placeholder="请输入用于查询订单的QQ账号,查询推广进度" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" id="submit_sublog" value="立即查询"
                                       class="btn btn-primary btn-block"
                                       style="background-color: #7266ba; border-color: #7266ba;">
                            </div>
                            <div id="result" class="form-group" style="display:none">
                                <div class="table-responsive">
                                    <table class="table table-vcenter table-condensed table-striped">
                                        <thead>
                                        <tr>
                                            <th>领取账号</th>
                                            <th>商品名称</th>
											<th>奖励次数</th>
											<th>状态</th>
											<th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody id="list" style="font-size: 13px;"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-themed" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
            <div class="block-header bg-amethyst"
                 style="background-color: #6a67c7; border-color: #6a67c7; padding: 10px 10px;">
                <h3 class="block-title"><i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;<b>推广统计信息</b></h3>
            </div>
            <div class="block-content block-content-mini block-content-full bg-gray-lighter">
                <div class="row text-center">
                    <div class="col-xs-4">
                        <div class="text-center text-muted">领取ＱＱ</div>
                    </div>
                    <div class="col-xs-4">
                        <div class="text-center text-muted">完成时间</div>
                    </div>
                    <div class="col-xs-4">
                        <div class="text-center text-muted">获得奖励</div>
                    </div>
                </div>
            </div>
            <marquee class="zmd" behavior="scroll" direction="UP" onmouseover="this.stop()" onmouseout="this.start()"
                     scrollamount="5" style="height:16em">
                <table class="table table-hover table-striped" style="text-align:center">
                    <thead>
                    <?php
                    $c = 80;
                    for ($a = 0; $a < $c; $a++) {
                        $sim = rand(1, 5); #随机数
                        $a1 = 'https://ae01.alicdn.com/kf/Hdf40fd7a47504d1ebbfc8bcac04e6c800.png'; #超级会员
                        $a2 = 'https://ae01.alicdn.com/kf/H0744ba68e4274c44ab3765677ca15057F.png'; #视频会员
                        $a3 = 'https://ae01.alicdn.com/kf/H124dd225e7964a7f983f5a56735da02bK.png'; #豪华黄钻
                        $a4 = 'https://ae01.alicdn.com/kf/H8c71f2d773e04943ac748d395e541d1eY.png'; #豪华绿钻
                        $a5 = 'https://ae01.alicdn.com/kf/H62814210ab734f578208b4e0276dd392k.png'; #名片赞
                        $e = 'a' . $sim;
                        if ($sim == '1') {
                            $name = '超级会员';
                        } else if ($sim == '2') {
                            $name = '视频会员';
                        } else if ($sim == '3') {
                            $name = '豪华黄钻';
                        } else if ($sim == '4') {
                            $name = '豪华绿钻';
                        } else if ($sim == '5') {
                            $name = rand(1000, 100000) . '名片赞';
                        }
                        $date = date('Y-m-d'); #今日
                        $time = date("Y-m-d", strtotime("-1 day"));
                        if ($a > 50) {
                            $date = $time;
                        } else {
                            if (date('H') == 0 || date('H') == 1 || date('H') == 2) {
                                if ($a > 8) {
                                    $date = $time;
                                }
                            }
                        }
                        echo '<tr></tr><tr><td>恭喜QQ' . rand(10, 999) . '**' . rand(100, 999) . '**</td><td>于' . $date . '日推广成功</td><td><font color="salmon">奖励<img src="' . $$e . '" width="15">' . $name . '</font></td></tr>';
                    }
                    ?>
                    </thead>
                </table>
            </marquee>
        </div>
    </div>
</div>
<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script type="text/javascript">
    var hashsalt =<?php echo $addsalt_js?>;
</script>
<script src="assets/js/invite.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>