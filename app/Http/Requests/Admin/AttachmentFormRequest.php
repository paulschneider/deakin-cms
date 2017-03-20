<?php namespace App\Http\Requests\Admin;

use Tax;
use App\Http\Requests\ModifiedRequest as FormRequest;

class AttachmentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $terms = Tax::vocabularyOptions(config('attachments.vocab'));
        $tids  = implode(',', array_keys($terms));

        switch ($this->method()) {
            case 'POST':
                return [
                    'title'   => 'required',
                    'file'    => 'required',
                    'term_id' => 'required|in:' . $tids,
                    'slug'    => 'regex:/\.([a-z]+)$/|unique:pages,slug|unique:attachments,slug',
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'title'   => 'required',
                    'term_id' => 'required|in:' . $tids,
                    'slug'    => 'regex:/\.([a-z]+)$/|unique:pages,slug|unique:attachments,slug,' . $this->attachment,
                ];

            default:
                break;
        }

        return [];
    }

    /**
     * Set defaults on the item via the attachment path header.
     *
     * @param  array   $data Input
     * @return array
     */
    public function modifyInput(array $data)
    {
        if ($path = $this->header('X-Attachment-Path', null)) {
            $tid = is_numeric($path) ? $path : config($path);
            // ACL possible tere.
            if ($tax = Tax::term($tid)) {
                $data['term_id'] = $tax->id;
            }
        }

        if (empty($data['term_id'])) {
            $data['term_id'] = config('attachments.path.default');
        }

        if (!empty($data['slug'])) {
            $data['slug'] = strtolower($data['slug']);
            $data['slug'] = ltrim($data['slug'], '/');
        }

        if (empty($data['title']) && $name = $data['file']->getClientOriginalName()) {
            $data['title'] = $name;
        } elseif (empty($data['title'])) {
            throw new \Exception('File has no file name.');
        }

        return $data;
    }
}
