<?php
namespace App\Http\Controllers\Admin;

use App\Forms\FormHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubmissionFormRequest;
use App\Repositories\SubmissionsRepository;
use Flash;
use Redirect;
use URL;

class SubmissionsController extends Controller
{
    /**
     * The form handler
     *
     * @var FormHandler
     */
    protected $form;

    /**
     * Submissions Repository
     *
     * @var SubmissionsRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormHandler $form, SubmissionsRepository $repository)
    {
        $this->form       = $form;
        $this->repository = $repository;
    }

    public function showList()
    {
        $routes = [
            'admin.submissions.type' => ['title' => 'Contact Form Submissions', 'type' => 'contact'],
        ];

        return view()->make('admin.submissions.list', compact('routes'));
    }

    /**
     * The index for a type
     *
     * @param  string     $type The type of form
     * @return Response
     */
    public function index($type)
    {
        $options     = ['orderBy' => ['created_at', 'DESC']];
        $submissions = $this->repository->getAllForType($type, true, $options);

        return view('admin.submissions.index', compact('submissions', 'type'));
    }

    /**
     * Edit or show a form
     *
     * @param  string     $type    The type of form
     * @param  integer    $form_id The form id
     * @return Response
     */
    public function edit($type, $form_id)
    {
        $submission = $this->repository->findOrFailType($form_id, $type);
        $fields     = $this->form->getFields($type, $submission);

        $this->form->stripInternal($fields, false);

        return view("admin.submissions.{$type}", compact('submission', 'type', 'fields'));
    }

    /**
     * Update the submission
     *
     * @param  string                $type    The type of form
     * @param  integer               $form_id The form id
     * @param  SubmissionFormRequest $request The request object
     * @return Response
     */
    public function update($type, $form_id, SubmissionFormRequest $request)
    {
        $submission = $this->repository->findOrFailType($form_id, $type);
        $this->repository->save($request, $form_id);

        Flash::success('Status updated.');

        return Redirect::route('admin.submissions.type.edit', [$type, $form_id]);
    }

    /**
     * Not restful delete url
     *
     * @param  string     $type    The type of submission
     * @param  int        $form_id The form id
     * @return Response
     */
    public function delete($type, $form_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.submissions.type.destroy', $type, $form_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this submission?',
                'title'        => 'Delete Submission',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string     $type    The type of submission
     * @param  int        $form_id The form id
     * @return Response
     */
    public function destroy($type, $form_id)
    {
        $this->repository->delete($form_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.submissions.type', $type);
    }
}
