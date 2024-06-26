<?php

namespace App\Http\Controllers\Api;

use App\Classes\TestClass;
use App\Http\Controllers\Controller;
use App\Models\Endpoint;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $responses = [];

        $endpoints = Endpoint::get();
        if (!$endpoints) {
            return 'No endpoint to test';
        }
        $test_class = new TestClass();
        // attempt test_user login 
        if ($test_class->login_test_user()) {
            foreach ($endpoints as $endpoint) {
                $responses[] = $test_class->getEndPoint($endpoint);
            }
            return $responses;
        }
        return response()->json(
            [
                'status' => 'error',
                'message' => 'could not authenticate test user',
            ],
            403
        );
    }
}
