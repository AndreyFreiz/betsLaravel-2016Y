@extends('layout')

@section('content')
<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">
			<h1><i class="fa fa-plus"></i> Добавить команду</h1>
				<label>Название:</label>
				<input type="text" class="form-control" name="date" id="names" placeholder="Na'Vi" required="">

				<label>Ссылка на лого:</label>
				<input type="text" class="form-control" name="stream" id="images" placeholder="https://upload.wikimedia.org/wikipedia/en/b/bc/NaVi_LOGO.jpg">

				<input type="submit" class="btn btn-success" style="margin-top:10px" name="submit" id="addTeam" value="Добавить">
				<a href="{{route('admin')}}" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Назад</a>
		</div>
</div>
<script>
	$(document).on('click', '#addTeam', function () {
		var name = $('#names').val();
		var logo = $('#images').val();
		$.ajax({
		    url: '/admin/addTeam',
		    type: 'POST',
		    dataType: 'json',
		    data: {name:name,logo:logo},
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