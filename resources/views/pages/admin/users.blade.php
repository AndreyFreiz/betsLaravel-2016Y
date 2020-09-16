@extends('layout')

@section('content')
			@forelse($users as $us)
				<div class="text-center ib" style="margin-left:800px">
					<a href="manage_user/{{$us->id}}"><img src="{{$us->avatar}}" class="about-avatar" /></a>
					<h1>{{$us->username}}</h1>
					<h4><strong>Баланс: </strong>{{$us->money}} монеток</h4>
					<h4><strong>Steam ID: </strong>{{$us->steamid64}}</h4>
					<h4><strong>Дата регистрации: </strong>{{$us->created_at}}</h4>
					<h4><strong>Привелегия: </strong>@if($us->is_admin == 1) <span style="color:green">Admin</span> @elseif($us->is_admin == 5) <span style="color:red">Banned</span> @else <span>User</span> @endif</h4>
					@if($us->ref == NULL)
					<h4><strong>Приглашён: </strong><a>Не приглашён</a></h4>
					@else
					<h4><strong>Приглашён: </strong><a href="manage_user/{{$us->ref->id}}">{{$us->ref->username}}</a></h4>
					@endif
					<h4><strong>Всего поставил: </strong>{{$us->stavok}} монеток</h4>
				</div>
			@empty
			
			@endforelse	
		</div>
		<nav class="text-center">
  		<ul class="pagination">
  		@include('pagination.default', ['paginator' => $users])

	<p class="text-center"><a href="{{route('admin')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Назад</a></p>
@endsection