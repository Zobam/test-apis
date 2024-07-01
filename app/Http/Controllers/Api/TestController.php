<?php

namespace App\Http\Controllers\Api;

use App\Classes\TestClass;
use App\Http\Controllers\Controller;
use App\Models\Endpoint;
use App\Models\Test_setting;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $responses = [];

        $endpoints = $this->get_endpoints();
        if (!$endpoints) {
            return 'No endpoint to test';
        }
        $test_class = new TestClass();
        // attempt test_user login 
        if ($test_class->login_test_user()) {
            foreach ($endpoints as $endpoint) {
                $responses[] = $test_class->getEndPoint($endpoint);
            }
            return [
                'status' => 'success',
                'count' => count($responses),
                'data' => $responses,
            ];
        }
        return response()->json(
            [
                'status' => 'error',
                'message' => 'could not authenticate test user',
            ],
            403
        );
    }

    /**
     * get_endpoints
     */
    public function get_endpoints()
    {
        $take = 5;
        $test_settings = Test_setting::first();
        $endpoints = Endpoint::skip($test_settings->endpoints_offset)->take($take)->get();
        // update endpoints_offset
        if (($test_settings->endpoints_offset + $take) <= Endpoint::count()) {
            $test_settings->endpoints_offset += $take;
        } else {
            $test_settings->endpoints_offset = 0;
        }
        $test_settings->save();

        return $endpoints;
    }
}
