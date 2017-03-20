<?php
namespace App\Http\Controllers\FrontEnd;

use Flash;
use Illuminate\Support\Facades\Request;
use GlobalJs;
use Redirect;
use GlobalClass;
use GuzzleHttp\Client;
use App\Forms\FormHandler;
use App\Banners\BannersManager;
use App\Http\Controllers\Controller;
use App\Repositories\SubmissionsRepository;
use App\Http\Requests\FrontEnd\ContactFormRequest;
use App\Http\Requests\FrontEnd\NewsletterFormRequest;

class SubmissionsController extends Controller
{
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
    public function __construct(SubmissionsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the contact form
     *
     * @param  ContactFormRequest $request The request object
     * @param  FormHandler        $handler The form hander
     * @return Redirect
     */
    public function contactSubmit(ContactFormRequest $request, FormHandler $handler)
    {
        return $this->genericSave($request, $handler, 'contact');
    }

    /**
     * Handle the newsletter form
     *
     * @param  NewsletterFormRequest $request The request object
     * @param  FormHandler           $handler The form hander
     * @return Redirect
     */
    public function newsletterSubmit(NewsletterFormRequest $request, FormHandler $handler)
    {
        return $this->newsletterSave($request, $handler, 'newsletter');
    }

    /**
     * Generic handler for a form
     *
     * @param  mixed      $request The request object
     * @param  mixed      $handler The form handler
     * @param  string     $name    The name of the form
     * @param  mixed      $message The message to display after the form has been processed
     * @return Response
     */
    protected function genericSave($request, $handler, $name, $message = null)
    {
        $values          = $request->except('_method', '_token');
        $original_values = $values;

        // Replace the values so that it's available to the traits
        Request::replace($values);

        $options = config('forms.forms.' . $name . '.options', []);
        if (!empty($options)) {
            $handler->replaceSelects($values, $options);
        }

        // Strip all the empty values
        $values = $handler->stripEmptyFields($values);
        // Handle the email delivery
        $handler->formSubmission($values, $name);

        if (!$message) {
            $message = 'Your message has bent sent.';
        }

        // Persist
        $saving = $this->repository->prepareData($values);
        $this->repository->create($saving);

        // Done
        return $this->getRedirect($name, $message, $values, $original_values);
    }

    /**
     * Generic thankyou page.
     * @return view
     */
    public function genericThankyou(BannersManager $manager)
    {
        $classes = GlobalClass::getAll();
        $js      = GlobalJs::getAll();

        $variables = compact('classes', 'js');

        $bannerAttributes = [
            'title'   => 'Thank you',
            'summary' => 'Feedback sent',
        ];

        $variables['banner'] = $manager->render(config('banners.default_banner'), null, $bannerAttributes);

        return view('frontend.submissions.thankyou', $variables);
    }

    /**
     * Newsletter handler for a form
     *
     * @param  mixed      $request The request object
     * @param  mixed      $handler The form handler
     * @param  string     $name    The name of the form
     * @param  mixed      $message The message to display after the form has been processed
     * @return Response
     */
    public function newsletterSave($request, $handler, $name, $message = null)
    {
        $values          = $request->except('_method', '_token');
        $original_values = $values;

        // Replace the values so that it's available to the traits
        Request::replace($values);

        $options = config('forms.forms.' . $name . '.options', []);

        if (!empty($options)) {
            $handler->replaceSelects($values, $options);
        }

        // Strip all the empty values
        $values = $handler->stripEmptyFields($values);

        if (!$message) {
            $message = 'Your message has bent sent.';
        }

        $client = new Client();

        try {
            // Send to Pardot / Salesforce
            $res = $client->request('POST', env('NEWSLETTER_ENDPOINT'), [
                'form_params' => [
                    'firstname' => $values['name'],
                    'lastname'  => $values['surname'],
                    'email'     => $values['email'],
                ],
            ]);
        } catch (\Exception $e) {
            // Could not do.
        }

        // Done
        return $this->getRedirect($name, $message, $values, $original_values);
    }

    /**
     * Generic thankyou page.
     * @return view
     */
    public function newsletterThankyou(BannersManager $manager)
    {
        $classes = GlobalClass::getAll();
        $js      = GlobalJs::getAll();

        $variables = compact('classes', 'js');

        $bannerAttributes = [
            'title'   => 'Thank you',
            'summary' => 'You have been subscribed to our newsletter',
        ];

        $variables['banner'] = $manager->render(config('banners.default_banner'), null, $bannerAttributes);

        return view('frontend.submissions.newsletter_thankyou', $variables);
    }

    /**
     * Build a redirect URL with efforts to contain within iframes.
     * @param  string     $name            Type of form.
     * @param  string     $message         What to show the user
     * @param  array      $original_values Data sent with form.
     * @return Redirect
     */
    private function getRedirect($name, $message, $values, $original_values)
    {
        // Get the path to redirect to
        $path = $values['redirect'];

        $redirect = config('forms.forms.' . $name . '.redirect', null);

        if (empty($redirect)) {
            Flash::success($message);
        }

        $params = config('forms.forms.' . $name . '.url_paramaters', []);
        $query  = parse_url($path, PHP_URL_QUERY);

        if (!is_array($query)) {
            $query = [];
        }

        foreach ($params as $param) {
            if (!empty($original_values[$param])) {
                $query[$param] = $original_values[$param];
            }
        }

        if (!empty($query)) {
            $path .= '?' . http_build_query($query);
        }

        return Redirect::to($path);
    }
}
