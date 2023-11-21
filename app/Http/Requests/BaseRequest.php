<?php

namespace App\Http\Requests;

use App\Http\Helpers\HttpHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => 'the :attribute is required!',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        HttpHelper::errorValidation($validator->errors()->first(), $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
