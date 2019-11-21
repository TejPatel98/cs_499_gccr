@extends('layouts/main')

@section('content')

<div class="container" style="margin-top: 20px">    
    <h4 class="float-left" style="text-align: center">Portfolio Value: $106,236.84</h4>
    <canvas id="chart-canvas" style="margin: 0 auto; padding: 10px"></canvas>
    <h5 class="float-right">Net Gain/Loss: $6,236.84</h5>
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
                <th scope="col">Symbol</th>
                <th scope="col">Purchase Date</th>
                <th scope="col">Expire Date</th>
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

<script src="{{URL('/js/chartSetup.js')}}"></script>
<script src="{{URL('/js/resultTable.js')}}"></script>

@endsection