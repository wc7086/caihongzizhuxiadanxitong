<?php
//分类屏蔽指定地区
if(!defined('IN_CRONLITE'))exit();

$hideData=[];
$rs=$DB->query("SELECT * FROM `pre_class` WHERE `active`=1 AND `block` IS NOT NULL AND `block`<>''");
while($row = $rs->fetch()){
	$hideData[$row['cid']] = explode(',',$row['block']);
}
$json = json_encode($hideData);
?>
<script src="https://pv.sohu.com/cityjson?ie=utf-8" type="text/javascript"></script>
<script>
    var hideData = <?php echo $json?>;
    p_setRegion();
    function p_setRegion() {
        $('#cid option').each(function (i, item) {
            let key = parseInt($(item).val());
            if (hideData.hasOwnProperty(key)) {
                for (let str of hideData[key]) {
                    if (returnCitySN['cname'].indexOf(str) > -1) {
                        $(item).remove();
                        break;
                    }
                }
            }
        });
        $.each(hideData, function (key, content) {
			for (let str of content) {
                if (returnCitySN['cname'].indexOf(str) > -1) {
					var tempDom = $('#collapse' + key);
					if (tempDom.length > 0)
						tempDom.parent().remove();
					$('.cid' + key).remove();
					break;
				}
			}
        });
    }
</script>