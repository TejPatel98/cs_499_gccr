@extends('layouts/main')

@section('content')

<div class="container" style="margin-top: 20px;">
    <h3 style="text-align: center">Portfolio Performance</h3>
    <canvas id="chart-canvas" style="margin: 0 auto; padding: 10px"></canvas>
    <div class="row" style="border-bottom: 2px solid black">
        <div class="col">
            <h4 id="endPortfolioValue"></h4>
        </div>
        <div class="col text-center">
            <button type="button" style="margin-bottom:5px; margin-top:0px" class="btn btn-primary" onclick='downloadCSV({ filename: "portfolioResults.csv" });'>Download Results</button>
        </div>
        <div class="col" style="text-align: right">
            <h4 id="netGainLoss"style="display:inline"></h4>
            <h4 id="netGainLossValue" style="display:inline"></h4>
        </div>
    </div>
    <br>
</div>


<div class="container" style="margin-top: 40px">
    <h5>
        <span id="tableDate"></span>
        <span id="tableCash" class="float-right"></span>
    </h5>
    <div class="table-responsive rounded">
        <table id="resultTable" class="table invisible">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Ticker</th>
                <th scope="col">Purchase Date</th>
                <th scope="col">Expire</th>
                <th scope="col">Strike</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Number of Contracts</th>
                <th scope="col">Current Price</th>
                <th scope="col">Current Value</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script> var results = @json($results);  console.log(results); </script>
<script src="{{URL('/js/chartSetup.js')}}"></script>
<script src="{{URL('/js/resultTable.js')}}"></script>
<script src="{{URL('/js/downloadData.js')}}"></script>


@endsection
