<?php

namespace App\Http\Controllers\Web;

use App\{Assignmentstatus,
    Attribute,
    Attributecontrol,
    Attributeset,
    Bon,
    Checkoutstatus,
    Consultationstatus,
    Coupon,
    Coupontype,
    Gender,
    Http\Requests\InsertUserRequest,
    Lottery,
    Major,
    Notifications\GeneralNotice,
    Notifications\UserRegisterd,
    Order,
    Orderstatus,
    Paymentmethod,
    Paymentstatus,
    Permission,
    Product,
    Producttype,
    Repositories\WebsitePageRepo,
    Role,
    Traits\APIRequestCommon,
    Traits\CharacterCommon,
    Traits\Helper,
    Traits\ProductCommon,
    Traits\RequestCommon,
    Traits\UserCommon,
    Transactiongateway,
    Transactionstatus,
    User,
    Userbon,
    Userbonstatus,
    Userstatus,
    Userupload,
    Useruploadstatus,
    Websitepage,
    Websitesetting,
    Http\Controllers\Controller};
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class AdminController extends Controller
{
    use Helper;
    use APIRequestCommon;
    use ProductCommon;
    use CharacterCommon;
    use UserCommon;
    use RequestCommon;

    public function __construct()
    {
        $this->middleware('ability:'.config('constants.ROLE_ADMIN').','.config('constants.USER_ADMIN_PANEL_ACCESS'),
            ['only' => 'admin']);
        $this->middleware('permission:'.config('constants.CONSULTANT_PANEL_ACCESS'),
            ['only' => 'consultantAdmin']);
        $this->middleware('permission:'.config('constants.PRODUCT_ADMIN_PANEL_ACCESS'),
            ['only' => 'adminProduct']);
        $this->middleware('permission:'.config('constants.CONTENT_ADMIN_PANEL_ACCESS'),
            ['only' => 'adminContent']);
        $this->middleware('permission:'.config('constants.LIST_ORDER_ACCESS'), ['only' => 'adminOrder']);
        $this->middleware('permission:'.config('constants.SMS_ADMIN_PANEL_ACCESS'), ['only' => 'adminSMS']);
        $this->middleware('permission:'.config('constants.REPORT_ADMIN_PANEL_ACCESS'), ['only' => 'adminReport']);
        $this->middleware('ability:'.config('constants.ROLE_ADMIN').','.config('constants.TELEMARKETING_PANEL_ACCESS'),
            ['only' => 'adminTeleMarketing']);
        $this->middleware('permission:'.config('constants.INSERT_COUPON_ACCESS'),
            ['only' => 'adminGenerateRandomCoupon']);
        $this->middleware('role:admin', [
            'only' => [
                'adminLottery',
                'registerUserAndGiveOrderproduct',
                'specialAddUser',
            ],
        ]);


    }


    /**
     * Show admin panel main page
     *
     * @return Response
     */
    public function admin()
    {
        $userStatuses       = Userstatus::pluck('displayName', 'id');
        $majors             = Major::pluck('name', 'id');
        $genders            = Gender::pluck('name', 'id');
        $gendersWithUnknown = clone $genders;
        $gendersWithUnknown->prepend('نامشخص');
        $permissions = Permission::pluck('display_name', 'id');
        $roles       = Role::pluck('display_name', 'id');
        //        $roles = array_add($roles , 0 , "همه نقش ها");
        //        $roles = array_sort_recursive($roles);
        $limitStatus  = [
            0 => 'نامحدود',
            1 => 'محدود',
        ];
        $enableStatus = [
            0 => 'غیرفعال',
            1 => 'فعال',
        ];

        $orderstatuses = Orderstatus::whereNotIn('id', [config('constants.ORDER_STATUS_OPEN')])
            ->pluck('displayName', 'id');

        $paymentstatuses = Paymentstatus::pluck('displayName', 'id');

        $hasOrder = [
            0 => 'همه کاربران',
            1 => 'کسانی که سفارش ثبت کرده اند',
            2 => 'کسانی که سفارش ثبت نکرده اند',
        ];

        $products = $this->makeProductCollection();

        $lockProfileStatus        = [
            0 => 'پروفایل باز',
            1 => 'پروفایل قفل شده',
        ];
        $mobileNumberVerification = [
            0 => 'تایید نشده',
            1 => 'تایید شده',
        ];

        $tableDefaultColumns = [
            'نام خانوادگی',
            'نام کوچک',
            'رشته',
            'کد ملی',
            'موبایل',
            'ایمیل',
            'شهر',
            'استان',
            'وضعیت شماره موبایل',
            'کد پستی',
            'آدرس',
            'مدرسه',
            'وضعیت',
            'زمان ثبت نام',
            'زمان اصلاح',
            'نقش های کاربر',
            'تعداد بن',
            'عملیات',
        ];

        $sortBy               = [
            'updated_at' => 'تاریخ اصلاح',
            'created_at' => 'تاریخ ثبت نام',
            'firstName'  => 'نام',
            'lastName'   => 'نام خانوادگی',
        ];
        $sortType             = [
            'desc' => 'نزولی',
            'asc'  => 'صعودی',
        ];
        $addressSpecialFilter = [
            'بدون فیلتر خاص',
            'بدون آدرس ها',
            'آدرس دارها',
        ];

        $coupons = Coupon::pluck('name', 'id')
            ->toArray();
        $coupons = array_sort_recursive($coupons);

        $checkoutStatuses    = Checkoutstatus::pluck('displayName', 'id')
            ->toArray();
        $checkoutStatuses[0] = 'نامشخص';
        $checkoutStatuses    = array_sort_recursive($checkoutStatuses);

        $pageName = 'admin';

        return view('admin.index',
            compact('pageName', 'majors', 'userStatuses', 'permissions', 'roles', 'limitStatus', 'orderstatuses',
                'paymentstatuses', 'enableStatus', 'genders',
                'gendersWithUnknown', 'hasOrder', 'products', 'lockProfileStatus', 'mobileNumberVerification',
                'tableDefaultColumns', 'sortBy', 'sortType',
                'coupons', 'addressSpecialFilter', 'checkoutStatuses'));
    }

    /**
     * Show product admin panel page
     *
     * @return Response
     */
    public function adminProduct()
    {
        $attributecontrols = Attributecontrol::pluck('name', 'id')
            ->toArray();
        $enableStatus      = [
            0 => 'غیرفعال',
            1 => 'فعال',
        ];
        $attributesets     = Attributeset::pluck('name', 'id')
            ->toArray();
        $limitStatus       = [
            0 => 'نامحدود',
            1 => 'محدود',
        ];

        $products   = Product::pluck('name', 'id')
            ->toArray();
        $coupontype = Coupontype::pluck('displayName', 'id');

        $productTypes = Producttype::pluck('displayName', 'id');

        $lastProduct = Product::getProducts(0, 1)
            ->get()
            ->sortByDesc('order')
            ->first();
        if (isset($lastProduct)) {
            $lastOrderNumber     = $lastProduct->order + 1;
            $defaultProductOrder = $lastOrderNumber;
        } else {
            $defaultProductOrder = 1;
        }

        $pageName = 'admin';

        return view('admin.indexProduct',
            compact('pageName', 'attributecontrols', 'enableStatus', 'attributesets', 'limitStatus', 'products',
                'coupontype', 'productTypes',
                'defaultProductOrder'));
    }

    /**
     * Show order admin panel page
     *
     * @return Response
     */
    public function adminOrder()
    {
        $pageName = 'admin';
        $user     = Auth::user();
        if ($user->can(config('constants.SHOW_OPENBYADMIN_ORDER'))) {
            $orderstatuses = Orderstatus::whereNotIn('id', [config('constants.ORDER_STATUS_OPEN')])
                ->pluck('displayName', 'id');
        } else {
            $orderstatuses = Orderstatus::whereNotIn('id', [
                config('constants.ORDER_STATUS_OPEN'),
                config('constants.ORDER_STATUS_OPEN_BY_ADMIN'),
            ])
                ->pluck('displayName', 'id')
                ->toArray();
        }
        //        $orderstatuses= array_sort_recursive(array_add($orderstatuses , 0 , "دارای هر وضعیت سفارش")->toArray());

        $paymentstatuses     = Paymentstatus::pluck('displayName', 'id')
            ->toArray();
        $majors              = Major::pluck('name', 'id');
        $checkoutStatuses    = Checkoutstatus::pluck('displayName', 'id')
            ->toArray();
        $checkoutStatuses[0] = 'نامشخص';
        $checkoutStatuses    = array_sort_recursive($checkoutStatuses);

        $products = collect();
        if ($user->hasRole('onlineNoroozMarketing')) {
            $products = [config('constants.ORDOO_GHEIRE_HOZOORI_NOROOZ_97_PRODUCT_ROOT')];
            $products = $this->makeProductCollection($products);
        } else {
            $products = $this->makeProductCollection();
        }

        $paymentMethods = Paymentmethod::pluck('displayName', 'id')
            ->toArray();

        $attributevalueCollection = collect();
        $extraAttributes          = Attribute::whereHas('attributegroups', function ($q) {
            $q->where('attributetype_id', 2);
        })
            ->get();
        foreach ($extraAttributes as $attribute) {
            $values = [];
            $values = array_merge($values, $attribute->attributevalues->pluck('id', 'name')
                ->toArray());
            if (!empty($values)) {
                $attributevalueCollection->put($attribute->displayName, $values);
            }
        }

        $sortBy   = [
            'updated_at'    => 'تاریخ اصلاح مدیریتی',
            'completed_at'  => 'تاریخ ثبت نهایی',
            'created_at'    => 'تاریخ ثبت اولیه',
            'userFirstName' => 'نام مشتری',
            'userLastName'  => 'نام خانوادگی مشتری'
            /* , "productName" => "نام محصول"*/
        ];
        $sortType = [
            'desc' => 'نزولی',
            'asc'  => 'صعودی',
        ];

        $transactionTypes = [
            0 => 'واریز شده',
            1 => 'بازگشت داده شده',
        ];

        $coupons = Coupon::pluck('name', 'id')
            ->toArray();
        $coupons = array_sort_recursive($coupons);

        $transactionStatuses = Transactionstatus::orderBy('order')
            ->pluck('displayName', 'id')
            ->toArray();

        $userBonStatuses = Userbonstatus::pluck('displayName', 'id');

        $orderTableDefaultColumns       = [
            'محصولات',
            'نام خانوادگی',
            'نام کوچک',
            'رشته',
            'استان',
            'شهر',
            'آدرس',
            'کد پستی',
            'موبایل',
            'مبلغ(تومان)',
            'عملیات',
            'ایمیل',
            'پرداخت شده(تومان)',
            'مبلغ برگشتی(تومان)',
            'بدهکار/بستانکار(تومان)',
            'توضیحات مسئول',
            'کد مرسوله پستی',
            'توضیحات مشتری',
            'وضعیت سفارش',
            'وضعیت پرداخت',
            'کدهای تراکنش',
            'تاریخ اصلاح مدیریتی',
            'تاریخ ثبت نهایی',
            'ویژگی ها',
            'تعداد بن استفاده شده',
            'تعداد بن اضافه شده به شما از این سفارش',
            'کپن استفاده شده',
            'تاریخ ایجاد اولیه',
        ];
        $transactionTableDefaultColumns = [
            'نام مشتری',
            'تراکنش پدر',
            'موبایل',
            'مبلغ سفارش',
            'مبلغ تراکنش',
            'کد تراکنش',
            'نحوه پرداخت',
            'تاریخ ثبت',
            'عملیات',
            'توضیح مدیریتی',
            'مبلغ فیلتر شده',
            'مبلغ آیتم افزوده',
        ];
        $userBonTableDefaultColumns     = [
            'نام کاربر',
            'تعداد بن تخصیص داده شده',
            'وضعیت بن',
            'نام کالایی که از خرید آن بن دریافت کرده است',
            'تاریخ درج',
            'عملیات',
        ];
        $addressSpecialFilter           = [
            'بدون فیلتر خاص',
            'بدون آدرس ها',
            'آدرس دارها',
        ];

        $paymentGateways = Transactiongateway::enable()->get()->pluck('displayName' , 'id');

        return view('admin.indexOrder',
            compact('pageName', 'orderstatuses', 'products', 'paymentMethods', 'majors', 'paymentstatuses', 'sortBy',
                'sortType', 'transactionTypes',
                'orderTableDefaultColumns', 'coupons', 'transactionStatuses', 'transactionTableDefaultColumns',
                'userBonTableDefaultColumns', 'userBonStatuses',
                'attributevalueCollection', 'addressSpecialFilter', 'checkoutStatuses', 'paymentGateways'));
    }

    /**
     * Show content admin panel page
     *
     * @return Response
     */
    public function adminContent()
    {
        $majors             = Major::pluck('name', 'id');
        $assignmentStatuses = Assignmentstatus::pluck('name', 'id');
        $assignmentStatuses->prepend('انتخاب وضعیت');
        $consultationStatuses = Consultationstatus::pluck('name', 'id');
        $consultationStatuses->prepend('انتخاب وضعیت');

        $pageName = 'admin';

        return view('admin.indexContent', compact('pageName', 'assignmentStatuses', 'consultationStatuses', 'majors'));
    }

    /**
     * Show consultant admin panel page
     *
     * @return Response
     */
    public function consultantAdmin()
    {
        $questions              = Userupload::all()
            ->sortByDesc('created_at');
        $questionStatusDone     = Useruploadstatus::all()
            ->where('name', 'done')
            ->first();
        $questionStatusPending  = Useruploadstatus::all()
            ->where('name', 'pending')
            ->first();
        $newQuestionsCount      = Userupload::all()
            ->where('useruploadstatus_id', $questionStatusPending->id)
            ->count();
        $answeredQuestionsCount = Userupload::all()
            ->where('useruploadstatus_id', $questionStatusDone->id)
            ->count();
        $counter                = 0;

        $pageName = 'consultantAdmin';

        return view('admin.consultant.consultantAdmin',
            compact('questions', 'counter', 'pageName', 'newQuestionsCount', 'answeredQuestionsCount'));
    }

    /**
     * Show adminSMS panel main page
     *
     * @return Response
     */
    public function adminSMS()
    {
        $userStatuses       = Userstatus::pluck('name', 'id');
        $majors             = Major::pluck('name', 'id');
        $genders            = Gender::pluck('name', 'id');
        $gendersWithUnknown = clone $genders;
        $gendersWithUnknown->prepend('نامشخص');
        $roles = Role::pluck('display_name', 'id');

        $orderstatuses = Orderstatus::whereNotIn('name', ['open'])
            ->pluck('displayName', 'id');

        $paymentstatuses = Paymentstatus::pluck('displayName', 'id');

        $products = $this->makeProductCollection();

        $lockProfileStatus        = [
            0 => 'پروفایل باز',
            1 => 'پروفایل قفل شده',
        ];
        $mobileNumberVerification = [
            0 => 'تایید نشده',
            1 => 'تایید شده',
        ];

        $relatives = ['فرد'];
//        $relatives = Relative::pluck('displayName', 'id');
//        $relatives->prepend('فرد');

        $sortBy               = [
            'updated_at' => 'تاریخ اصلاح',
            'created_at' => 'تاریخ ثبت نام',
            'firstName'  => 'نام',
            'lastName'   => 'نام خانوادگی',
        ];
        $sortType             = [
            'desc' => 'نزولی',
            'asc'  => 'صعودی',
        ];
        $addressSpecialFilter = [
            'بدون فیلتر خاص',
            'بدون آدرس ها',
            'آدرس دارها',
        ];

        $checkoutStatuses    = Checkoutstatus::pluck('displayName', 'id')
            ->toArray();
        $checkoutStatuses[0] = 'نامشخص';
        $checkoutStatuses    = array_sort_recursive($checkoutStatuses);

        $pageName = 'admin';

        $smsCredit = (int) $this->medianaGetCredit();

        $smsProviderNumber = config('constants.SMS_PROVIDER_NUMBER');

        $coupons = Coupon::pluck('name', 'id')
            ->toArray();
        $coupons = array_sort_recursive($coupons);

        return view('admin.indexSMS',
            compact('pageName', 'majors', 'userStatuses', 'roles', 'relatives', 'orderstatuses', 'paymentstatuses',
                'genders', 'gendersWithUnknown', 'products',
                'allRootProducts', 'lockProfileStatus', 'mobileNumberVerification', 'sortBy', 'sortType', 'smsCredit',
                'smsProviderNumber',
                'numberOfFatherPhones', 'numberOfMotherPhones', 'coupons', 'addressSpecialFilter', 'heckoutStatuses',
                'checkoutStatuses'));
    }

    /**
     * Admin panel for adjusting site configuration
     */
    public function adminSlideShow(Request $request , SlideShowController $slideShowController)
    {

        $slides             = $slideShowController->index();
        $slideDisk          = 9;
        $section            = 'slideShow';

        $websitePages = WebsitePageRepo::getWebsitePages(
            ['url' => [
                '/home',
                '/shop',
            ]]
        )->pluck('displayName' , 'id');


        return view('admin.siteConfiguration.slideShow',
            compact('slides', 'section', 'slideDisk', 'websitePages'));
    }

    /**
     * Admin panel for adjusting site configuration
     */
    public function adminArticleSlideShow()
    {

        $slideController    = new SlideShowController();
        $slideWebsitepageId = $websitePageId = Websitepage::all()
            ->where('url', '/لیست-مقالات')
            ->first()->id;
        $slides             = $slideController->index()
            ->where('websitepage_id', $slideWebsitepageId);
        $slideDisk          = 13;
        $slideContentName   = 'عکس اسلاید صفحه مقالات';
        $sideBarMode        = 'closed';
        $section            = 'articleSlideShow';

        return view('admin.siteConfiguration.articleSlideShow',
            compact('slides', 'sideBarMode', 'slideWebsitepageId', 'section', 'slideDisk', 'slideContentName'));
    }

    /**
     * Admin panel for adjusting site configuration
     */
    public function adminSiteConfig()
    {
        $this->setting = Websitesetting::where('version', 1)
            ->get()
            ->first();

        return redirect(action('Web\WebsiteSettingController@show', $this->setting));
    }

    /**
     * Admin panel for adjusting site configuration
     */
    public function adminMajor()
    {
        $parentName  = Input::get('parent');
        $parentMajor = Major::all()
            ->where('name', $parentName)
            ->where('majortype_id', 1)
            ->first();

        $majors = Major::where('majortype_id', 2)
            ->orderBy('name')
            ->whereHas('parents', function ($q) use ($parentMajor) {
                $q->where('major1_id', $parentMajor->id);
            })
            ->get();

        return view('admin.indexMajor', compact('parentMajor', 'majors'));
    }

    /**
     * Admin panel for getting a special report
     */
    public function adminReport()
    {
        $userStatuses       = Userstatus::pluck('displayName', 'id');
        $majors             = Major::pluck('name', 'id');
        $genders            = Gender::pluck('name', 'id');
        $gendersWithUnknown = clone $genders;
        $gendersWithUnknown->prepend('نامشخص');
        $permissions = Permission::pluck('display_name', 'id');
        $roles       = Role::pluck('display_name', 'id');
        //        $roles = array_add($roles , 0 , "همه نقش ها");
        //        $roles = array_sort_recursive($roles);
        $limitStatus  = [
            0 => 'نامحدود',
            1 => 'محدود',
        ];
        $enableStatus = [
            0 => 'غیرفعال',
            1 => 'فعال',
        ];

        $orderstatuses = Orderstatus::whereNotIn('id', [config('constants.ORDER_STATUS_OPEN')])
            ->pluck('displayName', 'id');

        $paymentstatuses = Paymentstatus::pluck('displayName', 'id');

        $hasOrder = [
            0 => 'همه کاربران',
            1 => 'کسانی که سفارش ثبت کرده اند',
            2 => 'کسانی که سفارش ثبت نکرده اند',
        ];

        $bookProductsId = [
            176,
            167,
        ];
        $bookProducts   = $this->makeProductCollection($bookProductsId);

        $products = $this->makeProductCollection();

        $lockProfileStatus        = [
            0 => 'پروفایل باز',
            1 => 'پروفایل قفل شده',
        ];
        $mobileNumberVerification = [
            0 => 'تایید نشده',
            1 => 'تایید شده',
        ];

        //        $tableDefaultColumns = ["نام" , "رشته"  , "موبایل"  ,"شهر" , "استان" , "وضعیت شماره موبایل" , "کد پستی" , "آدرس" , "مدرسه" , "وضعیت" , "زمان ثبت نام" , "زمان اصلاح" , "نقش های کاربر" , "تعداد بن" , "عملیات"];

        $sortBy               = [
            'updated_at' => 'تاریخ اصلاح',
            'created_at' => 'تاریخ ثبت نام',
            'firstName'  => 'نام',
            'lastName'   => 'نام خانوادگی',
        ];
        $sortType             = [
            'desc' => 'نزولی',
            'asc'  => 'صعودی',
        ];
        $addressSpecialFilter = [
            'بدون فیلتر خاص',
            'بدون آدرس ها',
            'آدرس دارها',
        ];

        $coupons = Coupon::pluck('name', 'id')
            ->toArray();
        $coupons = array_sort_recursive($coupons);

        $lotteries = Lottery::pluck('displayName', 'id')
            ->toArray();

        $pageName = 'admin';

        $checkoutStatuses    = Checkoutstatus::pluck('displayName', 'id')
            ->toArray();
        $checkoutStatuses[0] = 'نامشخص';
        $checkoutStatuses    = array_sort_recursive($checkoutStatuses);

        return view('admin.indexGetReport',
            compact('pageName', 'majors', 'userStatuses', 'permissions', 'roles', 'limitStatus', 'orderstatuses',
                'paymentstatuses', 'enableStatus', 'genders',
                'gendersWithUnknown', 'hasOrder', 'products', 'bookProducts', 'lockProfileStatus',
                'mobileNumberVerification', 'sortBy', 'sortType', 'coupons',
                'addressSpecialFilter', 'lotteries', 'checkoutStatuses'));
    }

    /**
     * Admin panel for lotteries
     */
    public function adminLottery(\App\Http\Requests\Request $request)
    {
        $userlotteries = collect();
        if ($request->has('lottery')) {
            $lotteryName        = $request->get('lottery');
            $lottery            = Lottery::where('name', $lotteryName)
                ->get()
                ->first();
            $lotteryDisplayName = $lottery->displayName;
            $userlotteries      = $lottery->users->where('pivot.rank', '>', 0)
                ->sortBy('pivot.rank');
        }

        $bonName     = config('constants.BON2');
        $bon         = Bon::where('name', $bonName)
            ->first();
        $pointsGiven = Userbon::where('bon_id', $bon->id)
            ->where('userbonstatus_id', 1)
            ->get()
            ->isNotEmpty();

        $pageName = 'admin';

        return view('admin.indexLottery',
            compact('userlotteries', 'pageName', 'lotteryName', 'lotteryDisplayName', 'pointsGiven'));
    }

    /**
     * Admin panel for tele marketing
     */
    public function adminTeleMarketing(Request $request)
    {
        if ($request->has('group-mobile')) {
            $marketingProducts = [
                210,
                211,
                212,
                213,
                214,
                215,
                216,
                217,
                218,
                219,
                220,
                221,
                222,
            ];
            $mobiles           = $request->get('group-mobile');
            $mobileArray       = [];
            foreach ($mobiles as $mobile) {
                $mobileArray[] = $mobile['mobile'];
            }
            $baseDataTime = Carbon::createFromTimeString('2018-05-03 00:00:00');
            $orders       = Order::whereHas('user', function ($q) use ($mobileArray, $baseDataTime) {
                $q->whereIn('mobile', $mobileArray);
            })
                ->whereHas('orderproducts', function ($q2) use ($marketingProducts) {
                    $q2->whereIn('product_id', $marketingProducts);
                })
                ->where('orderstatus_id', config('constants.ORDER_STATUS_CLOSED'))
                ->where('paymentstatus_id',
                    config('constants.PAYMENT_STATUS_PAID'))
                ->where('completed_at', '>=', $baseDataTime)
                ->get();
            $orders->load('orderproducts');
        }

        return view('admin.indexTeleMarketing', compact('orders', 'marketingProducts'));
    }

    /**
     * Temporary method for generating special couopns
     *
     * @param \App\Http\Requests\Request $request
     *
     * @return Factory|View
     */
    public function adminGenerateRandomCoupon(Request $request)
    {
        $productCollection = $products = $this->makeProductCollection();

        return view('admin.generateSpecialCoupon', compact('productCollection'));
    }

    public function registerUserAndGiveOrderproduct(\App\Http\Requests\Request $request)
    {
        try {
            $mobile       = $request->get('mobile');
            $nationalCode = $request->get('nationalCode');
            $firstName    = $request->get('firstName');
            $lastName     = $request->get('lastName');
            $major_id     = $request->get('major_id');
            $gender_id    = $request->get('gender_id');
            $user         = User::where('mobile', $mobile)
                ->where('nationalCode', $nationalCode)
                ->first();
            if (isset($user)) {
                $flag = false;
                if (!isset($user->firstName) && isset($firstName)) {
                    $user->firstName = $firstName;
                    $flag            = true;
                }
                if (!isset($user->lastName) && isset($lastName)) {
                    $user->lastName = $lastName;
                    $flag           = true;
                }
                if (!isset($user->major_id) && isset($major_id)) {
                    $user->major_id = $major_id;
                    $flag           = true;
                }
                if (!isset($user->gender_id) && isset($gender_id)) {
                    $user->gender_id = $gender_id;
                    $flag            = true;
                }

                if ($flag) {
                    $user->update();
                }
            } else {
                $registerRequest = new InsertUserRequest();
                $registerRequest->offsetSet('mobile', $mobile);
                $registerRequest->offsetSet('nationalCode', $nationalCode);
                $registerRequest->offsetSet('firstName', $firstName);
                $registerRequest->offsetSet('lastName', $lastName);
                $registerRequest->offsetSet('password', $nationalCode);
                //                $registerRequest->offsetSet("mobileNumberVerification" , 1);
                $registerRequest->offsetSet('major_id', $major_id);
                $registerRequest->offsetSet('gender_id', $gender_id);
                $registerRequest->offsetSet('userstatus_id', 1);
                $userController = new \App\Http\Controllers\UserController();
                $response       = $userController->store($registerRequest);
                $result         = json_decode($response->getContent());
                if ($response->getStatusCode() == 200) {
                    $userId = $result->userId;
                    if ($userId > 0) {
                        $user = User::where('id', $userId)
                            ->first();
                        $user->notify(new UserRegisterd());
                    }
                }
            }

            if (isset($user)) {
                $orderProductIds = [];

                $arabiProduct  = 214;
                $hasArabiOrder = $user->orderproducts()
                    ->where('product_id', $arabiProduct)
                    ->whereHas('order', function ($q) {
                        $q->where('orderstatus_id', config('constants.ORDER_STATUS_CLOSED'));
                        $q->where('paymentstatus_id', config('constants.PAYMENT_STATUS_PAID'));
                    })
                    ->get();
                if ($hasArabiOrder->isEmpty()) {
                    array_push($orderProductIds, $arabiProduct);
                }

                $shimiProduct  = 100;
                $hasShimiOrder = $user->orderproducts()
                    ->where('product_id', $shimiProduct)
                    ->whereHas('order', function ($q) {
                        $q->where('orderstatus_id', config('constants.ORDER_STATUS_CLOSED'));
                        $q->where('paymentstatus_id', config('constants.PAYMENT_STATUS_PAID'));
                    })
                    ->get();

                if ($hasShimiOrder->isEmpty()) {
                    array_push($orderProductIds, $shimiProduct);
                }

                $giftOrderDone = true;
                if (!empty($orderProductIds)) {
                    $orderController   = new OrderController();
                    $storeOrderRequest = new Request();
                    $storeOrderRequest->offsetSet('orderstatus_id', config('constants.ORDER_STATUS_CLOSED'));
                    $storeOrderRequest->offsetSet('paymentstatus_id', config('constants.PAYMENT_STATUS_PAID'));
                    $storeOrderRequest->offsetSet('cost', 0);
                    $storeOrderRequest->offsetSet('costwithoutcoupon', 0);
                    $storeOrderRequest->offsetSet('user_id', $user->id);
                    $giftOrderCompletedAt = Carbon::now()
                        ->setTimezone('Asia/Tehran');
                    $storeOrderRequest->offsetSet('completed_at', $giftOrderCompletedAt);
                    $giftOrder = $orderController->store($storeOrderRequest);

                    $giftOrderMessage = 'ثبت سفارش با موفیت انجام شد';
                    if ($giftOrder !== false) {
                        foreach ($orderProductIds as $productId) {
                            $request->offsetSet('cost', 0);
                            $request->offsetSet('orderId_bhrk', $giftOrder->id);
                            $request->offsetSet('userId_bhrk', $user->id);
                            $product = Product::where('id', $productId)
                                ->first();
                            if (isset($product)) {
                                $response       = $orderController->addOrderproduct($request, $product);
                                $responseStatus = $response->getStatusCode();
                                $result         = json_decode($response->getContent());
                                if ($responseStatus == 200) {

                                } else {
                                    $giftOrderDone    = false;
                                    $giftOrderMessage = 'خطا در ثبت آیتم سفارش';
                                    foreach ($result as $value) {
                                        $giftOrderMessage .= '<br>';
                                        $giftOrderMessage .= $value;
                                    }
                                }
                            } else {
                                $giftOrderDone    = false;
                                $giftOrderMessage = 'خطا در ثبت آیتم سفارش. محصول یافت نشد.';
                            }
                        }
                    } else {
                        $giftOrderDone    = false;
                        $giftOrderMessage = 'خطا در ثبت سفارش';
                    }
                } else {
                    $giftOrderMessage = 'کاربر مورد نظر محصولات را از قبل داشت';
                }
            } else {
                $giftOrderMessage = 'خطا در یافتن کاربر';
            }

            if ($giftOrderDone) {
                if (isset($user->gender_id)) {
                    if ($user->gender->name == 'خانم') {
                        $gender = 'خانم ';
                    } else {
                        if ($user->gender->name == 'آقا') {
                            $gender = 'آقای ';
                        } else {
                            $gender = '';
                        }
                    }
                } else {
                    $gender = '';
                }
                $message = $gender.$user->full_name."\n";
                $message .= 'همایش طلایی عربی و همایش حل مسائل شیمی به فایل های شما افزوده شد . دانلود در:';
                $message .= "\n";
                $message .= 'sanatisharif.ir/asset/';
                $user->notify(new GeneralNotice($message));
                session()->put('success', $giftOrderMessage);
            } else {
                session()->put('error', $giftOrderMessage);
            }

            if ($request->expectsJson()) {
                if ($giftOrderDone) {
                    return $this->response->setStatusCode(200);
                } else {
                    return $this->response->setStatusCode(503);
                }
            } else {
                return redirect()->back();
            }
        } catch (Exception    $e) {
            $message = 'unexpected error';

            return $this->response->setStatusCode(500)
                ->setContent([
                    'message' => $message,
                    'error'   => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                ]);
        }
    }

    public function specialAddUser(\App\Http\Requests\Request $request)
    {
        $majors   = Major::pluck('name', 'id');
        $genders  = Gender::pluck('name', 'id');
        $pageName = 'admin';

        return view('admin.insertUserAndOrderproduct', compact('majors', 'genders', 'pageName'));
    }

    public function adminBlock(Request $request)
    {
        $pageName = 'indexBlock';
        return view('admin.indexBlock', compact(['pageName']));
    }

}
