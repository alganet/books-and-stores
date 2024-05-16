<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssociateBookRequest extends FormRequest
{
    /**
     * Rules to apply when associating books with stores
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'required',
            'book_id' => 'required',
        ];
    }
}
