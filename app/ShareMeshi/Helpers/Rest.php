<?php namespace App\ShareMeshi\Helpers;

use Response;

/**
 *
 */
class Rest
{
    public static function success($mainResponse = array(), $outsideResponseData = array())
    {
        $response = [
            'type' => 'success',
            'data' => $mainResponse
            //'alert_type' => 'success'
        ];

        if(!is_array($outsideResponseData)){
            $outsideResponseData = (array)$outsideResponseData;
        }

        return Response::json($response + $outsideResponseData);
    }

    public static function successToken($token, $data = array())
    {
        return self::success($data, ['token' => $token]);
    }

    public static function updateSuccess($updated = true, $data = array(), $optionalOutsideResponse = array())
    {
        return Rest::success($data, [
            'updated' => $updated,
        ] + $optionalOutsideResponse);
    }

    public static function deleteSuccess($deleted = true, $data = array(), $optionalOutsideResponse = array())
    {
        return Rest::success($data, [
            'deleted' => $deleted,
        ] + $optionalOutsideResponse);
    }

    public static function failed($message, $data = array(), $outsideResponse = array(), $statusCode = 500)
    {
        $response = [
            'type' => 'error',
            //'alert_type' => 'danger',
            'message' => $message,
            'data' => $data
        ];

        return Response::json($response + $outsideResponse, $statusCode);

    }

    public static function notFound($message, $additionalData = array(), $outsideResponse = array(), $statusCode = 404)
    {
        return self::failed($message, $additionalData, $outsideResponse, $statusCode);
    }

    public static function validationFailed($validator, $data = array(), $message = "")
    {
        return Response::json([
            'type' => 'error',
            //'alert_type' => 'danger',
            'errors' => $validator->getMessageBag()->toArray(),
            'data' => $data,
            'message' => $message
        ], 400);
    }

    public static function rawList($list)
    {
        return Response::json($list);
    }

    public static function invalidCredentials(array $data = [], $message = "")
    {
        if($message == ""){
            $message = 'Invalid credentials.';
        }

        return Response::json([
            'type' => 'error',
            'message' => $message,
            'data' => $data
        ], 401);
    }
}