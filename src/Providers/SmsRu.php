<?php

namespace Yudina\LaravelSmsNotification\Providers;


class SmsRu extends SMS
{
    private $driver = 'smsru';
    private $api_id;
    private $url;

    public function create(array $config): ISms {
        foreach ($config as $key => $entry) {
            if ($key === 'api_id') {
                $this->api_id = $entry;
            } else if ($key === 'url') {
                $this->url = $entry;
            }
        }

        return $this;
    }

    public function isSupport(string $driver): bool
    {
        return $driver === $this->driver;
    }

    protected function createSenderUrl(string $msg, $phones)
    {
        return  "{$this->url}/sms/send?api_id={$this->api_id}&to={$phones}&msg={$msg}&json=1";
    }

    protected function createCheckCostUrl(string $msg, $phones)
    {
        return "{$this->url}/sms/cost?api_id={$this->api_id}&to={$phones}&msg={$msg}&json=1";
    }

    protected function createBalanceUrl()
    {
        return "{$this->url}/my/balance?api_id={$this->api_id}&json=1";
    }

    protected function analyseSendMessageResponse($response)
    {
        if ($response == null || $response->status_code != 100) {
            return false;
        }

        return true;
    }

    protected function analyseGetBalanceResponse($response)
    {
        if ($response == null || !isset($response->balance)) {
            return -1;
        }

        return $response->balance;
    }

    protected function analyseGetMessageCostResponse($response)
    {
        if ($response == null || !isset($response->total_cost)) {
            return -1;
        }

        return $response->total_cost;
    }
}
