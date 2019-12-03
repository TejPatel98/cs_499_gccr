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

<div class="container" style="margin-top: 50px; margin-bottom: 50px">
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
							{!! Form::select('scan', array('VolitilityHVIVDifference' => 'Volatility HV-IV Difference', 'TermLTSTDifference' => 'Term LT-ST Difference'), '', ['class' => 'form-control']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm">
							{!! Form::label('principle', 'Principle Amount ($)') !!}
							{!! Form::number('principle', '', ['class' => 'form-control']) !!}
						</div>

						<div class="form-group col-sm">
							{!! Form::label('investmentPercent', 'Allocation Amount (%)') !!}
							{!! Form::number('investmentPercent', '', ['class' => 'form-control', 'max' => '100', 'min' => '1']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col">
							{!! Form::label('maxTradesPerDay', 'Maximum Number of Trades per Day') !!}
							{!! Form::number('maxTradesPerDay', '', ['class' => 'form-control', 'max' => '15', 'min' => '1']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col">
							{!! Form::label('minTradeLength', 'Minimum Trading Length (Calendar Days)') !!}
							{!! Form::number('minTradeLength', '', ['class' => 'form-control', 'min' => '1']) !!}
						</div>
						<div class="form-group col">
							{!! Form::label('maxTradeLength', 'Maximum Trading Length (Calendar Days)') !!}
							{!! Form::number('maxTradeLength', '', ['class' => 'form-control', 'min' => '1']) !!}
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm">
							<label for="startDate">Start Date</label>
							<input type="date" id="startDate" name="startDate" class="form-control" min="2015-02-01" value="{{date('Y-m-d')}}">
						</div>
						<div class="form-group col-sm">
							<label for="endDate">End Date</label>
							<input type="date" id="endDate" name="endDate" class="form-control" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
						</div>
					</div>

					<button class="btn btn-lg btn-success" type="submit" style="margin-top:20px">Submit</button>

					{!! Form::close() !!}

					<div id="errorMessage" class="alert alert-danger" style="margin-top:20px; text-align:center; display: none" role="alert">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="{{URL('/js/portfolioSetup.js')}}"></script>

@endsection
