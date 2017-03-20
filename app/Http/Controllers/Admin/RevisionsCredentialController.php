<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\CredentialsRepository;

class RevisionsCredentialController extends RevisionsController
{
    /**
     * PagesRepository
     * @var PagesRepository
     */
    protected $repository;

    /**
     * Admin Route Base
     * @var string
     */
    protected $admin_route = 'admin.credentials';

    public function __construct(CredentialsRepository $repository)
    {
        $this->repository = $repository;
    }
}
