@extends('layout')

@section('content')
<div class="row" style="margin-left:200px">

		<div class="col-md-6 a-m-l">
			<h1>
				<i class="fa fa-arrow-up"></i>
				Будующие матчи
				<a href="{{route('add.match')}}" class="btn btn-default" style="float:right"><i class="fa fa-plus"></i> Добавить</a>
			</h1>
			@forelse($matches as $m)
								<div class="match-container">
									<span>{{$m->date}}</span>
									@if($m->live == 'neok' && $m->status == 1)
									<strong style="color:green">LIVE</strong>
									@else

									@endif
									<a href="/admin/manage_match/{{$m->id}}">
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
			
			@endforelse					
		</div>

		<div class="col-md-6 a-m-r">
			<h1>
				<i class="fa fa-arrow-down"></i>
				Live матчи
			</h1>
			@forelse($matchesOver as $mo)
								<div class="match-container">
									<span>{{$mo->date}} <strong style="color:green">LIVE</strong></span>
									<a href="/admin/manage_match/{{$mo->id}}">
										<div class="match">
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
            
										</div>
									</a>
								</div>
			@empty
			
			@endforelse					
		</div>

	</div>

	<p class="text-center"><a href="{{route('admin')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Назад</a></p>
@endsection