<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Paciiauth;
use App\Governorate;
use App\Neighborhood;
use App\Block;
use App\Street;
use Illuminate\Support\Facades\Input; //header
use Excel;
use Mail;
use Carbon\Carbon;

class PaciiapiController extends Controller
{
	public function getToken()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgis/sharing/generateToken',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('username' => 'FrontlineUser','password' => 'eR6j9@kX$In3Zy','client' => 'referer','referer' => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services','expiration' => '60','f' => 'pjson'),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$response_decode = json_decode($response);
		$data = new Paciiauth; 
        $data->token = $response_decode->token;
        $data->expires=$response_decode->expires;
        $data->save();
        $paciauth_id =$data->id;

        return $response_decode->token;

	}
	public function paciiauth()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgis/sharing/generateToken',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('username' => 'FrontlineUser','password' => 'eR6j9@kX$In3Zy','client' => 'referer','referer' => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services','expiration' => '60','f' => 'pjson'),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$response_decode = json_decode($response);
		$data = new Paciiauth; 
        $data->token = $response_decode->token;
        $data->expires=$response_decode->expires;
        $data->save();
        $paciauth_id =$data->id;

        self::getGovernorate($response_decode->token);

	}

	public function getGovernorate($token){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/PACIAddressSearch/FeatureServer/3/query?where=1%3D1&token='.$token.'&outFields=*&returnGeometry=false&f=pjson',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$response_decode = json_decode($response);

		foreach ($response_decode->features as $log) {
			 $attr = $log->attributes;
			 
			 $governorate=Governorate::where('gov_no',$attr->gov_no)->first();
			 if($governorate){
	            $data = Governorate::find($governorate->id);
		     }
	         else{
	            $data = new Governorate; 
	         }
	        $data->gov_no = $attr->gov_no;
	        $data->governoratearabic = $attr->governoratearabic;
	        $data->governorateenglish = $attr->governorateenglish;
	        $data->Shape__Area = $attr->Shape__Area;
	        $data->objectid = $attr->objectid;
	        $data->Shape__Length = $attr->Shape__Length;
	        $data->save();

	        self::getNeighborhood($token,$attr->gov_no);

		}
	}

	public function getNeighborhood($token,$gov_no)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/PACIAddressSearch/FeatureServer/2/query?where=gov_no%3D'.$gov_no.'&token='.$token.'&outFields=*&returnGeometry=false&f=pjson',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		$response_decode = json_decode($response);
		curl_close($curl);

		foreach ($response_decode->features as $log) {
			 $attr = $log->attributes;
			 
			 $neighborhood=Neighborhood::where('nhood_no',$attr->nhood_no)->first();
			 if($neighborhood){
	            $data = Neighborhood::find($neighborhood->id);
		     }
	         else{
	            $data = new Neighborhood; 
	         }
	        $data->gov_no = $attr->gov_no;
	        $data->nhood_no = $attr->nhood_no;
	        $data->governoratearabic = $attr->governoratearabic;
	        $data->governorateenglish = $attr->governorateenglish;
	        $data->Shape__Area = $attr->Shape__Area;
	        $data->objectid = $attr->objectid;
	        $data->Shape__Length = $attr->Shape__Length;
	        $data->neighborhoodarabic = $attr->neighborhoodarabic;
	        $data->neighborhoodenglish = $attr->neighborhoodenglish;
	        $data->location = $attr->location;
	        $data->centroid_x = $attr->centroid_x;
	        $data->centroid_y = $attr->centroid_y;
	        $data->save();
	        self::getBlocks($token,$attr->nhood_no);
		}
	}

	public function getBlocks($token,$nhood_no)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/PACIAddressSearch/FeatureServer/1/query?where=nhood_no%3D%20'.$nhood_no.'&token='.$token.'&outFields=*&returnGeometry=false&f=pjson',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$response_decode = json_decode($response);

		foreach ($response_decode->features as $log) {
			 $attr = $log->attributes;
			 
			 $block=Block::where('blockid',$attr->blockid)->first();
			 if($block){
	            $data = Block::find($block->id);
		     }
	         else{
	            $data = new Block; 
	         }
	        $data->blockid = $attr->blockid;
	        $data->nhood_no = $attr->nhood_no;
	        $data->Shape__Length = $attr->Shape__Length;
	        $data->Shape__Area = $attr->Shape__Area;
	        $data->objectid = $attr->objectid;
	        $data->neighborhoodarabic = $attr->neighborhoodarabic;
	        $data->neighborhoodenglish = $attr->neighborhoodenglish;
	        $data->governoratearabic = $attr->governoratearabic;
	        $data->governorateenglish = $attr->governorateenglish;
	        $data->location = $attr->location;
	        $data->centroid_x = $attr->centroid_x;
	        $data->centroid_y = $attr->centroid_y;
	        $data->blockarabic = $attr->blockarabic;
	        $data->blockenglish = $attr->blockenglish;
	        $data->save();
	        self::getStreet($token,$attr->nhood_no,$attr->blockenglish);
		}

	}

	public function getStreet($token,$nhood_no,$blockenglish)
	{		
	    $curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/PACIAddressSearch/FeatureServer/0/query?where=nhood_no%3D'.$nhood_no.'%20and%20blockenglish%3D%27'.$blockenglish.'%27&token='.$token.'&outFields=*&returnGeometry=false&f=pjson',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response_decode = json_decode($response);

		foreach ($response_decode->features as $log) {
			 $attr = $log->attributes;
			 
			 $street=Street::where('blockenglish',$attr->blockenglish)->where('nhood_no',$attr->nhood_no)->first();
			 if($street){
	            $data = Street::find($street->id);
		     }
	         else{
	            $data = new Street; 
	         }
	        $data->nhood_no = $attr->nhood_no;
	        $data->Shape__Length = $attr->Shape__Length;
	        $data->objectid = $attr->objectid;
	        $data->neighborhoodarabic = $attr->neighborhoodarabic;
	        $data->neighborhoodenglish = $attr->neighborhoodenglish;
	        $data->governoratearabic = $attr->governoratearabic;
	        $data->governorateenglish = $attr->governorateenglish;
	        $data->location = $attr->location;
	        $data->centroid_x = $attr->centroid_x;
	        $data->centroid_y = $attr->centroid_y;
	        $data->blockarabic = $attr->blockarabic;
	        $data->blockenglish = $attr->blockenglish;

	        $data->streetenglish = $attr->streetenglish;
	        $data->streetarabic = $attr->streetarabic;
	        $data->streetnumber = $attr->streetnumber;
	        $data->alternativestreetarabic1 = $attr->alternativestreetarabic1;
	        $data->alternativestreetarabic2 = $attr->alternativestreetarabic2;
	        $data->alternativestreetarabic3 = $attr->alternativestreetarabic3;
	        $data->alternativestreetarabic4 = $attr->alternativestreetarabic4;
	        $data->alternativestreetenglish1 = $attr->alternativestreetenglish1;
	        $data->alternativestreetenglish2 = $attr->alternativestreetenglish2;
	        $data->alternativestreetenglish3 = $attr->alternativestreetenglish3;
	        $data->alternativestreetenglish4 = $attr->alternativestreetenglish4;
	        $data->detailsarabic = $attr->detailsarabic;
	        $data->detailsenglish = $attr->detailsenglish;
	        $data->save();
		}
		
	}

	public function paciinosearch($paciino)
	{
		$curl = curl_init();
		$token=self::getToken();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/Hosted/PACIGeocoder/FeatureServer/0/query?where=civilid%3D'.$paciino.'&outFields=*&f=pjson&token='.$token,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
}