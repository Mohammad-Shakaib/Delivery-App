<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateParcelRequest;
use App\Http\Requests\PickParcelRequest;
use App\Models\Parcels;

class AuthController extends Controller
{
    public function sendResponse($data, $message, $status = 200) 
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // this authenticates the user details with the database and generates a token
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError([], "invalid login credentials", 400);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage(), 500);
        }

        $success = [
            'token' => $token,
        ];
        return $this->sendResponse($success, 'successful login', 200);
    }

    public function createParcel(CreateParcelRequest $request) {
        $parcel = Parcels::create([
            'name' => $request->name,
            'pick_up' => $request->pick_up,
            'drop_of' => $request->drop_of,
            'sender_id' => $request->user()->id
        ]);

        if (!$parcel) {
            return $this->sendResponse(['success' => false], 'No Parcel Found', 200);
        } else {
            ParcelLogs::create([
                'parcel_id' => $parcel->id,
                'status' => $parcel->status
            ]);
        }

        return $this->sendResponse(['success' => true], 'Parcel Created Succesfully', 200);
    }

    public function getSenderParcels() {
        $message = 'All Sender Parcels';

        $parcels = Parcels::where('sender_id', request()->user()->id)
            ->select('name', 'pick_up', 'drop_of', 'status')
            ->get();

        if (!$parcels) {
            $message = 'No Parcel Found!';
        }

        return $this->sendResponse($parcels, $message, 200);
    }

    public function getParcels() {
        $message = 'All Parcels';

        $parcels = Parcels::select('name', 'pick_up', 'drop_of', 'status')
            ->get();

        if (!$parcels) {
            $message = 'No Parcel Found!';
        }

        return $this->sendResponse($parcels, $message, 200);
    }

    public function pickParcel(PickParcelRequest $request) {
        $parcel = Parcels::find($request->parcel_id);

        if (@$parcel->status != 'ready_to_deliver') {
            return $this->sendResponse(['succes' => 'false'], 'Parcel is either delivered or picked by a biker!', 200);
        } else {
            $parcel = Parcels::where('id', $parcel->id)
                ->update([
                    'status' => 'picked_by_rider',
                    'biker_id' => $request->user()->id
                ]);

            ParcelLogs::create([
                'parcel_id' => @$parcel->id,
                'status' => @$parcel->status
            ]);
        }

        return $this->sendResponse(['success' => true], 'Parcel picked successfully!', 200);
    }


    public function parcelDelivered(PickParcelRequest $request) {
        $parcel = Parcels::find($request->parcel_id);

        if (@$parcel->status != 'picked_by_rider') {
            return $this->sendError(['succes' => 'false'], 'Parcel is either delivered or ready to deliver!', 200);
        } else {
            $parcel = Parcels::where('id', $parcel->id)
                ->update([
                    'status' => 'delivered'
                ]);

            ParcelLogs::create([
                'parcel_id' => @$parcel->id,
                'status' => @$parcel->status
            ]);
        }

        return $this->sendResponse(['success' => true], 'Parcel delivered successfully!', 200);
    }
}