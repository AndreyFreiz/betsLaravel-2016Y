@extends('layout')

@section('content')
<div class="text-center" style="padding:50px">
				<div style="display:inline-block">
				@if($status == 'good' || 'normal')

				@elseif($status == 'bad')						
				<div class="alert alert-warning text-center"><b><i class="fa fa-gear"></i>  Steam инвентари не в норме, трейды могут задерживаться.</b></div>
				@endif
				<div id="inlineAlert">
				</div>
                @if($deposit !== 0)
				<div class="panel panel-default text-left" id="offerPanel">
				  	<div class="panel-heading">
						<h3 class="panel-title"><b>Исходящий обмен <i class="fa fa-download"></i></b></h3>
				  	</div>
  					<div class="panel-body">
						<span id="offerContent" style="line-height: 40px">'Новое предложение обмена с секретным кодом {{$deposit->code}} за {{$deposit->summa}} монет. Пожалуйста, <a href=https://steamcommunity.com/tradeoffer/{{$deposit->tid}}/target="_blank" >примите предложение обмена</a></span>
						<div class="pull-right"><button class="btn btn-success" id="confirmButton" data-tid="{{$deposit->tid}}">Проверить статус</button></div><br><span style="color: red; font-weight: 700;">Примите обмен, и нажмите 'Проверить статус' что получить монетки на баланс.<span></span></span></div>
				</div>
                @endif
                <div class="panel panel-default text-left" id="offerPanel" style="display:none">
                    <div class="panel-heading">
                        <h3 class="panel-title"><b>Исходящий обмен <i class="fa fa-download"></i></b></h3>
                    </div>
                    <div class="panel-body">
                        <span id="offerContent" style="line-height: 40px"></span>
                        <div class="pull-right"><button class="btn btn-success" id="confirmButton" data-tid="0">Проверить статус</button></div><br><span style="color: red; font-weight: 700;">Примите обмен, и нажмите 'Проверить статус' что получить монетки на баланс.<span></span></span></div>
                </div>
				<div class="panel panel-default text-left fw-6">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Инвентарь : <span id="left_number">...</span> предметов</b></h3>
					</div>
					<div class="panel-body">				
						<div style="margin-bottom:10px">						
							<div style="display:inline-block;float:right">
								<form class="form-inline">
									<select class="form-control" id="orderBy">
										<option value="0">Default</option>
										<option value="1">Price Descending</option>
										<option value="2">Price Ascending</option>
										<option value="3">Name A-Z</option>
									</select>
								</form>
							</div>				
	  						<div style="overflow:hidden;padding-right:2px">
	    						<input type="text" class="form-control" id="filter" placeholder="Поиск..." style="width:100%">
	   						</div>
   						</div>  																										
						<div id="left" class="slot-group noselect">
							<span class="reals"></span>
							<span class="bricks">
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
							</span>		
						</div>						
					</div>						
				</div>
				<div class="panel panel-default text-left fw-4" style="vertical-align:top">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Депозит</b></h3>
					</div>
					<div class="panel-body">
						<button class="btn btn-success btn-lg" style="width:100%" @if($u->acessToken == '') onclick="showConfirm()" @endif id="showConfirmButton">Внесённые предметы<div style="font-size:12px"><span id="sum">0</span> монеток</div></button>				
						<div id="right" class="slot-group noselect">
							<span class="reals"></span>
							<span class="bricks">
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
								<div class="placeholder"></div>
							</span>								
						</div>																										
					</div>						
				</div>
			</div>
		</div>
		<div class="modal fade" id="confirmModal">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header">
				                <div class="close" data-dismiss="modal">&times;</div>
				                <h4 class="modal-title"><b>Подтверждение депозита</b></h4>
				            </div>
				            <div class="modal-body">                           
				                <label>Ссылка на обмен - <a href="https://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">найти мою ссылку на обмен</a></label>
								<input type="text" class="form-control steam-input" id="tradeurl" value="{{$u->trade_link}}">
								<div class="checkbox">
							    	<label>
							      		<input type="checkbox" id="remember"> Запомнить ссылку
							    	</label>
				            <div class="modal-footer">
				            <button class="btn btn-danger" data-dismiss="modal">Отменить</button>
				            <button class="btn btn-success" id="offerButton" onclick="offer()">Подтвердить</button>                
				            </div>
				        </div> 
				    </div>
				</div>		
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>
<script type="text/javascript" src="/js/socket.io.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    $(document).on("click", "#right .slot", function() {
        $(this).parent().remove();
        $(this).appendTo(".placeholder[data-pos='" + $(this).data("pos") + "']");
        addPadding("#right", 4);
        addUp();
    });
    $(document).on("click", "#left .slot:not(.reject)", function() {
        var b = $(this).data("bot") || null;
        if (b != null) {
            $("#botFilter .btn").removeClass("active").addClass("disabled");
            $("#botFilter .btn[data-bot='" + b + "']").addClass("active").removeClass("disabled");
            doFilter();
        }
        $("#right .reals").append("<div class='placeholder'></div>");
        $(this).appendTo($("#right .reals .placeholder:empty").first());
        addPadding("#right", 4);
        addUp();
    });
    $("#filter").on("keyup", function() {
        doFilter();
    });
    $("#orderBy").on("change", function() {
        var o = $(this).val();
        if (o == 1) {
            tinysort("#left .reals>.placeholder", {
                data: "price",
                order: "desc"
            });
        } else if (o == 2) {
            tinysort("#left .reals>.placeholder", {
                data: "price",
                order: "asc"
            });
        } else if (o == 3) {
            tinysort("#left .reals>.placeholder", {
                data: "name",
                order: "asc"
            });
        } else {
            tinysort("#left .reals>.placeholder", {
                data: "pos",
                order: "asc"
            });
        }
    });
    $("#botFilter .btn").on("click", function() {
        if ($(this).hasClass("disabled")) {
            return;
        }
        $("#botFilter .btn").removeClass("active");
        $(this).addClass("active");
        doFilter();
    });
    $("#confirmButton").on("click", confirmTrade);
    $(document).on("contextmenu", ".slot", function(e) {
        if (e.ctrlKey) return;
        e.preventDefault();
        var view = $(this).data("view");
        if (view == "none") {
            return;
        }
    });
    $(document).on("click", function() {
        $("#contextMenu").hide();
    });
});

