<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Appointment;
use App\Shipment;
use App\Rejectreason;
use App\Cardstatus;

class ApiController extends Controller
{
    public function checkbarcodeforassign(Request $request)
    {
        $user = $request->user(); // Authenticated user via token

        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');
        $shipment=Shipment::where('barcode',$barcode)->where('status_id','6')->first();
        if($shipment){
            $message='Shipments available for assign';
            $suc=true;
            $code=200;
        }
        else{
            $message='Shipments not available for assign';
            $suc=false;
            $code=404;
        }   

        return response()->json([
            'success' => $suc,
            'message' => $message
            ], $code);
    }

	public function assign(Request $request)
	{
	    $user = $request->user(); // Authenticated user via token

	    $request->validate([
            'barcodes' => 'required|array',
            'barcodes.*' => 'required|string'
        ]);
	    $message='Shipments assigned successfully';
	    $invalidBarcodes = [];

        foreach ($request->barcodes as $barcode) {
            $shipment=Shipment::where('barcode',$barcode)->where('status_id','6')->first();
            if($shipment){
                $old_driver = $shipment->driver->name ?? '';
            	app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($shipment->id,"Shipments assigned to Driver",$old_driver,$user->name);

            	$shipment->update(['status_id'=>7,'driver_id'=>$user->id]);
            	if($shipment->appointment){
            		$shipment->appointment->update(['status_id'=>7,'driver_id'=>$user->id]);
            	}
            }
            else{
            	 $invalidBarcodes[] = $barcode;
            }

        }

        if (!empty($invalidBarcodes)) {
        	return response()->json([
            'success' => false,
            'message' => 'Shipments assigned successfully and Some barcodes are invalid or not found.',
            'invalid_barcodes' => $invalidBarcodes
        	], 404);
        }
        else{
        	 return response()->json([
		        'success' => true,
		        'message' => 'Shipments assigned successfully'
		    ],200);
        }

	   
	}

    public function rejectreasons()
    {

        $rejectReasons = RejectReason::where('delete_status', 0)
                        ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $rejectReasons
            ], 200);
    }

    public function statusupdate(Request $request)
    {
        $user = $request->user(); // Authenticated user via token

        $request->validate([
            'shipment_id' => 'required|int',
            'status_id' => 'required|int'
        ]);
        $message='Shipments Status Updated successfully';

        Shipment::where('id',$request->shipment_id)->update(['status_id'=>$request->status_id]);

        $status = Cardstatus::find($request->status_id);

        app('App\Http\Controllers\ShipmentuploadController')->CreateShipmentinfo($request->shipment_id,"Status Updated",$status->name,$status->name);

        return response()->json([
                'success' => true,
                'message' => $message
            ],200);
    }

}