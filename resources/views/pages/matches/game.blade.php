@extends('layout')

@section('content')
<script>
var gameid = '{{$game->id}}';
</script>
<div class="row" style="padding:15px; margin-left:200px">
	<div class="col-md-6">
		<div class="match-container">
			<span>{{$game->date}} 
			@if($game->live == 'neok' && $game->status == '1')
			<strong style="color:green">LIVE</strong>
			@else

			@endif
			</span>

			<div class="match">
			@if($yourtype == 1 && $winner == 0)
				<p>(Поставили)</p>
			@endif
			@if($winner == 1)
				<p>(WINNER)</p>
			@endif	
				<span class="match-box"  id="selectedTeam1">
					<span  id="matchTeam1">{{$game->name1}}</span>
					<span class="match-percent">{{$game->percent1}}%</span>
				</span>

				<img src="{{$game->img1}}" id="selectedTeam1" class="match-logo" />

				<span class="match-vs">vs</span>

				<img src="{{$game->img2}}" id="selectedTeam2" class="match-logo" />

			@if($winner == 2)
				<p>(WINNER)</p>
			@endif	
				
				<span class="match-box"  id="selectedTeam2">
					<span  id="matchTeam2">{{$game->name2}}</span>
					<span class="match-percent">{{$game->percent2}}%</span>
				</span>

			@if($yourtype == 2 && $winner == 0)
				<p>(Поставили)</p>	
			@endif
            
			</div>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width:{{$game->percent1}}%"></div>
			<div class="progress-bar" style="width:{{$game->percent2}}%"></div>
		</div>

		<p class="potentialReward">Потенциальный выигрыш:</p>
		@if($myBets = 'neok')
		<div class="rewardLeft">
			<p><span id="valueFor1team1">{{$valueFor1team1}}</span> за 1</p>
			@if($ok1 == 'neok')
			<p><span id="betValuemultiplier1">0</span> за <span id="betValueDisplay1">0</span></p>
			@else
			<p><span id="betValuemultiplier1">{{$price * $valueFor1team1}}</span> за <span id="betValueDisplay1">{{$price}}</span></p>
			@endif
		</div>
		<div class="rewardRight">
			<p><span id="valueFor1team2">{{$valueFor1team2}}</span> for 1</p>
			@if($ok2 == 'neok')
			<p><span id="betValuemultiplier2">0</span> за <span id="betValueDisplay2">0</span></p>
			@else
			<p><span id="betValuemultiplier2">{{$price * $valueFor1team2}}</span> за <span id="betValueDisplay2">{{$price}}</span></p>
			@endif
		</div>
		@else
		<div class="rewardLeft">
			<p><span id="valueFor1team1">{{$valueFor1team1}}</span> за 1</p>
			<p><span id="betValuemultiplier1">0</span> за <span id="betValueDisplay1">0</span></p>
		</div>
		<div class="rewardRight">
			<p><span id="valueFor1team2">{{$valueFor1team2}}</span> for 1</p>
			<p><span id="betValuemultiplier2">0</span> за <span id="betValueDisplay2">0</span></p>
		</div>
		@endif
		@if(!Auth::guest())
		<div class="input-group" style="width:100%">
			<span class="input-group-btn">
				<button type="button" class="btn btn-default btn-number" data-type="minus" data-field="quant[1]" id="minusminus">
					<span class="glyphicon glyphicon-minus"></span>
				</button>
			</span>
			<input type="text" name="quant[1]" id="betValueInput" class="form-control input-number" value="1" min="1" max="{{$u->balance}}">
			<span class="input-group-btn">
				<button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[1]" id="plusplus">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
			</span>
		</div>
		@else

		@endif
		@if(Auth::guest())
			<h2 style="margin-top:50px"Пожалуйста, войдите чтобы поставить.</h2>
		@else
		<button class="btn btn-primary btn-block" id="placeBetButton" style="margin-top:15px">Поставить</button>
		<br>
		<div id="warning"></div>
		@endif
		@if($game->live == 'neok' && $game->twitchname !== '' && $winner == 0)
		<h2 style="margin-top:50px;">Stream:</h2>
			<div class="stream-container"><iframe src="//player.twitch.tv/?channel={{$game->twitchname}}&amp;autoplay=false" height="400" width="100%" frameborder="0" scrolling="no" allowfullscreen=""></iframe></div>
		@elseif($game->live == 'neok')
		<h2 style="margin-top:50px;">Stream:</h2>	
			<small>Увы, нет трансляции для этого матча</small>
		@endif	
	</div>

	<div class="col-md-6">
		@if($yourtype !== 0)
		<div class="alert alert-info" role="alert">Вы уже сделали ставку на этот матч. Перейдите в  <a href="{{route('my.bets')}}" class="alert-link">ваши ставки</a> и измените её (если нужно).</div>
		@endif
		<div id="placeBetAlerts"></div>
		<h1>Как поставить?</h1>
		<small>Нажмите на имя или иконку нужной команды, выберите сумму и нажмите "Поставить".</small>

		<h1>Где будет мой выигрыш?</h1>
		<small>Ваш баланс сразу увеличится, когда закончится матч, если ваша команда выиграла, конечно.</small>

		<h1>Могу ли я увидеть свою историю ставок?</h1>
		<small>Конечно. Нажмите на ваш ник и выберете "Мои ставки".</small>

		<h1>Где я могу получить более подробную информацию о матче?</h1>
		<small>Мы рекомендуем <a href="http://hltv.org/" target="_blank">hltv.org</a> для информаций матча.</small>
	</div>
		
</div>
@endsection