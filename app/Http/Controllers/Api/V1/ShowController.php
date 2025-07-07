<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Booking\BookingClientContract;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(version: '0.1', title: 'TestApi')]
#[OA\Server(
    url: "http://127.0.0.1:9000/api/v1",
    description: "API v1"
)]
class ShowController extends Controller
{
    #[OA\Get(
        path: "/shows",
        summary: "Получить список мероприятий",
        tags: ["Мероприятия"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ со списком мероприятий",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "response",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Show #1")
                                ],
                                type: "object"
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Ошибка",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: 'string', example: "Something went wrong")
                    ]
                )
            )
        ]
    )]
    public function index(BookingClientContract $bookingClient)
    {
        return response()->json(['response' => $bookingClient->shows()]);
    }
}
