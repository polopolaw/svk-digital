<?php

declare(strict_types=1);

namespace App\Api\Booking\LeadBook\DTOs;

use App\Contracts\Booking\EventContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

final readonly class Event implements Arrayable, EventContract, Responsable
{
    public function __construct(
        public int $id,
        public int $showId,
        public \DateTimeInterface $date,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'showId' => $this->showId,
            'date' => $this->date,
        ];
    }

    public function toResponse($request): array
    {
        return $this->toArray();
    }
}
