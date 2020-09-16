@extends('layout')

@section('content')
	<div class="a-parent" style="margin-left:200px">
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-users fa-4x"></i>
			<h1>Пользователи</h1>
			<small>Управляйте пользователями - баньте, давайте должность администратора, изменяйте баланс</small>
			<a href="{{route('admin.users')}}" class="btn btn-default">Перейти</a>
		</div>
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-gamepad fa-4x"></i>
			<h1>Матчи</h1>
			<small>Управляйте матчами</small>
			<a href="{{route('admin.matches')}}" class="btn btn-default">Перейти</a>
		</div>
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-bar-chart fa-4x"></i>
			<h1>Команды</h1>
			<small>Добавить команду</small>
			<a href="{{route('admin.addTeam')}}" class="btn btn-default">Перейти</a>
		</div>
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-bar-chart fa-4x"></i>
			<h1>Реферальная система</h1>
			<small>Редактировать / добавить реферальный код</small>
			<a href="{{route('admin.ref')}}" class="btn btn-default">Перейти</a>
		</div>
	</div>
@endsection