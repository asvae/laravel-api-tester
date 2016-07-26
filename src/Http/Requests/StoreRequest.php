<?php

namespace Asvae\ApiTester\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 *
 * @package \Asvae\ApiTester\Http\Requests
 */
class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'method'  => 'string|in:GET,HEAD,POST,PUT,PATCH,DELETE|required',
            'headers' => 'array|required',
            'path'    => 'string|required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
