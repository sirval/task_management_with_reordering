<?php
namespace App\Trait;

trait Utils
{
    protected function resp(bool $response, int $status, string $message=null, mixed $data )
    {
        return response()->json([
            "response" => $response,
            "status" => $status,
            "message" => $message,
            'data' => $data
        ]);
    }
}
