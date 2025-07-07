<?php

declare(strict_types=1);

namespace App\Contracts\Booking;

use App\Api\Booking\Client;

/**
 * @see Client
 */
interface BookingClientContract
{
    /** @return array<ShowContract> */
    public function shows(array $params = []): array;

    /** @return array<EventContract> */
    public function events(int $showId, array $params = []): array;

    /** @return array<PlaceContract> */
    public function places(int $eventId, array $params = []): array;

    public function reserve(int $eventId, ReservationRequestRequestContract $data): ReservationContract;
}
