<?php

namespace App\Http\Requests\Blog\Tags;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class CreateTag extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    public function validateInput(): void
    {
        $validator = Validator::make($this->request->all(), [
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        $validator->validate();

        if ($validator->errors()->all()) {
            throw new ValidationException($validator);
        }
    }

    public function getData(): array
    {
        $params = $this->all();
        $params['id'] = Uuid::uuid4()->toString();

        return $params;
    }
}
