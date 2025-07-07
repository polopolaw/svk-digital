<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ReservePlaceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'places' => ['required', 'array'],
            'places.*' => ['required', 'int'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->guard()->guest(); // todo авторизация тут, в тз нет речи про авторизацию, поэтому гость
    }

    public function getData(): array
    {
        return $this->validated();
    }
}
