@extends('layout')

@section('content')
<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">
			<h1><i class="fa fa-plus"></i> Добавить реферальный код</h1>
				<label>Название:</label>
				<input type="text" class="form-control" name="date" id="codesa" placeholder="BONUS" required="">

				<label>Сколько получит пользователь за код:</label>
				<input type="text" class="form-control" name="stream" id="money" placeholder="100">

				<label>Имя пользователя к которому привязать код (Должно быть все в точности):</label>
				<input type="text" class="form-control" name="stream" id="username" placeholder="ToXaHo">

				<input type="submit" class="btn btn-success" style="margin-top:10px" name="submit" id="addCode" value="Добавить">
				<br>
				<div id="warningRefs"></div>

				<a href="{{route('admin')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a>
		</div>
</div>
<script>
	$(document).on('click', '#addCode', function () {
		var codesa = $('#codesa').val();
		var money = $('#money').val();
		var username = $('#username').val();
		$.ajax({
		    url: '/admin/addCode',
		    type: 'POST',
		    dataType: 'json',
		    data: {code:codesa,money:money,username:username},
		    success: function (data) {
		    	if(data.type == 'ok'){
		    		location.reload();
		    	}else{
		    		$('#warningRefs').html('<div class="alert alert-danger" role="alert">'+data.msg+'</div>');
		    	}
		    },
		    error: function () {
		        location.reload();
		    }
    	});
	})
	</script>
@endsection