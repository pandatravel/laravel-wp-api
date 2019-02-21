<?php

namespace Ammonkc\WpApi\Http\Controllers\Auth;

use Ammonkc\WpApi\Http\Controllers\Controller;
use Ammonkc\WpApi\Http\Requests\JwtAuthRequest;
use Ammonkc\WpApi\WpApiClient;
use GuzzleHttp\Exception\ClientException;
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

        if (! is_null(\Cookie::get('wpapi_jwt_token'))) {
            try {
                $valid = $this->wp->jwtAuthToken()->validate();
            } catch (ClientException $e) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody()->getContents());
                $body->statusCode = $body->data->status;
                $code = explode(" ", $body->code);
                if (count($code) > 1) {
                    $body->code = 'jwt_auth_' . $code[1];
                }
                \Cookie::queue(\Cookie::forget('wpapi_jwt_token'));
                session()->flash('notifier', ['type' => 'danger', 'message' => trans('ammonkc/wp-api::auth.jwt.' . $body->code, (!property_exists($body, 'message')?[]:['message' => $body->message]))]);
            }
        }

        return view(Config::get('wp-api.auth_form', 'settings.wpapi.login'), ['valid' => $valid]);
    }

    /**
     * Authenticate JwtAuthToken.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(JwtAuthRequest $request)
    {
        try {
            $response = $this->wp->jwtAuthToken()->authenticate($request->validated());
            $body = json_decode($response->content());
            if (property_exists($body, 'token')) {
                $body->code = 'token';
            }
            $body->statusCode = $response->status();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody()->getContents());
            $body->statusCode = $body->data->status;
            $code = explode(" ", $body->code);
            if (count($code) > 1) {
                $body->code = 'jwt_auth_' . $code[1];
            }
        }

        if ($request->ajax()) {
            return ['redirect' => url($this->redirectPath()), 'message' => trans('ammonkc/wp-api::auth.jwt.' . $body->code, (!property_exists($body, 'message')?[]:['message' => $body->message]))];
        }

        session()->flash('notifier', ['type' => ($body->statusCode == '200' ? 'success' : 'danger'), 'message' => trans('ammonkc/wp-api::auth.jwt.' . $body->code, (!property_exists($body, 'message')?[]:['message' => $body->message]))]);

        return redirect($this->redirectPath());
    }

    /**
     * Logout of the JwtAuth token
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return redirect($this->redirectPath())->withCookie(\Cookie::forget('wpapi_jwt_token'));
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
