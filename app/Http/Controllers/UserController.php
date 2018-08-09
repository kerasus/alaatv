<?php

namespace App\Http\Controllers;

use App\Afterloginformcontrol;
use App\Bankaccount;
use App\Bloodtype;
use App\Bon;
use App\Checkoutstatus;
use App\Contact;
use App\Coupon;
use App\Employeeschedule;
use App\Employeetimesheet;
use App\Event;
use App\Eventresult;
use App\Gender;
use App\Grade;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\InsertCouponRequest;
use App\Http\Requests\InsertVoucherRequest;
use App\Http\Requests\PasswordRecoveryRequest;
use App\Http\Requests\RegisterForSanatiSharifHighSchoolRequest;
use App\Http\Requests\SubmitVerificationCode;
use App\Lottery;
use App\Major;
use App\Http\Requests\EditProfileInfoRequest;
use App\Http\Requests\EditProfilePasswordRequest;
use App\Http\Requests\EditProfilePhotoRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\InsertUserRequest;
use App\Phone;
use App\Product;
use App\Productvoucher;
use App\Province;
use App\Relative;
use App\Traits\CharacterCommon;
use App\Traits\DateCommon;
use App\Traits\Helper;
use App\Traits\ProductCommon;
use App\Traits\RequestCommon;
use App\Traits\UserCommon;
use App\Transaction;
use App\Transactiongateway;
use App\Order;
use App\Role;
use App\User;
use App\Userstatus;
use App\Verificationmessagestatuse;
use App\Websitesetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Monolog\Handler\ElasticSearchHandler;
use stdClass;
use SEO;

class UserController extends Controller
{
    protected $response ;
    protected $setting;
    
    use ProductCommon;
    use DateCommon;
    use RequestCommon;
    use CharacterCommon ;
    use Helper;
    use UserCommon;

    function __construct()
    {
        /** setting permissions
         *
         */
        $this->middleware('permission:'.Config::get('constants.LIST_USER_ACCESS')."|".Config::get('constants.GET_BOOK_SELL_REPORT')."|".Config::get('constants.GET_USER_REPORT'),['only'=>'index']);
        $this->middleware('permission:'.Config::get('constants.INSERT_USER_ACCESS'),['only'=>'create']);
        $this->middleware('permission:'.Config::get('constants.REMOVE_USER_ACCESS'),['only'=>'destroy']);
        $this->middleware('permission:'.Config::get('constants.SHOW_USER_ACCESS'),['only'=>'edit']);

        $this->response = new Response();
        $this->setting = json_decode(app('setting')->setting);
    }

