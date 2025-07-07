<?php

declare(strict_types=1);

namespace App\Providers;

use App\Api\Booking\Client;
use App\Api\Booking\LeadBook\DTOs\Event;
use App\Api\Booking\LeadBook\DTOs\Place;
use App\Api\Booking\LeadBook\DTOs\Reservation;
use App\Api\Booking\LeadBook\DTOs\ReservationRequestData;
use App\Api\Booking\LeadBook\DTOs\Show;
use App\Contracts\Booking\BookingClientContract;
use App\Contracts\Booking\EventContract;
use App\Contracts\Booking\PlaceContract;
use App\Contracts\Booking\ReservationContract;
use App\Contracts\Booking\ReservationRequestRequestContract;
use App\Contracts\Booking\ShowContract;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

final class BookingProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookingClientContract::class, fn () => new Client(
            mb_rtrim(config('services.leadbook.host'), '/'),
        ));

        $this->app->bind(EventContract::class, static function (Application $app, array $data) {
            $data['date'] = Carbon::parse($data['date']);

            return new Event(...$data);
        });
        $this->app->bind(PlaceContract::class, static function (Application $app, array $data) {
            $data['isAvailable'] = $data['is_available'];
            unset($data['is_available']);

            return new Place(...$data);
        });
        $this->app->bind(ShowContract::class, Show::class);
        $this->app->bind(ReservationContract::class, Reservation::class);
        $this->app->bind(ReservationRequestRequestContract::class, ReservationRequestData::class);
    }
}
