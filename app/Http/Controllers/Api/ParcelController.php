<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ParcelInterface;
use App\Http\Requests\CreateParcelRequest;
use App\Http\Requests\PickParcelRequest;
use JWTAuth;
class ParcelController extends Controller
{
    /**
     * @var ParcelInterface
     */
    protected $parcelInterface;
    public function __construct(ParcelInterface $parcelInterface)
    {
        $this->parcelInterface = $parcelInterface;
    }

    public function createParcel(CreateParcelRequest $request) {
        $response = $this->parcelInterface->createParcel($request);

        return $response;
    }

    public function getSenderParcels() {
        $senderParcels = $this->parcelInterface->getSenderParcels();

        return $senderParcels;
    }

    public function getBikerParcels() {
        $bikerParcels = $this->parcelInterface->getBikerParcels();

        return $bikerParcels;
    }

    public function parcelPicked(PickParcelRequest $request) {
        $parcelPicked = $this->parcelInterface->parcelPicked($request);

        return $parcelPicked;
    }


    public function parcelDelivered(PickParcelRequest $request) {
        $parcelDelivered = $this->parcelInterface->parcelDelivered($request);

        return $parcelDelivered;
    }
}
