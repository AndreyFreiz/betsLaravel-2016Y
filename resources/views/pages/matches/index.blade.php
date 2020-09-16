@extends('layout')

@section('content')
<div class="row" style="padding:15px; margin-left:200px">

	<div class="col-md-6">
		<h1>
			<i class="fa fa-arrow-up"></i>
			Следующие матчи
		</h1>
		@forelse($matches as $m)
		<div class="match-container">
			<span>{{$m->date}}</span>
			@if($m->live == 'neok' && $m->status == 1)
			<strong style="color:green">LIVE</strong>
			@else

			@endif

			<a href="/matchid/{{$m->id}}">

				<div class="match">

					<span class="match-box">
						<span class="match-team">{{$m->name1}}</span>
						<span class="match-percent">{{$m->percent1}}%</span>
					</span>

					<img src="{{$m->img1}}" class="match-logo" />

					<span class="match-vs">vs</span>

					<img src="{{$m->img2}}" class="match-logo" />

					<span class="match-box">
						<span class="match-team">{{$m->name2}}</span>
						<span class="match-percent">{{$m->percent2}}%</span>
					</span>
	            
				</div>

			</a>
		</div>
		@empty
		<h3>Нет матчей</h3>
		@endforelse
	</div>

	<div class="col-md-6">
		<h1>
			<i class="fa fa-arrow-down"></i>
			Прошедшие матчи
		</h1>
		@forelse($matchesOver as $mo)
		<div class="match-container">
			<span>{{$mo->date}}</span>

			<a href="/matchid/{{$mo->id}}">

				<div class="match match-over">
				@if($mo->win == 1)
					(Win)
				@endif	
					<span class="match-box">
						<span class="match-team">{{$mo->name1}}</span>
						<span class="match-percent">{{$mo->percent1}}%</span>
					</span>

					<img src="{{$mo->img1}}" class="match-logo" />

					<span class="match-vs">vs</span>

					<img src="{{$mo->img2}}" class="match-logo" />

					<span class="match-box">
						<span class="match-team">{{$mo->name2}}</span>
						<span class="match-percent">{{$mo->percent2}}%</span>
					</span>
					
				@if($mo->win == 2)
					(Win)
				@endif	

				</div>

			</a>
		</div>
		@empty
		<h3>Нет матчей</h3>
		@endforelse
	</div>

</div>
@endsection