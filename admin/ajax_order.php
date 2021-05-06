<?php
include("../includes/common.php");
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'setStatus':
	adminpermission('order', 2);
	$id=intval($_GET['name']);
	$status=intval($_GET['status']);
	if($status==5){
		if($DB->exec("DELETE FROM pre_orders WHERE id='$id'")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"删除订单失败！'.$DB->error().'"}');
	}else{
		if($DB->exec("update pre_orders set status='$status',result=NULL where id='{$id}'")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"修改订单失败！'.$DB->error().'"}');
	}
break;
case 'order':
	adminpermission('order', 2);
	$id=intval($_GET['id']);
	$rows=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$tool=$DB->getRow("select * from pre_tools where tid='{$rows['tid']}' limit 1");
	if(strpos($rows['tradeno'],'kid')!==false){
		$kid=explode(':',$rows['tradeno']);
		$kid=$kid[1];
		$trade=$DB->getRow("select * from pre_kms where kid='$kid' limit 1");
		$trade['type']='卡密';
		$addstr='<li class="list-group-item"><b>使用卡密：</b>'.$trade['km'].'</li>';
	}elseif(strpos($rows['tradeno'],'invite')!==false){
		$trade['type']='推广赠送';
	}elseif(!empty($rows['tradeno'])){
		$trade=$DB->getRow("select * from pre_pay where trade_no='{$rows['tradeno']}' limit 1");
		$addstr='<li class="list-group-item"><b>支付订单号：</b>'.$trade['trade_no'].'</li><li class="list-group-item"><b>支付金额：</b>'.$trade['money'].' 元'.($trade['tid']==-3?'（'.$trade['num'].'件商品）':null).'</li><li class="list-group-item"><b>获得利润：</b>'.($rows['money'] - $rows['cost']).' 元</li><li class="list-group-item"><b>支付IP：</b><a href="https://m.ip138.com/iplookup.asp?ip='.$trade['ip'].'" target="_blank" rel="noreferrer">'.$trade['ip'].'</a></li>';
		if($trade['type']=='rmb'||is_numeric($rows['userid']))$addstr.='<li class="list-group-item"><b>支付用户ID：</b>'.($rows['userid']!=$rows['zid']?'<a href ="userlist.php?zid='.$rows['userid'].'" target="_blank">'.$rows['userid'].'</a>':'<a href ="sitelist.php?zid='.$rows['zid'].'" target="_blank">'.$rows['zid'].'</a>').'</li>';
	}else{
		$trade['type']='默认';
	}
	$input=$tool['input']?$tool['input']:'下单QQ';
	$inputs=explode('|',$tool['inputs']);
	$value=$tool['value']>0?$tool['value']:1;
	$data = '<li class="list-group-item"><b>商品名称：</b>'.$tool['name'].'</li><li class="list-group-item" style="word-break:break-all;"><b>下单数据：</b><br/>'.$input.'：'.$rows['input'].($rows['input2']?'<br/>'.$inputs[0].'：'.$rows['input2']:null).($rows['input3']?'<br/>'.$inputs[1].'：'.$rows['input3']:null).($rows['input4']?'<br/>'.$inputs[2].'：'.$rows['input4']:null).($rows['input5']?'<br/>'.$inputs[3].'：'.$rows['input5']:null).'</li><li class="list-group-item"><b>下单数量：</b>'.($rows['value']*$value).'</li><li class="list-group-item"><b>站点ID：</b>'.$rows['zid'].'</li><li class="list-group-item"><b>下单时间：</b>'.$rows['addtime'].'</li><li class="list-group-item"><b>购买方式：</b>'.$trade['type'].'</li>'.$addstr;
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'order2':
	adminpermission('order', 2);
	$id=intval($_GET['id']);
	$rows=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$tool=$DB->getRow("select * from pre_tools where tid='{$rows['tid']}' limit 1");
	$input=$tool['input']?$tool['input']:'下单ＱＱ';
	$inputs=explode('|',$tool['inputs']);
	$data = '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname">'.$input.'</div><input type="text" id="inputvalue" value="'.$rows['input'].'" class="form-control" required/></div></div>';
	$i=2;
	foreach($inputs as $input){
		if(!$input)continue;
		if(strpos($input,'{')!==false && strpos($input,'}')!==false){
			$inputname = substr($input,0,strpos($input,'{'));
			$arr = explode(',',getSubstr($input,'{','}'));
			$select='<option value="'.$rows['input'.$i].'">'.$rows['input'.$i].'</option>';
			foreach($arr as $option){
				if(strpos($option,':')!==false){
					$select.='<option value="'.explode(':',$option)[0].'">'.$option.'</option>';
				}else{
					$select.='<option value="'.$option.'">'.$option.'</option>';
				}
			}
			$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'.$i.'">'.$inputname.'</div><select id="inputvalue'.$i.'" class="form-control">'.$select.'</select></div></div>';
		}else{
			$data .= '<div class="form-group"><div class="input-group"><div class="input-group-addon" id="inputname'.$i.'">'.$input.'</div><input type="text" id="inputvalue'.$i.'" value="'.$rows['input'.$i].'" class="form-control" required/></div></div>';
		}
		$i++;
	}
	$data .= '<input type="submit" id="save" onclick="saveOrder('.$id.')" class="btn btn-primary btn-block" value="保存">';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'order3':
	adminpermission('order', 2);
	$id=intval($_GET['id']);
	$rows=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$data = '<div class="form-group"><div class="input-group"><div class="input-group-addon">份数</div><input type="text" id="num" value="'.$rows['value'].'" class="form-control" required/></div></div>';
	$data .= '<input type="submit" id="save" onclick="saveOrderNum('.$id.')" class="btn btn-primary btn-block" value="保存">';
	$result=array("code"=>0,"msg"=>"succ","data"=>$data);
	exit(json_encode($result));
