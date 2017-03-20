<?php namespace App\Http\Controllers\Admin;

use App\Repositories\PagesRepository;

class RevisionsPageController extends RevisionsController
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
    protected $admin_route = 'admin.pages';

    public function __construct(PagesRepository $repository)
    {
        $this->repository = $repository;
    }
}
