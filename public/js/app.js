$(document).ready(function () {
	var team1;
	var team2;
	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	number_matches();
	refOkno();

	function refOkno()
	{
		$.ajax({
			url: '/refCode',
			type: 'POST',
			dataType: 'json',
			success: function(data){
				if(data.type == 'error'){
					$('#oknoe').hide();
				}else{
					$('#oknoe').show();
				}
			},
			error: function(){

			}
		});
	}

	function number_matches()
	{
		$.ajax({
	        url: '/number_matches',
	        type: 'POST',
	        dataType: 'json',
	        success: function (data) {
	            $('.badge').text(data);
	        },
	        error: function () {
	        	console.log('Ошибка');
	        }
    	});
	}

	$(document).on('click', '#updateBalance', function () {
		$.ajax({
	        url: '/get_balance',
	        type: 'POST',
	        dataType: 'json',
	        success: function (data) {
	            $('#balance-text').text(data);
	        },
	        error: function () {
	        	console.log('Ошибка');
	        }
    	});
	});

	$(document).on('click', '#ChangeBet', function () {
		var gameids = $('#ChangeBet').data('id');
		$.ajax({
	        url: '/change_bet',
	        type: 'POST',
	        dataType: 'json',
	        data: {game: gameids},
	        success: function (data) {
	            
	        },
	        error: function () {
	        	
	        }
    	});
	});

	$(document).on('click', '#plusplus', function () {
		if(team1){
		var money = $('#betValueInput').val();
		money++
		var update = $('#valueFor1team1').text();
		var moneyUp = (money*update).toFixed(2);
		$('#betValuemultiplier1').text(moneyUp);
		$('#betValueDisplay1').text(money);
		$('#betValueInput').val(money);
		}else if(team2){
		var money = $('#betValueInput').val();
		money++
		var update = $('#valueFor1team2').text();
		var moneyUp = (money*update).toFixed(2);
		$('#betValuemultiplier2').text(moneyUp);
		$('#betValueDisplay2').text(money);
		$('#betValueInput').val(money);
		}else{

		}
	});

	$(document).on('click', '#minusminus', function () {
		if(team1){
		var money = $('#betValueInput').val();
		money--
		if(money < 1){
			money = 1;
		}
		var update = $('#valueFor1team1').text();
		var moneyUp = (money*update).toFixed(2);
		$('#betValuemultiplier1').text(moneyUp);
		$('#betValueDisplay1').text(money);
		$('#betValueInput').val(money);
		}else if(team2){
		var money = $('#betValueInput').val();
		money--
		if(money < 1){
			money = 1;
		}
		var update = $('#valueFor1team2').text();
		var moneyUp = (money*update).toFixed(2);
		$('#betValuemultiplier2').text(moneyUp);
		$('#betValueDisplay2').text(money);
		$('#betValueInput').val(money);
		}else{

		}	
	});

	$(document).on('click', '#selectedTeam1', function () {
		team1 = true;
		team2 = false;
		$('#matchTeam1').addClass('match-team');
		$('#matchTeam2').removeClass('match-team');
	});

	$(document).on('click', '#selectedTeam2', function () {
		team2 = true;
		team1 = false;
		$('#matchTeam1').removeClass('match-team');
		$('#matchTeam2').addClass('match-team');
	});

	$(document).on('click', '#placeBetButton', function () {
		if(team1){
			var money = $('#betValueInput').val();
			$.ajax({
		        url: '/new_bet',
		        type: 'POST',
		        dataType: 'json',
		        data: {team: 1, money: money, gameid: gameid},
		        success: function (data) {
		        	if(data.status == 'success'){
		            location.reload();
		        	}else{
		        		$('#warning').html('<div class="alert alert-danger" role="alert">'+data.msg+'</div>')
		        	}
		        },
		        error: function () {
		        	console.log('Ошибка');
		        }
    		});
		}else if(team2){
			var money = $('#betValueInput').val();
			$.ajax({
		        url: '/new_bet',
		        type: 'POST',
		        dataType: 'json',
		        data: {team: 2, money: money, gameid: gameid},
		        success: function (data) {
		            if(data.status == 'success'){
		            location.reload();
		        	}else{
		        		$('#warning').html('<div class="alert alert-danger" role="alert">'+data.msg+'</div>')
		        	}
		        },
		        error: function () {
		        	console.log('Ошибка');
		        }
    		});
		}else{

		}
	});
	$(document).on('click', '#redeem', function () {
		var code = $('#codek').val();
		$.ajax({
	        url: '/acceptCode',
	        type: 'POST',
	        dataType: 'json',
	        data:{code:code},
	        success: function (data) {
	            if(data.type == 'success'){
	            	location.reload()
	            }else{
	            	$('#warningRef').html('<div class="alert alert-danger" role="alert">'+data.msg+'</div>');
	            }
	        },
	        error: function () {
	        	
	        }
    	});
	});	
});