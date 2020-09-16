@extends('layout')

@section('content')
<div class="error-page">
		<h2 style="color:red"> Banned!</h2>
		<div class="error-content">
			<h3><i class="fa fa-ban" style="color:red"></i> Ваш аккаунт забанен администратором.</h3>
			<p>
				Вы больше не можете играть на нашем сайте.
				Если вы были забанены по ошибке, <a href="mail:sobol.toni.oo@gmail.com">свяжитесь с нами</a>.
			</p>
		</div>
	</div>
</html>
@endsection    