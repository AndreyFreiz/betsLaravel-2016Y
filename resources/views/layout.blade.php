<!doctype html>
<html class="no-js ru" lang="ru">
	<head>
	    <meta charset="utf-8"/>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	    <title> BETS by freisent</title>
	    <meta name="keywords" content="ставки на киберспорт"/>
	    <meta name="description" content="Rezak.pw. Ставки на киберспорт"/>
	    <meta name="csrf-token" content="{!!  csrf_token()   !!}">
	    <!-- 					Стиль 							-->
	    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
	    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
	    <link href="{{asset('css/bootstrap-theme.css')}}" rel="stylesheet">
	    <link href="{{asset('css/bootstrap-theme.min.css')}}" rel="stylesheet">
	    <link href="{{asset('css/cosmo.css')}}" rel="stylesheet">
		<link href="{{asset('css/style.css')}}" rel="stylesheet">
		<link href="{{asset('css/home.css')}}" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" /> 
 		<script src="{{asset('js/jquery.js')}}"></script>
 		<script src="{{asset('js/jstz.js')}}"></script>
 		<script src="{{asset('js/jstz.min.js')}}"></script>
  		<script src="{{asset('js/bootstrap.min.js')}}"></script>
  		<script type="text/javascript" src="{{asset('/js/jquery.dataTables.min.js?v=222')}}"></script>	
  		<script type="text/javascript" src="{{asset('/js/tinysort.js')}}"></script>	
	    <!-- 					Стиль 							-->
	    <script src="{{asset('js/app.js')}}"></script>
	</head>
<!-- TOP NAVBAR -->
	@if(!Auth::guest())
		@if($u->is_admin == 5)
		<script>
		if(window.location.href !== "http://185.46.8.115/banned"){
			location.replace("/banned");
		}else{

		}
		</script>
		@else

		@endif
	@else

	@endif

	@if(Auth::guest())
	<script>var steamid = '0'</script>
	@else
	<script>var steamid = '{{$u->steamid64}}'</script>
	@endif
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand logoa logob" href="/">
					Rezak.pw
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					@if(Auth::guest())
							<li>
								<a href="{{route('login')}}" id="loginA">
									<i class="fa fa-steam-square" style="color:#000"></i> Войти через Steam
								</a>
							</li>
					@else
							@if($u->is_admin == 1)
								<li><a href="{{route('admin')}}"><i class="fa fa-lock"></i> Админ панель</a></li>
							@else
							
							@endif	
							<span id="balanceContainer"><span class="navbar-text" id="balance"><strong>Баланс:</strong> <span id="balance-text">{{$u->money}}</span> <i class="fa fa-refresh refreshBalance" id="updateBalance"></i></span></span>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<img style="width:20px;height:20px;" src="{{$u->avatar}}">
										{{$u->username}}
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										@if($u->is_admin == 1)
											<li><a href="{{route('admin')}}"><i class="fa fa-lock"></i> Админ панель</a></li>
										@else
							
										@endif	
										<li><a href="{{route('my.bets')}}"><i class="fa fa-pie-chart"></i> Мои ставки</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="{{route('logout')}}" id="logoutA"><i class="fa fa-sign-out"></i> Выйти</a></li>
									</ul>
								</li>
					@endif			
				</ul>
			</div>
		</div>
	</nav>	
<!-- TOP NAVBAR -->

<!-- LEFT NAVBAR -->
	<nav class="navbar2 navbar navbar-default logob kkk" role="navigation" style="height:1500px;float:left;border-right:1px solid #E7E7E7">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse2 navbar-collapse navbar-ex1-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav2 navbar-nav">

				<li>
					<a href="{{route('index')}}">
						<i class="fa fa-home menu-icon" style="font-size:18px;color:#333333;"></i> Главная
					</a>
				</li>

				<li>
					<a href="{{route('matches')}}">
						<i class="fa fa fa-gamepad menu-icon" style="font-size:18px;color:#333333;"></i> Матчи
						<span class="badge" style="float:right">...</span>
					</a>
				</li>

				<li class="line"></li>

				<li>
					<a href="{{route('deposit')}}">
						<i class="fa fa-plus-circle menu-icon" style="color:#5FAE63;font-size:18px;"></i> Депозит
					</a>
				</li>
				<li>
					<a href="{{route('withdraw')}}">
						<i class="fa fa-minus-square menu-icon" style="color:#BC5652;font-size:18px;"></i> Вывод
					</a>
				</li>

				<li>
					<a href="#" data-toggle="modal" data-target="#myModal" id="oknoe">
						<i class="fa fa-users menu-icon" style="color:#4A87F2;font-size:18px;"></i> Free Coins
					</a>
				</li>

				<li class="line"></li>

				<li>
					<a href="{{route('contact')}}">
						<i class="fa fa-envelope-o menu-icon" style="font-size:18px;color:#333333;"></i> Контакты
					</a>
				</li>

				<li>
					<a href="{{route('about')}}">
						<i class="fa fa-question-circle menu-icon" style="font-size:18px;color:#333333;"></i> О сайте
					</a>
				</li>

				<li class="line"></li>

				<!-- STEAM STATUS -->
				<div class="steam-status">
					<p>
						<span style="font-weight:bold">
							Steam статус
							<i class="fa fa-question-circle menu-icon" style="font-size:18px;float:right" data-toggle="tooltip" data-placement="bottom" title="Если Steam Offline, то могут возникнуть проблемы с вводом и выводом предметов с сайта"></i>
						</span>
						<br>
						@if($status == 'good' || 'normal')
								<span style="color:green">Online</span>
						@elseif($status == 'bad')		
								<span style="color:red">Offline</span>
						@endif		
					</p>
				</div>

				<li class="line"></li>

				<!-- FOOTER -->
				<footer>
					<div class="footer">
						<p>© Coded by ToXaHo</p>
						<p>Powered by Steam</p>
					</div>
				</footer>
			</ul>
		</div><!-- /.navbar-collapse -->
	</nav>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Введите код чтобы получить монетки</h4>
				</div>
				<div class="modal-body">
					<input type="text" placeholder="Code..." class="form-control" id="codek" required>
					<p id="redeemResult"></p>
				</div>
				<div class="modal-footer" style="margin-right:230px">
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					<button type="button" class="btn btn-primary" id="redeem">Ввести</button>
				</div>
			</div>
			<br>
			<div id="warningRef"></div>
		</div>
	</div>
	@yield('content')
	<!-- Free coins Modal -->
</html>    