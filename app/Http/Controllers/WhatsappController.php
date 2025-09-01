<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Auth;
use App\VicidialList;
use App\Upload;
use App\Shipment;
use App\Shipmentlog;
use App\User;
use App\Tray;
use App\Commodity;
use App\Cardtype;
use App\Webhooklog;
use App\Walog;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
	public function webhooklog(Request $request)
	{

		Log::info('Webhook URL hit.');

        // Log full incoming request data (both JSON & form)
        Log::info('Webhook Payload:', $request->all());
		 $data = $request->json()->all();
		  Webhooklog::create([
		  	'name'=> "Receive",
            'messages' => json_encode($data),
       	 ]);

		  return true;
	}

	public function appointment_confirmation1($mobile,$name,$date,$time,$area,$block,$street,$building,$floor,$flat,$landmark,$barcode)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		if(empty($area)){
			$area='-';
		}
		if(empty($block)){
			$block='-';
		}
		if(empty($street)){
			$street='-';
		}
		if(empty($building)){
			$building='-';
		}
		if(empty($floor)){
			$floor='-';
		}
		if(empty($flat)){
			$flat='-';
		}
		if(empty($landmark)){
			$landmark='-';
		}
		if(empty($time)){
			$time='-';
		}

		$curl = curl_init();

	  curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://emiratick.ae/api/send/template',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
		"phone": "+965'.$mobile.'",
		"template": {
			"name": "appbook",
			"language": {
				"code": "en"
			},
			"components": [
				{
					"type": "body",
					"parameters": [
						{
							"type": "text",
							"text": "'.$name.'"
						},
						{
							"type": "text",
							"text": "'.$barcode.'"
						},
	                    {
							"type": "text",
							"text": "'.$date.'"
						},
						{
							"type": "text",
							"text": "'.$time.'"
						},
	                    {
							"type": "text",
							"text": "'.$area.'"
						},
	                    {
							"type": "text",
							"text": "'.$block.'"
						},
	                    {
							"type": "text",
							"text": "'.$street.'"
						},
	                    {
							"type": "text",
							"text": "'.$building.'"
						},
	                    {
							"type": "text",
							"text": "'.$floor.'"
						},
	                    {
							"type": "text",
							"text": "'.$flat.'"
						},
	                    {
							"type": "text",
							"text": "'.$landmark.'"
						}
					]
				}
			]
		}
	}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'Authorization: Bearer sC54EeLbJi32YXNbh1CLonu5Mz4PlUleuDQkz0Cz'
	  ),
	));

	$response = curl_exec($curl);
		
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		Walog::insert(['response'=>$response,'code'=>$httpCode,'mobile'=>$mobile]);

		curl_close($curl);
	}
}