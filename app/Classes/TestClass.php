<?php

namespace App\Classes;

use App\Models\Endpoint;
use App\Models\Test_setting;
use Exception;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use stdClass;

class TestClass
{
    private $request_status;
    private Test_setting $test_setting;
    /**
     * constructor
     */
    public function __construct()
    {
        $this->test_setting = Test_setting::first();
    }

    /**
     * login_test_user
     */
    public function login_test_user()
    {
        $response = Curl::to($this->test_setting->base_url . '/login')
            ->withData(array('email' => $this->test_setting->test_user_email, 'password' => $this->test_setting->test_user_password))
            ->asJsonResponse()->returnResponseObject()
            ->asJson(true)->post();
        Log::info('::::::::::Logged in test user:::::::::');
        if ($response->status == 200) {
            Log::info($response->content['message']);
            $this->test_setting->auth_token = $response->content['auth_token'];
            $this->test_setting->save();
            return true;
        }
        return false;
        // Log::info(json_encode($response));
    }

    public function getEndPoint(Endpoint $endpoint)
    {
        try {
            $response = Curl::to($this->test_setting->base_url . $endpoint->link)
                ->withBearer($this->test_setting->auth_token)
                ->asJsonResponse()->returnResponseObject();
            $response = $response->get();
        } catch (Exception $e) {
            Log::error($e);
            $response = [
                'status' => 'error',
                'message' => 'endpoint call failed'
            ];
        } finally {
            Log::info('back from endpoint');
        }


        // return json_decode($response, true);
        return $this->processRequestResponse($response, $endpoint);
    }

    public function processRequestResponse(stdClass $response, $endpoint)
    {
        // $response = json_decode($response, true);
        if ($response->status == 200) {
            $response = [
                'status' => 'success',
                'status_code' => $response->status,
                'message' => 'endpoint is live'
            ];
        } else {
            $response = [
                'status' => 'error',
                'status_code' => $response->status,
                'message' => 'endpoint is down'
            ];
        }
        $response['route'] = "$endpoint->name ($endpoint->link)";
        return $response;
    }
}
