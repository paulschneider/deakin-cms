<?php namespace App\Http\Requests\Admin;

use Carbon;
use App\Http\Requests\ModifiedRequest as FormRequest;

class ArticleFormRequest extends FormRequest
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
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function modifyInput(array $data)
    {
        // Create a slug
        if (empty($data['slug'])) {
            $data['slug'] = $data['title'];
        }
        // Make sure the passed in slug is a slug
        $data['slug'] = str_slug($data['slug']);

        if (!empty($data['body']) && empty($data['summary'])) {
            $summary         = clean($data['body'], 'basic_html');
            $data['summary'] = truncate_html($summary, 600, $elipsis = '&hellip;');
        }

        if (config('articles.events')) {
            if (!empty($data['event_at_time']) && !empty($data['event_at_date'])) {
                $when             = Carbon::createFromFormat('d/m/Y h:i A', $data['event_at_date'] . ' ' . $data['event_at_time']);
                $data['event_at'] = $when;
            }
        }

        if (!empty($data['created_at_time']) && !empty($data['created_at_date'])) {
            $when               = Carbon::createFromFormat('d/m/Y h:i A', $data['created_at_date'] . ' ' . $data['created_at_time']);
            $data['created_at'] = $when;
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'body'  => 'required',
            'slug'  => 'required',
        ];
    }
}
