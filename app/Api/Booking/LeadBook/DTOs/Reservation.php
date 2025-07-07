<?php

declare(strict_types=1);

namespace App\Api\Booking\LeadBook\DTOs;

use App\Api\Exceptions\ReservationFailedException;
use App\Contracts\Booking\ReservationContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

final readonly class Reservation implements Arrayable, ReservationContract, Responsable
{
    public string $reservationId;

    /**
     * @throws ReservationFailedException
     */
    public function __construct(array $data)
    {
        if ($data['success'] !== true) {
            throw new ReservationFailedException;
        }
        $this->reservationId = $data['reservation_id'];
    }

    public function toResponse($request): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return ['reservation_id' => $this->reservationId];
    }
}
