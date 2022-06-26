<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;

class SubscriberRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $fieldIds = array_map(fn($field) => $field['id'], $this->fields ?? []);
        $fields = Field::whereIn('id', $fieldIds)->get();

        return [
            'name' => 'required|string',
            'email' => 'required|email:dns',
            'state' => 'sometimes|nullable|in:active,unsubscribed,junk,bounced,unconfirmed',
            'fields' => 'sometimes|nullable|array',
            'fields.*.id' => 'required|exists:fields',
            'fields.*.value' => [
                'required',
                function($attribute, $value, $fail) use ($fields) {
                    $exploded = explode('.', $attribute);
                    $index = $exploded[1];
                    
                    $fieldId = $this->fields[$index]['id'];
                    $field = $fields->first(fn($field) => $field->id === $fieldId);

                    $validation = $field->type;

                    if($validation == 'number') {
                        $validation = 'numeric';
                    }

                    $validator = 'validate' . ucfirst($validation);
                    if(!$this->validator->$validator($attribute, $value)) {
                        $fail('Field ' . $field->title . ' must be a ' . $field->type);
                    }
                }
            ],
        ];
    }
}
