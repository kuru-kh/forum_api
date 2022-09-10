<?php
namespace App\Traits;
trait ResponseTrait
{
    public function jsonResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }
}