@extends('layout')

@section('content')
<style type="text/css">
	.mmmmmmmmmmm > h2, img, i
{
	display: inline-block;
}
.mmmmmmmmmmm > i, img
{
	width: 100px;
}
.mmmmmmmmmmm > h2
{
	margin-left: 10px;
	margin-right: 10px;
}
</style>

	<div class="text-center mmmmmmmmmmm" style="padding:20px">

		<h1><strong style="@if($game->winners == 1) color:green @endif">{{$game->name1}}</strong> vs <strong style="@if($game->winners == 2) color:green @endif">{{$game->name2}}</strong></h1>

		<img src="{{$game->img1}}"> 
		<h2>VS</h2> 
		<img src="{{$game->img2}}">

		<div class="progress" style="width:50%;margin-left:25%;margin-top:15px">
			<div class="progress-bar progress-bar-success" style="width:{{$game->percent1}}%"></div>
			<div class="progress-bar" style="width:{{$game->percent2}}%"></div>
		</div>

		<h3>{{$game->percent1}}% / {{$game->percent2}}%</h3>

		<h4><strong>Дата: </strong>{{$game->Time}}</h4>
		<h4><strong>Стрим: </strong>{{$game->twitchname}}</h4>
		<h4><strong>Всего ставок: </strong><a href="/admin/userBet/{{$game->id}}">{{$game->totalBet}} монеток</a> ({{$game->totalUser}} пользователей)</h4>
		<h4><strong>Победитель: </strong>{{$game->winner}}</h4>

		<h3>Действия:</h3>
			@if($game->winners !== 0 && $game->status !== 1)
			<a role="button" disabled="disabled" class="btn btn-primary"><i class="fa fa-trophy"></i> Выбрать победителя</a>
			@else
			<a role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i class="fa fa-trophy"></i> Выбрать победителя</a>
			@endif

			@if($game->twitchname == '')
			<a role="button" class="btn btn-default" data-toggle="collapse" href="#collapseExample3" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-twitch"></i> Добавить стрим</a>
			@else
			<a role="button" class="btn btn-default" data-toggle="collapse" href="#collapseExample3" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-twitch"></i> Изменить стрим</a>
			@endif

			<div class="collapse" id="collapseExample" style="margin-top:10px;font-size:20px">
				<a role="button" id="win1team">{{$game->name1}}</a> 
				или
				<a role="button" id="win2team">{{$game->name2}}</a>
			</div>

		<div class="collapse" id="collapseExample3" style="margin-top:10px;font-size:20px">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="input-group">
					<input type="text" id="streams" class="form-control" value="{{$game->twitchname}}" placeholder="cartmanzbs">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="streamid">Изменить!</button>
					</span>
				</div>
			</div>
		</div>


		<div class="col-md-12">
			<a href="{{route('admin.matches')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a>
		</div>

	</div>

	<script>
	var gameid = '{{$game->id}}';
	$(document).on('click', '#win1team', function () {
		$.ajax({
		    url: '/admin/winteam',
		    type: 'POST',
		    dataType: 'json',
		    data: {team: 1, game: gameid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		        location.reload();
		    }
    	});
	})

	$(document).on('click', '#win2team', function () {
		$.ajax({
		    url: '/admin/winteam',
		    type: 'POST',
		    dataType: 'json',
		    data: {team: 2, game: gameid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		       	location.reload();
		    }
    	});
	})

	$(document).on('click', '#streamid', function () {
		var stream = $('#streams').val();
		$.ajax({
		    url: '/admin/stream',
		    type: 'POST',
		    dataType: 'json',
		    data: {stream:stream, game: gameid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		        location.reload();	
		    }
    	});
	})

	</script>
@endsection