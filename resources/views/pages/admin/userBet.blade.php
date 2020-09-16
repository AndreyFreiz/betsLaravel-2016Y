@extends('layout')

@section('content')
<div class="refByContainer" style="margin-left:200px; padding:50px">
		<h1>Пользователи, кто поставил на  <b>{{$game->name1}}</b> vs <b>{{$game->name2}}</b></h1>
		<h3>{{$game->totalBet}} монеток ({{$game->users}} пользователей)</h3>
			<table class="table table-bordered">
			<thead>
			<tr><td>Steam64 ID</td><td>Команда</td><td>Поставил</td></tr>
			<tbody>
			@foreach($bets as $b)
				<tr>
				<td><a href="/admin/manage_user/{{$b->id}}">{{$b->steamid64}}</a></td>
				<td>{{$b->name}}</td>
				<td>{{$b->price}}</td>
				</tr>
			@endforeach
			</tbody>
			</table>
			<a href="/admin/manage_match/{{$game->id}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back</a></div>
		<nav class="text-center">
  		<ul class="pagination">
  			@include('pagination.default', ['paginator' => $bets])
		</ul>
		</nav>
</div>
@endsection