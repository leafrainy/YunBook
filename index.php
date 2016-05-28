<?php
require_once "config.php";
require_once "jssdk.php";
$jssdk = new JSSDK(wxAppId, wxSecret);
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

	<link rel="stylesheet" href="css/jquery-weui.css">
	<link rel="stylesheet" href="css/weui.min.css">
	<link rel="stylesheet" href="css/app.css">
	<title>云书架-贴心的线上书架</title>
</head>
<body>

	<div class="weui_tab">
		<div class="weui_navbar header">
			<a class="weui_navbar_item weui_bar_item_on" id="index">
				书架(<span id="book_count">0</span>)
			</a>
			<a class="weui_navbar_item" id="scan">
				入库
			</a>
			<a class="weui_navbar_item" id="about">
				关于
			</a>
		</div>
		<div class="weui_tab_bd">
			<div class="weui_panel_bd">
				<div id="now_have"></div>
			</div>

		</div>
	</div>

	<div class="weui_msg" id='add_success' style="display:none;">
		<div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">添加成功</h2>
			<p class="weui_msg_desc">好好学习，天天向上</p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a href="" class="weui_btn weui_btn_primary" id="index">确定</a>
			</p>
		</div>

	</div>
	<div class="weui_msg" id='add_erro' style="display:none;">
		<div class="weui_icon_area"><i class="weui_icon_warn weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">添加失败</h2>
			<p class="weui_msg_desc">服务器开小差了~~</p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a href="" class="weui_btn weui_btn_primary" id="index">确定</a>
			</p>
		</div>

	</div>
	<div class="weui_msg" id='book_exit' style="display:none;">
		<div class="weui_icon_area"><i class="weui_icon_info weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">本书已存在哦</h2>
			<p class="weui_msg_desc">温故而知新</p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a href="" class="weui_btn weui_btn_primary" id="index">确定</a>
			</p>
		</div>

	</div>
	<div class="weui_msg" id='not_find' style="display:none;">
		<div class="weui_icon_area"><i class="weui_icon_warn weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title">没有找到该书</h2>
			<p class="weui_msg_desc">残念哒~</p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a href="" class="weui_btn weui_btn_primary" id="index">确定</a>
			</p>
		</div>

	</div>
	<div id="about_content" style="display:none;">
		<img src="http://leafrainy.com/l.png" style="display: block;margin:20px auto">
		<p class="about_p">@叶雨梧桐</p>
		<p class="about_p">主页:leafrainy.com</p>
		<p class="about_p">YunBook</p>
	</div>


</body>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bmob-min.js"></script>
<script type="text/javascript" src="js/jquery-weui.js"></script>
<script type="text/javascript">
	Bmob.initialize('<?php echo dbAppId ?>', '<?php echo dbApiKey ?>');
	
	var bookObject = Bmob.Object.extend("book");
	var query = new Bmob.Query(bookObject);
	var bookObject = new bookObject();
	query.find({
		success: function(results) {

			var now_book_count=results.length;
			$("#book_count").html(now_book_count);
		    // 循环处理查询到的数据
		    var book_names="";
		    for (var i = 0; i < results.length; i++) {
			    var object = results[i];
		    	book_names+= '<a href="#" class="weui_media_box weui_media_appmsg">\
		    	<div class="weui_media_hd">\
		    		<img class="weui_media_appmsg_thumb" src="'+object.get('book_img')+'" alt="">\
		    	</div>\
		    	<div class="weui_media_bd">\
		    		<h4 class="weui_media_title">'+object.get('book_name')+'</h4>\
		    		<p class="weui_media_desc">作者：'+object.get('book_author')+'</p>\
		    		<p class="weui_media_desc">入库：'+object.createdAt+'</p>\
		    	</div></a>'
			}
			$("#now_have").html(book_names);
		}});

		$("#about").click(function(){
			$("#now_have").hide();
			$("#about_content").show();
		});

		$("#index").click(function(){
			location.reload();
		});

	wx.config({
		debug: 0,
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: '<?php echo $signPackage["timestamp"];?>',
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList: [
		'scanQRCode'
		]
	});
	wx.ready(function () {

   		//扫码
   		$("#scan").click(function(){
   			wx.scanQRCode({
   				needResult: 1,
   				desc: 'scanQRCode desc',
   				success: function (res) {
   					var resStatus = res.errMsg;
   					var resStr = res.resultStr;

   					if(resStatus=='scanQRCode:ok'){
   						var isbn_arr = resStr.split(",");
   						var isbn = isbn_arr[1];
   						$.ajax({
   							url: 'api.php?book='+isbn,
   							type:'get',
   							success: function(data){
   								var data_res = JSON.parse(data);
   								if(data_res.code=='6000'){
   									$("#not_find").show();
   								}else{
   									query.equalTo("book_isbn", data_res.isbn13); 

   									query.find({
   										success:function(queryRes){
   											alert(queryRes.length);
   											if(queryRes.length){
   												$("#now_have").hide();
   												$("#book_exit").show();

   											}else{
   												bookObject.save({book_name: data_res.title,book_author:data_res.author,book_img:data_res.image,book_isbn:data_res.isbn13,book_publisher:data_res.publisher}, {
   													success: function(object) {
   														$("#now_have").hide();
   														$("#add_success").show();
   													},
   													error: function(model, error) {
   														$("#now_have").hide();
   														$("#add_erro").show();
   													}
   												});
   											}

   										},
   										error:function(error){

   											bookObject.save({book_name: data_res.title,book_author:data_res.author,book_img:data_res.image,book_isbn:data_res.isbn13,book_publisher:data_res.publisher}, {
   												success: function(object) {
   													$("#now_have").hide();
   													$("#add_success").show();
   												},
   												error: function(model, error) {
   													$("#now_have").hide();
   													$("#add_erro").show();
   												}
   											});
   										}
   									});

   								}	
   							}
   						});
   					}else{
   						$("#add_erro").show();
   					}
   				}
   			});
   		});

   	});

   </script>
   </html>
