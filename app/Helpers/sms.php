<?php

namespace App\Helpers;
use App\Models\Resort;

class CustomSMS
{

    static function send($number, $message)
    {
		$resort = Resort::findOrFail(1);
		if($resort->points < 0) {
			$ch = curl_init();
			$parameters = array(
				'apikey' => 'b96892f0be3b73851d59d21f75fe4f8b', //Your API KEY
				'number' => $number,
				'message' => $message,
				'sendername' => 'SakaResort'
			);
			curl_setopt( $ch, CURLOPT_URL,'http://api.semaphore.co/api/v4/messages' );
			curl_setopt( $ch, CURLOPT_POST, 1 );
	
			//Send the parameters set above with the request
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );
	
			// Receive response from server
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$output = curl_exec( $ch );
			curl_close ($ch);
	
			$resort->points = $resort->points - 1;
			$resort->update();
			//Show the server response
			return $output;
		} else {
			return 'No available points';
		}
    }
}