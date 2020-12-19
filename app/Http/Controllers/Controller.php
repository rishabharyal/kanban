<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data): \Illuminate\Http\JsonResponse
    {
        return response()->json($data);
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Unknown Error Occurred!', $code = 500): \Illuminate\Http\JsonResponse
    {
        return response()->json($message, $code);
    }
}
