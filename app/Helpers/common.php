<?php

/* start:API RESPONSE */
if (! function_exists('jsonResponse')) {      
    /**
     * jsonResponse
     *
     * @param  mixed $data
     * @param  mixed $message
     * @param  mixed $code
     * @return Illuminate\Http\JsonResponse
     */
    function jsonResponse(bool $status = true, string $message = 'Success', int $code = 200, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'row' => $data
        ], $code);
    }
}
/* end:API RESPONSE */