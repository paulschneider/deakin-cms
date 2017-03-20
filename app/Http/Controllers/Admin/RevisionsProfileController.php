<?php namespace App\Http\Controllers\Admin;

use App\Repositories\ProfilesRepository;

class RevisionsProfileController extends RevisionsController
{
    /**
     * ProfilesRepository
     * @var ProfilesRepository
     */
    protected $repository;

    /**
     * Admin Route Base
     * @var string
     */
    protected $admin_route = 'admin.profiles';

    /**
     * Allow previews
     * @var string
     */
    protected $preview;

    public function __construct(ProfilesRepository $repository)
    {
        $this->repository = $repository;
        $this->preview    = false;
    }
}
