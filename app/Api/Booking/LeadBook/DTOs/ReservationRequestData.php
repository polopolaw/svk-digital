<?php

declare(strict_types=1);

namespace App\Api\Booking\LeadBook\DTOs;

use App\Contracts\Booking\ReservationRequestRequestContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

final readonly class ReservationRequestData implements Arrayable, ReservationRequestRequestContract, Responsable
{
    public string $name;

    public array $places;

    public function __construct(array $data)
    {
        if (! isset($data['name']) || ! isset($data['places'])) {
            throw new \InvalidArgumentException("Missing 'name' and 'places' parameter");
        }

        $this->name = $data['name'];
        $this->places = $data['places'];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'places' => $this->places,
        ];
    }

    public function toResponse($request): array
    {
        return $this->toArray();
    }
}
