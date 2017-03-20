<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Client;

class Slack
{
    public function exception(Exception $e)
    {
        $line    = $e->getLine();
        $file    = $e->getFile();
        $message = $e->getMessage();

        $built = 'Website has encountered an error on line ' . $line . ' of ' . $file . PHP_EOL . PHP_EOL . '`' . $message . '`';

        $payload = (object) [
            'username'   => 'Laravel Exception',
            'icon_emoji' => ':ghost:',
            'text'       => $built,
        ];

        $client = new Client();
        $client->post(env('SLACK_URL'), [
            'form_params' => [
                'payload' => json_encode($payload),
            ],
        ]);
    }
}
