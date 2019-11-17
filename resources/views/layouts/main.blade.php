<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Don Fishback</title>

		<!-- Favicon -->
		<link rel="shortcut icon" href="{{URL('/images/ODDS-online-icon.png')}}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

		<!-- May delete this starting stylesheet -->
		<!-- <link rel="stylesheet" href="css/app.css"> -->

        <script src="js/app.js" charset="utf-8"></script>

		<!-- Bootstrap core CSS -->
		<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

		<!-- Chart.js CDN -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
		<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-crosshair@1.1.2"></script> -->

		<!-- MomentJS CDN -->
		<script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/moment.min.js" type="text/javascript"></script>

    </head>
    <body>
		
		<div>
			@include('layouts/navbar')
		</div>

        <div class="flex-center position-ref full-height">
	    	@yield('content')
	    </div>
    </body>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</html>
