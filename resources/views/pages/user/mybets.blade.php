@extends('layout')

@section('content')
<div class="row" style="margin:15px; margin-left:200px;">
	<div class="col-md-6">
		<h1>Актуальные ставки</h1>
			@forelse($igri as $i)
			<div class="match-container">
				<span>{{$i->date}} CET</span>
						@if($i->status == 0)
						<span class="change-bet"><a id="ChangeBet" data-id="{{$i->id}}" style="cursor:pointer;">Сменить ставку</a></span>
						@else
						<strong style="color:green">LIVE</strong>
						@endif
							<a href="/matchid/{{$i->id}}">
								<div class="match">
			@if($i->yourtype == 1)
				<p>(Поставили)</p>
			@endif
										<span class="match-box">
										<span @if($i->yourtype == 1)class="match-team" @endif>{{$i->name1}}</span>
										<span class="match-percent">{{$i->percent1}}%</span>
										</span>
									<img src="{{$i->img1}}" class="match-logo" />
								<span class="match-vs">vs</span>
						<img src="{{$i->img2}}" class="match-logo" />
					<span class="match-box">
				<span @if($i->yourtype == 2)class="match-team" @endif>{{$i->name2}}</span>
			<span class="match-percent">{{$i->percent2}}'%</span>
		</span>
			@if($i->yourtype == 2)
				<p>(Поставили)</p>
			@endif
			</div>
				</a>
					</div>	
				<p><strong>Bet value: </strong>{{$i->betValue}} coins</p>
			<p><strong>Potential reward: </strong> {{$i->potencial}} coins</p>
	@empty	
				Ставок нет... <a href="{{route('matches')}}">Нажмите сюда</a>, чтобы поставить		
	@endforelse	
	</div>
	<div class="col-md-6">

		<h1>История ставок</h1>
		@forelse($igriHistory as $is)
		<table class="table table-condensed text-center">
			<tbody>
						<tr>
						@if($is->win == 0) 
						<td class="lost"></td>
						@else
						<td class="won"></td>
						@endif
							<td><a href="/matchid/{{$is->id}}">{{$is->name1}} vs {{$is->name2}}</a></td>
							<td>{{$is->date}}</td>
							@if($is->win == 0) 
							<td><span style="color:red">-{{$is->price}}</span></td>
							@else
							<td><span style="color:green">+{{$is->potencial}}</span></td>
							@endif
						</tr>
			</tbody> 
		</table>
		@empty

		@endforelse

	</div>
	
</div>
@endsection