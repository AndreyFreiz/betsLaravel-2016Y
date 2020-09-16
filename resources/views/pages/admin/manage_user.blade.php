@extends('layout')

@section('content')
					<span>user</span>
					<span style="color:green">Admin</span>
					<span style="color:red">Banned</span>

	<div class="text-center">

		<a target="_blank" href="http://steamcommunity.com/profiles/{{$user->steamid64}}>"><img src="{{$user->avatar}}" class="about-avatar" /></a>

		<h1>{{$user->username}}</h1>

					<h4><strong>Баланс: </strong>{{$user->money}} монеток</h4>
					<h4><strong>Steam ID: </strong>{{$user->steamid64}}</h4>
					<h4><strong>Дата регистрации: </strong>{{$user->created_at}}</h4>
					<h4><strong>Привелегия: </strong>@if($user->is_admin == 1) <span style="color:green">Admin</span> @elseif($user->is_admin == 5) <span style="color:red">Banned</span> @else <span>user</span> @endif</h4>
					@if($user->ref == NULL)
					<h4><strong>Приглашён: </strong><a>Не приглашён</a></h4>
					@else
					<h4><strong>Приглашён: </strong><a href="manage_user/{{$user->ref->id}}">{{$user->ref->username}}</a></h4>
					@endif
					<h4><strong>Пригласил людей: </strong><a>{{$user->priglasil}}</a></h4>
					<h4><strong>Всего поставил: </strong>{{$user->stavok}} монеток</h4>

		<h3>Действия:</h3>
			@if($user->is_admin != 5)
			<a class="btn btn-danger" id="Bans"><i class="fa fa-ban"></i> Забанить</a>
			@else
			<a class="btn btn-success" id="Unban"><i class="fa fa-check"></i> Разбанить</a>
			@endif

			@if($user->is_admin != 1)
			<a class="btn btn-primary" id="Set"><i class="fa fa-unlock"></i> Дать админа</a>
			@else
			<a class="btn btn-info" id="Unset"><i class="fa fa-lock"></i> Забрать админа</a>
			@endif

		<a role="button" class="btn btn-primary" id="Setbalance"><i class="fa fa-diamond"></i> Изменить баланс</a>
		<div id="setBalanceDisplay">
			<div class="input-group" style="width:15%;margin-left:auto;margin-right:auto">
					<input type="text" class="form-control" value="{{$user->money}}" placeholder="New balance..." id="balances" required />
					<span class="input-group-btn">
						<input type="submit" name="submit" class="btn btn-default" id="newBalance" value="Изменить" />
					</span>
			</div>
		</div>


		<br><a href="{{route('admin.users')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a>

	</div>

	<script>
	var userid = '{{$user->id}}';
	var showBalance = false;
	$(document).on('click', '#Bans', function () {
		$.ajax({
		    url: '/admin/manage_user',
		    type: 'POST',
		    dataType: 'json',
		    data: {action: 2, user: userid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		        location.reload();		
		    }
    	});
	})

	$(document).on('click', '#Unban', function () {
		$.ajax({
		    url: '/admin/manage_user',
		    type: 'POST',
		    dataType: 'json',
		    data: {action: 3, user: userid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		      	location.reload();  	
		    }
    	});
	})

	$(document).on('click', '#Set', function () {
		$.ajax({
		    url: '/admin/manage_user',
		    type: 'POST',
		    dataType: 'json',
		    data: {action: 4, user: userid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
				location.reload();		        	
		    }
    	});
	})
		
	$(document).on('click', '#Unset', function () {
		$.ajax({
		    url: '/admin/manage_user',
		    type: 'POST',
		    dataType: 'json',
		    data: {action: 5, user: userid},
		    success: function (data) {
		    	location.reload();
		    },
		    error: function () {
		     	location.reload();   	
		    }
    	});
	})

	$(document).on('click', '#Setbalance', function () {
		if(!showBalance){
		$('#setBalanceDisplay').show();
		showBalance = true;
		}else{
		$('#setBalanceDisplay').hide();
		showBalance = false;	
		}
	})

	$(document).on('click', '#newBalance', function () {
		var balance = $('#balances').val();
		$.ajax({
		    url: '/admin/manage_user',
		    type: 'POST',
		    dataType: 'json',
		    data: {action: 1, balance: balance, user: userid},
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