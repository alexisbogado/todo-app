<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send JSON response with status code
     *
     * @param array $data
     * @param integer $code
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function sendJsonResponse(array $data, $code = 200)
    {
        $result = [
            'success' => in_array($code, [200, 201]),
            'code' => $code,
            'contents' => $data,
        ];
        
        return response($result, $code);
    }

    /**
     * Validate request data
     *
     * @param Request $request
     * @param array $array
     * @param bool $arrayOfObjects
     * 
     * @return object
     */
    protected function validator(
        Request $request,
        array $array,
        bool $arrayOfObjects = false)
    {
        $data = $arrayOfObjects ?
            $request->all() :
            $request->only(array_keys($array));

        $validator = validator($data, $array);

        if ($validator->fails()) {
            $data = [ 'errors' => $this->getValidatorErrors($validator) ];
        }

        return (object)[
            'is_valid' => (!$validator->fails()),
            'content' => $data
        ];
    }

    /**
     * Get validator error messages
     *
     * @param Validator $validator
     * 
     * @return array
     */
    private function getValidatorErrors(Validator $validator)
    {
        return array_merge(...array_map(function ($key) use ($validator) {
            return [ $key => $validator->errors()->first($key) ];
        }, $validator->errors()->keys()));
    }
}
