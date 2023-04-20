<?php

namespace App\Repository;

use App\Interfaces\ParcelInterface;
use App\Models\ParcelLogs;
use App\Models\Parcels;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use App\Helpers\ApiResponses;
class ParcelRepository implements ParcelInterface
{
    public function createParcel($request) {
        $parcel = new Parcels();
        $parcel->fill($request->all());
        $parcel->save();

        if (!$parcel) {
            return ApiResponses::sendError(['success' => false], 'No Parcel Found', HttpResponse::HTTP_OK);
        } else {
            ParcelLogs::create([
                'parcel_id' => $parcel->id,
                'status' => $parcel->status
            ]);
        }

        return ApiResponses::sendResponse(['success' => true], 'Parcel Created Succesfully', HttpResponse::HTTP_OK);
    }

    public function getSenderParcels() {
        $message = 'Sender Parcels';

        $parcels = Parcels::where('sender_id', request()->user()->id)
            ->select('id', 'name', 'pick_up', 'drop_of', 'status')
            ->get();

        if (!$parcels) {
            $message = 'No Parcel Found!';
        }

        return ApiResponses::sendResponse($parcels, $message, HttpResponse::HTTP_OK);
    }

    public function getBikerParcels() {
        $message = 'Biker Parcels';

        $parcels = Parcels::select('id', 'name', 'pick_up', 'drop_of', 'status')
            ->get();

        if (!$parcels) {
            $message = 'No Parcel Found!';
        }

        return ApiResponses::sendResponse($parcels, $message, HttpResponse::HTTP_OK);
    }

    public function parcelPicked($request) {
        $status = 'picked_by_rider';
        $parcel = Parcels::find($request->parcel_id);

        if (@$parcel->status != 'ready_to_deliver') {
            return ApiResponses::sendResponse(['succes' => 'false'], 'Parcel is either delivered or picked by a biker!', 200);
        } else {
            $parcel = Parcels::where('id', $parcel->id)
                ->update([
                    'status' => $status,
                    'biker_id' => $request->user()->id
                ]);

            ParcelLogs::create([
                'parcel_id' => $request->parcel_id,
                'status' => $status
            ]);
        }

        return ApiResponses::sendResponse(['success' => true], 'Parcel picked successfully!', 200);
    }


    public function parcelDelivered($request) {
        $status = 'delivered';

        $parcel = Parcels::find($request->parcel_id);

        if (@$parcel->status != 'picked_by_rider') {
            return ApiResponses::sendResponse(['succes' => 'false'], 'Parcel is either delivered or ready to deliver!', 200);
        } else {
            $parcel = Parcels::where('id', $parcel->id)
                ->update([
                    'status' => $status
                ]);

            ParcelLogs::create([
                'parcel_id' => $request->parcel_id,
                'status' => $status
            ]);
        }

        return ApiResponses::sendResponse(['success' => true], 'Parcel delivered successfully!', 200);
    }
}
