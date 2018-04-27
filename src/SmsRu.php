<?php

namespace luciuz\smsru;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class SmsRu
{
    /**
     * @var string
     */
    public $apiId;

    /**
     * @var string
     */
    public $from;

    /**
     * @var bool
     */
    public $translit = false;

    /**
     * @var bool
     */
    public $test = false;

    /**
     * @var int
     */
    public $partnerId;

    /**
     * @var string
     */
    public $url = 'https://sms.ru';

    /**
     * @var Client
     */
    protected $client;

    /**
     * SmsRu constructor.
     * @param string $apiId
     * @param string|null $from
     * @param bool $translit
     * @param bool $test
     * @param int|null $partnerId
     */
    public function __construct($apiId, $from = null, $translit = false, $test = false, $partnerId = null)
    {
        $this->apiId = $apiId;
        $this->from = $from;
        $this->translit = $translit;
        $this->test = $test;
        $this->partnerId = $partnerId;
        $this->client = new Client();
    }

    /**
     * @param string $method
     * @param array $params
     * @return \stdClass
     * @throws \Exception
     */
    public function do($method, $params = [])
    {
        $params['api_id'] = $this->apiId;
        $res = $this->client->request('POST', $this->url . '/' . $method, [
            RequestOptions::QUERY => ['json' => 1],
            RequestOptions::FORM_PARAMS => $params
        ]);
        return $this->handle(\GuzzleHttp\json_decode($res->getBody()));
    }

    /**
     * @param \stdClass $response
     * @param string|array $out
     * @return \stdClass
     * @throws \Exception
     */
    public function handle($response)
    {
        if ($response->status == 'ERROR') {
            throw new \Exception($response->status_text, $response->status_code);
        }
        unset($response->status);
        unset($response->status_code);
        $keys = array_keys((array) $response);
        if (count($keys) == 1) {
            return $response->{$keys[0]};
        }
        return $response;
    }

    /**
     * @param Sms $sms
     * @return \stdClass
     */
    public function send($sms)
    {
        return $this->do('sms/send', [
            'from' => $this->from,
            'translit' => (int) $this->translit,
            'test' => (int) $this->test,
            'to' => $sms->to,
            'msg' => $sms->msg
        ]);
    }

    /**
     * @param Sms[] $smsBundle
     * @return \stdClass
     */
    public function sendMulti($smsBundle)
    {
        $multi = [];
        foreach ($smsBundle as $sms) {
            $multi[$sms->to] = $sms->msg;
        }
        return $this->do('sms/send', [
            'from' => $this->from,
            'translit' => (int) $this->translit,
            'test' => (int) $this->test,
            'multi' => $multi
        ]);
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->do('/my/balance');
    }

    /**
     * @return array
     */
    public function getSenders()
    {
        return $this->do('/my/senders');
    }

    /**
     * @return \stdClass
     */
    public function getLimit()
    {
        return $this->do('/my/limit');
    }
}
