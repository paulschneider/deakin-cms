<?php
namespace App\Repositories;

class CredentialsRevisionsRepository extends BasicRepository
{
    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\CredentialRevision';
    }
}
