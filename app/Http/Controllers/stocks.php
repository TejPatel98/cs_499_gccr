<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class stocks extends Controller
{
	public function submitSelection(Request $request)
	{
		dd($request->all());
	}
}
