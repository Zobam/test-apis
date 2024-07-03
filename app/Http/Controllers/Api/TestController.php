<?php

namespace App\Http\Controllers\Api;

use App\Classes\TestClass;
use App\Http\Controllers\Controller;
use App\Mail\TestDone;
use App\Models\Endpoint;
use App\Models\Test_result;
use App\Models\Test_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use stdClass;

class TestController extends Controller
{
    private $reached_last_endpoint = false;
    public function index()
    {
        $this->process_test_results();
        return;

        $responses = [];

        $endpoints = $this->get_endpoints();
        if (!$endpoints) {
            return 'No endpoint to test';
        }
        $test_class = new TestClass();
        // attempt test_user login 
        if ($test_class->login_test_user()) {
            foreach ($endpoints as $endpoint) {
                $response = $test_class->getEndPoint($endpoint);
                $endpoint->test_results()->create([
                    'status' => $response['status'],
                    'status_code' => $response['status_code'],
                ]);
                $responses[] = $response;
            }
            if ($this->reached_last_endpoint) {
                // once the last endpoints has been tested, process and send test result
                Log::info(':::::::::Fetched the last batch of endpoints: ' . Endpoint::count());
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
            $this->reached_last_endpoint = true;
        }
        $test_settings->save();

        return $endpoints;
    }

    /**
     * process_test_results
     */
    public function process_test_results()
    {
        $endpoints_count = Endpoint::count();
        $last_test_results = Test_result::orderBy('created_at', 'desc')->take($endpoints_count)->with('endpoint')->get();
        // Log::info($last_test_results);
        $passed_tests = $last_test_results->where('status', 'success')->values();
        $failed_tests = $last_test_results->where('status', 'error')->values();

        $test_data = new stdClass();
        $test_data->has_failed_tests = count($failed_tests) > 0;
        // only send email if there are failed tests
        if ($test_data->has_failed_tests) {
            $test_data->failed_tests = $last_test_results->where('status', 'error')->values();
            $test_data->passed_tests = $last_test_results->where('status', 'success')->values();
            // add 1 because of the login endpoint which will always have to pass to get here
            $test_data->tested_endpoint_count = $last_test_results->count() + 1;
            $test_data->failure_percent = round(100 * count($test_data->failed_tests) / $test_data->tested_endpoint_count, 1);
            $test_data->base_url = Test_setting::first()->base_url;
            Mail::to('chizoba@bexit.com.ng')->send(new TestDone($test_data));
        }
    }
}
