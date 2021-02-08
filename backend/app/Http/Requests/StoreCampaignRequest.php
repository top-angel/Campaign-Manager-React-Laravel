<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
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
            'name' => 'required|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|date_format:Y-m-d',
            'daily_budget' => 'required|numeric',
            'total_budget' => 'required|numeric',
            'active' => 'required|boolean',
            'creative_uploads.*' => 'mimes:png,jpg,jpeg|image',
        ];
    }
}
