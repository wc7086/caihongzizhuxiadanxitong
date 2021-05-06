
function dopay(type,orderid){
	if(type == 'rmb'){
		var ii = layer.msg('正在提交订单请稍候...', {icon: 16,shade: 0.5,time: 15000});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=payrmb",
			data : {orderid: orderid},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 1){
					alert(data.msg);
					if(top.location != self.location)top.location.href='./user/shop.php?buyok=1';
					else window.location.href='?buyok=1';
				}else if(data.code == -2){
					alert(data.msg);
					if(top.location != self.location)top.location.href='./user/shop.php?buyok=1';
					else window.location.href='?buyok=1';
				}else if(data.code == -3){
					var confirmobj = layer.confirm('你的余额不足，请充值！', {
					  btn: ['立即充值','取消']
					}, function(){
						top.location.href='./user/#chongzhi';
					}, function(){
						layer.close(confirmobj);
					});
				}else if(data.code == -4){
					var confirmobj = layer.confirm('你还未登录，是否现在登录？', {
					  btn: ['登录','注册','取消']
					}, function(){
						top.location.href='./user/login.php';
					}, function(){
						top.location.href='./user/reg.php';
					}, function(){
						layer.close(confirmobj);
					});
				}else{
					layer.alert(data.msg);
				}
			} 
		});
	}else{
		top.location.href='other/submit.php?type='+type+'&orderid='+orderid;
	}
}
function cancel(orderid){
	layer.closeAll();
	$.ajax({
		type : "POST",
		url : "ajax.php?act=cart_cancel",
		data : {orderid: orderid, hashsalt: hashsalt},
		dataType : 'json',
		async : true,
		success : function(data) {
			if(data.code == 0){
				orderid = null;
			}else{
				layer.msg(data.msg);
				window.location.reload();
			}
		},
		error:function(data){
			window.location.reload();
		}
	});
}
var GoodsCart = {
    CartList: function (type) {
		type = type || 1;
        if (type == 1) layer.load(2, {time: 9999999});

        $.ajax({
            type: "get",
            url: "ajax.php?act=cart_list",
            dataType: "json",
            success: function (data) {
                if (type == 1) layer.closeAll();
                if (data.code == 0 && data.count > 0) {
                    $(".CartCount").text('(' + data.count + ')');
                    GoodsCart.Success(data);
                    if (type == 1) GoodsCart.GoodsRound();
                } else {
                    GoodsCart.CartNull();
                    if (type == 1) GoodsCart.GoodsRound();
                }
            },
            error: function () {
                layer.alert('加载失败！');
            }
        });
    },
    GoodsRound: function () {
        $.ajax({
            type: "POST",
            url: "ajax.php?act=gettool",
            dataType: "json",
			data: {kw: 'random'},
            success: function (data) {
                if (data.code == 0) {
                    var content = '';
                    $.each(data.data, function (key, val) {
                        var url = './?mod=buy&cid=' + val.cid + '&tid=' + val.tid;
                        content += '<li>\n' +
                            '          <a href="' + url + '">\n' +
                            '              <img src="' + val.shopimg + '" onerror="this.src=\'assets/store/picture/error_img.png\'" style="height: ' + [$(window).width() > 460 ? '36' : '26'] + 'vh" class="proimg"/>\n' +
                            '              <p class="tit">' + val.name + '</p>\n' +
                            '              <p class="price">￥' + val.price + '<img src="./assets/store/images/f3.png"/></p>\n' +
                            '          </a>\n' +
                            '      </li>';
                    });
                    $("#GoodsRound").html(content);
                    layui.use('flow', function () {
                        var flow = layui.flow;
                        flow.lazyimg();
                    });
                }
            },
            error: function () {
                layer.alert('商品推荐获取失败！');
            }
        });
    },
    iframe: function (url) {
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        console.log(index)
        top.location.href = url;
    },
    CartNull: function () {
        $("#CartContent").html($("#CartNull").html());
        $(".hejiBox").hide();
    },
    Success: function (data) {
        var price_all = 0;//所有订单总金额
        var content = '<div class="gwc1_1" >\n' +
            '            <div class="g1 Goodsd_1">\n' +
            '                <div class="gwccheck on"></div>\n' +
            '            </div>\n' +
            '            <div class="g2">\n' +
            '                <a href="./">\n' +
            '                    <span>' + data.sitename + '</span>\n' +
            '                    <img src="./assets/store/images/mre1.png"/>\n' +
            '                </a>\n' +
            '            </div>\n' +
            '        </div>';
        content += '<div class="clear"></div>\n' +
            '        <div class="gwc1_2" id="GoodsCart_1">';

        $.each(data.data, function (key, val) {
            price_all += parseFloat(val.money);
            content += '<div class="gwcone" id="CartShop_' + val.id + '" data-count="' + val.num + '" data-multi="' + val.multi + '">\n' +
                '                <div class="go1 CartSelect_1" data-id="' + val.id + '" id="Cart_' + val.id + '" data-price="' + val.money + '">\n' +
                '                    <div class="gwccheck on"></div>\n' +
                '                </div>\n' +
                '                <div class="go2"><a href="./?mod=buy&cid=' + val.cid + '&tid=' + val.tid + '"><img src="' + val.shopimg + '" onerror="this.src=\'assets/store/picture/error_img.png\'"/></a></div>\n' +
                '                <div class="go3">\n' +
                '                    <div class="go3_1">\n' +
                '                        <a href="./?mod=buy&cid=' + val.cid + '&tid=' + val.tid + '"><p class="p1">' + val.name + '</p></a>\n' +
                '                        <p class="p2" style="text-decoration: none">商品售价</p>\n' +
                '                    </div>\n' +
                '                    <div class="go3_2">\n' +
                '                        <p class="p3">' + val.inputsdata + '</p>\n' +
                '                        <p class="p4">￥' + val.money + '</p>\n' +
                '                    </div>\n' +
                '                    <div class="go3_3">\n' +
                '                        <div class="num1" data-id="' + val.id + '">-</div>\n' +
                '                        <div class="num2" id="Sum_' + val.id + '">' + val.num + '</div>\n' +
                '                        <div class="num3" data-id="' + val.id + '">+</div>\n' +
                '                        <div class="del" data-id="' + val.id + '" data-name="' + val.name + '"><img src="./assets/store/images/del.png"/></div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>';
        });
        content += '</div>';

        $("#CartContent").html(content);

        $("#price_all").text('￥' + price_all.toFixed(2));
        $("#CartContent").attr({
            'data-price': price_all, //不可变动,购物车总
            'data-price2': price_all, //可变动，购物车总
        });
        $(".num1").click(function () {
            var id = $(this).attr('data-id');

            if ($("#CartShop_" + id).attr('data-multi') == '0') {
                layer.msg('此商品下单份数不可修改！');
                return false;
            }

            var count = $("#CartShop_" + id).attr('data-count') - 0; //下单数量
            if ((count - 1) <= 0) {
                layer.msg('最低下单一份!');
                return false;
            }

            /**
             * 执行减少份数操作！
             */
            var load = layer.load(2);
            $.ajax({
                type: "POST",
                url: "ajax.php?act=cart_num",
                data: {id: id, num: (count - 1)},
                dataType: "json",
                success: function (data) {
                    layer.closeAll();
                    if (data.code == 0) {
                        $("#Sum_" + id).text((count - 1));
                        GoodsCart.CartList(2);
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function () {
                    layer.alert('减少份数失败！');
                }
            });
        });

        $(".num3").click(function () {

            var id = $(this).attr('data-id');

            if ($("#CartShop_" + id).attr('data-multi') == '0') {
                layer.msg('此商品下单份数不可修改！');
                return false;
            }

            var count = $("#CartShop_" + id).attr('data-count') - 0; //下单数量

            /**
             * 执行增加份数操作！
             */
            var load = layer.load(2);
            $.ajax({
                type: "POST",
                url: "ajax.php?act=cart_num",
                data: {id: id, num: (count + 1)},
                dataType: "json",
                success: function (data) {
                    layer.closeAll();
                    if (data.code == 0) {
                        $("#Sum_" + id).text((count + 1));
                        GoodsCart.CartList(2);
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function () {
                    layer.alert('增加份数失败！');
                }
            });
        });

        $(".del").click(function () {
            var id = $(this).attr('data-id');
			var that = this;
            layer.open({
                title: false,
                content: '是否要把[' + $(that).attr('data-name') + ']移出购物车？',
                btn: ['确认执行', '取消'],
                btn1: function (layero, index) {
                    var load = layer.load(2);
                    $.ajax({
                        type: "POST",
                        url: "ajax.php?act=cart_shop_del",
						data : {id: id},
                        dataType: "json",
                        success: function (data) {
                            layer.closeAll();
                            if (data.code == 0) {
                                $("#CartShop_" + id).hide(100);
                                layer.msg(data.msg, {icon: 1});
                                setTimeout(function () {
                                    GoodsCart.CartList(2);
                                }, 500);
                            } else {
                                layer.msg(data.msg, {icon: 2});
                            }
                        },
                        error: function () {
                            layer.alert('删除失败！');
                        }
                    });
                }
            });
        });

        $(".Goodsd_1,.heji_1").click(function () {
            var PriceSum = price_all - 0; //不变数
            if ($(".Goodsd_1 div").attr('class') == 'gwccheck' || $(".heji_1 div").attr('class') == 'gwccheck') {
                //开启全部
                $(".CartSelect_1 div,.Goodsd_1 div,.heji_1 div").attr('class', 'gwccheck on');
                $("#CartContent").attr('data-price2', PriceSum);
                $("#price_all").text('￥' + PriceSum.toFixed(2));
            } else {
                //关闭全部
                $(".CartSelect_1 div,.Goodsd_1 div,.heji_1 div").attr('class', 'gwccheck');
                $("#CartContent").attr('data-price2', 0);
                $("#price_all").text('￥' + 0);
            }
        });

        $(".CartSelect_1").click(function () {
            var PriceSum = price_all - 0;
            var PriceSum2 = $("#CartContent").attr('data-price2') - 0;

            var PriceKey = $("#" + this.id + " .gwccheck").attr('class');
            if (PriceKey == 'gwccheck on') {
                //需关闭
                $("#" + this.id + " .gwccheck").attr('class', 'gwccheck');
                var Prices = $("#" + this.id).attr('data-price') - 0;
                $("#CartContent").attr('data-price2', PriceSum2 - Prices);
                $("#price_all").text('￥' + ((PriceSum2 - Prices <= 0 ? 0 : PriceSum2 - Prices)).toFixed(2));
            } else {
                //需开启
                $("#" + this.id + " .gwccheck").attr('class', 'gwccheck on');
                var Prices = $("#" + this.id).attr('data-price') - 0;
                $("#CartContent").attr('data-price2', PriceSum2 + Prices);
                $("#price_all").text('￥' + (PriceSum2 + Prices).toFixed(2));
            }

        })
    }
    , submit: function () {
        var arr = GoodsCart.selected();
        if (arr == -1) {
            layer.msg('最少选择一件商品哦');
            return false;
        }
        $.ajax({
            type: "post",
            url: "ajax.php?act=cart_buy",
            data: {shop_id: arr, hashsalt: hashsalt},
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    var url = './?mod=cartorder&orderid='+data.trade_no;
					window.location.href = url;
					setTimeout(function () {
						layer.alert('<font size="4" color="#2e8b57">若未跳转付款界面,请点击下方付款按钮！</font>', {
							title: '温馨提示!',
							btn: ['付款'], time: 9999999, btn1: function (layero, index) {
								window.location.href = url;
							}
						})
					}, 500);
                } else layer.msg(data.msg, {icon: 2});
            },
            error: function () {
                layer.alert('加载失败！');
            }
        });
    },
    selected: function () {
		var shop_id=new Array();
		$(".CartSelect_1").each(function(){
			var shopid = $(this).attr('data-id');
			if ($(this).find(".gwccheck").attr('class') == 'gwccheck on') {
				shop_id.push(shopid);
			}
		});
        return shop_id;
    }
	, PaySrt: function (srt) {
        var array = srt.split('|');
        layer.load(2, {time: 9999999});
        $.ajax({
            type: "post",
            url: "ajax.php?act=cart_list",
            data: {ids: array},
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {
                    GoodsCart.PaySrtSucc(data);
                } else {
                    layer.msg(data.msg);
                    layer.closeAll();
                }
            },
            error: function () {
                layer.alert('加载失败！');
            }
        });
    },
    PaySrtSucc: function (data) {
        var price = 0;
        content = '';
        var les = data.data.length;
        $.each(data.data, function (key, val) {
            price += val.money;

            if (val.is_curl == 0) {
                var comm = '<span class="layui-badge" style="background-color: #009688">手动发货</span>';
            } else if (val.is_curl == 4) {
                var comm = '<span class="layui-badge " style="background-color: #FF9800">自动发卡</span>';
            } else {
                var comm = '<span class="layui-badge" style="background-color: #00C853">自动发货</span>';
            }

            content += '<div ' + (window.innerWidth > 480 ? 'class="layui-col-sm' + (les == 1 ? '12' : '6') + '" style="margin-top: 0.7em;"' : 'class="layui-col-xs12" style="padding: 0.1em 0.8em 0 0.8em;margin-bottom: 0.9em"') + '>\n' +
                '                <div class="layui-card" style="border-radius: 0.5em">\n' +
                '                    <div class="layui-card-header">\n' +
                '                        下单份数：' + val.num + '份\n' +
                '                        <span style="float: right;">' + comm + '</span>\n' +
                '                    </div>\n' +
                '                    <div class="layui-row layui-col-space8">\n' +
                '                        <div class="layui-col-xs2">\n' +
                '                            <div style="border-radius: 0.5em;width: 3em;text-align: center;height: 3em;line-height: 3em;background-color: #fff;color:white;margin: 0.8em;box-shadow: 3px 3px 16px #eee">\n' +
                '                                <img lay-src="' + val.shopimg + '"  onerror="this.src=\'assets/store/picture/error_img.png\'" style="width: 100%;border-radius: 0.5em"/>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                        <div class="layui-col-xs10">\n' +
                '                            <div class="layui-card-header"\n' +
                '                                 style="font-size: 1.1em;line-height: 2em;height:auto;">\n' +
                '                                ' + val.name + '\n' +
                '                            </div>\n' +
                '                            <div class="layui-card-body information layui-text" style="line-height: 2em">\n' +
                '                                <p><span>购买数量</span><span\n' +
                '                                            style="float: right;">' + val.num + '个</span></p>' +
                '                                </p>\n' +
                '<p>' + val.inputsdata + '</p>' +
                '                                <p style="float: right;margin-top: 0.8em;font-size: 1.05em">商品售价：<font\n' +
                '                                            color="#ff4500"\n' +
                '                                            style="font-size: 1.1em">￥' + val.money + '</font>\n' +
                '                                </p>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>';
        });
        $("#Content").html(content);
        layer.closeAll();
        layui.use('flow', function () {
            var flow = layui.flow;
            flow.lazyimg();
        });
    },
    PaySubmit: function (data) {
        var orderid = $('#orderid').val();
        var paytype = $("input[name=pay]:checked").val();
        if (paytype == undefined) {
            swal({
                title: '注意',
                type: 'warning',
                html: '<h4>请选择付款方式！</h4>',
                confirmButtonText: '好的',
            });
            return false;
        }
        if(paytype == 'help'){
			var content = $("#demo_url").attr('data-url');
			var clipboard = new Clipboard('.btssn', {
				text: function () {
					return content;
				}
			});
			layer.open({
				title: '代付订单创建成功',
				btn: false,
				content: '<center style="color: seagreen">代付订单已经创建成功，发给好友帮你付款吧~</center><hr><textarea  class="layui-textarea">' + content + '</textarea><hr>' +
					'<center><button class="layui-btn layui-btn-fluid layui-btn-radius layui-btn-sm layui-btn-normal btssn">点击复制</button></center>',
			});
			clipboard.on('success', function (e) {
				swal({
					title: '恭喜',
					type: 'success',
					html: '代付订单链接已经帮您复制到剪切板上啦，快去发送给朋友让他帮你付款吧~',
					confirmButtonText: '好的',
				});
				layer.closeAll();
			});
			clipboard.on('error', function (e) {
				console.log(e);
				swal({
					title: '异常',
					type: 'warning',
					html: '复制功能好像出了点问题，去手动复制代付订单链接发给朋友吧',
					confirmButtonText: '好的',
				});
				layer.closeAll();
			});
			return false;
		}else if(paytype == 'rmb'){
			var ii = layer.msg('正在提交订单请稍候...', {icon: 16,shade: 0.5,time: 15000});
			$.ajax({
				type : "POST",
				url : "ajax.php?act=payrmb",
				data : {orderid: orderid},
				dataType : 'json',
				success : function(data) {
					layer.close(ii);
					if(data.code == 1){
						alert(data.msg);
						window.location.href='?buyok=1';
					}else if(data.code == -2){
						alert(data.msg);
						window.location.href='?buyok=1';
					}else if(data.code == -3){
						var confirmobj = layer.confirm('你的余额不足，请充值！', {
						  btn: ['立即充值','取消']
						}, function(){
							window.location.href='./user/recharge.php';
						}, function(){
							layer.close(confirmobj);
						});
					}else if(data.code == -4){
						var confirmobj = layer.confirm('你还未登录，是否现在登录？', {
						  btn: ['登录','注册','取消']
						}, function(){
							window.location.href='./user/login.php';
						}, function(){
							window.location.href='./user/reg.php';
						}, function(){
							layer.close(confirmobj);
						});
					}else{
						layer.alert(data.msg);
					}
				} 
			});
		}else{
			window.location.href='other/submit.php?type='+paytype+'&orderid='+orderid;
		}
    }
}