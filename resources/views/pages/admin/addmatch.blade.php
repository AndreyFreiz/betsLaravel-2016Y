@extends('layout')

@section('content')
<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">

			<h1><i class="fa fa-plus"></i> Match adding</h1>

				<label>Команда 1:</label>
				<select id="teams1" class="form-control" name="team1"">
				@foreach($teams as $t)
				<option data-id="{{$t->id}}">{{$t->name}}</option>
				@endforeach
				</select>
				<label>Команда 2:</label>
				<select id="teams2" class="form-control" name="team2">
				@foreach($teams as $t)
				<option data-id="{{$t->id}}">{{$t->name}}</option>
				@endforeach
				</select>
				<label>Дата (CET):</label>
				<input type="text" class="form-control" name="date" id="dates" placeholder="2016-01-26 19:25:00" required="">

				<label>Название стрима (не обязательно):</label>
				<input type="text" class="form-control" name="stream" id="streames" placeholder="cartmanzbs">

				<input type="submit" class="btn btn-success" style="margin-top:10px" name="submit" id="addMatch" value="Добавить">
				<a href="{{route('admin.matches')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a>


			
		</div>

	</div>

	<script>
	$(document).on('click', '#addMatch', function () {
		var team1 = $('#teams1').val();
		var team2 = $('#teams2').val();
		var date = $('#dates').val();
		var stream = $('#streames').val();
		$.ajax({
		    url: '/admin/addMatch',
		    type: 'POST',
		    dataType: 'json',
		    data: {team1:team1,team2:team2,date:date,stream:stream},
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