    public function findTech(Request $request){
        $user = User::where('techCode',$request->techCode)->first();
        if(isset($user))
            return action('UserController@show',$user);
        return 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $createdTimeEnable = Input::get('createdTimeEnable');
        $createdSinceDate = Input::get('createdSinceDate');
        $createdTillDate = Input::get('createdTillDate');
        if(strlen($createdSinceDate) > 0 && strlen($createdTillDate) > 0  && isset($createdTimeEnable))
        {
            $createdSinceDate = Carbon::parse($createdSinceDate)->format('Y-m-d') . " 00:00:00";
            $createdTillDate = Carbon::parse($createdTillDate)->format('Y-m-d') . " 23:59:59";
            $users = User::whereBetween('created_at', [$createdSinceDate, $createdTillDate])->orderBy('created_at', 'Desc');
        }
        else{
            $users = User::orderBy('created_at', 'Desc');
        }

        $updatedSinceDate = Input::get('updatedSinceDate');
        $updatedTillDate = Input::get('updatedTillDate');
        $updatedTimeEnable = Input::get('updatedTimeEnable');
        if(strlen($updatedSinceDate)>0 && strlen($updatedTillDate)>0 && isset($updatedTimeEnable))
        {
            $users = $this->timeFilterQuery($users, $updatedSinceDate, $updatedTillDate, 'updated_at');
        }

        //filter by firstName, lastName, nationalCode, mobile
        $firstName = trim(Input::get('firstName'));
        if(isset($firstName) && strlen($firstName)>0)
        {
            $users = $users->where('firstName', 'like', '%' . $firstName . '%');
        }

        $lastName = trim(Input::get('lastName'));
        if(isset($lastName) && strlen($lastName)>0)
        {
            $users = $users->where('lastName', 'like', '%' . $lastName . '%');
        }

        $nationalCode = trim(Input::get('nationalCode'));
        if(isset($nationalCode) && strlen($nationalCode)>0)
        {
            $users = $users->where('nationalCode', 'like', '%' . $nationalCode . '%');
        }

        $mobile = trim(Input::get('mobile'));
        if(isset($mobile) && strlen($mobile)>0)
        {
            $users = $users->where('mobile', 'like', '%' . $mobile . '%');
        }

        //filter by role, major , coupon
        $roleEnable = Input::get('roleEnable');
        $rolesId = Input::get('roles');
        if(isset($roleEnable) && isset($rolesId))
        {
            $users = User::roleFilter($users, $rolesId);
        }

        $majorEnable = Input::get('majorEnable');
        $majorsId = Input::get('majors');
        if(isset($majorEnable) && isset($majorsId))
        {
            $users = User::majorFilter($users, $majorsId);
        }

        $couponEnable = Input::get('couponEnable');
        $couponsId = Input::get('coupons');
        if(isset($couponEnable) && isset($couponsId))
        {
            if (in_array(0, $couponsId))
                $users = $users->whereHas("orders" , function ($q) use($couponsId) {
                    $q->whereDoesntHave("coupon")->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN"),Config::get("constants.ORDER_STATUS_CANCELED"),Config::get("constants.ORDER_STATUS_OPEN_BY_ADMIN")]);
                });
            else
                $users = $users->whereHas("orders" , function ($q) use($couponsId) {
                    $q->whereIn("coupon_id", $couponsId)->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN"),Config::get("constants.ORDER_STATUS_CANCELED"),Config::get("constants.ORDER_STATUS_OPEN_BY_ADMIN")]);
                });
        }

        //filter by product
        $seenProductEnable = Input::get('productEnable');
        $productsId = Input::get('products');
        if(isset($seenProductEnable) && isset($productsId))
        {
            $productUrls = [];
            $baseUrl = url("/");
            foreach ($productsId as $productId)
            {
                array_push($productUrls , str_replace($baseUrl , "" , action("ProductController@show" , $productId)));
            }
            $users = $users->whereHas('seensitepages', function($q) use ($productUrls)
            {
                $q->whereIn("url",  $productUrls);
            });
        }

        $orderProductEnable = Input::get("orderProductEnable");
        $productsId = Input::get('orderProducts');
        if(isset($orderProductEnable) || isset($productsId))
        {
            if(in_array(-1, $productsId)) {
                $users = $users->whereDoesntHave("orders" , function ($q){
                    $q->where("orderstatus_id","<>",1)->where("orderstatus_id","<>",3)->where("orderstatus_id","<>",4);
                });
            }
            elseif(in_array(0, $productsId)) {
                $users = $users->whereHas("orders" , function ($query){
                    $query->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN"),Config::get("constants.ORDER_STATUS_CANCELED"),Config::get("constants.ORDER_STATUS_OPEN_BY_ADMIN")]);
                });
            }
            elseif(isset($productsId)){
                $products = Product::whereIn('id', $productsId)->get();
                foreach ($products as $product) {
                    if($product->producttype_id == Config::get("constants.PRODUCT_TYPE_CONFIGURABLE"))
                        if ($product->hasChildren())
                        {
                            $productsId = array_merge($productsId, Product::whereHas('parents', function ($q) use ($productsId) {
                                $q->whereIn("parent_id", $productsId);
                            })->pluck("id")->toArray());
                        }
                }

                if(Input::has("checkoutStatusEnable"))
                {
                    $checkoutStatuses = Input::get("checkoutStatuses");
                    if(in_array(0 , $checkoutStatuses))
                    {
                        $orders = Order::whereHas("orderproducts", function ($q) use ($productsId) {
                            $q->whereIn("product_id", $productsId)->whereNull("checkoutstatus_id");
                        })->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN")]);
                    }else{
                        $orders = Order::whereHas("orderproducts", function ($q) use ($productsId , $checkoutStatuses) {
                            $q->whereIn("product_id", $productsId)->whereIn("checkoutstatus_id" , $checkoutStatuses);
                        })->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN")]);
                    }
                }else{
                    $orders = Order::whereHas("orderproducts", function ($q) use ($productsId) {
                        $q->whereIn("product_id", $productsId);
                    })->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN")]);
                }

                $createdSinceDate = Input::get('completedSinceDate');
                $createdTillDate = Input::get('completedTillDate');
                $createdTimeEnable = Input::get('completedTimeEnable');
                if(strlen($createdSinceDate)>0 && strlen($createdTillDate)>0 && isset($createdTimeEnable))
                {
                    $orders = $this->timeFilterQuery($orders, $createdSinceDate, $createdTillDate, 'created_at');
                }
                $orders = $orders->get();
                $users = $users->whereIn("id" , $orders->pluck("user_id")->toArray());
              }
        }elseif(Input::has("checkoutStatusEnable"))
        {
            $checkoutStatuses = Input::get("checkoutStatuses");
            if(in_array(0 , $checkoutStatuses))
            {
                $orders = Order::whereHas("orderproducts", function ($q) use ($productsId) {
                    $q->whereNull("checkoutstatus_id");
                })->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN")]);
            }else{
                $orders = Order::whereHas("orderproducts", function ($q) use ($productsId , $checkoutStatuses) {
                    $q->whereIn("checkoutstatus_id" , $checkoutStatuses);
                })->whereNotIn('orderstatus_id', [Config::get("constants.ORDER_STATUS_OPEN")]);
            }
            $orders = $orders->get();
            $users = $users->whereIn("id" , $orders->pluck("user_id")->toArray());
        }

        $paymentStatusesId = Input::get('paymentStatuses');
        if(isset($paymentStatusesId) )
        {
            //Muhammad Shahrokhi : kar nemikone!
//            $users = $users->whereHas("orders" , function ($q) use ($paymentStatusesId) {
//                $q->whereIn("paymentstatus_id", $paymentStatusesId)->whereNotIn('orderstatus_id', [1]);
//            }
            if(!isset($orders)) $orders = Order::all();
            else $orders = Order::paymentStatusFilter($orders, $paymentStatusesId);
            $users = $users->whereIn("id", $orders->pluck("user_id")->toArray());
        }

        $orderStatusesId = Input::get('orderStatuses');
        if(isset($orderStatusesId) )
        {
            //Muhammad Shahrokhi : kar nemikone!
//            $users = $users->whereHas("orders" , function ($q) use ($orderStatusesId) {
//                $q->whereIn("orderstatus_id", $orderStatusesId)->whereNotIn('orderstatus_id', [1]);
//            });
            if(!isset($orders)) $orders = Order::all();
            else $orders = Order::orderStatusFilter($orders, $orderStatusesId);
            $users = $users->whereIn("id" , $orders->pluck("user_id")->toArray());
        }
        //filter by gender ,lockProfile , mobileVerification
        $genderId = Input::get("gender_id");
        if(isset($genderId) && strlen($genderId) > 0)
        {
            if($genderId == 0)
                $users = $users->whereDoesntHave("gender");
            else
                $users = $users->where("gender_id" , $genderId);
        }

        $userstatusId = Input::get("userstatus_id");
        if(isset($userstatusId) && strlen($userstatusId) > 0 && $userstatusId != 0){
            $users = $users->where("userstatus_id" , $userstatusId);
        }

        $lockProfileStatus = Input::get("lockProfileStatus");
        if(isset($lockProfileStatus) && strlen($lockProfileStatus) > 0){
            $users = $users->where("lockProfile" , $lockProfileStatus);
        }

        $mobileNumberVerification = Input::get("mobileNumberVerification");
        if(isset($mobileNumberVerification) && strlen($mobileNumberVerification) > 0){
            $users = $users->where("mobileNumberVerification" , $mobileNumberVerification);
        }

        //filter by postalCode, province , city, address, school , email
        $withoutPostalCode = Input::get("withoutPostalCode");
        if(isset($withoutPostalCode)) {
            $users = $users->where(function ($q){
                $q->whereNull("postalCode")->orWhere("postalCode" , "");
            });
        }
        else{
            $postalCode = Input::get("postalCode");
            if(isset($postalCode) && strlen($postalCode) > 0)
                $users = $users->where('postalCode', 'like', '%' . $postalCode . '%');
        }

        $withoutProvince = Input::get("withoutProvince");
        if(isset($withoutProvince)) {
            $users = $users->where(function ($q){
                $q->whereNull("province")->orWhere("province" , "");
            });
        }
        else{
            $province = Input::get("province");
            if(isset($province) && strlen($province) > 0)
                $users = $users->where('province', 'like', '%' . $province . '%');
        }

        $withoutCity = Input::get("withoutCity");
        if(isset($withoutCity)) {
            $users = $users->where(function ($q){
                $q->whereNull("city")->orWhere("city" , "");
            });
        }
        else{
            $city = Input::get("city");
            if(isset($city) && strlen($city) > 0)
                $users = $users->where('city', 'like', '%' . $city . '%');
        }

//        $withoutAddress = Input::get("withoutAddress");
//        if(isset($withoutAddress)) {
//            $users = $users->where(function ($q){
//                $q->whereNull("address")->orWhere("address" , "");
//            });
//        }
//        else{
//            $address = Input::get("address");
//            if (isset($address) && strlen($address) > 0)
//                $users = $users->where('address', 'like', '%' . $address . '%');
//        }

        $addressSpecialFilter = Input::get("addressSpecialFilter");
        if(isset($addressSpecialFilter)) {
            switch ($addressSpecialFilter){
                case "0":
                    $address = Input::get("address");
                    if (isset($address) && strlen($address) > 0)
                    $users = $users->where('address', 'like', '%' . $address . '%');
                    break;
                case "1":
                    $users = $users->where(function ($q){
                        $q->whereNull("address")->orWhere("address" , "");
                    });
                    break;
                case  "2":
                    $users = $users->where(function ($q){
                        $q->whereNotNull("address")->Where("address" , "<>" , "");
                    });
                    break;
                default:
                    break;
            }

        }
        else{
            $address = Input::get("address");
            if (isset($address) && strlen($address) > 0)
                $users = $users->where('address', 'like', '%' . $address . '%');
        }

        $withoutSchool = Input::get("withoutSchool");
        if(isset($withoutSchool)) {
            $users = $users->where(function ($q){
                $q->whereNull("school")->orWhere("school" , "");
            });
        }
        else{
            $school = Input::get("school");
            if (isset($school) && strlen($school) > 0)
                $users = $users->where('school', 'like', '%' . $school . '%');
        }

        $withoutEmail = Input::get("withoutEmail");
        if(isset($withoutEmail)) {
            $users = $users->where(function ($q){
                $q->whereNull("email")->orWhere("email" , "");
            });
        }
        else{
            $email = Input::get("email");
            if (isset($email) && strlen($email) > 0)
                $users = $users->where('email', 'like', '%' . $email . '%');
        }

        //sort by


        $users = $users->get();
        /**
         * For selling books
         */
        $hasPishtaz= [];
        if(isset($orders))
            foreach ($users as $user)
            {
                if($user->orders()->whereIn("id" , $orders->pluck("id")->toArray())->whereHas("orderproducts" , function ($q){
                    $q->whereHas("attributevalues" , function ($q2){
                        $q2->where("id" , 48 );
                    });
                })->get()->isNotEmpty()) array_push($hasPishtaz , $user->id);
            }

        /**
         * end
         */

        $sortBy = Input::get("sortBy");
        $sortType = Input::get("sortType");
        if(strlen($sortBy) > 0 && strlen($sortType) > 0){
            if(strcmp($sortType , "desc") == 0) $users = $users->sortByDesc($sortBy);
            else $users = $users->sortBy($sortBy);
        }

        $previousPath = url()->previous();
        $usersId = [];
        $numberOfFatherPhones = 0;
        $numberOfMotherPhones = 0;
        $usersIdCount=0;
        $index = "";
        $reportType = "";

        if(strcmp($previousPath , action("HomeController@adminSMS"))==0) {
            $uniqueUsers = $users->groupBy("nationalCode") ;
            $users = collect();
            foreach ($uniqueUsers as $user)
            {
                if($user->where("mobileNumberVerification" , 1)->isNotEmpty())
                {
                    $users->push($user->where("mobileNumberVerification" , 1)->first());
                }
                else
                {
                    $users->push($user->first());
                }

            }
            $index = "user.index2" ;
            $usersId = $users->pluck("id");
            $usersIdCount = $usersId->count();
            $numberOfFatherPhones = Phone::whereIn('contact_id', Contact::whereIn('user_id', $usersId)->where('relative_id', 1)->pluck('id'))->where("phonetype_id", 1)->count();
            $numberOfMotherPhones = Phone::whereIn('contact_id', Contact::whereIn('user_id', $usersId)->where('relative_id', 2)->pluck('id'))->where("phonetype_id", 1)->count();
        }
        elseif(strcmp($previousPath , action("HomeController@admin"))==0)
        {
            $index = "user.index" ;
        }elseif(strcmp($previousPath , action("HomeController@adminReport"))==0)
        {
            $minCost = Input::get("minCost") ;
            if(isset($minCost[0]))
            {
                foreach ($users as $key => $user)
                {
                    $userOrders = $user->orders;
                    $transactionSum = 0 ;
                    foreach ($userOrders as $order)
                    {
                        $successfullTransactions = $order->successfulTransactions()->where("created_at" , ">" , "2017-09-22" )->get();
                        foreach ($successfullTransactions as $transaction )
                        {
                            $transactionSum += $transaction->cost ;
                        }
                    }
                    if($transactionSum < (int)$minCost)
                        $users->forget($key) ;
                }
            }
            $index = "admin.partials.getReportIndex" ;

            if(Input::has("lotteries"))
            {
                $lotteryId = Input::get("lotteries") ;
                $lotteries = Lottery::where("id" , $lotteryId)->get();
            }

            if(Input::has("reportType"))
                $reportType = Input::get("reportType") ;

            if(Input::has("seePaidCost"))
                $seePaidCost = true;
        }
        $result =  array(
            'index' => View::make($index, compact('users' , 'products' , 'paymentStatusesId' , 'reportType' , 'hasPishtaz' , 'orders'  , 'seePaidCost' , 'lotteries'))->render()
            , 'products'=>(isset($products))? $products : [],
            'lotteries'=>(isset($lotteries))? $lotteries : [],
            "allUsers" => $usersId , "allUsersNumber" => $usersIdCount ,
            "numberOfFatherPhones" => $numberOfFatherPhones , "numberOfMotherPhones" => $numberOfMotherPhones
        );

        return response(json_encode($result),200)->header('Content-Type','application/json') ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \app\Http\Requests\InsertUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsertUserRequest $request)
    {
        try{
            $softDeletedUsers = User::onlyTrashed()->where("mobile" , $request->get("mobile"))->where("nationalCode" , $request->get("nationalCode"))->get();
            if(!$softDeletedUsers->isEmpty())
            {
                $softDeletedUsers->first()->restore();
                return $this->response->setStatusCode(200);
            }

            $user = new User();
            $user->fill($request->all());

            if ($request->hasFile("photo")) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $fileName = basename($file->getClientOriginalName() , ".".$extension) . "_" . date("YmdHis") . '.' . $extension;
                if (Storage::disk(Config::get('constants.DISK1'))->put($fileName, File::get($file))) {
                    $user->photo = $fileName;
                }
            }else{
                $user->photo = Config::get('constants.PROFILE_DEFAULT_IMAGE');
            }

            if(strlen($request->get("major_id")) == 0)
                $user->major_id = null;
            if(strlen($request->get("gender_id")) == 0)
                $user->gender_id = null;

            if ( $request->has("mobileNumberVerification"))
                $user->mobileNumberVerification = 1;
            else
                $user->mobileNumberVerification = 0 ;

            $user->password = bcrypt($request->get("password"));

            if ($user->save()) {
                if(Auth::user()->can(Config::get('constants.INSET_USER_ROLE'))){

                    $newRoleIds = array() ;
                    if($request->has("roles"))
                    {
                        $newRoleIds = $request->get("roles");
                        foreach ($newRoleIds as $key => $newRoleId)
                        {
                            $newRole = Role::FindOrFail($newRoleId) ;
                            if($newRole->isDefault) {
                                if (!Auth::user()->can(Config::get('constants.GIVE_SYSTEM_ROLE')))
                                    unset($newRoleIds[$key]);
                            }
                        }
                    }

                    if(!empty($newRoleIds))
                    {
                        foreach ($newRoleIds as $role_id)
                        {
                            $user->attachRole($role_id);
                        }
                    }
                }

                $responseStatusCode = 200;
                $responseContent = "درج کاربر با موفقیت انجام شد";
                $storedUserId = $user->id;

            }
            else{
                $responseStatusCode = 503;
                $responseContent = "خطا در ذخیره کاربر";
            }

            return $this->response
                ->setStatusCode($responseStatusCode)
                ->setContent([
                    "message" => $responseContent ,
                    "userId" => (isset($storedUserId)?$storedUserId:0)
                    ]);
        }
        catch (\Exception    $e) {
            $message = "unexpected error";
            return $this->response
                ->setStatusCode(500)
                ->setContent([
                    "message"=>$message ,
                    "error"=>$e->getMessage() ,
                    "line"=>$e->getLine() ,
                    "file"=>$e->getFile()
                ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        if(
            ( $user->id === Auth::id() ) ||
            ( Auth::user()->hasRole(Config::get('constants.ROLE_ADMIN') ) ) ||
            ( $user->hasRole(Config::get('constants.ROLE_TECH') ) )
          ){

            if (session()->has("tab")) session()->flash("tabPane", session()->pull("tab"));
            if (session()->has("belongsTo")) session()->flash("belongsTo", session()->pull("belongsTo"));
            if (session()->has("success")) session()->flash("success", session()->pull("success"));
            if (session()->has("error")) session()->flash("error", session()->pull("error"));
            $genders = Gender::pluck('name', 'id')->prepend("نامشخص");
            $majors = Major::pluck('name', 'id')->prepend("نامشخص");
            $sideBarMode = "closed";

            /** LOTTERY POINTS*/
            $now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())
                            ->timezone('Asia/Tehran');
            $startTime = Carbon::create(2018, 06, 11, 07, 00, 00, 'Asia/Tehran');
            $endTime = Carbon::create(2018, 06, 15, 12, 00, 00, 'Asia/Tehran');
            $flag = ($now->between($startTime, $endTime));
//	        $flag = true;
            $hamayeshHozouriProductId = 223;
            $hasHamayeshHozouriArabi = $user->orders()
                ->whereHas("orderproducts" , function ($q) use ($hamayeshHozouriProductId){
                    $q->where("product_id" , $hamayeshHozouriProductId);
                })
                ->where("orderstatus_id" , config("constants.ORDER_STATUS_CLOSED"))
                ->where("paymentstatus_id" , config("constants.PAYMENT_STATUS_PAID"))
                ->get()
                ->isNotEmpty();
            if($flag)
            {

                    if(!$hasHamayeshHozouriArabi)
                    {
                        $hamayeshTalaiProductId = [ 210 , 211 ,212 ,213 , 214,215,216,217,218,219,220,221, 222 ];
                        $hasHamayeshTalaiArabi = $user->orders()
                            ->whereHas("orderproducts" , function ($q) use ($hamayeshTalaiProductId){
                                $q->whereIn("product_id" , $hamayeshTalaiProductId);
                            })
                            ->where("orderstatus_id" , config("constants.ORDER_STATUS_CLOSED"))
                            ->where("paymentstatus_id" , config("constants.PAYMENT_STATUS_PAID"))
                            ->get()
                            ->isNotEmpty();
                    }
            }

            $startTime2 = Carbon::create(2018, 06, 15, 07, 00, 00, 'Asia/Tehran');
            $endTime2 = Carbon::create(2018, 06, 15, 23, 59, 30, 'Asia/Tehran');
            $flag2 = ($now->between($startTime2, $endTime2));
            if($flag2)
            {
                $bon = Bon::where("name" , Config::get("constants.BON2"))->first() ;
                $userPoints = 0 ;
                if(isset($bon))
                {
                    $userPoints = $user->userHasBon($bon->name);
                    $exchangeAmount = $userPoints * config("constants.HAMAYESH_LOTTERY_EXCHANGE_AMOUNT");
                }
                if($userPoints <= 0)
                {
                    $lottery = Lottery::where("name" , Config::get("constants.LOTTERY_NAME"))
                        ->get()
                        ->first();
                    if(isset($lottery))
                    {
                        $userLottery = $user->lotteries()
                            ->where("lottery_id" , $lottery->id)
                            ->get()
                            ->first() ;
                        if(isset($userLottery))
                        {
                            $lotteryName = $lottery->displayName;
                            $lotteryMessage = "شما در قرعه کشی ".$lotteryName." شرکت داده شدید و متاسفانه برنده نشدید." ;
                            if(isset($userLottery->pivot->prizes))
                            {
                                $lotteryRank = $userLottery->pivot->rank;
                                if($lotteryRank == 0)
                                {
                                    $lotteryMessage = "شما از قرعه کشی ".$lotteryName." انصراف دادید." ;
                                }
                                else
                                {
                                    $lotteryMessage = "شما در قرعه کشی ".$lotteryName." برنده ".$lotteryRank." شدید." ;
                                }

                                $prizes = json_decode($userLottery
                                    ->pivot
                                    ->prizes
                                )->items;
                                $prizeCollection = collect() ;
                                foreach ($prizes as $prize)
                                {
                                    if(isset($prize->objectId))
                                    {
                                        $id = $prize->objectId;
                                        $model_name = $prize->objectType;
                                        $model = new $model_name;
                                        $modelObject = $model->find($id);

                                        $prizeCollection->push(["name"=>$prize->name]);
                                    }else{
                                        $prizeCollection->push(["name"=>$prize->name]);
                                    }
                                }
                            }

                        }
                    }
                }
            }

            $hasCompleteProfile = $user->orders()->whereHas("orderproducts" , function ($q)
            {
                $q->whereIn("product_id" , Config::get("constants.ORDOO_GHEIRE_HOZOORI_NOROOZ_97_PRODUCT"))->orwhereIn("product_id" , Config::get("constants.ORDOO_HOZOORI_NOROOZ_97_PRODUCT"));
            })->whereIn("orderstatus_id" , [Config::get("constants.ORDER_STATUS_CLOSED")])->get()->isNotEmpty();
            $userCompletion = (int)$user->completion();
            return view("user.profile.profile", compact("genders", "majors", "sideBarMode", "user" , "userPoints" ,
                "exchangeAmount" , "userLottery" ,"prizeCollection" , "hasCompleteProfile" , "userCompletion" , "lotteryRank" , "lottery" , "lotteryMessage" ,
                "hasHamayeshTalaiArabi" , "hasHamayeshHozouriArabi" , "lotteryName"));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        $majors = Major::pluck('name', 'id')->toArray();
        $userStatuses = Userstatus::pluck('displayName', 'id');
        $roles = Role::pluck('display_name', 'id')->toArray();
        $userRoles = $user->roles()->pluck('id')->toArray();
        $genders = Gender::pluck('name', 'id')->toArray();

        return view("user.edit" , compact("user" , "majors" , "userStatuses" , "roles" , "userRoles" , "genders")) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \app\Http\Requests\EditUserRequest $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, $user)
    {
        $photo = $user->photo;
        $password = $user->password ;
        $user->fill($request->all());

        if($request->has('techCode'))
            $user->techCode = $request->get('techCode');

        if(strlen($user->major_id) == 0) $user->major_id = null;
        if(strlen($user->gender_id) == 0) $user->gender_id = null;
        if(strlen($user->grade_id) == 0) $user->grade_id = null;
        if(strlen($user->bloodtype_id) == 0) $user->bloodtype_id = null;
        if(strlen(preg_replace('/\s+/', '', $user->email )) == 0) $user->email = null;
        if(strlen(preg_replace('/\s+/', '', $user->phone )) == 0) $user->phone = null;
        if(strlen(preg_replace('/\s+/', '', $user->city )) == 0) $user->city = null;
        if(strlen(preg_replace('/\s+/', '', $user->province )) == 0) $user->province = null;
        if(strlen(preg_replace('/\s+/', '', $user->address )) == 0) $user->address = null;
        if(strlen(preg_replace('/\s+/', '', $user->postalCode )) == 0) $user->postalCode = null;
        if(strlen(preg_replace('/\s+/', '', $user->school )) == 0) $user->school = null;
        if(strlen(preg_replace('/\s+/', '', $user->allergy )) == 0) $user->allergy = null;
        if(strlen(preg_replace('/\s+/', '', $user->medicalCondition )) == 0) $user->medicalCondition = null;
        if(strlen(preg_replace('/\s+/', '', $user->diet )) == 0) $user->diet = null;

        if(!$request->has("password") || strlen($request->get('password')) == 0)
        {
            $user->password = $password; //Pasword should not be updated
        }
        else
        {
            $user->password = bcrypt($request->get("password"));
        }

        $file = $this->requestHasFile($request , "photo");
        if ( $file !== false) {
            $extension = $file->getClientOriginalExtension();
            $fileName = basename($file->getClientOriginalName(), "." . $extension) . "_" . date("YmdHis") . '.' . $extension;
            if (Storage::disk(Config::get('constants.DISK1'))->put($fileName, File::get($file))) {
                if (strcmp($photo, Config::get('constants.PROFILE_DEFAULT_IMAGE')) != 0) Storage::disk(Config::get('constants.DISK1'))->delete($photo);
                $user->photo = $fileName;
            }

        }

        if ( $request->has("mobileNumberVerification"))
            $user->mobileNumberVerification = 1;
        else
            $user->mobileNumberVerification = 0 ;

        if ( $request->has("lockProfile"))
            $user->lockProfile = 1;
        else
            $user->lockProfile = 0 ;

        if ($user->update())
        {
            if(Auth::check() && Auth::User()->can(Config::get('constants.INSET_USER_ROLE')))
            {
                $newRoleIds = array() ;
                $oldRoles = $user->roles ;
                if($request->has("roles"))
                {
                    $newRoleIds = $request->get("roles");
                    foreach ($newRoleIds as $key => $newRoleId)
                    {
                        $newRole = Role::FindOrFail($newRoleId) ;
                        if($newRole->isDefault)
                        {
                            if(!Auth::user()->can(Config::get('constants.GIVE_SYSTEM_ROLE')))
                                unset($newRoleIds[$key]);
                        }

                    }

                    foreach ($oldRoles as $oldRole)
                    {
                        if($oldRole->isDefault)
                            if(!in_array($oldRole->id , $newRoleIds)) array_push($newRoleIds , $oldRole->id) ;
                    }
                    $user->roles()->sync($newRoleIds);
                }

            }
            if($request->has("fromAPI"))
            {
                $message = "اطلاعات با موفقیت اصلاح شد";
                $status = 200;
            }
             else
             {
                 session()->put("success", "اطلاعات کاربر با موفقیت اصلاح شد");
             }
        } else
        {
            if($request->has("fromAPI"))
            {
                $message = "خطای پایگاه داده";
                $status = 503;
            }
            else
            {
                session()->put("error", "خطای پایگاه داده.");
            }
        }

        if($request->has("fromAPI"))
            return $this->response->setStatusCode($status)->setContent(["message"=>$message]);
        else
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($user)
    {
//        if ($user->delete()) session()->put('success', 'کاربر با موفقیت اصلاح شد');
//        else session()->put('error', 'خطای پایگاه داده');
        $user->delete();
        return redirect()->back() ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \app\Http\Requests\EditProfileInfoRequest $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(EditProfileInfoRequest $request){
        $user = Auth::user();
        $user->fill($request->all());

        if ($request->hasFile("photo")) {
            $photoRequest = new EditProfilePhotoRequest();
            $photoRequest['photo'] = $request->photo;
            $this->updatePhoto($photoRequest);
        }

        if(strcmp($user->gender_id , "0")==0 || strlen($user->gender_id) == 0) $user->gender_id = null;
        if(strcmp($user->major_id , "0")==0 || strlen($user->major_id) == 0) $user->major_id = null;

        if($user->completion("lockProfile") == 100) $user->lockProfile = 1;
        if ($user->update()) {
            session()->put("belongsTo","moreInfo");
            session()->put("success", "اطلاعات شما با موفقیت اصلاح شد.");
        } else {
            session()->put("belongsTo", "moreInfo");
            session()->put("error", "خطای پایگاه داده.");
        }
        session()->put("tab", "tab_1_1");
        return redirect()->back()->withInput();
    }

    /**
     * Update the specified resource's photo in storage..
     *
     * @param  \app\Http\Requests\EditProfilePhotoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(EditProfilePhotoRequest $request ){
        $user = Auth::user();
        $file = $request->photo;
        $extension = $file->getClientOriginalExtension();
        $fileName = basename($file->getClientOriginalName() , ".".$extension) . "_" . date("YmdHis") . '.' . $extension;

        if (Storage::disk(Config::get('constants.DISK1'))->put($fileName, File::get($file))) {
			if(strcmp($user->photo , Config::get('constants.PROFILE_DEFAULT_IMAGE') )!=0) Storage::disk(Config::get('constants.DISK1'))->delete($user->photo);
            $user->photo = $fileName ;
        }
        if ($user->update()) {
            if($request->ajax())
            {
                $newPhotoSrc = route('image', ['category'=>'1','w'=>'150' , 'h'=>'150' ,  'filename' => $fileName ]);
                return $this->response->setStatusCode(200)
                                        ->setContent(["newPhoto"=>$newPhotoSrc]);
            }
            else
            {
                session()->put("belongsTo", "photo");
                session()->put("success", "تغییر عکس با موفقیت انجام شد.");
            }
        } else {
            if($request->ajax())
            {
                return $this->response->setStatusCode(503);
            }
            else
            {
                session()->put("belongsTo", "photo");
                session()->put("error", "خطای پایگاه داده.");
            }
        }
        session()->put("tab", "tab_1_2");
        return redirect()->back();
    }

    /**
     * Update the specified resource's password in storage..
     *
     * @param  \app\Http\Requests\EditProfilePasswordRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(EditProfilePasswordRequest $request)
    {
        $user = Auth::user();

        if (Hash::check($request->oldPassword, $user->password)) {
            if(Hash::check($request->password, $user->password))
            {
                session()->put("belongsTo", "password");
                session()->put("error", "رمز عبور جدید و قدیم یکسان می باشند!") ;
            }else{
                if($user->fill([
                    'password' => bcrypt($request->password)
                ])->update()) {
                    session()->put("belongsTo", "password");
                    session()->put("success", "رمز عبور با موفقیت تغییر یافت.");
                }
                else {
                    session()->put("belongsTo", "password");
                    session()->put("error", "خطا در تغییر رمز عبور ، لطفا دوباره اقدام نمایید.") ;
                }
            }

        }else{
            session()->put("belongsTo", "password");
            session()->put("error", "رمز عبور قدیم وارد شده اشتباه می باشد.") ;
        }
        session()->put("tab", "tab_1_3");
        return redirect()->back();
    }

    /**
     * Show authenticated user belongings
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function showBelongings()
    {
        $belongings = Auth::user()->belongings;
        $sideBarMode = "closed";
        $user = Auth::user();
        return view("user.belongings" , compact("belongings" , "sideBarMode" ,"user"));
    }

    /**
     * Display a listing user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function userOrders()
    {
//        if(Auth::user()->completion("fullAddress")!= 100) {
//            session()->put("userOrders",true);
//            return redirect(action("UserController@showProfile"));
//        }

        $debitCard = Bankaccount::all()->where("user_id" , 2)->first();
        $excludedOrderStatuses = [
            Config::get("constants.ORDER_STATUS_OPEN") ,
            Config::get("constants.ORDER_STATUS_OPEN_BY_ADMIN"),
            Config::get("constants.ORDER_STATUS_OPEN_BY_WALLET"),
            Config::get("constants.ORDER_STATUS_OPEN_DONATE"),
        ];
        $user = Auth::user() ;
        $orders = $user->orders
                            ->whereNotIn("orderstatus_id",$excludedOrderStatuses)
                            ->sortByDesc("completed_at");

        $transactions = $user->orderTransactions()
                            ->whereDoesntHave("parents")
                            ->where(function ($q){
                                $q->where("transactionstatus_id", Config::get("constants.TRANSACTION_STATUS_SUCCESSFUL"))
                                    ->orWhere("transactionstatus_id", Config::get("constants.TRANSACTION_STATUS_ARCHIVED_SUCCESSFUL"))
                                    ->orWhere("transactionstatus_id", Config::get("constants.TRANSACTION_STATUS_PENDING")) ;
                            })
                            ->orderByDesc("completed_at")
                            ->get()
                            ->groupBy("order_id");

        $instalments  = Transaction::whereIn("order_id" , $orders->pluck("id"))
                                    ->whereDoesntHave("parents")
                                    ->where("transactionstatus_id", Config::get("constants.TRANSACTION_STATUS_UNPAID"))
                                    ->orderBy("deadline_at")
                                    ->get();

        $gateways = Transactiongateway::all()
                                        ->where("enable",1)
                                        ->sortBy("order")
                                        ->pluck("displayName" , "name");

        $orderCoupons = collect();
        foreach($orders as $order)
        {
            $orderCoupon = $order->determineCoupontype();
            if( $orderCoupon!== false)
            {
                if($orderCoupon["type"] == Config::get("constants.DISCOUNT_TYPE_PERCENTAGE"))
                {
                    $orderCoupons->put($order->id , ["caption"=>"کپن ".$order->coupon->name." با ".$orderCoupon["discount"]." % تخفیف"]);
                }
                elseif($orderCoupon["type"] == Config::get("constants.DISCOUNT_TYPE_COST"))
                {
                    $orderCoupons->put($order->id , ["caption"=>"کپن ".$order->coupon->name." با ".number_format($orderCoupon["discount"])." تومان تخفیف"]);
                }
            }
        }
        return view("user.ordersList", compact("orders" , "gateways" , "debitCard" , "transactions" , "instalments" , "orderCoupons"));
    }

    /**
     * Display a page where user can upaload his consulting questions
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadConsultingQuestion()
    {

        return view("user.uploadConsultingQuestion");
    }

    /**
     * Display the list of uploaded files by user
     *
     * @return \Illuminate\Http\Response
     */
    public function uploads(){

        $questions = Auth::user()->useruploads->where("isEnable","1");
        $counter = 1;
        return view("user.consultingQuestions" , compact("questions" , "counter"));
    }

    /**
     * Send an account verification code to the user
     *
     * @return \Illuminate\Http\Response
     */
    public function sendVerificationCode()
    {
        $verificationMessageStatusSent = Verificationmessagestatuse::all()->where("name","sent")->first();
        $verificationMessageStatusNotDel = Verificationmessagestatuse::all()->where("name","notDelivered")->first();
        $verificationMessageStatusExpired = Verificationmessagestatuse::all()->where("name","expired")->first();
        $now = Carbon::now();
        $verificationMessages = collect();

        $user = Auth::user() ;
        $verificationMessages = $user->verificationmessages->where("verificationmessagestatus_id",$verificationMessageStatusSent->id)->sortByDesc("created_at");

        if($user->mobileNumberVerification)
        {
            session()->put("getVerificationCodeSuccess" , "حساب کاربری شما قبلا تایید شده است.");
            return redirect()->back() ;
        }

//        for($i=1 ; $i<=10 ; $i++)
//        {
//            $generatedCode = rand(1000,99999);
//            $similarCodes = Verificationmessage::all()->where("code" , $generatedCode)->where("verificationmessagestatus_id",$verificationMessageStatusSent->id);
//            if($similarCodes->isEmpty()){
//                $verificationCode = $generatedCode;
//                break;
//            }
//            else{
//                foreach ($similarCodes as $similarCode)
//                {
//                    if(!isset($similarCode->expired_at) ||  $now > $similarCode->expired_at)
//                    {
//                        $similarCode->verificationmessagestatus_id = $verificationMessageStatusExpired->id;
//                        if(!isset($similarCode->expired_at)) $similarCode->expired_at = $now;
//                        if($similarCode->update())  $verificationCode = $similarCode->code;
//                    }
//                }
//            }
//        }
        $verificationCode = rand(1000,99999);
        if(!isset($verificationCode)) {
            session()->put("verificationCodeInfo" , "در حال امکان تخصیص کد احراز هویت به شما وجود ندارد . لطفا چند لحظه دیگر اقدام نمایید");
            return redirect()->back() ;
        }

        $smsInfo = [];
        $smsInfo["to"] = array(ltrim($user->mobile, '0'));
        $smsInfo["message"] = "کد احراز شما در آلاء: ".$verificationCode;

        if($verificationMessages->isEmpty())
        {
            $response = $this->medianaSendSMS($smsInfo);
//                  $response = array("error"=>false , "message"=>"ارسال موفقیت آمیز بود");
            if(!$response["error"]){
                $request = new Request();
                $request->offsetSet("user_id" ,  $user->id);
                $request->offsetSet("code" ,  $verificationCode);
                $request->offsetSet("verificationmessagestatus_id" ,  $verificationMessageStatusSent->id);
                $request->offsetSet("expired_at" ,   Carbon::now()->addMinutes(Config::get('constants.MOBILE_VERIFICATION_TIME_LIMIT')));
                $verificationMessageController = new VerificationmessageController();
                if($verificationMessageController->store($request))
                {
                    session()->put("getVerificationCodeSuccess" , "کد احراز هویت شما با موفقیت به شماره تلفن همراهتان پیامک شد. شما ۳۰ دقیقه فرصت دارید کد دریافتی را در اینجا وارد نموده و بدین وسیله حساب کاربری خود را تایید نمایید. در صورت عدم دریافت پیامک می توانید پس از گذشت ۵ دقیقه دوباره درخواست کد نمایید.");
                    return redirect()->back() ;
                }else{
                    session()->put("verificationCodeError" , "خطای پایگاه داده در ارسال کد . لطفا چند لحظه دیگر اقدام نمایید.اگر در این فاصله پیامکی دریافت کردید لطفا آن را در نظر نگیرید");
                    return redirect()->back() ;
                }

            }else{
                session()->put("verificationCodeError" , "ارسال پیامک حاوی رمز عبور با مشکل مواجه شد! لطفا دوباره درخواست ارسال پیامک نمایید.");
                return redirect()->back() ;
            }
        }else{
                if($verificationMessages->count()>1)
                {
                    foreach($verificationMessages as $verificationMessage)
                    {
                        if($verificationMessage->id != $verificationMessages->first()->id)
                        {
                            $verificationMessage->verificationmessagestatus_id = $verificationMessageStatusExpired->id;
                            $verificationMessage->expired_at = $now;
                            $verificationMessage->update();
                        }
                    }
                }
                $verificationMessage = $verificationMessages->first();
                if($now->diffInMinutes($verificationMessage->created_at) > Config::get('constants.MOBILE_VERIFICATION_WAIT_TIME'))
                {
                    $verificationMessage->verificationmessagestatus_id = $verificationMessageStatusNotDel->id;
                    $verificationMessage->expired_at = $now ;
                    if($verificationMessage->update())
                    {
                        $response = $this->medianaSendSMS($smsInfo);
//                  $response = array("error"=>false , "message"=>"ارسال موفقیت آمیز بود");
                        if(!$response["error"]){
                            $request = new Request();
                            $request->offsetSet("user_id" ,  $user->id);
                            $request->offsetSet("code" ,  $verificationCode);
                            $request->offsetSet("verificationmessagestatus_id" ,  $verificationMessageStatusSent->id);
                            $request->offsetSet("expired_at" ,   Carbon::now()->addMinutes(Config::get('constants.MOBILE_VERIFICATION_TIME_LIMIT')));
                            $verificationMessageController = new VerificationmessageController();
                            if($verificationMessageController->store($request))
                            {
                                session()->put("getVerificationCodeSuccess" , "کد احراز هویت شما با موفقیت به شماره تلفن همراهتان پیامک شد. شما ۳۰ دقیقه فرصت دارید کد دریافتی را در اینجا وارد نموده و بدین وسیله حساب کاربری خود را تایید نمایید. در صورت عدم دریافت پیامک می توانید پس از گذشت ۵ دقیقه دوباره درخواست کد نمایید.");
                                return redirect()->back() ;
                            }else{
                                session()->put("verificationCodeError" , "خطای پایگاه داده در ارسال کد . لطفا چند لحظه دیگر اقدام نمایید.اگر در این فاصله پیامکی دریافت کردید لطفا آن را در نظر نگیرید");
                                return redirect()->back() ;
                            }

                        }else{
                            session()->put("verificationCodeError" , "ارسال پیامک حاوی رمز عبور با مشکل مواجه شد! لطفا دوباره درخواست ارسال پیامک نمایید.");
                            return redirect()->back() ;
                        }
                    }else{
                        session()->put("verificationCodeError" , "خطای پایگاه داده در ارسال کد . لطفا چند لحظه دیگر اقدام نمایید.");
                        return redirect()->back() ;
                    }
                }else{
                    if($now->diffInMinutes($verificationMessage->created_at) > 0 ) $timeInterval = $now->diffInMinutes($verificationMessage->created_at)." دقیقه ";
                    else $timeInterval = $now->diffInSeconds($verificationMessage->created_at)." ثانیه ";

                    session()->put("verificationCodeWarning" , "شما پس از گذشت ۵ دقیقه از آخرین درخواست خود می توانید دوباره درخواست ارسال نمایید .از زمان ارسال آخرین پیامک تایید برای شما ".$timeInterval."می گذرد." );
                    return redirect()->back() ;
                }
        }
    }

    /**
     * Send system generated password to the user that does not belong to anyone
     *
     * @param \App\Http\Requests\PasswordRecoveryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sendGeneratedPassword(PasswordRecoveryRequest $request){
        //uncomment and put permission to extend the code
        $mobile = $request->get("mobileNumber");
        if(isset($mobile))
        {
            $users = User::all()->where("mobile" , $mobile);
            if($users->isEmpty())
            {
                session()->put("error" , "شماره موبایل وارد شده اشتباه می باشد!");
                return redirect()->back();
            }else $user = $users->first();
        }

        if(!isset($user)) {
            if (Auth::check()) $user = Auth::user();
            else return redirect(action("HomeController@error403"));
        }
//        $user = Auth::user();
        $now = Carbon::now();
        if(isset($user->passwordRegenerated_at) && $now->diffInMinutes(Carbon::parse($user->passwordRegenerated_at)) < Config::get('constants.GENERATE_PASSWORD_WAIT_TIME')){
            if($now->diffInMinutes(Carbon::parse($user->passwordRegenerated_at)) > 0 ) $timeInterval = $now->diffInMinutes(Carbon::parse($user->passwordRegenerated_at))." دقیقه ";
            else $timeInterval = $now->diffInSeconds(Carbon::parse($user->passwordRegenerated_at))." ثانیه ";
            session()->put("warning" , "شما پس از گذشت ۵ دقیقه از آخرین درخواست خود می توانید دوباره درخواست ارسال رمز عبور نمایید .از زمان ارسال آخرین پیامک تایید برای شما ".$timeInterval."می گذرد.");
            session()->put("belongsTo", "password");
            session()->put("tab", "tab_1_3");
            return redirect()->back();
        }
//        $password = $this->generateRandomPassword(4);
        $password = ["rawPassword"=>$user->nationalCode , "hashPassword"=>bcrypt($user->nationalCode)];
        $user->password = $password["hashPassword"];

        /**
         * Sending auto generated password through SMS
         */
        $smsInfo = [];
        $smsInfo["to"] = array(ltrim($user->mobile, '0'));
        $smsInfo["message"] = "کاربر گرامی رمز عبور شما تغییر کرد.\n رمزعبور جدید ".$password["rawPassword"]."\n آلاء";
        $response = $this->medianaSendSMS($smsInfo);
//          $response = array("error"=>false , "message"=>"ارسال موفقیت آمیز بود");
        if(!$response["error"]){
            $user->passwordRegenerated_at = Carbon::now();
            session()->put("belongsTo", "password");
            session()->put("success" , "پیامک حاوی رمز عبور شما با موفقیت به شماره موبایلتان ارسال شد . در صورت عدم دریافت پیامک پس از ۵ دقیقه می توانید دوباره درخواست ارسال رمز عبور  نمایید");
        }else{
            $user->passwordRegenerated_at = null;
            session()->put("belongsTo", "password");
            session()->put("error" , "ارسال پیامک حاوی رمز عبور با مشکل مواجه شد! لطفا دوباره درخواست ارسال پیامک نمایید.");
        }
        $user->update();
        session()->put("tab", "tab_1_3");
        return redirect()->back();
    }

    /**
     * Verifying user account
     *
     * @param \App\Http\Requests\SubmitVerificationCode $request
     * @return \Illuminate\Http\Response
     */
    public function submitVerificationCode(SubmitVerificationCode $request)
    {
        if(Auth::user()->mobileNumberVerification)
        {
            return redirect(action("HomeController@error403"));
        }
        $code = $request->get("code");

        $verificationMessageStatusSent = Verificationmessagestatuse::all()->where("name","sent")->first();
        $verificationMessageStatusExpired = Verificationmessagestatuse::all()->where("name","expired")->first();
        $verificationMessageStatusSuccess = Verificationmessagestatuse::all()->where("name","successful")->first();
        $verificationMessages= Auth::user()->verificationmessages->where("code",$code)->where("verificationmessagestatus_id",$verificationMessageStatusSent->id)->sortByDesc("created_at");
        if($verificationMessages->isEmpty())
        {
            session()->put("verificationCodeError" , "کد وارد شده اشتباه می باشد و یا باطل شده است");
            return redirect()->back() ;
        }else{
            $verificationMessage = $verificationMessages->first();
            $now = Carbon::now();
            if(!isset($verificationMessage->expired_at) || $now < $verificationMessage->expired_at)
            {
                Auth::user()->mobileNumberVerification = 1;
                if(Auth::user()->update()) {
                    $verificationMessage->verificationmessagestatus_id = $verificationMessageStatusSuccess->id;
                    $verificationMessage->expired_at = $now;
                    if ($verificationMessage->update()) {
                        session()->put("verificationSuccess" , "حساب کاربری شما با موفقیت تایید شد! با تشکر.");
                        return redirect()->back() ;
                    } else {
                        session()->put("verificationCodeError" , "خطای پایگاه داده در تایید حساب کاربری . لطفا کد احراز هویت را مجددا وارد نمایید");
                        return redirect()->back() ;
                    }
                }
            }else{
                $verificationMessage->verificationmessagestatus_id = $verificationMessageStatusExpired->id;
                if($verificationMessage->update())
                {
                    session()->put("verificationCodeError" , "کد احراز هویت شما منقضی شده است . لطفا مجددا درخواست کد نمایید.");
                    return redirect()->back() ;
                }else{
                    session()->put("verificationCodeError" , "خطای پایگاه داده در تایید حساب کاربری . لطفا کد احراز هویت را مجددا وارد نمایید");
                    return redirect()->back() ;
                }
            }
        }
    }

    /**
     * Showing the form to the user for adding extra information after registeration
     *
     * @return \Illuminate\Http\Response
     */
    public function completeRegister()
    {
        if(Auth::user()->completion("afterLoginForm") == 100) {
            session()->pull("success");
            session()->pull("tab");
            session()->pull("belongsTo");
            if(session()->has("redirectTo")) return redirect(session()->pull("redirectTo"));
            else return redirect(action("HomeController@index"));
        }
        $previousPath = url()->previous();
        if(strcmp($previousPath , route('login'))==0) {
//            ToDo: config , obligating this form to the use or not
            if(true)
                $formByPass = false ;
            else
                $formByPass = true;
            $note = "برای ورود به سایت لطفا اطلاعات زیر را تکمیل نمایید";
        }
        else
            $note = "برای استفاده از این خدمت سایت لطفا اطلاعات زیر را تکمیل نمایید";
        $formFields =Afterloginformcontrol::getFormFields();
        $tables = [];
        foreach ($formFields as $formField){
            if(strpos($formField->name, "_id")) {
                $tableName = $formField->name;
                $tableName = str_replace("_id" ,"s" , $tableName);
                $tables[$formField->name] = DB::table($tableName)->pluck('name', 'id');
            }
        }
        return view("user.completeRegister" , compact("formFields" , "note" , "formByPass", "tables"));
    }

    private function addVideoPamphlet($productArray, &$productsWithPamphlet ,  &$productsWithVideo,  Collection &$pamphlets , Collection &$videos){


        if(!empty($productArray)){
            $videoArray = [];
            $pamphletArray = [];
            foreach ($productArray as $product)
            {

                if(!in_array($product->id,$pamphletArray) && !in_array($product->id, $videoArray))
                {
                    array_push($productsWithPamphlet,$product->id) ;
                    array_push($productsWithVideo, $product->id);

                    if(isset($pamphlets[$product->id]))
                        $pamphletArray = $pamphlets[$product->id];
                    else
                        $pamphletArray = [];
                    if(isset($videos[$product->id]))
                        $videoArray = $videos[$product->id];
                    else
                        $videoArray = [];

                    foreach($product->validProductfiles as $productfile)
                    {
                        if($productfile->productfiletype_id == Config::get("constants.PRODUCT_FILE_TYPE_PAMPHLET")) {
                            array_push($pamphletArray, [
                                "file" => $productfile->file,
                                "name" => $productfile->name,
                                "product_id" => $productfile->product_id
                            ]);
                        }
                        else {

                            array_push($videoArray, [
                                "file" => $productfile->file,
                                "name" => $productfile->name,
                                "product_id" => $productfile->product_id
                            ]);
                        }

                    }

                    if(!empty($pamphletArray))
                        $pamphlets->put($product->id, [
                            "productName"=>$product->name,
                            "pamphlets"=>$pamphletArray
                        ]);

                    if(!empty($videoArray))
                        $videos->put($product->id, [
                            "productName"=>$product->name,
                            "videos"=>$videoArray
                        ]);
                }

                $this->addVideoPamphlet($product->complimentaryproducts,$productsWithPamphlet,$productsWithVideo,$pamphlets,$videos);
            }
        }
    }

    /**
     * Showing files to user which he has got for his orders
     *
     * @return \Illuminate\Http\Response
     */
    public function userProductFiles()
    {

        $sideBarMode = "closed";
        $user = Auth::user();
        $products = $user->products();



        $key="user:userProductFiles:".$user->cacheKey().":P=".md5($products->pluck("id")->implode('-') );
        [$videos,$pamphlets] = Cache::remember($key,Config::get("constants.CACHE_60"),function () use ($products){
            $products->load('complimentaryproducts');
            $products->load('children');
            $products->load('validProductfiles');
            $productsWithVideo = [];
            $productsWithPamphlet = [];
            $pamphlets = collect();
            $videos = collect() ;
            foreach($products as $product)
            {
                if(!in_array($product->id,$productsWithPamphlet) && !in_array($product->id,$productsWithVideo ))
                {

                    array_push($productsWithPamphlet,$product->id) ;
                    array_push($productsWithVideo, $product->id);

                    $parentsArray = $this->makeParentArray($product);

                    $this->addVideoPamphlet($parentsArray,$productsWithPamphlet,$productsWithVideo,$pamphlets,$videos);

                    $childrenArray = $product->children;
                    $this->addVideoPamphlet($childrenArray,$productsWithPamphlet,$productsWithVideo,$pamphlets,$videos);

                    $pamphletArray = [];
                    $videoArray = [];
                    if($pamphlets->has($product->id))
                        $pamphletArray = $pamphlets->pull($product->id) ;
                    if ($videos->has($product->id))
                        $videoArray = $videos->pull($product->id);

                    foreach($product->validProductfiles as $productfile)
                    {
                        if($productfile->productfiletype_id == Config::get("constants.PRODUCT_FILE_TYPE_PAMPHLET"))
                            array_push($pamphletArray , [
                                "file"=>$productfile->file ,
                                "name"=>$productfile->name ,
                                "product_id"=>$productfile->product_id
                            ]);
                        else
                            array_push($videoArray, [
                                "file" => $productfile->file,
                                "name" => $productfile->name ,
                                "product_id" => $productfile->product_id
                            ]);

                    }
                    if(!empty($pamphletArray))
                        $pamphlets->put($product->id,
                            [
                                "productName"=>$product->name,
                                "pamphlets"=>$pamphletArray
                            ]);

                    if(!empty($videoArray))
                        $videos->put($product->id, [
                            "productName"=>$product->name,
                            "videos"=>$videoArray
                        ]);
                    $c = $product->complimentaryproducts;
                    $this->addVideoPamphlet($c,$productsWithPamphlet,$productsWithVideo,$pamphlets,$videos);
                }
            }
            return [$videos,$pamphlets];
        });

        $isEmptyProducts = $products->isEmpty();
        $userCompletion = (int)$user->completion();
        return view("user.assetsList" ,
            compact('section' , 'sideBarMode'  ,'isEmptyProducts' ,  'pamphlets' , 'videos' , 'user' , 'userCompletion')
        );
    }

    /**
     * Showing a survey to user to take part in
     *
     * @return \Illuminate\Http\Response
     */
    public function showSurvey()
    {
//        return redirect(action("HomeController@error404"));
        $event = Event::FindOrFail(1);
        $surveys = $event->surveys ;
        foreach ($surveys as $survey)
        {
            $questions = $survey->questions->sortBy("pivot.order");
            $questionsData = collect();
            $answersData = collect();
            foreach ($questions as $question)
            {
                $requestBaseUrl = $question->dataSourceUrl ;
                /**
                 * Getting raw answer
                 */
                $requestUrl = action("UserSurveyAnswerController@index");
                $requestUrl .= "?event_id[]=".$event->id ."&survey_id[]=".$survey->id."&question_id[]=".$question->id;
                $originalInput = \Illuminate\Support\Facades\Request::input();
                $request = \Illuminate\Support\Facades\Request::create($requestUrl , 'GET');
                \Illuminate\Support\Facades\Request::replace($request->input());
                $response = Route::dispatch($request);
                $answersCollection = json_decode($response->content());
                \Illuminate\Support\Facades\Request::replace($originalInput);
                $questionAnswerArray = [];
                foreach ($answersCollection as $answerCollection)
                {
                    /** Making answers */
                    $answerArray = $answerCollection->userAnswer->answer;
                    $requestUrl = url("/").$requestBaseUrl . "?ids=$answerArray";
                    $originalInput = \Illuminate\Support\Facades\Request::input();
                    $request = \Illuminate\Support\Facades\Request::create($requestUrl, 'GET');
                    \Illuminate\Support\Facades\Request::replace($request->input());
                    $response = Route::dispatch($request);
                    $dataJson = json_decode($response->content());
                    \Illuminate\Support\Facades\Request::replace($originalInput);
                    foreach ($dataJson as $data)
                    {
                        $questionAnswerArray = array_add($questionAnswerArray ,$data->id ,$data->name);
                    }
                }
                $answersData->put($question->id ,$questionAnswerArray );
                /**
                 *  Making questions
                 */
                if(strpos( $question->dataSourceUrl , "major" ) !== false)
                {
                    $userMajor = Auth()->user()->major;
                    $userMajors = collect();
                    $userMajors->push($userMajor);
                    foreach ($userMajors as $major)
                    {
                        $accessibleMajors = $major->accessibles ;
                        foreach ($accessibleMajors as $accessibleMajor)
                        {
                            $userMajors->push($accessibleMajor) ;
                        }
                    }
                    $userMajors = $userMajors->pluck('id')->toArray() ;
                    $requestUrl = url("/") . $requestBaseUrl."?";
                    foreach ($userMajors as $major)
                    {
                        $requestUrl .= "&parents[]=$major";
                    }
                    $originalInput = \Illuminate\Support\Facades\Request::input();
                    $request = \Illuminate\Support\Facades\Request::create($requestUrl, 'GET');
                    \Illuminate\Support\Facades\Request::replace($request->input());
                    $response = Route::dispatch($request);
                    $dataJson = json_decode($response->content());
                    \Illuminate\Support\Facades\Request::replace($originalInput);
                    $rootMajorArray = [];
                    $majorsArray = array() ;
                    foreach ($dataJson as $item)
                    {
                        $majorsArray = array_add($majorsArray ,$item->id ,$item->name);
                    }
                    $rootMajorArray = array_add($rootMajorArray ,$userMajor->name ,$majorsArray);
                    $questionsData->put($question->id , $rootMajorArray);
                }elseif(strpos( $question->dataSourceUrl , "city" ) !== false)
                {
                    $provinces = Province::orderBy("name")->get();
                    $provinceCityArray = [];
                    foreach ($provinces as $province)
                    {
                        $requestUrl = url("/").$requestBaseUrl."?provinces[]=$province->id";
                        $originalInput = \Illuminate\Support\Facades\Request::input();
                        $request = \Illuminate\Support\Facades\Request::create($requestUrl, 'GET');
                        \Illuminate\Support\Facades\Request::replace($request->input());
                        $response = Route::dispatch($request);
                        $dataJson = json_decode($response->content());
                        \Illuminate\Support\Facades\Request::replace($originalInput);
                        $citiesArray = array() ;
                        foreach ($dataJson as $item)
                        {
                            $citiesArray = array_add($citiesArray ,$item->id ,$item->name);
                        }
                        $provinceCityArray = array_add($provinceCityArray ,$province->name ,$citiesArray);
                        $questionsData->put($question->id , $provinceCityArray);
                    }
                }


            }

        }
        $pageName = "showSurvey";
        return view("survey.show" , compact("event" , "survey", "questions" , "questionsData" , "answersData" , "pageName" ));
    }

    /**
     * Storing user's work time (for employees)
     *
     * @param \App\Http\Controllers\EmployeetimesheetController $employeetimesheetController
     * @param \App\Http\Controllers\HomeController $homeController
     * @return \Illuminate\Http\Response
     */
    public function submitWorkTime(Request $request , EmployeetimesheetController $employeetimesheetController , HomeController $homeController)
    {
        $userId = Auth::user()->id ;
        $request->offsetSet("user_id" , $userId);
        $request->offsetSet("date" , Carbon::today('Asia/Tehran')->format("Y-m-d"));

        $toDayJalali = $this->convertToJalaliDay(Carbon::today('Asia/Tehran')->format('l')) ;
        $employeeSchedule = Employeeschedule::where("user_id", $userId)->where("day" , $toDayJalali)->get()->first();
        if (isset($employeeSchedule))
        {
            $request->offsetSet("userBeginTime" , $employeeSchedule->getOriginal("beginTime"));
            $request->offsetSet("userFinishTime" , $employeeSchedule->getOriginal("finishTime"));
            $request->offsetSet("allowedLunchBreakInSec" , gmdate("H:i:s",$employeeSchedule->getOriginal("lunchBreakInSeconds")));
        }

        $request->offsetSet( "modifier_id" , Auth::user()->id  ) ;
        $request->offsetSet( "serverSide" , true  ) ;
        $insertRequest = new \App\Http\Requests\InsertEmployeeTimeSheet($request->all()) ;
        $userTimeSheets = Employeetimesheet::where("date" , Carbon::today('Asia/Tehran'))->where("user_id" , Auth::user()->id)->get() ;
        if($userTimeSheets->count() == 0)
        {
            $done = $employeetimesheetController->store($insertRequest) ;
        }elseif($userTimeSheets->count() == 1)
        {
            $done = $employeetimesheetController->update($insertRequest , $userTimeSheets->first()) ;
        }else{
            $message = "شما بیش از یک ساعت کاری برای امروز ثبت نموده اید!";
            return $homeController->errorPage($message) ;
        }
        if($done)
            session()->flash("success", "ساعت کاری با موفقیت ذخیره شد") ;
        else
            session()->flash("error", "خطای پایگاه داده") ;

        return redirect()->back();
    }

    /**
     * Removes user from lottery
     *
     * @param \App\Http\Controllers\CouponController $couponController
     * @return \Illuminate\Http\Response
     */
    public function removeFromLottery()
    {
        $user = Auth::user() ;
        $message= "" ;

        $bonName = Config::get("constants.BON2") ;
        $bon = Bon::where("name" , $bonName)->first() ;
        if(isset($bon))
        {
            $userbons = $user->userValidBons($bon);
            if($userbons->isNotEmpty())
            {
                $usedUserBon = collect();
                $sumBonNumber = 0 ;
                foreach ($userbons as $userbon)
                {
                    $totalBonNumber = $userbon->totalNumber - $userbon->usedNumber;
                    $usedUserBon->put($userbon->id,["used"=>$totalBonNumber]);
                    $sumBonNumber += $totalBonNumber ;
                    $userbon->usedNumber = $userbon->usedNumber + $totalBonNumber;
                    $userbon->userbonstatus_id = Config::get("constants.USERBON_STATUS_USED");
                    $userbon->update();
                }
                $userBonTaken = true;

                [
                    $result ,
                    $responseText ,
                    $prizeName ,
                    $walletId
                ] = $this->exchangeLottery($user , $sumBonNumber);

                if($result)
                {
                    $lottery = Lottery::where("name" , Config::get("constants.LOTTERY_NAME"))
                                        ->first();
                    if(isset($lottery))
                    {
                        $prizes = '{
                          "items": [
                            {
                              "name": "'.$prizeName.'",
                              "objectType": "App\\\\Wallet",
                              "objectId": "'.$walletId.'"
                            }
                          ]
                        }';
                        if($user->lotteries()
                            ->where("lottery_id",$lottery->id)
                            ->get()
                            ->isEmpty())
                        {
                            $attachResult = $user->lotteries()
                                ->attach($lottery->id, [
                                        "rank" => 0,
                                        "prizes" => $prizes
                                    ]
                                );

                            /**  clearing cache */
                            Cache::tags('bon')->flush();
                            $done = true ;
                        }
                        else
                        {
                            $done = false ;
                            $message = "شما قبلا از قرعه کشی انصراف داده اید";
                        }
                    }
                    else
                    {
                        $done = false ;
                        $message = "خطای غیر منتظره. لطفا بعدا دوباره اقدام نمایید";
                    }
                }
                else{
                    $message = $responseText;
                    $done = false ;
                }
            }else{
                $done = false ;
                $message = "شما در قرعه کشی نیستید";
            }
        }else{
            $done = false;
            $message = "خطای غیر منتظره . لطفا بعدا اقدام فرمایید";
        }

        if(isset($done))
            if($done)
            {
                return $this->response->setStatusCode(200);
            }
            else
            {
                if(isset($userBonTaken) && $userBonTaken){
                    foreach ($userbons as $userbon)
                    {
                        if(isset($usedUserBon[$userbon->id]))
                        {
                            $usedNumber = $usedUserBon[$userbon->id]["used"] ;
                            $userbon->usedNumber = max($userbon->usedNumber - $usedNumber , 0);
                            $userbon->userbonstatus_id = Config::get("constants.USERBON_STATUS_ACTIVE");
                        }else{
                            $userbon->usedNumber = 0;
                            $userbon->userbonstatus_id = Config::get("constants.USERBON_STATUS_ACTIVE");
                        }

                        $userbon->update();
                    }
                }
                return $this->response->setStatusCode(503)->setContent(["message"=>$message]);
            }
        else
            return $this->response->setStatusCode(503)->setContent(["message"=>"عملیاتی انجام نشد"]);
    }

    /**
     * Show the form for completing information of the specified resource.(Created for orduatalaee 97)
     *
     * @return \Illuminate\Http\Response
     */
    public function informationPublicUrl()
    {
        return redirect(action("UserController@information" , Auth::user()) , 301) ;
    }
    public function information($user)
    {
        $validOrders = $user->orders()->whereHas("orderproducts" , function ($q)
        {
            $q->whereIn("product_id" , Config::get("constants.ORDOO_GHEIRE_HOZOORI_NOROOZ_97_PRODUCT"))
			->orwhereIn("product_id" , Config::get("constants.ORDOO_HOZOORI_NOROOZ_97_PRODUCT"))
			->orwhereIn("product_id" , [199 , 202]);
        })->whereIn("orderstatus_id" , [Config::get("constants.ORDER_STATUS_CLOSED")]);

        if($validOrders->get()->isEmpty())
        {
            return redirect(action("ProductController@landing2"));
        }
        $unPaidOrders = $validOrders->get() ;
        $paidOrder = $validOrders->whereIn("paymentstatus_id" ,[Config::get("constants.PAYMENT_STATUS_PAID") , Config::get("constants.PAYMENT_STATUS_INDEBTED")] )->get();
        if($paidOrder->isNotEmpty()) $order = $paidOrder->first();
        else $order = $unPaidOrders->first();

        if(!isset($order)) abort(403);

        $orderproduct = $order->orderproducts(Config::get("constants.ORDER_PRODUCT_TYPE_DEFAULT"))->get()->first();
        $product = $orderproduct->product ;
        if(in_array($product->id , Config::get("constants.ORDOO_HOZOORI_NOROOZ_97_PRODUCT"))) $userHasMedicalQuestions = true;
        else $userHasMedicalQuestions = false;
        $grandParent = $product->getGrandParent();
        if($grandParent !== false)
        {
            $userProduct = $grandParent->name;
        }else
        {
            $userProduct = $product->name;
        }


        $simpleContact = \App\Contacttype::where("name" , "simple")->get()->first();
        $mobilePhoneType = \App\Phonetype::where("name" , "mobile")->get()->first();
        $parents = \App\Relative::whereIn("name" , ["father" , "mother"])->get();
        $parentsNumber = collect();
        foreach ($parents as $parent)
        {
            $parentContacts = $user->contacts->where("relative_id" , $parent->id)->where("contacttype_id" , $simpleContact->id);
            if($parentContacts->isNotEmpty())
            {
                $parentContact = $parentContacts->first();
                $parentMobiles = $parentContact->phones->where("phonetype_id" , $mobilePhoneType->id)->sortBy("priority");
                if($parentMobiles->isNotEmpty())
                {
                    $parentMobile = $parentMobiles->first()->phoneNumber;
                    $parentsNumber->put($parent->name, $parentMobile);
                }
            }
        }
        $majors = Major::pluck('name', 'id')->toArray();
        $majors[0] = "نامشخص";
        $majors = array_sort_recursive($majors);
        /////////////////////////////////////////
        $genders = Gender::pluck('name', 'id')->toArray();
        $genders[0] = "نامشخص";
        $genders = array_sort_recursive($genders);
        ///////////////////////
        $bloodTypes = Bloodtype::pluck('name', 'id')->toArray();
        $bloodTypes[0] = "نامشخص";
        $bloodTypes = array_sort_recursive($bloodTypes);
        //////////////////////////
        $grades = Grade::pluck('displayName', 'id')->toArray();
        $grades[0] = "نامشخص";
        $grades = array_sort_recursive($grades);
        $orderFiles = $order->files;

        //////////Lock fields//////////
        $lockedFields = [];
        if($user->lockProfile)
        {
            $lockedFields = $user->returnLockProfileItems();
        }
        if($userHasMedicalQuestions)
        {
            $completionFields = $user->returnCompletionItems();
            $completionFieldsCount = count($completionFields);
            $completionPercentage = (int)$user->completion("completeInfo");
        }else
        {
            $completionFields = array_diff($user->returnCompletionItems() , $user->returnMedicalItems()) ;
            $completionFieldsCount = count($completionFields);
            $completionPercentage = (int)$user->completion("custom" , $completionFields);
        }

        $completedFieldsCount = (int)ceil(($completionPercentage * $completionFieldsCount)/100);
        if($orderFiles->isNotEmpty()) {
            $completedFieldsCount++;
        }
        $completionFieldsCount++;

        if(isset($order->customerExtraInfo))
        {
            $customerExtraInfo = json_decode($order->customerExtraInfo);
            foreach($customerExtraInfo as $item)
            {
                if(isset($item->info) && strlen(preg_replace('/\s+/', '', $item->info)) > 0)
                {
                    $completedFieldsCount++;
                }
                $completionFieldsCount++;
            }
        }

        if(isset($parentsNumber["father"]))
        {
            $completedFieldsCount++;
        }
        $completionFieldsCount++;

        if(isset($parentsNumber["mother"]))
        {
            $completedFieldsCount++;
        }
        $completionFieldsCount++;

        $completionPercentage = (int)(($completedFieldsCount/$completionFieldsCount)*100) ;
        if($completionPercentage == 100)
        {
            if($user->completion("lockProfile") == 100) {
                $user->lockProfile = 1 ;
                $user->timestamps = false;
                $user->update();
                $user->timestamps = true;
            }
        }
        return view("user.completeInfo" , compact("user" , "parentsNumber" ,"majors" , "genders" , "bloodTypes" , "grades" , "userProduct" , "order" , "orderFiles" , "userHasMedicalQuestions" , "lockedFields" ,"completionPercentage" , "customerExtraInfo"));
    }

    /**
     * Store the complentary information of specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function completeInformation(User $user ,Request $request , UserController $userController , PhoneController $phoneController
        , ContactController $contactController , OrderController $orderController  )
    {
        if(strlen($request->get("phone"))>0) $this->convertToEnglish(preg_replace('/\s+/', '',$request->get("phone") ));
		if(strlen($request->get("postalCode"))>0) $this->convertToEnglish(preg_replace('/\s+/', '',$request->get("postalCode") ));
        if(strlen($request->get("parentMobiles")["father"])>0) $this->convertToEnglish(preg_replace('/\s+/', '', $request->get("parentMobiles")["father"] ));
        if(strlen($request->get("parentMobiles")["mother"])>0) $this->convertToEnglish(preg_replace('/\s+/', '', $request->get("parentMobiles")["mother"] ));
        if(strlen($request->get("school"))>0) $this->convertToEnglish($request->get("school"));
        if(strlen($request->get("allergy"))>0)$this->convertToEnglish($request->get("allergy"));
        if(strlen($request->get("medicalCondition"))>0) $this->convertToEnglish($request->get("medicalCondition"));
        if(strlen($request->get("diet"))>0) $this->convertToEnglish($request->get("diet"));
        if(strlen($request->get("introducer"))>0) $this->convertToEnglish($request->get("introducer"));
        $this->validate($request, [
            'photo' => 'image|mimes:jpeg,jpg,png|max:200',
            'file' => 'mimes:jpeg,jpg,png,zip,pdf,rar',
        ]);
        if(Auth::user()->id != $user->id) abort(403) ;
        if($request->has("order")) {
            $orderId = $request->get("order");
            $order = Order::FindOrFail($orderId);
            if ($order->user_id != Auth::user()->id) abort(403);
        }else{
            return $this->response->setStatusCode(422);
        }
        /**
         * User's basic info
         **/
        $editUserRequest = new EditUserRequest() ;
        if($request->hasFile("photo")) $editUserRequest->offsetSet("photo",$request->file("photo"));
        $editUserRequest->offsetSet("province",$request->get("province"));
		$editUserRequest->offsetSet("address",$request->get("address"));
		$editUserRequest->offsetSet("postalCode",$request->get("postalCode"));
        $editUserRequest->offsetSet("city",$request->get("city"));
        $editUserRequest->offsetSet("school",$request->get("school"));
        if($request->get("major_id") != 0)  $editUserRequest->offsetSet("major_id",$request->get("major_id"));
        if($request->get("grade_id") != 0)   $editUserRequest->offsetSet("grade_id",$request->get("grade_id"));
        if($request->get("gender_id") != 0)   $editUserRequest->offsetSet("gender_id",$request->get("gender_id"));
        if($request->get("bloodtype_id") != 0)   $editUserRequest->offsetSet("bloodtype_id",$request->get("bloodtype_id"));
        $editUserRequest->offsetSet("phone",$request->get("phone"));
        $editUserRequest->offsetSet("allergy",$request->get("allergy"));
        $editUserRequest->offsetSet("medicalCondition",$request->get("medicalCondition"));
        $editUserRequest->offsetSet("diet",$request->get("diet"));
        $userController->update($editUserRequest , $user);

        /**
         *
         */
        /**
         * Parent's basic info
         **/
        $simpleContact = \App\Contacttype::where("name" , "simple")->get()->first();
        $mobilePhoneType = \App\Phonetype::where("name" , "mobile")->get()->first();
        $parentsNumber = $request->get("parentMobiles");

        foreach ($parentsNumber as $relative => $mobile)
        {
            if(strlen(preg_replace('/\s+/', '', $mobile )) == 0) continue;
            $parent = \App\Relative::where("name" , $relative)->get()->first();
            $parentContacts = $user->contacts->where("relative_id" , $parent->id)->where("contacttype_id" , $simpleContact->id);
            if($parentContacts->isEmpty())
            {
                $storeContactRequest = new \App\Http\Requests\InsertContactRequest();
                $storeContactRequest->offsetSet("name" , $relative);
                $storeContactRequest->offsetSet("user_id" , $user->id);
                $storeContactRequest->offsetSet("contacttype_id" , $simpleContact->id);
                $storeContactRequest->offsetSet("relative_id" , $parent->id);
                $storeContactRequest->offsetSet("isServiceRequest" , true);
                $response = $contactController->store($storeContactRequest);
                if($response->getStatusCode() == 200)
                {
                    $responseContent = json_decode($response->getContent("contact"));
                    $parentContact = $responseContent->contact;
                }elseif($response->getStatusCode() == 503){

                }
            }else{
                $parentContact = $parentContacts->first();
            }
            if(isset($parentContact))
            {
                $parentContact = Contact::where("id" , $parentContact->id)->get()->first();
                $parentMobiles = $parentContact->phones->where("phonetype_id" , $mobilePhoneType->id)->sortBy("priority");
                if($parentMobiles->isEmpty())
                {
                    $storePhoneRequest = new \App\Http\Requests\InsertPhoneRequest();
                    $storePhoneRequest->offsetSet("phoneNumber" , $mobile);
                    $storePhoneRequest->offsetSet("contact_id" , $parentContact->id);
                    $storePhoneRequest->offsetSet("phonetype_id" , $mobilePhoneType->id);
                    $response = $phoneController->store($storePhoneRequest);
                    if($response->getStatusCode() == 200)
                    {

                    }elseif($response->getStatusCode() == 503){

                    }

                }else
                {
                    $parentMobile = $parentMobiles->first();
                    $parentMobile->phoneNumber = $mobile;
                    if($parentMobile->update())
                    {

                    }else
                    {

                    }
                }
            }
        }
        /**
         *
         */


        $updateOrderRequest = new \App\Http\Requests\EditOrderRequest();
        if($request->hasFile("file")) $updateOrderRequest->offsetSet("file",$request->file("file"));
        /**
         * customerExtraInfo
         */
        $jsonConcats = "" ;
        $extraInfoQuestions = array_sort_recursive($request->get("customerExtraInfoQuestion"));
        $customerExtraInfoAnswers = $request->get("customerExtraInfoAnswer");
        foreach ($extraInfoQuestions as $key => $question)
        {
            $obj = new stdClass();
            $obj->title = $question ;
            if(strlen(preg_replace('/\s+/', '', $customerExtraInfoAnswers[$key])) > 0 ) $obj->info = $customerExtraInfoAnswers[$key] ;
            else $obj->info = null;
            if(strlen($jsonConcats) > 0 )
                $jsonConcats = $jsonConcats . ',' . json_encode($obj) ;
            else
                $jsonConcats = json_encode($obj) ;
        }
        $customerExtraInfo = "[" . $jsonConcats . "]";
        $updateOrderRequest->offsetSet("customerExtraInfo", $customerExtraInfo );
        $orderController->update($updateOrderRequest , $order);

        session()->put("success" , "اطلاعات با موفقیت ذخیره شد");
        return redirect()->back();

    }


    /**
     * Register student for sanati sharif highschool
     *
     * @param  \App\Http\Requests\RegisterForSanatiSharifHighSchoolRequest $request
     * @return \Illuminate\Http\Response
     */
    public function registerForSanatiSharifHighSchool(RegisterForSanatiSharifHighSchoolRequest $request ,
                                                      RegisterController $registerController ,
                                                    EventresultController $eventResultController ,
                                                    HomeController $homeController)
    {
        $event = Event::where("name" , "sabtename_sharif_97")->get();
        if($event->isEmpty())
        {
            session()->put("error", "رخداد یافت نشد");
            return redirect()->back();
        }
        else
        {
            $event = $event->first() ;
        }

        if(Auth::check())
            $user = Auth::user();
        else
            $registeredUser = User::where("mobile" , $request->get("mobile"))->where("nationalCode" , $request->get("nationalCode"))->get();

        if(!isset($user) && $registeredUser->isEmpty()) {
            $registerRequest = new Request();
            $registerRequest->offsetSet("firstName", $request->get("firstName"));
            $registerRequest->offsetSet("lastName", $request->get("lastName"));
            $registerRequest->offsetSet("mobile", $request->get("mobile"));
            $registerRequest->offsetSet("nationalCode", $request->get("nationalCode"));
            $registerRequest->offsetSet("major_id", $request->get("major_id"));
            $registerRequest->offsetSet("grade_id", $request->get("grade_id"));
//            $registerRequest->offsetSet("gender_id", 1);
            $registerController = new RegisterController();
            $response = $registerController->register($registerRequest);
            if ($response->getStatusCode() != 302)
            {
                session()->put("error", "خطایی در ثبت اطلاعات شما اتفاق افتاد . لطفا دوباره اقدام نمایید.");
                return redirect()->back();
            }
            $user = Auth::user();
        }
        else
        {
            if(!isset($user))
                $user = $registeredUser->first();
            $updateRequest = new EditUserRequest();
            if($request->has("firstName") && (!isset($user->firstName) || strlen(preg_replace('/\s+/', '', $user->firstName )) == 0) )
                $updateRequest->offsetSet("firstName", $request->get("firstName"));
            if($request->has("lastName") && ( !isset($user->lastName) || strlen(preg_replace('/\s+/', '', $user->lastName )) == 0) )
                $updateRequest->offsetSet("lastName", $request->get("lastName"));
            $updateRequest->offsetSet("major_id" , $request->get("major_id"));
            $updateRequest->offsetSet("grade_id" , $request->get("grade_id"));
            $updateRequest->offsetSet("fromAPI" , 1);
            $response =  $this->update($updateRequest , $user);
            if($response->getStatusCode() == 503)
            {
                session()->put("error", "خطایی در ثبت اطلاعات شما رخ داد. لطفا مجددا اقدام نمایید");
                return redirect()->back();
            }
        }

        $eventRegistered = $user->eventresults->where("user_id" , $user->id)->where("event_id" , $event->id) ;
        if($eventRegistered->isNotEmpty())
        {
            session()->put("error", "شما قبلا ثبت نام کرده اید");
            return redirect()->back();
        }
        else
        {
            $evenResultRequest = new \App\Http\Requests\InsertEventResultRequest();
            $evenResultRequest->offsetSet("user_id" , $user->id);
            $evenResultRequest->offsetSet("event_id" , $event->id);
            $evenResultRequest->offsetSet("participationCodeHash" ,$request->get("score") );
            $evenResultRequest->offsetSet("fromAPI" , 1);
            $response = $eventResultController->store($evenResultRequest) ;
            if($response->getStatusCode() == 503)
            {
                session()->put("error", "خطایی در ثبت نام شما رخ داد. لطفا مجددا اقدام نمایید");
                return redirect()->back();
            }
            else
            {
//                $result = json_decode($response->getContent());
//                if(isset($result->participationCode))
//                    $participationCode = $result->participationCode;
            }
        }

        $message = "پیش ثبت نام شما در دبیرستان دانشگاه صنعتی شریف با موفقیت انجام شد ." ;
        if(isset($participationCode))
            $message .= "کد داوطلبی شما: ".$participationCode;
//        $sendSMSRequest = new \App\Http\Requests\SendSMSRequest();
//        $sendSMSRequest->offsetSet("message" , $message);
//        $sendSMSRequest->offsetSet("users" , [$user->id]);
//        $sendSMSRequest->offsetSet("relatives" , [0]);
//        $response = $homeController->sendSMS($sendSMSRequest);
        session()->put("success", $message);
        return redirect()->back();
    }

    /**
     * Submit user request for voucher request
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function voucherRequest(Request $request)
    {
        $url = $request->url();
        $title = "آلاء| درخواست اینترنت آسیاتک";
        SEO::setTitle($title);
        SEO::opengraph()->setUrl($url);
        SEO::setCanonical($url);
        SEO::twitter()->setSite("آلاء");
        SEO::setDescription($this->setting->site->seo->homepage->metaDescription);
        SEO::opengraph()->addImage(route('image', ['category'=>'11','w'=>'100' , 'h'=>'100' ,  'filename' =>  $this->setting->site->siteLogo ]), ['height' => 100, 'width' => 100]);

        $user = Auth::user();
        $genders = Gender::pluck('name', 'id')->prepend("انتخاب کنید");
        $majors = Major::pluck('name', 'id')->prepend("انتخاب کنید");
        $sideBarMode = "closed";

        $asiatechProduct = config("constants.ASIATECH_FREE_ADSL") ;
        $nowDateTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran');
        $userHasRegistered = false;

            $asitechPendingOrders = Order::whereHas("orderproducts" , function ($q) use ($asiatechProduct)
                                            {
                                                $q->where("product_id" , $asiatechProduct );
                                            })
                                                ->where("orderstatus_id" , config("constants.ORDER_STATUS_PENDING"))
                                                ->where("paymentstatus_id" , config("constants.PAYMENT_STATUS_PAID"))
                                                ->orderBy("completed_at")
                                                ->get();
            $userAsitechPendingOrders = $asitechPendingOrders->where("user_id" , $user->id) ;
            if($userAsitechPendingOrders->isNotEmpty())
            {
                $rank = $userAsitechPendingOrders->keys()->first() + 1 ;

                $userHasRegistered = true;
            }
            else
            {
                $asitechApprovedOrders = $user->orders()
                                                ->whereHas("orderproducts" , function ($q) use ($asiatechProduct)
                                                {
                                                    $q->where("product_id" , $asiatechProduct );
                                                })
                                                    ->where("orderstatus_id" , config("constants.ORDER_STATUS_CLOSED"))
                                                    ->where("paymentstatus_id" , config("constants.PAYMENT_STATUS_PAID"))
                                                    ->orderBy("completed_at")
                                                    ->get();
                if($asitechApprovedOrders->isNotEmpty())
                {
                    $userVoucher = $user->productvouchers
                                ->where("expirationdatetime" , ">" , $nowDateTime)
                                ->where("product_id" , $asiatechProduct)
                                ->first();

                    $userHasRegistered = true;
                }
            }

        return view("user.submitVoucherRequest" , compact("user" ,
                                                                     "genders" ,
                                                                         "majors" ,
                                                                         "sideBarMode" ,
                                                                         "userHasRegistered" ,
                                                                         "rank" ,
                                                                         "userVoucher"
        ));
    }

    /**
     * Submit user request for voucher request
     *
     * @param  \App\Http\Requests\InsertVoucherRequest InsertVoucherRequest
     * @return \Illuminate\Http\Response
     */
    public function submitVoucherRequest(InsertVoucherRequest $request)
    {
        $asiatechProduct = config("constants.ASIATECH_FREE_ADSL") ;
        $user = Auth::user();
        $nowDateTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran');
        $vouchers = $user->productvouchers
                        ->where("expirationdatetime" , ">" , $nowDateTime)
                        ->where("product_id" , $asiatechProduct);
        if($vouchers->isNotEmpty())
        {
            session()->put("error","شما برای اینترنت رایگان ثبت نام کرده اید");
            return redirect()->back();
        }

        $updateRequest = new EditUserRequest();
        $updateRequest->offsetSet("fromAPI" , 1);
        $updateRequest->offsetSet("postalCode" , $request->get("postalCode"));
        $updateRequest->offsetSet("email" , $request->get("email"));
        $updateRequest->offsetSet("gender_id" , $request->get("gender_id"));
        $updateRequest->offsetSet("province" , $request->get("province"));
        $updateRequest->offsetSet("city" , $request->get("city"));
        $updateRequest->offsetSet("address" , $request->get("address"));
        if($user->mobileNumberVerification )
            $updateRequest->offsetSet("mobileNumberVerification" , 1);
        $birthdate = Carbon::parse($request->get("birthdate") )
                            ->setTimezone("Asia/Tehran")->format('Y-m-d');
        $updateRequest->offsetSet("birthdate" , $birthdate);
        $updateRequest->offsetSet("school" , $request->get("school"));
        $updateRequest->offsetSet("major_id" , $request->get("major_id"));
        $updateRequest->offsetSet("introducedBy" , $request->get("introducedBy"));
        $response =  $this->update($updateRequest , $user);
        $completionColumns = [
                                "firstName",
                                "lastName",
                                "mobile",
                                "nationalCode",
                                "province",
                                "city",
                                "address",
                                "postalCode",
                                "gender_id" ,
                                "birthdate",
                                "school",
                                "major_id",
                                "introducedBy",
                                "email",
                                "mobileNumberVerification",
                                "photo"
                            ];
        if($response->getStatusCode() == 200)
        {
            if($user->completion("custom" ,$completionColumns) < 100)
            {
                session()->put("error","اطلاعات شما ذخیره شد اما برای ثبت درخواست اینترنت رایگان آسیاتک کامل نمی باشند . لطفا اطلاعات خود را تکمیل نمایید.");
            }
            else
            {
                $asiatechOrder = new Order();
                $asiatechOrder->orderstatus_id = config("constants.ORDER_STATUS_PENDING") ;
                $asiatechOrder->paymentstatus_id = config("constants.PAYMENT_STATUS_PAID") ;
                $asiatechOrder->cost = 0 ;
                $asiatechOrder->costwithoutcoupon = 0;
                $asiatechOrder->user_id = $user->id ;
                $asiatechOrder->completed_at = Carbon::now()->setTimezone("Asia/Tehran");
                if($asiatechOrder->save())
                {
                    $request->offsetSet("cost" , 0);
                    $request->offsetSet("orderId_bhrk" , $asiatechOrder->id);
                    $product =  Product::where("id" , $asiatechProduct)->first();
                    if(isset($product))
                    {
                        $orderController = new OrderController();
                        $response = $orderController->addOrderproduct($request , $product) ;
                        $responseStatus = $response->getStatusCode();
                        $result = json_decode($response->getContent());
                        if($responseStatus == 200)
                        {
                            $nowDateTime = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran');
                            $unusedVoucher = Productvoucher::whereNull("user_id")
                                                            ->where("enable" , 1)
                                                            ->where("expirationdatetime" , ">" , $nowDateTime)
                                                            ->where("product_id" , $asiatechProduct)
                                                            ->get()
                                                            ->first();
                            if(false)
                            {
                                $unusedVoucher->user_id = $user->id;
                                if($unusedVoucher->update())
                                {
                                    $user->lockProfile = 1;
                                    $user->update();
                                }
                                else
                                {
                                    session()->put("error","خطا در تخصیص کد تخفیف");
                                }
                            }
                            else
                            {
                                session()->put("error","کد تخفیفی برای شما یافت نشد");
                            }
                        }
                        else
                        {
                            session()->put("error","خطا در ثبت محصول اینرنت رایگان آسیاتک");
                        }
                    }
                    else
                    {
                        session()->put("error","محصول اینترنت آسیاتک یافت نشد");
                    }
                }
                else
                {
                    session()->put("error","خطا در ثبت سفارش اینترنت رایگان. لطفا بعدا اقدام نمایید");
                }
            }
        }
        else
        {
            session()->put("error","مشکل غیر منتظره ای در ذخیره اطلاعات شما پیش آمد . لطفا مجددا اقدام نمایید");
        }

        return redirect()->back();
    }
}
