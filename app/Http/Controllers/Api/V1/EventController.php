<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Booking\BookingClientContract;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class EventController extends Controller
{
    #[OA\Get(
        path: "/shows/{showId}/events",
        summary: "Получить список событий мероприятия",
        tags: ["Мероприятия"],
        parameters: [
            new OA\Parameter(
                name: "showId",
                description: "ID мероприятия",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ со списком событий",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "response",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: "id",
                                        description: "ID события",
                                        type: "integer",
                                        example: 46
                                    ),
                                    new OA\Property(
                                        property: "showId",
                                        description: "ID мероприятия",
                                        type: "integer",
                                        example: 10
                                    ),
                                    new OA\Property(
                                        property: "date",
                                        description: "Дата и время события",
                                        type: "string",
                                        format: "date-time",
                                        example: "2019-08-22 20:26:38"
                                    )
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
                        new OA\Property(property: "message", type: 'string', example: "Place #5 not available")
                    ]
                )
            )
        ]
    )]
    public function index(int $showId, BookingClientContract $bookingClient)
    {
        return response()->json($bookingClient->events($showId));
    }
}
