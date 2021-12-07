<?php

namespace Modules\FileLinkModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\FileLinkModule\Entities\FileLink;

class FileLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if($this->route('id'))
        {
            return $this->user()->can('edit', FileLink::find($this->route('id')));
        }

        return $this->user()->can('create', FileLink::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'link_name'        => 'required|string',
            'expire'           => 'required|date',
            'allow_upload'     => 'required|boolean',
            'has_instructions' => 'required|boolean',
            'instructions'     => 'nullable',
        ];
    }
}
