<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CompleteInfo;
use App\Traits\CharacterCommon;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use CharacterCommon;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->redirectTo = action("HomeController@index");
    }

    /**
     * Handle a login request to the application.
     *
     * @param  LoginRequest $request
     * @return Response
     */
    public function login(Request $request)
    {
        $request->offsetSet("mobile" ,  $this->convertToEnglish($request->get("mobile")));
        $request->offsetSet("password" , $this->convertToEnglish($request->get("password")));

        $validator = Validator::make($request->all(), [
            'mobile' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
             return redirect()->back()
                 ->withInput($request->only('mobile', 'remember'))
                 ->withErrors([
                     'validation' => 'خطای ورودی ها'
                 ],"login");
        }

//             $credentials = $this->getCredentials($request);

        if($request->has("remember"))
            $remember = true;
        else
            $remember = false;

        $intendedUsers = User::where("mobile" , $request->get("mobile"))->get();

        foreach ($intendedUsers as $user)
        {
            if (Auth::attempt(['id'=>$user->id,'mobile' => $user->mobile, 'password' => $request->get("password")] , $remember)) {
                if (strcmp(Auth::user()->userstatus->name, "inactive") == 0) {
                    Auth::logout();
                    Session::flush();
                    return redirect()->back()
                        ->withInput($request->only('mobile', 'remember'))
                        ->withErrors([
                            'inActive' => 'حساب کاربری شما غیر فعال شده است!'
                        ], "login");
                }
                break;
            }
        }
        if(!Auth::check())
        {
            if(User::where("mobile" , $request->get("mobile"))->where("nationalCode" , $request->get("password"))->get()->isEmpty())
            {
                $registerRequest = new Request();
                $registerRequest->offsetSet("mobile" ,  $request->get("mobile"));
                $registerRequest->offsetSet("nationalCode" , $request->get("password"));
                $registerRequest->offsetSet("firstName" ,  null);
                $registerRequest->offsetSet("lastName" , null);
                $registerController = new RegisterController();
                $registerController->register($registerRequest);
                //            $client = new Client(['base_uri' =>  url("/")]);
        //            $response = $client->request('POST', '/register', [
        //                'form_params' => $registerRequest->all()
        //            ]);
            }else
            {
                return redirect()->back()
                    ->withInput($request->only('mobile', 'remember'))
                    ->withErrors([
                        'credential' => 'اطلاعات وارد شده معتبر نمی باشند '
                    ],"login");
            }
        }

        $baseUrl = url("/");
        $targetUrl = redirect()->intended()->getTargetUrl();
        if(strcmp($targetUrl , $baseUrl) == 0)
        {
            if(strcmp(URL::previous() , route('login')) != 0) $this->redirectTo = URL::previous() ;
        }else
        {
            $this->redirectTo = $targetUrl ;
        }

        //ToDo: config , it has to be replaced with setting
        if(true)
        {//config variable for showing the form or not
            if(Auth::user()->completion("afterLoginForm") != 100)
            {
                if(strcmp(URL::previous() , action("OrderController@checkoutAuth")) == 0)
                {
                    return redirect(action("OrderController@checkoutCompleteInfo"));
                }else{
                    session()->put("redirectTo" , $this->redirectTo );
                    return redirect(action("UserController@completeRegister"));
                }

            }
        }

        return redirect($this->redirectTo);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login3' );
    }
}
