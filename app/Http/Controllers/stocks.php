<?php

namespace App\Http\Controllers;

use App\Exceptions\FSSNotFoundException;
use App\Exceptions\InvalidDateFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class stocks extends Controller
{
	public function submitSelection(Request $request)
	{
		$invAmount = $request->input('principal') * ($request->input('investment_percent') / 100);

		$amountPerStock = $invAmount / 5;

	    $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

	    $dateResults = DB::connection('ovs')->select("SELECT * FROM `ovscalendar` where calType = '2' and date_ BETWEEN ? and ? order by date_ asc", [$startDate, $endDate]);

		try
		{
			$stockResults = \FSSCLE::VolitilityHVIVDifference($request->input('start_date'));

		    dd($stockResults);
		}
		catch(FSSNotFoundException $e)
		{
			echo $e->getMessage();
		}
        catch(InvalidDateFormat $e)
        {
            echo $e->getMessage();
        }
	}
}
