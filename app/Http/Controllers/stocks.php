<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class stocks extends Controller
{
	public function submitSelection(Request $request)
	{

		try
		{
			echo \FSSCLE::test();
		}
		catch($e)
		{
			echo $exception->getMessage();
		}
	}
}
