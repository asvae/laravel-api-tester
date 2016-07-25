<?php

namespace Asvae\ApiTester\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package \Asvae\ApiTester\Http\Requests
 */
class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            //todo: implement rules
        ];
    }

    public function authorize(){
        return true;
    }
}
