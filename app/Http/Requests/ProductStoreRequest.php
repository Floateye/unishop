<?php

namespace App\Http\Requests;

use App\Enum\PermissionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows(PermissionType::ProductCreate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>['required','string','max:255'],
            'price' =>['required','numeric'],
            'image' =>['required','image','mimes:jpeg,png,jpg'],
            'quantity' =>['required','numeric','min:1'],
            'description' =>['nullable','string','max:255'],
        ];
    }
}
