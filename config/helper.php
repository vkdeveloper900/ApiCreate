<?php
if (!function_exists('successResponse')) {
    function successResponse($msg = '', $data = null)
    {
        return response()->json([
            'success' => true,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($msg = '',$data = null, $errCode = 500 )
    {
        return response()->json([
            'success' => false,
            'msg' => $msg,
            'err_code' => $errCode,
            'data' => $data,
        ]);
    }
}
