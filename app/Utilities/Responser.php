<?php
namespace App\Utilities;

use Illuminate\Http\Response;

trait Responser
{

    /**
     * @param  $data
     * @param  $code
     * @return void
     */
    public function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    /**
     * @param  $message
     * @param  $code
     * @return void
     */
    public function errorResponse($message, $code)
    {
        return response()->json($message, $code);
    }
}
