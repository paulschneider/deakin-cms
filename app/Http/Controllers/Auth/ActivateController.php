<?php namespace App\Http\Controllers\Auth;

use Auth;
use Flash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivateController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function activate($token = null)
    {
        if (null === $token) {
            throw new NotFoundHttpException;
        }

        return view('auth.activate')->with('token', $token);
    }

    public function postActivate(Request $request)
    {
        $this->validate($request, [
            'activation_code' => 'required',
            'email'           => 'required|email',
            'password'        => 'required|confirmed|' . config('auth.passwords.users.rule'),
        ]);

        $credentials           = $request->only('email', 'activation_code');
        $credentials['active'] = 0;

        $users = User::where($credentials)->get();

        if ($users->count()) {
            $user = $users->first();

            if ($user->valid_activation) {
                $user->password           = bcrypt($request->get('password'));
                $user->active             = 1;
                $user->activation_code    = null;
                $user->activation_expires = null;

                Flash::success('Account Activated.');

                $user->save();

                return redirect()->to('login');
            } else {
                return redirect()->back()->withErrors('Activation has expired. Please contact an administrator.');
            }
        }

        return redirect()->back()->withErrors('Could not validate your email address with this activation code');
    }
}
