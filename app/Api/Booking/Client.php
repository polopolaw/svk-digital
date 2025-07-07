<?php
declare(strict_types=1);

namespace App\Api\Booking;

use App\Api\Booking\LeadBook\DTOs\Reservation;
use App\Api\Exceptions\ExternalApiError;
use App\Api\Exceptions\ReservationFailedException;
use App\Contracts\Booking\BookingClientContract;
use App\Contracts\Booking\EventContract;
use App\Contracts\Booking\PlaceContract;
use App\Contracts\Booking\ReservationContract;
use App\Contracts\Booking\ReservationRequestRequestContract;
use App\Contracts\Booking\ShowContract;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class Client implements BookingClientContract
{
    private PendingRequest $client;
    public function __construct(
        private readonly string $host,
    ) {
        $this->client = Http::withHeaders(['Authorization' => $this->getToken(), 'Content-Type' => 'application/json']);
    }

    private function getToken(): string
    {
        return "Bearer pmN3TQFQalcOhCwZc18KcPMWZyG2EQHz8al9sCYw"; // todo реализация поучения токена
    }

    /**
     * @param array<string, mixed> $params
     * @throws ExternalApiError
     */
    public function shows(array $params = []): array
    {
        try {
            return $this->response(
                'shows',
                $this->client->get("$this->host/shows", $params)->json()
            );
        } catch (\Throwable $exception) {
            logger()->error($exception);
            throw new ExternalApiError($exception->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $params
     * @throws ExternalApiError
     */
    public function events(int $showId, array $params = []): array
    {
        try {
            return $this->response(
                'events',
                $this->client->get("$this->host/shows/$showId/events", $params)->json()
            );
        } catch (\Throwable $exception) {
            logger()->error($exception);
            throw new ExternalApiError($exception->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $params
     * @throws ExternalApiError
     */
    public function places(int $eventId, array $params = []): array
    {
        try {
            return $this->response(
                'places',
                $this->client->get("$this->host/events/$eventId/places", $params)->json()
            );
        } catch (\Throwable $exception) {
            logger()->error($exception);
            throw new ExternalApiError($exception->getMessage());
        }
    }

    /**
     * @throws ExternalApiError
     */
    public function reserve(int $eventId, ReservationRequestRequestContract $data): ReservationContract
    {
        try {
            return $this->response(
                'reserve',
                $this->client->asForm()->post("$this->host/events/$eventId/reserve", $data->toArray())->json()
            );
        } catch (\Throwable $exception) {
            logger()->error($exception);
            throw new ExternalApiError($exception->getMessage());
        }
    }

    /**
     * @throws ExternalApiError|ReservationFailedException
     */
    private function response(string $aggregation, array $response): array|ReservationContract
    {
        if (! isset($response['response'])) {
            logger()->error("Wrong response format {$aggregation}", $response);
            throw new ExternalApiError($response['error'] ?? "Something went wrong");
        } // todo завязка на формат ответа глобально, но решил что это не большая проблема тут

        $data = $response['response'];
        return match ($aggregation) {
            'shows' => array_map(fn (array $item) => app(ShowContract::class, $item), $data),
            'events' => array_map(fn (array $item) => app(EventContract::class, $item), $data),
            'places' => array_map(fn (array $item) => app(PlaceContract::class, $item), $data),
            'reserve' => app(ReservationContract::class, ['data' => $data]),
        };
    }
}