function showConfirm() {
    $("#confirmModal").modal("show");
}

function offer() {
    $('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><i class="fa fa-gear"></i><b> Процесс отправки предложения, пожалуйста, подождите</b></div>');
    $("#confirmModal").modal("hide");
    var csv = "";
    var hashname = "";
    var sum = 0;
    var botid = 1;
    $("#right .slot").each(function(i, e) {
        csv += $(this).data("id") + ",";
        sum += $(this).data("price");
        hashname += $(this).data("hashname") + ",";
    });
    var turl = $("#tradeurl").val();
    var remember = $("#remember").is(":checked") ? "on" : "off";
    if(csv == ""){
    	$('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><i class="fa fa-gear"></i><b> Вы не выбрали предметы, предложение отклонено</b></div>');
    }else if(turl == ""){
    	$('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><i class="fa fa-gear"></i><b> Вы не указали ссылку на обмен, предложение отклонено</b></div>');
    }else{	
	    $.ajax({
	        "url": '/newOffer',
	        type: "POST",
	        data: {
	            "assetids": csv,
	            "tradeurl": turl,
	            "checksum": sum,
	            "remember": remember,
	            "hashname": hashname
	        },
	        success: function(data) {
                    console.log(data);
	                if (data.type == "success") {
	                    $('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><i class="fa fa-gear"></i><b> Обмен обрабатывается, пожалуйста, подождите</b></div>');
	                } else {
	                    $('#inlineAlert').html('<div class="alert alert-danger" style="font-weight:bold"><i class="fa fa-gear"></i><b> '+data.msg+'</b></div>');
	                }
            },        
	        error: function(err) {
	            $('#inlineAlert').html('<div class="alert alert-danger" style="font-weight:bold"><i class="fa fa-gear"></i><b> Ошибка сервера, пожалуйста, обновите страницу</b></div>');
        },
    });
	}
}

function showPending(code,amount,tid) {
    var content = 'Новое предложение обмена с секретным кодом '+code+' за '+amount+' монет. Пожалуйста, <a href="https://steamcommunity.com/tradeoffer/'+tid+'/" target="_blank" >примите предложение обмена</a>';
    $("#offerContent").html(content);
    $("#confirmButton").data("tid", tid);
    if (amount > 0) {
    	$("#confirmButton").html('Проверить статус');
    }
    $("#offerPanel").slideDown();
}

myinventory();

