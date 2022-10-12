<?php
/**
* FUNCTION FOR JSON RESPONSE
*/

if (! function_exists('jsonResponseData')) {
    function jsonResponseData($resCode,$resMessage,$response){
        return response()->json([
            'status_code' => $resCode,
            'message' => $resMessage,
            'data' => $response
        ]);
    }
}

?>