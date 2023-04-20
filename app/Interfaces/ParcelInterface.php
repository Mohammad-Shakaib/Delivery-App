<?php

namespace App\Interfaces;

interface ParcelInterface
{
    public function createParcel($request);
    public function getSenderParcels();

    public function getBikerParcels();

    public function parcelPicked($request);

    public function parcelDelivered($request);
}