function myinventory()
{
    $('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><b> Предметы загружаются ... <i class="fa fa-spinner fa-spin"></i></b></div>');
    $.ajax({
        url: '/myinventory',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            console.log(data);
            var predmet = 0;      
            var eleA = [];

            if (!data.success && data.Error) $('#inlineAlert').html('<div class="alert alert-danger" style="font-weight:bold"><i class="fa fa-gear"></i><b> Произошла ошибка, попробуйте еще раз</b></div>');

            if (data.success && data.rgInventory && data.rgDescriptions) {
                $('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><b> Предметы загружаются ... <i class="fa fa-spinner fa-spin"></i></b></div>');
                var items = mergeWithDescriptions(data.rgInventory, data.rgDescriptions);        
                console.log(items);
                items.sort(function(a, b) { return parseFloat(b.price) - parseFloat(a.price) });
                _.each(items, function(item) {
                	var price_content = item.price;
                	var price_class = "ball-0";
                	var slot_class = "";
                	var url = 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/'+item.classid+'/110fx50f';
					var DIV = "<div class='placeholder matched' data-name='"+item.name+"' data-price='"+item.amount+"' data-pos='"+predmet+"' data-hashname'"+item.market_name+"'>";
    				DIV += "<div class='slot data-name='"+item.name+"' data-pos='"+predmet+"' data-price='"+item.amount+"' data-id='"+item.id+"' data-hashname='"+item.market_name+"' style='background-image:url(\""+url+"\")'>";
    				DIV += "<div class='name'>"+item.name+"</div>";
    				DIV += "<div class='price ball-0'>"+item.amount+"</div>";
    				DIV += "</div></div>";                	
                    var ele = DIV;       
                    eleA.push(ele);
                    predmet++               
                });
            }            
            $('#left_number').html(''+predmet+'');
            $('#inlineAlert').html('');
            $('#inlineAlert').html('<div class="alert alert-success" style="font-weight:bold"><i class="fa fa-check"></i><b> Загружено '+predmet+' предметов.</b></div>');
            document.getElementById("left").getElementsByClassName("reals")[0].innerHTML = eleA.join('');
            addPadding("#left", 6);
        },
        error: function (data) {
            console.log(data);
            $('#inlineAlert').html('');
            $('#inlineAlert').html('<div class="alert alert-danger" style="font-weight:bold"><i class="fa fa-gear"></i><b> Ошибка сервера, пожалуйста, обновите страницу</b></div>');
        }
    });
}

function confirmTrade() {
    $('#inlineAlert').html('<div class="alert alert-warning" style="font-weight:bold"><i class="fa fa-gear"></i><b> Подтверждение предложения обмена - пожалуйста подождите...</b></div>');
    $this = $("#confirmButton");
    $this.prop("disabled", true);
    var tid = $this.data("tid");
    $.ajax({
        url: "/confirmTrade",
        type: "POST",
        data: {
            "tid": tid
        },
        success: function(data) {

        },
        error: function(err) {
                
        }
    });    
}

function addPadding(lr, across) {
    var MIN = 2;
    var count = $(lr + " .reals>.placeholder:not(.hidden)").length;
    var needed = 0;
    if (count <= across * MIN) {
        needed = across * MIN - count;
    } else {
        needed = (across - (count % across)) % across;
    }
    $(lr + " .bricks>.placeholder").addClass("hidden").slice(0, needed).removeClass("hidden");
}

function mergeWithDescriptions(items, descriptions) {
        return Object.keys(items).map(function(id) {
            var item = items[id];
            var description = descriptions[item.classid + '_' + (item.instanceid || '0')];
            for (var key in description) {
                item[key] = description[key];

                delete item['icon_url'];
                delete item['icon_drag_url'];
                delete item['icon_url_large'];
            }
            return item;
        })
}

function addUp() {
    var creds = 0;
    var count = 0;
    $("#right .slot").each(function(i, e) {
        creds += $(this).data("price");
        count++;
    });
    $("#sum").html(creds);
    if (count == 0) {
        $("#botFilter .btn").removeClass("disabled");
    }
}

