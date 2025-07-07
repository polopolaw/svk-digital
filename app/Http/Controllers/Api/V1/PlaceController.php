<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Booking\BookingClientContract;
use App\Contracts\Booking\ReservationRequestRequestContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservePlaceRequest;
use OpenApi\Attributes as OA;

class PlaceController extends Controller
{
    #[OA\Get(
        path: "/events/{eventId}/places",
        summary: "Получить список мест события",
        tags: ["Мероприятия"],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ со списком мест",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "response",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "x", type: "number", format: "float", example: 0.0),
                                    new OA\Property(property: "y", type: "number", format: "float", example: 0.0),
                                    new OA\Property(property: "width", type: "number", format: "float", example: 20.0),
                                    new OA\Property(property: "height", type: "number", format: "float", example: 20.0),
                                    new OA\Property(property: "is_available", type: "boolean", example: true)
                                ],
                                type: "object"
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function index(int $eventId, BookingClientContract $bookingClient)
    {
        return response()->json(['response' => $bookingClient->places($eventId)]);
    }

    #[OA\Post(
        path: "/events/{eventId}/reserve",
        summary: "Забронировать места события",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Иван Иванов"),
                    new OA\Property(
                        property: "places",
                        type: "array",
                        items: new OA\Items(type: "integer", example: 1)
                    )
                ],
                type: "object",
                example: [
                    "name" => "Иван Иванов",
                    "places" => [1, 2, 3]
                ]
            )
        ),
        tags: ["Мероприятия"],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешное бронирование",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "response",
                            properties: [
                                new OA\Property(property: "success", type: "boolean", example: true),
                                new OA\Property(property: "reservation_id", type: "string", example: "5d519fe58e3cf")
                            ],
                            type: "object"
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Ошибка валидации",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
                        new OA\Property(
                            property: "errors",
                            type: "object",
                            additionalProperties: new OA\AdditionalProperties(type: "string")
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
    public function reserve(int $eventId, BookingClientContract $bookingClient, ReservePlaceRequest $request)
    {
        return response()->json([
            'response' => $bookingClient->reserve(
                $eventId, app(ReservationRequestRequestContract::class, ['data' => $request->getData()])
            )
        ]);
    }
}