break;
case 'editOrder':
	adminpermission('order', 2);
	$id=intval($_POST['id']);
	$inputvalue=trim(daddslashes($_POST['inputvalue']));
	$inputvalue2=trim(daddslashes($_POST['inputvalue2']));
	$inputvalue3=trim(daddslashes($_POST['inputvalue3']));
	$inputvalue4=trim(daddslashes($_POST['inputvalue4']));
	$inputvalue5=trim(daddslashes($_POST['inputvalue5']));
	$sds=$DB->exec("update `pre_orders` set `input`='$inputvalue',`input2`='$inputvalue2',`input3`='$inputvalue3',`input4`='$inputvalue4',`input5`='$inputvalue5' where `id`='$id'");
	if($sds!==false)
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'editOrderNum':
	adminpermission('order', 2);
	$id=intval($_POST['id']);
	$num=intval($_POST['num']);
	$sds=$DB->exec("update `pre_orders` set `value`='$num' where `id`='$id'");
	if($sds!==false)
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'operation':
	adminpermission('order', 2);
	$status=$_POST['status'];
	$checkbox=$_POST['checkbox'];
	$i=0;
	$statuss=$conf['shequ_status']?$conf['shequ_status']:1;
	foreach($checkbox as $id){
		if($status=='操作订单')continue;
		if($status==4)$DB->exec("DELETE FROM pre_orders WHERE id='$id'");
		elseif($status==5){
			$result = do_goods($id);
			if(strpos($result,'成功')!==false){
				$DB->exec("update pre_orders set status='$statuss',djzt='1',result=NULL where id='{$id}'");
			}
		}elseif($status==6){
			$row=$DB->getRow("select * from pre_orders where id='$id' limit 1");
			if($row && $row['zid']>1 && $row['status']==3 && is_numeric($row['userid'])){
				$zid = intval($row['userid']);
				changeUserMoney($zid, $row['money'], true, '退款', '订单(ID'.$id.')已退款到余额');
				rollbackPoint($id);
				$DB->exec("update pre_orders set status='4',result=NULL where id='{$id}'");
			}
		}
		else $DB->exec("update pre_orders set status='$status' where id='$id' limit 1");
		$i++;
	}
	exit('{"code":0,"msg":"成功改变'.$i.'条订单状态"}');
break;
case 'result':
	$id=intval($_POST['id']);
	$rows=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	exit('{"code":0,"result":"'.$rows['result'].'"}');
