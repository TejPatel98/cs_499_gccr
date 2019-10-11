<?php

namespace App\Custom\FishbackStockScanner;

use App\Exception\FSSNotFoundException;

class CommandLineExecutor
{
	public function lsTest()
	{
		return shell_exec('ls -la');
	}

	public function test()
	{
		$filename = "/home/ukfl2019/FishbackStockScanner";

		// Check to see if the program exists.
		// If not it's probably because you're on your local machine
		// and not the server
		if(!file_exists($filename))
		{
			throw new FSSNotFoundException('The FishbackStockScanner program was not found.');
		}

		$command_parts = array();

		$command_parts[] = $filename;
		$command_parts[] = "-date=2013-03-19";
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

		$command = implode(" ", $command_parts);

		return shell_exec('ls -la');
	}
}
