@extends('layouts/main')

@section('content')

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
  {
  "symbols": [
    {
      "proName": "OANDA:SPX500USD",
      "title": "S&P 500"
    },
    {
      "proName": "OANDA:NAS100USD",
      "title": "Nasdaq 100"
    },
    {
      "proName": "FX_IDC:EURUSD",
      "title": "EUR/USD"
    },
    {
      "proName": "BITSTAMP:BTCUSD",
      "title": "BTC/USD"
    },
    {
      "proName": "BITSTAMP:ETHUSD",
      "title": "ETH/USD"
    }
  ],
  "colorTheme": "dark",
  "isTransparent": false,
  "displayMode": "adaptive",
  "locale": "en"
}
  </script>
</div>
<!-- TradingView Widget END -->

<div class="container" style="margin-top:50px;">
	<div class="card">
		<div class="card-header">
			<h1>Portfolio Setup</h1>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					{!! Form::open(array('url' => '/stock/submit', 'id' => 'portfolioSetup')) !!}

					<div class="row">
						<div class="form-group col-sm">
							{!! Form::label('strategy', 'Strategy') !!}
							{!! Form::select('strategy', array('strategy_call' => 'Buy Call', 'strategy_put' => 'Buy Put'), '', ['class' => 'form-control']) !!}
						</div>

						<div class="form-group col-sm">
							{!! Form::label('scan', 'Scan') !!}
							{!! Form::select('scan', array('scan_one' => 'Scan One', 'scan_two' => 'Scan Two', 'scan_three' => 'Scan Three'), '', ['class' => 'form-control']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm">
							{!! Form::label('principle', 'Principle Amount ($)') !!}
							{!! Form::number('principle', '', ['class' => 'form-control']) !!}
						</div>

						<div class="form-group col-sm">
							{!! Form::label('investmentPercent', 'Investment Percent (%)') !!}
							{!! Form::number('investmentPercent', '', ['class' => 'form-control', 'max' => '100,m']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm">
							<label for="startDate">Start Date</label>
							<input type="date" id="startDate" name="startDate" class="form-control" min="2001-1-1" value="{{date('Y-m-d')}}">
						</div>
						<div class="form-group col-sm">
							<label for="endDate">End Date</label>
							<input type="date" id="endDate" name="endDate" class="form-control" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
						</div>
					</div>

					<button class="btn btn-lg btn-success" type="submit" style="margin-top:20px">Submit</button>

					{!! Form::close() !!}

					<div id="errorMessage" class="alert alert-danger invisible" style="margin-top:20px; text-align:center;" role="alert">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var removeInvalidClass = function(){
		this.classList.remove("is-invalid");
	}
	
	var validate = function(event){
		var err = "";
		var alert = document.getElementById('errorMessage');
		var principle = this.principle;
		var investment = this.investmentPercent;
		var start = this.startDate;
		var end = this.endDate;

		if (principle.value == ""){
			err += "* Principle Amount required <br>";
			principle.classList.add('is-invalid');
			principle.addEventListener("change", removeInvalidClass);
		}
		if (investment.value == ""){
			err += "* Investment Percent required <br>";
			investment.classList.add('is-invalid');
			investment.addEventListener("change", removeInvalidClass);
		}
		if (start.value >= end.value){	
			err += "* Start date must be before end date <br>";
			start.classList.add('is-invalid');
			start.addEventListener("change", removeInvalidClass);
		}

		if(err == "")
			return;
		
		alert.innerHTML = err;
		alert.classList.remove('invisible');
		event.preventDefault();
	}

	var form = document.getElementById("portfolioSetup");
	form.addEventListener("submit", validate, true);

	
</script>


@endsection