break;
case 'setresult':
	adminpermission('order', 2);
	$id=intval($_POST['id']);
	$rows=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$rows)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$result=str_replace(array("\r\n","\n"),'',$_POST['result']);
	if($DB->exec("update pre_orders set result='$result' where id='{$id}'")!==false)
		exit('{"code":0,"msg":"修改订单成功！"}');
	else
		exit('{"code":-1,"msg":"修改订单失败！'.$DB->error().'"}');
break;
case 'getmoney': //退款查询
	adminpermission('refund', 2);
	$id=intval($_POST['id']);
	$row=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	if($row['zid']<1 && !is_numeric($row['userid']))exit('{"code":-1,"msg":"退款失败，该订单属于主站"}');
	if($row['status']==4)exit('{"code":-1,"msg":"该订单已退款请勿重复提交"}');
	//if($row['status']!=0&&$row['status']!=3)exit('{"code":-1,"msg":"只有未处理和异常的订单才支持退款"}');
	if($row['money']==0){
		$tool=$DB->getRow("select * from pre_tools where tid='{$row['tid']}' limit 1");
		$money=$tool['price'];
		$money=$row['value']*$money;
	}else{
		$money=$row['money'];
	}
	//$tc_point=$DB->getColumn("select point from pre_points where zid='{$row['zid']}' and action='提成' and orderid='$id' limit 1");
	//if($tc_point>0)$money-=$tc_point;
	if($money==0)exit('{"code":-1,"msg":"该订单为0元"}');
	exit('{"code":0,"money":"'.$money.'"}');
break;
case 'refund': //退款操作
	adminpermission('refund', 2);
	$id=intval($_POST['id']);
	$money=trim(daddslashes($_POST['money']));
	$row=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	if($row['zid']<1 && !is_numeric($row['userid']))exit('{"code":-1,"msg":"退款失败，该订单属于主站"}');
	if($row['status']==4)exit('{"code":-1,"msg":"该订单已退款请勿重复提交"}');
	if($row['status']!=0&&$row['status']!=3)exit('{"code":-1,"msg":"只有未处理和异常的订单才支持退款"}');
	if($money<=0)$money=$row['money'];
	if(is_numeric($row['userid'])){
		$zid = intval($row['userid']);
		changeUserMoney($zid, $money, true, '退款', '订单(ID'.$id.')已退款到余额');
	}
	rollbackPoint($id);
	$DB->exec("update pre_orders set status='4',result=NULL where id='{$id}'");
	if(is_numeric($row['userid'])){
		exit('{"code":0,"msg":"该订单已成功退款给UID'.$zid.'"}');
	}else{
		exit('{"code":0,"msg":"该订单属于未注册用户，需要手动退款！相关提成已扣除成功"}');
	}
break;
case 'djOrder': //重新下单
	adminpermission('order', 2);
	$id=intval($_GET['id']);
	$url=$_POST['url'];
	$post=$_POST['post'];
	$result = do_goods($id,$url,$post);
	if(strpos($result,'成功')!==false){
		exit('{"code":0,"msg":"下单成功！"}');
	}else{
		exit('{"code":-1,"msg":"'.$result.'"}');
	}