function doFilter() {
    var b = $("#botFilter .btn.active").data("bot") || 0;
    var t = $("#filter").val().toLowerCase();
    var total = $("#left .reals>.placeholder").length;
    var n = $("#left .reals>.placeholder").addClass("hidden").filter(function(i, e) {
        var bx = $(this).data("bot") || "";
        var tx = $(this).data("name") || "";
        var px = $(this).data("price") || 0;
        if (b == 0 || b == bx) {
            if (tx.toLowerCase().indexOf(t) >= 0) {
                return true;
            } else if (t.charAt(0) == ">") {
                return px > parseInt(t.substr(1));
            } else if (t.charAt(0) == ">") {
                return px < parseInt(t.substr(1));
            }
        }
    }).removeClass("hidden").length;
    if (t === "" && b == 0) {
        $("#left_number").html(total);
    } else {
        $("#left_number").html(n + "/" + total);
    }
    addPadding("#left", 6);
}
var socket = io.connect(':2020');
    socket
         .on('statusTrade', function (code,amount,tid,steamids) {
            if(steamid == steamids){
                $('#inlineAlert').html('');
                showPending(code,amount,tid);
            }
         })
         .on('declineTrade', function(msg,steamids){
            if(steamid == steamids){
                $('#inlineAlert').html('');
                $('#inlineAlert').html('<div class="alert alert-danger" style="font-weight:bold"><i class="fa fa-gear"></i><b> '+msg+'</b></div>');
            }
         })
        .on('acceptOffer', function(msg,steamids){
            if(steamid == steamids){
                $('#inlineAlert').html('');
                $('#inlineAlert').html('<div class="alert alert-success" style="font-weight:bold"><i class="fa fa-gear"></i><b> '+msg+'</b></div>');
            }   
         })
