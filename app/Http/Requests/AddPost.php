<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class AddPost extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:30000',
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
        $params['author_id'] = Auth::id();

        return $params;
    }
}
