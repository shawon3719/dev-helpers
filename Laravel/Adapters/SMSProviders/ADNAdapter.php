<?php

namespace App\Adapters\SMSProviders;

use App\Interfaces\SMSInterface;
use App\Services\CurlService;
use App\Services\ErrorNotifierService;
use Exception;

class ADNAdapter implements SMSInterface
{
    private $curl;
    private $api;
    private $api_key;

    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;

        $smsConfiguration = config('sms.adn');
        $this->api = $smsConfiguration['api'];
        $this->sender = $smsConfiguration['sender'];
        $this->api_key = $smsConfiguration['api_key'];
        $this->api_secret = $smsConfiguration['api_secret'];
        if(is_null($this->api) || is_null($this->api_key) || is_null($this->api_secret))
        {
            throw new Exception('Invalid SMS gateway configuration');
        }
    }

    public function send(string $to, string $message) :array
    {
        $data = [
            "api_key" => $this->api_key,
            'api_secret' => $this->api_secret,
            'request_type' => "SINGLE_SMS",
            'message_type' => "TEXT",
            'mobile' => trim($to),
            'message_body' => $message,
            'isPromotional' => 0,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/send-sms?{$queryString}";

        $response = $this->curl::post($url);

        return $this->parseResponse($response, $to);
    }
    public function balance() :array
    {
        $data = [
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/check-balance?{$queryString}";

        $response = $this->curl::post("{$url}");

        return $this->parseResponse($response);
    }

    public function campaignStatus($campaign_uid) :array
    {
        $data = [
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
            'campaign_uid' => $campaign_uid,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/campaign-status?{$queryString}";

        $response = $this->curl::post("{$url}");

        return $this->parseResponse($response);
    }

    public function smsStatus($sms_uid) :array
    {
        $data = [
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
            'sms_uid' => $sms_uid,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/sms-status?{$queryString}";

        $response = $this->curl::post("{$url}");

        return $this->parseResponse($response);
    }

    public function sendOTP(string $to, string $message) :array
    {
        $data = [
            "api_key" => $this->api_key,
            'api_secret' => $this->api_secret,
            'request_type' => "OTP",
            'message_type' => "TEXT",
            'mobile' => trim($to),
            'message_body' => $message,
            'isPromotional' => 0,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/send-sms?{$queryString}";

        $response = $this->curl::post($url);

        return $this->parseResponse($response, $to);
    }

    public function sendBulkSMS(string $to, string $message, string $campaign_title) :array
    {
        $data = [
            "api_key" => $this->api_key,
            'api_secret' => $this->api_secret,
            'request_type' => "GENERAL_CAMPAIGN",
            'message_type' => "TEXT",
            'mobile' => $to,
            'message_body' => $message,
            'campaign_title' => $campaign_title,
            'isPromotional' => 1,
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/api/v1/secure/send-sms?{$queryString}";

        $response = $this->curl::post($url);

        return $this->parseResponse($response, $to);
    }

    private function parseResponse(array $response, string $destination = null)
    {
        if($response['status'] === 'success')
        {
            return [
                'status' => 'success',
                'message' => $response['message'],
                'gateway_response' => $response['rawResponse'],
                'data' => $response['data'],
            ];
        }
        
        if($response['status'] === 'error')
        {
            if(is_null($destination))
            {
                $errorMessage = "SMS Error:\n Gateway Own Error Code: {$response['data']['error']['error_code']}\n Gateway Own Error Message: {$response['data']['error']['error_message']}\n Gateway Response : {$response['rawResponse']}\n" ;
            }else
            {
                $errorMessage = "SMS Error:\n Phone Number:{$destination} \n Gateway Own Error Code: {$response['data']['error']['error_code']}\n Gateway Own Error Message: {$response['data']['error']['error_message']}\n Gateway Response : {$response['rawResponse']}\n" ;
            }

            ErrorNotifierService::notifyError( $errorMessage);
            return [
                'status' => 'error',
                'message' => $response['message'],
                'gateway_response' => $response['rawResponse'],
                'data' => $response['data'],
            ];
        }

    }
}