break;
case 'showStatus': //订单进度查询
	adminpermission('order', 2);
	$id=intval($_GET['id']);
	$row=$DB->getRow("select * from pre_orders where id='$id' limit 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$tool=$DB->getRow("select * from pre_tools where tid='{$row['tid']}' limit 1");
	$shequ=$DB->getRow("select * from pre_shequ where id='{$tool['shequ']}' limit 1");
	if($shequ['type']=='yile'){
		$list = yile_chadan($shequ['url'], $row['djorder'], $shequ['username'], $shequ['password']);
		$shopurl = 'http://'.$shequ['url'].'/home/order/'.$tool['goods_id'];
	}elseif($shequ['type']=='jiuwu'){
		$list = jiuwu_chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder']);
		$shopurl = 'http://'.$shequ['url'].'/index.php?m=home&c=goods&a=detail&id='.$tool['goods_id'].'&goods_type='.$tool['goods_type'];
	}elseif($shequ['type']=='shangmeng'){
		$list = shangmeng_chadan($shequ['username'], $shequ['password'], $row['djorder']);
	}elseif($shequ['type']=='kashangwl'){
		$list = kashangwl_chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder']);
		$shopurl = 'http://'.$shequ['url'].'/buy/'.$tool['goods_id'];
	}elseif($shequ['type']=='shangzhanwl'){
		$list = shangzhanwl_chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder']);
		$shopurl = 'http://'.$shequ['url'].'/product/'.$tool['goods_id'].'.html';
	}elseif($shequ['type']=='daishua'){
		$list = this_chadan($shequ['url'], $row['djorder']);
		$shopurl = 'http://'.$shequ['url'].'/?tid='.$tool['tid'];
	}elseif($shequ['type']=='liuliangka'){
		$list = liuliangka_chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder']);
	}elseif($shequ['type']=='zhike'){
		$list = zhike_chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder']);
		$shopurl = 'http://'.$shequ['url'].'/shop/goods/detail/?sn='.$tool['goods_param'];
		$tool['goods_id'] = $tool['goods_param'];
	}elseif($shequ['type']=='extend'){
		if(class_exists("ExtendAPI", false) && method_exists('ExtendAPI','chadan')){
			$list = ExtendAPI::chadan($shequ['url'], $shequ['username'], $shequ['password'], $row['djorder'], $tool['goods_id'], [$row['input'], $row['input2'], $row['input3'], $row['input4'], $row['input5']]);
		}else{
			exit('{"code":-1,"msg":"该对接类型暂不支持查询订单进度"}');
		}
	}else{
		exit('{"code":-1,"msg":"该对接类型暂不支持查询订单进度"}');
	}
	if(($list['order_state']=='已完成'||$list['order_state']=='订单已完成'||$list['订单状态']=='已完成'||$list['订单状态']=='已发货'||$list['订单状态']=='交易成功') && $row['status']==2){
		$DB->exec("UPDATE `pre_orders` SET `status`=1 WHERE id='{$id}'");
	}
	if((strpos($list['order_state'],'异常')!==false||strpos($list['order_state'],'退单')!==false||$list['订单状态']=='异常'||$list['订单状态']=='已退单') && $row['status']<3){
		$DB->exec("UPDATE `pre_orders` SET `status`=3 WHERE id='{$id}'");
	}
	if(is_array($list)){
		$list['orderid'] = $row['djorder'];
		$result=array('code'=>0,'msg'=>'succ','domain'=>$shequ['url'],'shopid'=>$tool['goods_id'],'shopurl'=>$shopurl,'list'=>$list);
	}elseif($list){
		$result=array('code'=>-1,'msg'=>$list);
	}else{
		$result=array('code'=>-1,'msg'=>'获取数据失败');
	}
	exit(json_encode($result));
break;

case 'fillPayOrder':
	$trade_no = trim($_POST['trade_no']);
	$srow = $DB->getRow("SELECT * FROM pre_pay WHERE trade_no=:trade_no", [':trade_no'=>$trade_no]);
	if (!$srow) exit(json_encode(array('code'=>-1,'msg'=>'记录不存在')));
	if($srow['status']==0){
		if($DB->exec("UPDATE `pre_pay` SET `status`='1',`endtime`=NOW() WHERE `trade_no`=:trade_no", [':trade_no'=>$trade_no])){
			$conf['message_duijie']=0;
			$conf['message_buy']=0;
			$orderid = processOrder($srow);
			exit(json_encode(array('code'=>0,'msg'=>'补单成功！','orderid'=>$orderid)));
		}
	}else{
		exit(json_encode(array('code'=>-1,'msg'=>'该订单已经是完成状态')));
	}
break;
case 'delPayOrder':
	$trade_no = trim($_POST['trade_no']);
	if($DB->exec("DELETE FROM pre_pay WHERE trade_no=:trade_no", [':trade_no'=>$trade_no])!==false)
		exit('{"code":0,"msg":"删除支付记录成功！"}');
	else
		exit('{"code":-1,"msg":"删除支付记录失败！'.$DB->error().'"}');
break;

default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}