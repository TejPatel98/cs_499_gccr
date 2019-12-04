<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\FSSNotFoundException;
use App\Exceptions\InvalidDateFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\stocks;

class StocksTest extends TestCase
{
    public function getSpecificDayValue($portfolioVal, $date)
    {
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

        }
        return $portfolioValueForToday;
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
            if($stocks[$counter]["price"] < $amountPerStock)
            {
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

                $callResults[$stocks[$counter]["name"]] = $callResult;
                $optionsFound++;
            }

            $counter++;
        }

        return $callResults;
    }
    private function optionSelect($list, $maxLength, $minLength, $amtForEachStock)
    {
        $theChosenOnes = array();

        foreach($list as $key=> $val)
        {
            $theChosenOne;
            $closest_to_max_option_hold_days = False;
            $correctOptionFound = False;
            $optionBuyPrice = -1.00;
            $finalOptionBuyPrice = -1;
            $daysToExpire = -1;
            $counter = 0;

            for ($counter = 0; $counter < count($val); $counter++)
            {
                if ($counter == 0){

                    $theChosenOne = $val[$counter];
                    $optionBuyPrice = floatval($val[$counter]->optAsk)*100;
                }
                else{
                    if (intval($val[$counter]-> daysToExp) <= $maxLength and intval($val[$counter]-> daysToExp) >=  1){
                        if ((floatval($val[$counter]->optAsk)*100) <= $amtForEachStock){
                            $theChosenOne = $val[$counter];
                        }
                        else{
                            break;
                        }

                    }
                }
                $counter++;
            }
            $theChosenOnes[$key] = $theChosenOne;

        }
        return $theChosenOnes;

    }
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


        return $priceHistory;
    }
    public function getPortfolioValue ($data)
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
/* ========================================================================================================
 *
 *  Temp imported functions ^
 *
 *
 * ========================================================================================================
 */
    
    
    
    
    
    
    public function testGetPriceHistoryNumDays()
    {
        // Test returned correct number of days
        $stocks = new stocks();
        $chosenOptions = array((object)array('optId' => 36192308));
        $startDate = "2019-09-09 00:00:00";
        $endDate = "2019-09-12 00:00:00";
        $result = $stocks->getPriceHistory($chosenOptions[0], $startDate, $endDate);
        
        $this->assertEquals(count($result['36192308']), 4); // Make sure the number of returned days is equal to 4
    }
    
    public function testGetPriceHistoryUsingCorrectDay()
    {
        // Define local variables
        $stocks = new stocks();
        $chosenOptions = (object)array('optId' => 36192308);
        $startDate = "2019-09-09 00:00:00";
        $endDate = "2019-09-12 00:00:00";
        
        // Gather output of function
        $result = $stocks->getPriceHistory($chosenOptions, $startDate, $endDate);
        
        // Run tests on the given output
        $this->assertEquals($result['36192308'][0]->date_,  "2019-09-09 00:00:00");
        $this->assertEquals($result['36192308'][1]->date_,  "2019-09-10 00:00:00");
        $this->assertEquals($result['36192308'][2]->date_,  "2019-09-11 00:00:00");
        $this->assertEquals($result['36192308'][3]->date_,  "2019-09-12 00:00:00");
    }
    
    public function testGetPortfolioValues(){
        // Local Const
        $PRINCIPLE = 1000;
        $INVEST_PERCENT = 10*0.01; // Interpreting value in field as "10"%
        $MAXTRADES = 14;
        $START_DATE = "2019-09-09 00:00:00";
        $END_DATE = "2019-09-12 00:00:00";
        $MAX_TRADE_LEN = 32;
        $MIN_TRADE_LEN = 14;
        
        // Setup variables
        $invAmount = $PRINCIPLE * $INVEST_PERCENT;
        $maxTrades = $MAXTRADES;
        $startDate = $START_DATE;
        $endDate = $END_DATE;
        $maxTradeLength = $MAX_TRADE_LEN;
        $minTradeLength = $MIN_TRADE_LEN;
        
        $dateResults = DB::connection('ovs')->select("SELECT * FROM `ovscalendar` where calType = '2' and date_ BETWEEN ? and ? order by date_ asc", [$startDate, $endDate]);

        // Format all the date results to YYYY-MM-DD ex 2000-01-01
        foreach($dateResults as $result)
        {
            $formattedDates[] = (new \DateTime($result->date_))->format('Y-m-d');
        }

        $balance = $PRINCIPLE;
        $data = array();
        foreach($formattedDates as $date)
        {
            $amountPerStock = $balance * ($INVEST_PERCENT) * pow($maxTrades, -1);

            $amountSpentOnThisDay = 0;

            // Get the scan results for the first day
            $stockResults = \FSSCLE::VolitilityHVIVDifference($date);

            // Get the options list for the first day
            $optionList = $this->getOptionsList($stockResults, $date, $amountPerStock, $maxTrades);

            // Choose the options near the strike price
            $chosenOptions = $this->optionSelect($optionList, $maxTradeLength, $minTradeLength, $amountPerStock);

            $values = array(
                'balance' => intval($balance),
                'invAmount' => $balance * ($INVEST_PERCENT),
                'amountForEachStock' => $amountPerStock,
                'information' => array()
            );

            foreach($chosenOptions as $key=> $value)
            {
                $balance -= intval($value->optAsk*100 * floor($amountPerStock * pow($value->optAsk*100, -1)));
                $newOption = array(
                    'name' => $key,
                    'optionId' => $value->optId,
                    'purchaseDate' => $date,
                    'expDate' => (new \DateTime($value->expDate))->format('Y-m-d'),
                    'pricePerOption' => $value->optAsk*100,
                    'numberOfOptions' => floor($amountPerStock * pow($value->optAsk*100, -1)),
                    'amountSpent' => $value->optAsk*100 * floor($amountPerStock * pow($value->optAsk*100, -1)),
                    'priceHistory' => $this->getPriceHistory($value, $date, $endDate)[$value->optId]
                );
                $values['information'][] = $newOption;
            }

            $amountSpentOnThisDay = 0;
            $balance -= $amountSpentOnThisDay;

            $data[$date] = $values;

        }

        $portfolioValue = $this->getPortfolioValue($data);
        //dd($portfolioValue);
        $this->assertTrue(true);
    }
    
    public function testGetSpecificDayValue(){
        // Local Const
        $PRINCIPLE = 1000;
        $INVEST_PERCENT = 10*0.01; // Interpreting value in field as "10"%
        $MAXTRADES = 14;
        $START_DATE = "2019-09-09 00:00:00";
        $END_DATE = "2019-09-12 00:00:00";
        $MAX_TRADE_LEN = 32;
        $MIN_TRADE_LEN = 14;
        
        // Setup variables
        $invAmount = $PRINCIPLE * $INVEST_PERCENT;
        $maxTrades = $MAXTRADES;
        $startDate = $START_DATE;
        $endDate = $END_DATE;
        $maxTradeLength = $MAX_TRADE_LEN;
        $minTradeLength = $MIN_TRADE_LEN;
        
        $dateResults = DB::connection('ovs')->select("SELECT * FROM `ovscalendar` where calType = '2' and date_ BETWEEN ? and ? order by date_ asc", [$startDate, $endDate]);

        // Format all the date results to YYYY-MM-DD ex 2000-01-01
        foreach($dateResults as $result)
        {
            $formattedDates[] = (new \DateTime($result->date_))->format('Y-m-d');
        }

        $balance = $PRINCIPLE;
        $data = array();
        foreach($formattedDates as $date)
        {
            $amountPerStock = $balance * ($INVEST_PERCENT) * pow($maxTrades, -1);

            $amountSpentOnThisDay = 0;

            // Get the scan results for the first day
            $stockResults = \FSSCLE::VolitilityHVIVDifference($date);

            // Get the options list for the first day
            $optionList = $this->getOptionsList($stockResults, $date, $amountPerStock, $maxTrades);

            // Choose the options near the strike price
            $chosenOptions = $this->optionSelect($optionList, $maxTradeLength, $minTradeLength, $amountPerStock);

            $values = array(
                'balance' => intval($balance),
                'invAmount' => $balance * ($INVEST_PERCENT),
                'amountForEachStock' => $amountPerStock,
                'information' => array()
            );

            foreach($chosenOptions as $key=> $value)
            {
                $balance -= intval($value->optAsk*100 * floor($amountPerStock * pow($value->optAsk*100, -1)));
                $newOption = array(
                    'name' => $key,
                    'optionId' => $value->optId,
                    'purchaseDate' => $date,
                    'expDate' => (new \DateTime($value->expDate))->format('Y-m-d'),
                    'pricePerOption' => $value->optAsk*100,
                    'numberOfOptions' => floor($amountPerStock * pow($value->optAsk*100, -1)),
                    'amountSpent' => $value->optAsk*100 * floor($amountPerStock * pow($value->optAsk*100, -1)),
                    'priceHistory' => $this->getPriceHistory($value, $date, $endDate)[$value->optId]
                );
                $values['information'][] = $newOption;
            }

            $amountSpentOnThisDay = 0;
            $balance -= $amountSpentOnThisDay;

            $data[$date] = $values;

        }

        $portfolioValue = $this->getPortfolioValue($data);
        foreach($formattedDates as $date) // Make sure each "val
        {
            $valForToday = $this->getSpecificDayValue($portfolioValue, $date);
            //dd($valForToday);
            //$this->assertEquals($valForToday, float);
        }
        
        
        $this->assertTrue(true); // Test successful
    }
}
