<?php

namespace Ammonkc\WpApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class JwtAuthRequest extends FormRequest
{
    protected $rules = [
        'username'    => ['required', 'string'],
        'password'    => ['required', 'string'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true; //Gate::allows('admin.user.edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        $rules = $this->rules;

        return $rules;
    }
}
