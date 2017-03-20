<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CronFormRequest;
use App\Repositories\CronJobsRepository;
use Flash;
use URL;

class CronController extends Controller
{
    /**
     * The jobs repository
     *
     * @var CronJobsRepository
     */
    protected $jobs;

    /**
     * Dependency Injection
     *
     * @param CronJobsRepository $jobs The instance of CronJobsRepository
     */
    public function __construct(CronJobsRepository $jobs)
    {
        $this->jobs = $jobs;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $options = ['filter' => false];
        $jobs    = $this->jobs->paginate(15, $options);

        return view('admin.cron.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.z
     *
     * @return Response
     */
    public function create()
    {
        $years = range(date('Y'), date('Y', strtotime('now +5 years')));
        $years = array_combine($years, $years);

        return view('admin.cron.create', compact('years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CronFormRequest $request)
    {
        $job = $this->jobs->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.cron.edit', $job->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $job_id
     * @return Response
     */
    public function show($job_id)
    {
        $job = $this->jobs->findOrFail($job_id);

        return view('admin.cron.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $job_id
     * @return Response
     */
    public function edit($job_id)
    {
        $job   = $this->jobs->findOrFail($job_id, ['filter' => false]);
        $years = range(date('Y'), date('Y', strtotime('now +5 years')));
        $years = array_combine($years, $years);

        return view('admin.cron.edit', compact('job', 'years'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $job_id
     * @return Response
     */
    public function update($job_id, CronFormRequest $request)
    {
        $job = $this->jobs->save($request, $job_id, ['filter' => false]);

        Flash::success('Saved.');

        return redirect()->route('admin.cron.edit', $job->id);
    }

    /**
     * Not restful delete url
     *
     * @param  int        $job_id The job id
     * @return Response
     */
    public function delete($job_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.cron.destroy', $job_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this job?',
                'title'        => 'Delete Job',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $job_id
     * @return Response
     */
    public function destroy($job_id)
    {
        $this->jobs->delete($job_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.cron.index');
    }
}
