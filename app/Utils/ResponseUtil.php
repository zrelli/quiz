<?php

namespace App\Utils;

class ResponseUtil
{
    /**
     * @param  mixed  $data
     */
    public static function makeResponse(string $message, $data): array
    {
        $response = [
            'success' => true,
            'data' => $data
        ];
        if ($message) {
            $response['message'] = $message;
        }
        return $response;
    }
    public static function makeError(string $message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];
        if (!empty($data)) {
            $res['data'] = $data;
        }
        return $res;
    }
}
