<?php
namespace App\Validators;

class Recaptcha
{
    /**
     * Validate the recaptcha
     *
     * @param  string    $attribute  The attribute
     * @param  string    $value      The value
     * @param  array     $parameters The parameters
     * @return boolean
     */
    public function validateRecaptcha($attribute, $value, $parameters)
    {
        $values = $this->buildValues($value);
        $check  = $this->request($values);

        if ($check->success == true) {
            return true;
        }

        return false;
    }

    /**
     * Get remote ip
     *
     * @return string
     */
    public function getRemoteIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Build the values for the check
     *
     * @param  string  $value The response value
     * @return array
     */
    protected function buildValues($value)
    {
        return [
            'secret'   => env('RECAPTCHA_PRIVATE_KEY'),
            'response' => $value,
            'remoteip' => $this->getRemoteIPAddress(),
        ];
    }

    /**
     * Send the request
     *
     * @param  array      $data The data array
     * @return stdClass
     */
    protected function request($data)
    {
        $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response;
    }
}
