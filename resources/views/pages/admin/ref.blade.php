@extends('layout')

@section('content')
<div class="refByContainer" style="margin-left:200px; padding:50px">
<a href="{{route('add.ref.code')}}" class="btn btn-default" style="margin-left:707px"><i class="fa fa-plus"></i> Добавить</a>
	<h1>Реферальные коды</h1>
		<h3></h3>
			<table class="table table-bordered">
			<thead>
			<tr><td>Код</td><td>Пользователь</td><td>Пригласил</td><td>Сколько получает приглашённый</td><td>Всего поставили пользователи</td><td>Всего внесли вещей пользователи</td></tr>
			@forelse($users as $us)
			<tbody>
				<tr>
				<td>{{$us->ref}}</td>
				<td><a href="/admin/manage_user/{{$us->id}}">{{$us->username}}</a></td>
				<td>{{$us->priglasil}}</td>
				<td>{{$us->refMoney}}</td>
				<td>{{$us->postavili}}</td>
				<td>{{$us->vnes}}</td>
				</tr>
			</tbody>
			@empty

			@endforelse
			</table>
			<a href="{{route('admin')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a></div>
		<nav class="text-center">
  		<ul class="pagination">
  			
		</ul>
	</nav>
</div>
@endsection
@endsection