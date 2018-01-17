<?php

namespace Ammonkc\WpApi\Http\Controllers\Auth;

use Ammonkc\WpApi\Http\Controllers\Controller;
use Ammonkc\WpApi\Http\Requests\JwtAuthRequest;
use Ammonkc\WpApi\WpApiClient;
use Illuminate\Support\Facades\Config;

class JwtAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * @var Ammonkc\WpApi\WpApiClient
     */
    protected $wp;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WpApiClient $wp)
    {
        $this->redirectTo = Config::get('wp-api.login_redirect');
        $this->wp = $wp;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $valid = $this->wp->jwtAuthToken()->validate();

        return view(Config::get('auth_form', 'settings.wpapi.login'), ['valid' => $valid]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(JwtAuthRequest $request)
    {
        $this->wp->jwtAuthToken()->authenticate($request->validated());

        return redirect($this->redirectPath());
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}
