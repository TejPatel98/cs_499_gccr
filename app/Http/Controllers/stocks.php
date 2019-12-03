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
		// Setup variables
		$invAmount = $request->input('principle') * ($request->input('investmentPercent') * 0.01);
		$maxTrades = $request->input('maxTradesPerDay');
		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');
		$scanType = $request->input('scan');
		$maxTradeLength = $request->input('maxTradeLength');
		$minTradeLength = $request->input('minTradeLength');		

		$dateResults = DB::connection('ovs')->select("SELECT * FROM `ovscalendar` where calType = '2' and date_ BETWEEN ? and ? order by date_ asc", [$startDate, $endDate]);

		// Format all the date results to YYYY-MM-DD ex 2000-01-01
		foreach($dateResults as $result)
		{
			$formattedDates[] = (new \DateTime($result->date_))->format('Y-m-d');
		}
		$test = array();
		$balance = $request->input('principle');
		$data = array();
		foreach($formattedDates as $date)
		{
			$amountPerStock = $balance * ($request->input('investmentPercent') * 0.01) * pow($maxTrades, -1);


			$amountSpentOnThisDay = 0;

			// Get the scan results for the first day
			if($scanType == 'VolitilityHVIVDifference')
				$stockResults = \FSSCLE::VolitilityHVIVDifference($date);
			else if($scanType == 'TermLTSTDifference')
				$stockResults = \FSSCLE::TermLTSTDifference($date);

			// Get the options list for the first day
			$optionList = $this->getOptionsList($stockResults, $date, $amountPerStock, $maxTrades);
			
			// Choose the options near the strike price
			$chosenOptions = $this->optionSelect($optionList, $maxTradeLength, $minTradeLength, $amountPerStock);

			$values = array(
				'balance' => intval($balance),
				'invAmount' => $balance * ($request->input('investmentPercent') * 0.01),
				'amountForEachStock' => $amountPerStock,
				'information' => array()
				// $this->fillOptionData($date, $amountPerStock, $maxTrades, $maxTradeLength, $minTradeLength, $balance, $startDate, $endDate)
			);
			if (count($chosenOptions) > 0)
				foreach($chosenOptions as $key=> $value)
				{
					if ($value[0] != null)
					{
						$balance -= intval($value[0]->optAsk*100 * floor($amountPerStock * pow($value[0]->optAsk*100, -1)));
						$newOption = array(
							'name' => $key,
							'optionId' => $value[0]->optId,
							'purchaseDate' => $date,
							'expDate' => (new \DateTime($value[0]->expDate))->format('Y-m-d'),
							'price' => $value[1],
							'pricePerOption' => $value[0]->optAsk*100,
							'numberOfOptions' => floor($amountPerStock * pow($value[0]->optAsk*100, -1)),
							'amountSpent' => $value[0]->optAsk*100 * floor($amountPerStock * pow($value[0]->optAsk*100, -1)),
							'priceHistory' => $this->getPriceHistory($value[0], $date, $endDate)[$value[0]->optId]
						);
						$values['information'][] = $newOption;
					}
				}

			$data[$date] = $values;

		}	

		$portfolioValue = $this->getPortfolioValue($data);

		foreach($formattedDates as $date)
		{
			$valForToday = $this->getSpecificDayValue($portfolioValue, $date);
			$data[$date]["portfolioValue"] = $data[$date]["balance"] + $valForToday[1];
			$data[$date]["balance"] +=  $valForToday[0];
		}

		$optionDateData = $this->getoptionDataForEachDay($data, $formattedDates);

		foreach($formattedDates as $date)
		{
			unset($data[$date]['information']);
			$data[$date]['information'] = $optionDateData[$date];
		}

		$viewData = [
			'results' => $data,
		];
		
		return view('results/results', $viewData);
	

	}

	private function getPortfolioValue ($data)
	{
		$chosenOptionsThroughout = array();
		foreach($data as $key=> $value)
		{
			foreach($value['information'] as $temp)
			{
				$chosenOptionsThroughout[] = $temp;
			}
		}
		return $chosenOptionsThroughout;
	}

	private function getSpecificDayValue($portfolioVal, $date)
	{
		$balanceValueAddition = 0;
		$portfolioValueForToday = 0;
		foreach ($portfolioVal as $optionVals)
		{
			if($optionVals['expDate'] > $date)
				foreach($optionVals['priceHistory'] as $priceHistoryVar)
				{
					$optPriceHistoryDate = (new \DateTime($priceHistoryVar->date_))->format('Y-m-d');
					if ($optPriceHistoryDate == $date)
						$portfolioValueForToday += floatval($priceHistoryVar->bid*100*$optionVals['numberOfOptions']);
				}
			else if ($optionVals['expDate'] == $date)
			{
				foreach($optionVals['priceHistory'] as $priceHistoryVar)
				{
					$optPriceHistoryDate = (new \DateTime($priceHistoryVar->date_))->format('Y-m-d');
					if ($optPriceHistoryDate == $date)
						$balanceValueAddition += max(floatval($optionVals['price']) - floatval($priceHistoryVar->bid*100*$optionVals['numberOfOptions']), 0);
				}

				
			}

		}
		return array($balanceValueAddition,$portfolioValueForToday);
	}

	private function getoptionDataForEachDay($data, $dates)
	{
		$optionArray = array();
		$finalOptionData = array();

		foreach($data as $key=>$value)
		{	
			$tempValueHolder = $value['information'];
			foreach ($tempValueHolder as $opt)
			{
				foreach ($opt['priceHistory'] as $pricedOpt)
				{
					$temp = array(
						'name' => $opt['name'], 
						'purchaseDate' => $opt['purchaseDate'],
						'expDate' => $opt['expDate'],
						'strike' => $opt['price'],
						'pricePerOption' => $opt['pricePerOption'],
						'numberOfOptions' => $opt['numberOfOptions'],
						'amountSpent' => $opt['amountSpent'],
						'date' => (new \DateTime($pricedOpt->date_))->format('Y-m-d'),
						'bid' => $pricedOpt->bid,
						'currentValue' => $opt['numberOfOptions'] * $pricedOpt->bid * 100
					);
					$optionArray[] = $temp;
				}
				
			}	
			
		}
		foreach ($dates as $date)
		{
			$tempVal = array();
			foreach($optionArray as $option)
			{
				if ($option['date'] == $date)
				{
					$tempVal[] = $option;
				}
			}
			$finalOptionData[$date] = $tempVal;
		}
		return $finalOptionData;


	}

    private function optionSelect($list, $maxLength, $minLength, $amtForEachStock)
    {

        $theChosenOnes = array();
		
		foreach($list as $key=> $val)
		{
			$theChosenOne = null;
            $closest_to_max_option_hold_days = False;
            $correctOptionFound = False;
            $optionBuyPrice = -1.00;
            $finalOptionBuyPrice = -1;
            $daysToExpire = -1;
			$counter = 0;
			$price = 0;
			for ($counter = 0; $counter < count($val[0]); $counter++)
			{
				
				if (intval($val[0][$counter]-> daysToExp) <= $maxLength and intval($val[0][$counter]-> daysToExp) >= $minLength){
					if ((floatval($val[0][$counter]->optAsk)*100) <= $amtForEachStock){
						$theChosenOne = $val[0][$counter]; 
						$price = $val[1];
						break;
					}
					else{
						
						break;
					}

				}
				$counter++;
			}
			$theChosenOnes[$key] = array($theChosenOne, $price);

		}
		return $theChosenOnes;
    }

	// This takes in a list of options and gets the price history
	// from a start date to an end date.
	private function getPriceHistory($option, $startDate, $endDate)
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
		// }
		

		return $priceHistory;
	}

	private function getOptionsList($stocks, $date, $amountPerStock, $maxTrade)
	{
		$optionsFound = 0;
		$counter = 0;
		$callResults = array();
		while($optionsFound < $maxTrade && $counter < count($stocks)) 
		{
			// You cannot use named bindings multiple times in the same query.
			// There must be an individual named binding for each spot.

			$arguments = [
				'date1' => $date,
				'date2' => $date,
				'date3' => $date,
				'days_to_exp' => 5,
				'eq_id' => $stocks[$counter]['id'],
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

			$callResults[$stocks[$counter]["name"]] = array($callResult, $stocks[$counter]["price"]);
			$optionsFound++;	

			$counter++;
		}

		return $callResults;
	}
}

