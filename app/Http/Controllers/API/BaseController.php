<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $result, 'message' => $message], 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $error, 'data' => $errorMessages], $code);
    }
}
