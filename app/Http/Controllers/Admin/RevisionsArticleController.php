<?php namespace App\Http\Controllers\Admin;

use App\Repositories\ArticlesRepository;

class RevisionsArticleController extends RevisionsController
{
    /**
     * ArticlesRepository
     * @var ArticlesRepository
     */
    protected $repository;

    /**
     * Admin Route Base
     * @var string
     */
    protected $admin_route = 'admin.articles';

    public function __construct(ArticlesRepository $repository)
    {
        $this->repository = $repository;
    }
}
