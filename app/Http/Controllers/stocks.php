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
		$invAmount = $request->input('principle') * ($request->input('investmentPercent') / 100);

		$amountPerStock = $invAmount / 5;

	    $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

	    $dateResults = DB::connection('ovs')->select("SELECT * FROM `ovscalendar` where calType = '2' and date_ BETWEEN ? and ? order by date_ asc", [$startDate, $endDate]);

		try
		{
			$stockResults = \FSSCLE::VolitilityHVIVDifference($request->input('startDate'));
		}
		catch(FSSNotFoundException $e)
		{
			echo $e->getMessage();
		}
        catch(InvalidDateFormat $e)
        {
            echo $e->getMessage();
        }

		$date = $request->input('startDate');

		$optionsFound = 0;
		$counter = 0;

		while($optionsFound < 5 && $counter < count($stockResults)) 
		{
			// You cannot use named bindings multiple times in the same query.
			// There must be an individual named binding for each spot.
			if($amountPerStock > $stockResults[$counter]["price"])
			{
				$arguments = [
					'date1' => $date,
					'date2' => $date,
					'date3' => $date,
					'days_to_exp' => 5,
					'eq_id' => $stockResults[$counter]['id'],
					'p_c' => 'C',
				];

				$query = '
					SELECT 
					oc.eqId,
					DATEDIFF(oc.expDate, :date1) AS daysToExp, 
					DATEDIFF(oc.expDate, oc.startDate) AS optLength,
					oc.optId,
					oc.expDate,
					op.date_,
					oc.putCall,
					oc.strike,
					op.ask AS optAsk,
					op.bid AS optBid,
					eqp.ask,
					eqp.bid,
					ivl.ivAsk,
					ivl.ivBid
					FROM optcontract AS oc
					INNER JOIN optprice AS op ON op.optId=oc.optId AND op.ask > 0 AND op.bid > 0 AND op.date_=:date2
					INNER JOIN eqprice AS eqp ON eqp.eqId = oc.eqId AND eqp.date_ = op.date_
					INNER JOIN ivlisted AS ivl ON ivl.optId = oc.optId AND ivl.date_ = op.date_
					WHERE DATEDIFF(oc.expDate, :date3) >= :days_to_exp AND oc.eqId=:eq_id AND oc.putCall=:p_c AND oc.strike > eqp.ask

					ORDER by oc.strike ASC, oc.expDate ASC
				';

				$callResult = DB::connection('ovs')->select($query, $arguments);

				$callResults[] = $callResult;
				$optionsFound++;
			}	

			$counter++;
		}

		$chosenOptions = $this->optionSelect($callResults);

		foreach($chosenOptions as $option)
		{
			$arguments = [
				'optId' => $option->optId,
				'startDate' => $startDate,
				'endDate' => $endDate,
			];

			$query = "
				SELECT *
				FROM `optprice`
				WHERE optId=:optId and date_ BETWEEN :startDate and :endDate
			";

			$result = DB::connection('ovs')->select($query, $arguments);

			$priceHistory[$option->optId] = $result;
		}

		dd($priceHistory);
	}

    private function optionSelect($list)
    {
        $theChosenOnes = array();
        $max_option_hold_days = 50;

        for ($i =0; $i < 5; $i++){
            $theChosenOne;
            $closest_to_max_option_hold_days = False;
            $correctOptionFound = False;
            $optionBuyPrice = -1.00;
            $finalOptionBuyPrice = -1;
            $daysToExpire = -1;
            $counter = 0;
            for ($counter = 0; $counter < count($list[$i]); $counter++){
                //echo $counter." ";    
                if ($counter == 0){
                    
                    $theChosenOne = $list[$i][$counter];
                    $optionBuyPrice = floatval($list[$i][$counter]->optAsk)*100;
                    //dd(substr(explode("+\"strike\": \"", $list[$i][$counter])[1], 0, 5));
                }
                else{
                    //echo intval($list[$i][$counter]-> daysToExp)." ";
                    if (intval($list[$i][$counter]-> daysToExp) < $max_option_hold_days){
                        if ((floatval($list[$i][$counter]->optAsk)*100) <= $optionBuyPrice){
                                $theChosenOne = $list[$i][$counter]; 
                            }
                            else{
                                break;
                            }

                    }
                }   

                $counter += 1;

            }

            array_push($theChosenOnes, $theChosenOne);

        } 

        return $theChosenOnes;
    }
}
