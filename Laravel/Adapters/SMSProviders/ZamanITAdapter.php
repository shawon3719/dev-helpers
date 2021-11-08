<?php

namespace App\Adapters\SMSProviders;

use App\Interfaces\SMSInterface;
use App\Services\CurlService;
use Exception;

class ZamanITAdapter implements SMSInterface
{
    private $curl;

    private $api;
    private $user;
    private $password;
    private $sender;
    private $api_key;

    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;

        $smsConfiguration = config('sms.zaman-it');
        $this->api = $smsConfiguration['api'];
        $this->user = $smsConfiguration['user'];
        $this->password = $smsConfiguration['password'];
        $this->sender = $smsConfiguration['sender'];
        $this->api_key = $smsConfiguration['api_key'];

        if(is_null($this->api) || is_null($this->user) || is_null($this->password))
        {
            throw new Exception('Invalid SMS gateway configuration');
        }
    }

    public function send(string $to, string $message) :array
    {
        $data = [
            "api_key" => $this->api_key,
            "type" => 'text',
            "contacts" => $to,
            "senderid" => $this->sender,
            "msg" => urlencode($message),
        ];

        $queryString = http_build_query($data);

        $url = "{$this->api}/smsapi?{$queryString}";

        $response = $this->curl::get($url);

        return $response;
    }

    public function getBalance() :array
    {
        $url = "{$this->api}/miscapi/{$this->api_key}/getBalance";

        $response = $this->curl::get($url);

        return $response;
    }

    public function getDeliveryReport () :array
    {
        $url = "{$this->api}/miscapi/{$this->api_key}/getDLR/getAll";

        $response = $this->curl::get($url);

        return $response;
    }

    public function getAPIKey() :array
    {
        $url = "{$this->api}/getkey/{$this->user}/{$this->password}";

        $response = $this->curl::get($url);

        return $response;
    }
}