</script>		
<style>
html{position:relative;min-height:100%}body{background-color:#fdfdfd}.well{background-color:#f7f7f7}.panel{background-color:#f7f7f7}.panel-default>.panel-heading{background-color:#f7f7f7}#mainpage{font-family:"Ubuntu-Regular"}a{color:#eb5348}.rounded{border-radius:25px}.betlist .rounded{margin:0px 10px;border-radius:25px}.ball{color:#fff;cursor:default;border-radius:45px;border:1px solid transparent;width:45px;height:45px;background-color:#000;font-size:17px;line-height:45px;padding:0;text-align:center;display:inline-block;margin:1px}.td-val{text-align:center;color:#fff;font-size:15px;font-weight:700}.ball-1{background-color:#b04a43;border:0px!important}.ball-0{background-color:#6fb26b;border:0px!important}.ball-8{background:#454545;border:0px!important}.footer img{height:32px;margin-top:6px}.reject{opacity:.5}.footer{position:absolute;bottom:0px;left:0px;right:0px;text-align:center;padding:20px;padding-bottom:10px;padding-top:10px;margin-top:20px;background-color:#fdfdfd;margin:0px;margin-bottom:-80px;border-right:0px;border-radius:0px;color:#222226!important;font-family:"Ubuntu-Regular"!important;height:60px}.placeholder{width:110px;height:110px;background-color:#b8b8b8;display:inline-block;margin:1px}.bricks .placeholder{}.placeholder:empty{}.slot{width:110px;height:110px;background-position:0px 15px;background-repeat:no-repeat;cursor:pointer;font-size:12px;position:relative;background-color:#e2e2e2}.name{text-align:center;position:absolute;bottom:0px;left:0px;right:0px;background-color:#e2e2e2;color:#363636}.price{text-align:center;position:absolute;color:#fff;top:0px;right:0px;padding:2px;border-bottom-left-radius:4px}.bot{text-align:center;position:absolute;top:0px;left:0px;padding:2px;border-bottom-right-radius:4px;color:#363636}.noselect{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.matched{}.fw-6{display:inline-block;width:704px}.fw-4{display:inline-block;width:480px}.float-space{overflow:hidden}.slot-group{font-size:0px;padding:0px;margin-top:3px;margin-bottom:3px}.chat-link,.chat-link:hover,.chat-link:active{color:#333}.chat-link-own{color:#e00}.chat-link-mod{color:#0587ff}.chat-link-streamer{color:#9b66ff}.chat-link-vet{color:#4f8195}.chat-link-adm{color:#f17b08}.chat-link-youtuber{color:#ad0d0e}.total-row{font-size:13px;padding:10px;padding-right:15px;padding-left:15px;overflow:hidden}.total,.mytotal{font-weight:700;font-family:"Ubuntu-Medium";font-size:17px;margin-bottom:2px}.my-row{font-size:13px;padding:10px}.mybet{color:#eb5348}.mybet .amount{font-weight:700}.betlist{margin-bottom:0px;border-radius:12px!important;background-color:#e8e8e8!important}.betlist .list-group-item{background-color:#f9f9f9}#pointer{width:3px;background:#c3c3c3;position:absolute;left:50%;top:0px;height:100%}.hist{height:32px;width:32px;display:inline-block;margin:5px}#banner{font-size:14px;position:absolute;left:0px;width:100%;text-align:center;line-height:45px;color:#373737}.side-icon{width:100%;height:50px;text-align:center;line-height:50px}.side-icon:hover{background-color:#ccc}.side-icon.active{background-color:#ccc}.chat-img{margin:2px 5px 2px 0px;height:28px;width:28px;border-radius:25px;float:left}.chat-msg{font-size:13px;font-family:"Ubuntu-Medium";padding:3px 0px}.chat-msg>div{padding:0px 0px 0px 34px}#sidebar{width:50px;position:absolute;left:0px;top:50px;bottom:0px;background-color:#eee;border-right:1px solid #ddd}#pullout{width:auto;background-color:#f7f7f7;border-radius:10px;padding:10px;margin-top:25px}.divchat{resize:none;height:380px;overflow-y:auto;word-wrap:break-word;padding:5px}.smiles>li>img{width:40px;height:40px;margin-top:5px;padding:5px}#chatMessage_k{border-radius:5px;font-family:"Ubuntu-Medium";color:#999;background-color:#ebebeb;box-shadow:0px 0px 0px;border:0px;font-weight:100;font-size:11px;height:40px;width:103%}#Smiles{background-color:#eb5348;border-color:#eb5348;color:#b94139;padding:8px 10px;border-radius:10px}#betAmount{width:150px;display:inline-block;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 0 0 rgba(0,0,0,.075);border:0px;background-color:#4d4d4f;color:#fff;text-align:center;font-size:13px;height:36px;border-radius:10px}.betButton.btn-danger{background-color:#d9534f;color:#fff}.betButton.btn-danger:hover{background-color:#d42f2a}.betButton.btn-inverse{background-color:#4a4a4a;color:#fff}.betButton.btn-inverse:hover{background-color:#404040;color:#fff}.betButton.btn-success{color:#fff;background-color:#5cb85c;border-color:#5cb85c}.betButton.btn-success:hover{color:#fff;background-color:#44a844;border-color:#44a844}.betButton{border:1px}.betBlock{font-family:"Ubuntu-Medium";font-size:13px}.betBlock b{font-weight:100}.flag{padding-top:15px}.avatar{border-radius:25px;margin-right:6px}.steam_name{font-family:"Ubuntu-Medium";font-weight:100}.amount{font-size:17px;font-family:"Ubuntu-Regular"}@media screen and (max-width:992px){ul.betlist{display:none}#panel11-7-b > div > button > span:nth-child(1) {display:none}#panel11-7-b > div > button > span:nth-child(2)::after {content:"1-7"}#panel8-14-b > div > button > span:nth-child(1) {display:none}#panel8-14-b > div > button > span:nth-child(2)::after {content:"8-14"}.bet-buttons>span{width:100%;display:block}#betAmount{margin:5px 0px 20px 0px;width:100%}.roulette .well{margin-top:0px!important}.roulette .well .form-group{margin-bottom:0px}#pullout{margin-top:0px}}@media screen and (max-width:1199px){.flag{padding-left:30px;padding-top:0px;width:100%}}@media screen and (max-width:968px){#pullout{width:100%;display:inline-block;position:inherit;padding-left:0px;padding-bottom:10px}.col-xs-3{width:100%}.col-xs-9{width:100%}.navbar{position:fixed;width:100%}#mainpage{width:100%;margin-left:0px!important;padding-left:15px!important;margin-bottom:0px!important;padding-top:55px!important}#sidebar{display:none}.bet-buttons>button{margin-bottom:5px}.affiliates_alert{margin-top:75px}}.link_underline{color:#7d7d7d;text-decoration:underline;font-weight:100}table{border-radius:10px}table>tbody>tr:nth-child(1)>td, table>thead>tr:nth-child(1)>th, table>tbody>tr>td, .table>tbody>tr>th {border:0px}.table-bordered>tbody>tr>td{border-top:1px solid #ccc}.panel-heading{}.navbar-default .navbar-nav>li>a:hover{text-decoration:none}.streamer>img{float:left;border-radius:25px;height:40px}.online{color:green}.streamer{text-align:left;margin:10px 0px}.streamer>div{margin-left:50px}.viewers{font-size:11px}.streamer .sname{line-height:10px;padding-bottom:10px;font-size:14px}.settings-header{padding:0px auto;width:50%;text-align:center;display:inline-block}.template{display:inline-block;width:50px;text-align:center;padding:5px;border-radius:5px;cursor:pointer}.balance{font-size:13px;background-color:#b04a43;padding:10px 20px;margin-right:5px;color:#fff;border-radius:10px}.bet-panel>.panel-heading{border-bottom-left-radius:12px;border-bottom-right-radius:12px}
</style>		
@endsection