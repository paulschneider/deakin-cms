<?php
namespace App\Zendesk;

use Zendesk\API\HttpClient as ZendeskAPI;
use Zendesk\API\Exceptions\ApiResponseException;

class Api
{
    /**
     * $zendesk
     *
     * @var Zendesk\API\HttpClient
     */
    protected $zendesk;

    /**
     * API token to use with this class
     *
     * @var string
     */
    protected $token = 'p9drXkMGEHtGBJbFy9mnu4qu0Ak0sFFggXPqJZQ8';

    /**
     * the subdomain to use when instantiating the API
     *
     * @var string
     */
    protected $subdomain = 'deakindigital';

    /**
     * the username to use when instantiating the API
     * @var string
     */
    protected $username = 'michael.tan@deakindigital.com';

    /**
     * the fields a new ticket must contain
     *
     * @var array
     */
    protected $requiredFields = [
        'name'    => null,
        'email'   => null,
        'message' => null,
    ];

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->zendesk = new ZendeskAPI($this->subdomain, $this->username);
        $this->zendesk->setAuth('basic', ['username' => $this->username, 'token' => $this->token]);
    }

    /**
     * submit a new ticket to zendesk
     *
     * @param  array  $data   the data to submit
     * @param  string $source where should we say the ticket originated from
     * @return null
     */
    public function createTicket($data, $source = 'unknown')
    {
        // check to make sure we have all the field we need to create a ticket
        if (!$this->hasRequiredFields($data)) {
            return $false;
        }

        // map the supplied data to the fields we want to store
        $params = [
            'requester' => [
                'name'  => $data['name'],
                'email' => $data['email'],
            ],
            'subject'   => 'Enquiry',
            'comment'   => [
                'body' => $data['message'],
            ],
            'tags'      => [$source],
        ];

        // ... create the ticket
        return $this->zendesk->tickets()->create($params);
    }

    /**
     * retrieve a ticket with a given ID
     *
     * @param  int     $id the identifier for the ticket to locate
     * @return mixed
     */
    public function getTicket($id)
    {
        try {
            $response = $this->zendesk->tickets()->find($id);
        } catch (ApiResponseException $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    /**
     * delete a ticket
     *
     * @param  int     $id the identifier for the ticket to delete
     * @return mixed
     */
    public function deleteTicket($id)
    {
        try {
            $response = $this->zendesk->tickets()->delete($id);
        } catch (ApiResponseException $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    /**
     * check to see if the supplied data contains array items that match the required fields listing
     *
     * @param  array   $data the data to check
     * @return boolean pass / fail
     */
    private function hasRequiredFields($data)
    {
        // if we get an empty array back from this check then all fields are present
        // (array_diff_key would return an array containing the missing items)
        return count(array_diff_key($this->requiredFields, $data)) == 0 ? true : false;
    }
}
