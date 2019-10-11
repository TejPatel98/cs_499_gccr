<?php

namespace App\Custom\FishbackStockScanner;

use Illuminate\Support\Facades\Facade;

class FSSFacade extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'fsscle';
	}
}
