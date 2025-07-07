<?php

namespace App\Api\Booking\LeadBook\DTOs;

use App\Contracts\Booking\PlaceContract;

readonly class Place implements PlaceContract
{
    public function __construct(
        public int   $id,
        public float $x,
        public float $y,
        public float $width,
        public float $height,
        public bool  $isAvailable,
    )
    {
    }
}
