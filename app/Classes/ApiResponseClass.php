<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Enums\HttpCodesEnum;

class ApiResponseClass
{
    public static function rollback($e, $message = "Something went wrong! Process not completed")
    {
        DB::rollBack()    ;
        self::throw($e, $message);
    }

    public static function throw($e, $message =  "Something went wrong! Process not completed", $code = HttpCodesEnum::INTERNAL_SERVER_ERROR)
    {
        Log::error($e);
        throw new HttpResponseException(response()->json([
            'message' => $message
        ],$code->value));
    }

    public static function sendResponse($result, $message, $code = HttpCodesEnum::OK){
        $response=[
            'success' => true,
            'data' => $result
        ];
        if(!empty($message)){
            $response['message'] = $message;
        }

        return response()->json($response, $code->value);
    }
}
