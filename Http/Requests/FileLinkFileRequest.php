<?php

namespace Modules\FileLinkModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\FileLinkModule\Entities\FileLink;

class FileLinkFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', FileLink::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            //  Empty array as no paramaters are actually passed
        ];
    }
}
