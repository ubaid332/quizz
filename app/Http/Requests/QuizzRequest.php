<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuizzRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'questions' => 'required|array',
            'questions.*.question' => 'required',
            'questions.*.mandatory' => ['nullable', Rule::in(['1', '0'])],
            'questions.*.answers' => 'required|array',
            'questions.*.answers.*.answer' => 'required',
            'questions.*.answers.*.right_answer' => ['nullable', Rule::in(['1', '0'])],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
        'status' => true
        ], 422));
    }
}
