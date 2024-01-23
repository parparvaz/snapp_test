<?php

namespace App\Http\Requests;

use App\Packages\Response\ResponseFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SwapRequest extends FormRequest
{
    public function rules()
    {
        return [
            'source' => ['required', 'exists:card_numbers,card_number', 'different:destination', 'ir_bank_card_number'],
            'destination' => ['required', 'exists:card_numbers,card_number', 'different:source', 'ir_bank_card_number'],
            'balance' => ['required', 'gte:1000', 'lte:50000000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('source')) {
            $this->merge([
                'source' => convertToEnglish($this->get('source'))
            ]);
        }
        if ($this->has('destination')) {
            $this->merge([
                'destination' => convertToEnglish($this->get('destination'))
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $data = array();

        foreach ($validator->errors()->messages() as $name => $error) {
            $data[$name] = implode(', ', $error);
        }

        $response = ResponseFormatter::entity($data);

        throw new HttpResponseException(response()->json($response, $response['code']));
    }
}
