# Sms notifications channel for Laravel 5.8

This package makes it easy to send SMS notifications for some sms providers with Laravel 5.8.

Supported SMS providers:
* [SMS.RU](http://sms.ru)
* [SMSC.RU](https://smsc.ru)

## Installation

Add SmsNotification package to your Laravel project via composer:

``` bash
composer required yudina/laravel-sms
```

Then publish configuration file:
``` bash
php artisan vendor:publish --provider="Yudina\LaravelSms\SmsSenderServiceProvider"
```

## Settings up your account for select sms provider

Add the environment variables to your config/services.php:

``` bash
// config/services.php
...
'default' => env('SMS_PROVIDER', 'smscru'),

'providers' => [
    'smsru' => [
        'api_id' => env('SMSRU_API_ID')
    ],
    'smscru' => [
        'login' => env('SMSCRU_LOGIN'),
        'password' => env('SMSCRU_PASSWORD'),
        'sender' => env('SMSCRU_SENDER')
    ],
    ...
]
...
```

Add your necessary keys to your .env:
``` bash
 // .env
 SMSRU_API_ID=
 ...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use Yudina\LaravelSms\SmsSenderChannel;
use Yudina\LaravelSms\SmsSenderMessage;

use Illuminate\Notifications\Notification;

class CodeGenerationNotification extends Notification
{
    public function via($notifiable)
    {
        return [SmsSenderChannel::class];
    }

    public function toSms($notifiable)
    {
        $message = 'Activation code: ' . $this->generateCode(6);
    
        return new SmsSenderMessage($message);
    }
    
    private function generateCode(int $length): string
    {
        $result = '';

        for($i = 0; $i < $length; $i++)
            $result .= mt_rand(0, 9);

        return $result;
    }
}
```
