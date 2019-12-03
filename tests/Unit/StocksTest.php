<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\stocks;

class StocksTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }
    
    public function testGetPriceHistoryValid()
    {
        $stocks = new stocks();
        $chosenOptions = array((object)array('optId' => 36192308),
                               (object)array('optId' => 652883668),
                               (object)array('optId' => 808872332));
        $startDate = "2019-09-09 00:00:00";
        $endDate = "2019-09-12 00:00:00";
        dd($stocks->getPriceHistory($chosenOptions, $startDate, $endDate));
        $this->assertTrue(true);
    }
}
