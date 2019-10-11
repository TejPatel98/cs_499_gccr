<?php

namespace App\Exceptions;

use Exception;

class FSSNotFoundException extends Exception
{
	/**
	* Report or log an exception.
	*
	* @return void
	*/
	public function report()
	{
		\Log::debug('FishBackStockScanner not found.');
	}
}
