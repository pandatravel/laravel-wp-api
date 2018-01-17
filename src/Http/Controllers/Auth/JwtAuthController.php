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
        $valid = ['code' => 'jwt_no_token'];

        if (! is_null(\Cookie::get('pandaonline_token'))) {
            $valid = $this->wp->jwtAuthToken()->validate();
        }

        return view(Config::get('auth_form', 'settings.wpapi.login'), ['valid' => $valid]);
    }

    /**
     * Authenticate JwtAuthToken.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(JwtAuthRequest $request)
    {
        $response = $this->wp->jwtAuthToken()->authenticate($request->validated());

        if (array_has($response, 'token')) {
            $response['code'] = 'token';
            $response['data'] = ['status' => '200'];
        }

        if ($request->ajax()) {
            return ['redirect' => url($this->redirectPath()), 'message' => trans('ammonkc/wpapi::auth.jwt.' . $response['code'], $response)];
        }

        session(['notifier' => ['type' => ($response['data']['status'] == '200' ? 'success' : 'danger'), 'message' => trans('ammonkc/wpapi::auth.jwt.' . $response['code'], $response)]]);

        return redirect($this->redirectPath());
    }

    /**
     * Logout of the JwtAuth token
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return redirect($this->redirectPath())->withCookie(\Cookie::forget('pandaonline_token'));
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
