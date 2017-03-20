<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersFormRequest;
use App\Http\Requests\Admin\UsersMeFormRequest;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Auth;
use Flash;
use Illuminate\Contracts\Auth\PasswordBroker;

// Might need to change this for 5.1
use URL;

class UsersController extends Controller
{
    /**
     * The users repository
     *
     * @var UsersRepository
     */
    protected $users;

    /**
     * The roles repository
     *
     * @var RolesRepository
     */
    protected $roles;

    /**
     * The passwords broker
     *
     * @var PasswordBroker
     */
    protected $passwords;

    /**
     * Dependency Injection
     *
     * @param UsersRepository $users     The instance of UsersRepository
     * @param RolesRepository $roles     The instance of RolesRepository
     * @param PasswordBroker  $passwords The instance of PasswordBroker
     */
    public function __construct(UsersRepository $users, RolesRepository $roles, PasswordBroker $passwords)
    {
        $this->users     = $users;
        $this->roles     = $roles;
        $this->passwords = $passwords;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $options = ['filter' => false];
        $users   = $this->users->paginate(15, $options);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->roles->all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UsersFormRequest $request)
    {
        $user = $this->users->save($request, null, ['except' => ['password']]);

        $this->users->setActivation($user);

        Flash::success('Saved.');

        return redirect()->route('admin.users.edit', $user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function show($user_id)
    {
        $user = $this->users->findOrFail($user_id);

        return view('admin.pages.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function edit($user_id)
    {
        $user  = $this->users->findOrFail($user_id);
        $roles = $this->roles->all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function me()
    {
        $user = Auth::user();

        return view('admin.users.me', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function update($user_id, UsersFormRequest $request)
    {
        $user = $this->users->save($request, $user_id, ['except' => ['password']]);

        Flash::success('Saved.');

        return redirect()->route('admin.users.edit', $user->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function updateMe(UsersMeFormRequest $request)
    {
        $account = Auth::user();

        $user           = $this->users->save($request, $account->id, ['except' => ['password', 'password_confirmation']]);
        $user->password = bcrypt($request->get('password'));
        $user->save();

        Auth::login($user);

        Flash::success('Saved.');

        return redirect()->route('admin.users.me');
    }

    /**
     * Not restful delete url
     *
     * @param  int        $user_id The page id
     * @return Response
     */
    public function delete($user_id)
    {
        if ($user_id == Auth::user()->id) {
            abort(403, 'You cannot delete your own account.');
        }

        return view('admin.layouts.confirm')->with(
            [
                'confirm_text' => 'Delete',
                'action'       => ['route' => ['admin.users.destroy', $user_id], 'method' => 'DELETE'],
                'cancel_text'  => 'Cancel',
                'return_url'   => URL::previous(),
                'question'     => 'Are you sure you want to delete this user?',
                'title'        => 'Delete Users',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int        $user_id
     * @return Response
     */
    public function destroy($user_id)
    {
        $this->users->delete($user_id);

        Flash::success('Deleted.');

        return redirect()->route('admin.users.index');
    }

    /**
     * Trigger a password reset.
     * @param  [type] $user_id        [description]
     * @return [type] [description]
     */
    public function resetPassword($user_id)
    {
        $user = $this->users->findOrFail($user_id);

        $response = $this->passwords->sendResetLink(['email' => $user->email], function ($m) {
            $m->subject('Your Password Reset Link');
        });

        Flash::success('Password reset link sent.');

        return redirect()->route('admin.users.index');
    }

    /**
     * Trigger a password reset.
     * @param  [type] $user_id        [description]
     * @return [type] [description]
     */
    public function resetActivation($user_id)
    {
        $user = $this->users->findOrFail($user_id);

        $this->users->setActivation($user);

        Flash::success('Action link resent.');

        return redirect()->route('admin.users.index');
    }
}
