<?php

namespace App\Custom\FishbackStockScanner;

use App\Exceptions\FSSNotFoundException;
use App\Exceptions\InvalidDateFormat;

class CommandLineExecutor
{
	public function VolitilityHVIVDifference($date)
	{
        $filename = config('scan.scan_program');

	    // If the date format isn't YYYY-MM-DD then throw an error.
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))
		{
		    throw new InvalidDateFormat('The date format need to be YYYY-MM-DD');
		}

		// To see if the program exists.
		// If not it's probably because you're on your local machine
		// and not the server
		if(!file_exists($filename))
		{
			throw new FSSNotFoundException('The FishbackStockScanner program was not found.');
		}

		$date_param = "-date=" . $date;

		$command_parts = [];

		$command_parts[] = $filename;
		$command_parts[] = $date_param;
		$command_parts[] = "-sessionID=appopttablewide";
		$command_parts[] = "-minVolRatio=.03";
		$command_parts[] = "-maxVolRatio=2";
		$command_parts[] = "-VolTypeTop=15";
		$command_parts[] = "-VolTypeBottom=-1";
		$command_parts[] = "-VRMoneyBottom=100";
		$command_parts[] = "-printStockFactor=VolatilityDiff";
		$command_parts[] = "-maxDaystoExpire=180";
		$command_parts[] = "-app";
		$command_parts[] = "-validBids";
		$command_parts[] = "-minStockPrice=5";
		$command_parts[] = "-noETF";
		$command_parts[] = "|sort -t, -k4,4 -nr";

		$command = implode(" ", $command_parts);

        $shell_output = shell_exec($command);

		// These next few lines should parse the CSV file.
		$results = [];
		$temp = explode("\n", $shell_output);
		foreach($temp as $t)
		{
			if($t != '')
			{
				$results[] = [
                    'id' =>     (int)explode(",", $t)[0],
                    'name' =>  explode(",", $t)[1],
                    'price' =>   (float)explode(",", $t)[2],
                ];
			}
		}

		return $results;
	}

	public function TermLTSTDifference($date)
	{
        $filename = config('scan.scan_program');

	    // If the date format isn't YYYY-MM-DD then throw an error.
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))
		{
		    throw new InvalidDateFormat('The date format need to be YYYY-MM-DD');
		}

		// To see if the program exists.
		// If not it's probably because you're on your local machine
		// and not the server
		if(!file_exists($filename))
		{
			throw new FSSNotFoundException('The FishbackStockScanner program was not found.');
		}

		$date_param = "-date=" . $date;

		$command_parts = [];

		$command_parts[] = $filename;
		$command_parts[] = $date_param;
		$command_parts[] = "-sessionID=appopttablewide";
		$command_parts[] = "-printStockFactor=EXPRDiff";
		$command_parts[] = "-validBids";
		$command_parts[] = "-minVolRatio=0.000001";
		$command_parts[] = "-maxVolRatio=10000000";
		$command_parts[] = "-app";
		$command_parts[] = "-validBids";
		$command_parts[] = "-minStockPrice=5";
		$command_parts[] = "-noETF";
		$command_parts[] = "|sort -t, -k4,4 -nr";

		$command = implode(" ", $command_parts);

        $shell_output = shell_exec($command);

		// These next few lines should parse the CSV file.
		$results = [];
		$temp = explode("\n", $shell_output);
		foreach($temp as $t)
		{
			if($t != '')
			{
				$results[] = [
                    'id' =>     (int)explode(",", $t)[0],
                    'name' =>  explode(",", $t)[1],
                    'price' =>   (float)explode(",", $t)[2],
                ];
			}
		}

		return $results;
	}
}
