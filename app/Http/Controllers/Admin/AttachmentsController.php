<?php namespace App\Http\Controllers\Admin;

use Tax;
use URL;
use Flash;
use Attachment;
use Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Repositories\AttachmentsRepository;
use App\Http\Requests\Admin\AttachmentFormRequest;
use App\Http\Requests\Admin\AttachmentWysiwygRequest;
use App\Http\Requests\Admin\AttachmentSelectionRequest;

class AttachmentsController extends Controller
{
    /**
     * The attachment repository
     *
     * @var AttachmentsRepository
     */
    protected $attachment;

    /**
     * Dependency Injection
     *
     * @param AttachmentsRepository $attachment The instance of AttachmentsRepository
     */
    public function __construct(AttachmentsRepository $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $folder_id = $this->attachment->getPathId(true);
        return view('admin.attachments.index', compact('folder_id'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $folder_id   = $this->attachment->getPathId(true);
        $options     = ['filter' => false, 'scopes' => ['folder' => ['id' => $folder_id]]];
        $attachments = $this->attachment->query($options);

        return Datatables::of($attachments)
            ->addColumn('action', function ($attachment) {
                if (Request::get('insert') == "true") {
                    return '<button class="btn btn-success btn-outline btn-sm attachment-attach" data-file-id="' . $attachment->id . '" data-file-type="' . $attachment->file->contentType() . '">Use this file</button>';
                }
                return '<input type="checkbox" name="selected[]" value="' . $attachment->id . '" class="attachment-selection">';
            })
            ->addColumn('thumb', function ($attachment) {
                if (stristr($attachment->file->contentType(), 'image/')) {
                    return '<img src="' . $attachment->file->url('micro') . '" class="img-responsive" height="40" data-toggle="thumbnail" data-thumbnail="' . $attachment->file->url('thumb') . '">';
                }
                return '<span class="btn btn-default btn-circle nohover"><i class="fa fa-file"></i></span>';
            })
            ->addColumn('slugtext', function ($attachment) {
                if ($attachment->slug) {
                    return '<a href="' . url($attachment->slug) . '" class="btn btn-outline btn-xs">Friendly URL</a>';
                }
                return '-';
            })
            ->addColumn('edit', function ($attachment) {
                return link_to_route('admin.attachments.edit', 'Edit', $attachment->id, ['class' => 'btn btn-outline btn-success btn-xs']);
            })
            ->addColumn('delete', function ($attachment) {
                return link_to_route('admin.attachments.delete', 'Delete', $attachment->id, ['class' => 'btn btn-outline btn-danger btn-xs']);
            })
            ->editColumn('created_at', function ($article) {
                return $article->created_at->format('d/m/Y');
            })
            ->editColumn('updated_at', function ($article) {
                return $article->created_at->format('d/m/Y');
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource for thw wysiwyg / popup.
     *
     * @return Response
     */
    public function iframe()
    {
        $folder_id = $this->attachment->getPathId(true);

        return view('admin.attachments.iframe', compact('folder_id'));
    }

    /**
     * Generate a basic preview.
     * @param  int    $file_id File id
     * @return view
     */
    public function iframePreview($file_id = null)
    {
        if ($file_id) {
            $attachment = $this->attachment->findOrFail($file_id, ['filter' => false]);

            return view('admin.attachments.preview', compact('attachment'));
        }
        return view('admin.attachments.preview');
    }

    /**
     * Return JSON of a tree for the folder opened.
     * @param  int      $parent_id Optionally pass the id of the folder.
     * @return Response JSON
     */
    public function ajaxTree()
    {
        $id = $this->attachment->getPathId();

        $terms = Tax::terms(config('attachments.vocab'));
        $tree  = Tax::getTree($terms, $id);

        $output = [];

        foreach ($tree as $branch) {
            $object = (object) [
                'id'       => 'tree_' . $branch['term']->id,
                'text'     => $branch['term']->name,
                'children' => (!empty($branch['children'])),
            ];

            if (empty($id) && $branch['term']->id == config('attachments.path.default')) {
                $object->state = (object) ['selected' => true];
            }

            $output[] = $object;
        }

        return $output;
    }

    /**
     * Return HTML to the WYSIWYG for previews.
     *
     * @param  AttachmentWysiwygRequest $request [description]
     * @return Attachment::getHtml.
     */
    public function wysiwyg(AttachmentWysiwygRequest $request)
    {
        if ($file_id = $request->get('data-attachment-id')) {
            return Attachment::getHtml($file_id, $request->all());
        }
    }

    /**
     * Move files around or delete them based on method.
     * @param  AttachmentSelectionRequest $request
     * @return Response
     */
    public function modifySelected(AttachmentSelectionRequest $request)
    {
        if ($request->get('move')) {
            foreach ($request->get('selected') as $file_id) {
                $attachment          = $this->attachment->findOrFail($file_id, ['filter' => false]);
                $attachment->term_id = $request->get('term_id');
                $attachment->save();
            }

            return ['message' => 'Files moved'];
        } elseif ($request->get('delete')) {
            foreach ($request->get('selected') as $file_id) {
                $this->attachment->delete($file_id);
            }

            return ['message' => 'Files deleted'];
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.attachments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AttachmentFormRequest $request)
    {
        $attachment = $this->attachment->save($request);

        Flash::success('Saved.');

        return redirect()->route('admin.attachments.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function dropzone(AttachmentFormRequest $request)
    {
        $attachment = $this->attachment->save($request);

        return response()->json(['id' => $attachment->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $file_id
     * @return Response
     */
    public function show($file_id)
    {
        $attachment = $this->attachment->findOrFail($file_id);

        return view('admin.attachments.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $file_id
     * @return Response
     */
    public function edit($file_id)
    {
        $attachment = $this->attachment->findOrFail($file_id);

        return view('admin.attachments.edit', compact('attachment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $file_id
     * @return Response
     */
    public function update($file_id, AttachmentFormRequest $request)
    {
        $attachment = $this->attachment->save($request, $file_id);

        Flash::success('Updated.');

        return redirect()->route('admin.attachments.index');
    }

    /**
     * Not restful delete url
     *
     * @param  int        $file_id The attachment id
     * @return Response
     */
    public function delete($file_id)
    {
        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.attachments.destroy', $file_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this file?',
                'title'        => 'Delete File',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $file_id
     * @return Response
     */
    public function destroy($file_id)
    {
        $this->attachment->delete($file_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.attachments.index');
    }

    /**
     * Get the file url
     *
     * @param  int      $file_id The file id
     * @return string
     */
    public function fileUrl($file_id)
    {
        $file = $this->attachment->find($file_id);

        if ($file) {
            return \Response::json(['url' => $file->file->url()], 200);
        }

        return \Response::json(['error' => 'Image not found'], 400);
    }
}
