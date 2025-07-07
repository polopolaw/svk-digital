<?php

declare(strict_types=1);

namespace App\Api\Booking\LeadBook\DTOs;

use App\Contracts\Booking\ShowContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

final readonly class Show implements Arrayable, Responsable, ShowContract
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    public function toResponse($request): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
