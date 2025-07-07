<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Booking\BookingClientContract;
use App\Contracts\Booking\ShowContract;
use App\Http\Controllers\Api\V1\ShowController;
use Mockery\MockInterface;
use Tests\TestCase;

final class ShowControllerTest extends TestCase
{
    private MockInterface $bookingClientMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingClientMock = $this->mock(BookingClientContract::class);
        $this->controller = new ShowController;
    }

    /** @test */
    public function it_returns_successful_response_with_shows_list(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Test Show 1'],
            ['id' => 2, 'name' => 'Test Show 2'],
        ];
        $mockShows = [];

        foreach ($data as $datum) {
            $mockShows[] = app(ShowContract::class, $datum);
        }

        $this->bookingClientMock
            ->shouldReceive('shows')
            ->once()
            ->andReturn($mockShows);

        $response = $this->get(route('shows.index'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['response' => $data], $response->json());
    }

    /** @test */
    public function it_handles_empty_shows_list(): void
    {
        $this->bookingClientMock
            ->shouldReceive('shows')
            ->once()
            ->andReturn([]);

        $response = $this->get(route('shows.index'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['response' => []], $response->json());
    }
}
