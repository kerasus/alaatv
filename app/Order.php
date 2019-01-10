<?php

namespace App;

use App\Classes\Checkout\Alaa\OrderCheckout;
use App\Classes\Checkout\Alaa\ReObtainOrderFromRecords;
use App\Classes\Pricing\Alaa\AlaaInvoiceGenerator;
use App\Collection\OrderCollections;
use App\Collection\ProductCollection;
use App\Traits\DateTrait;
use App\Traits\Helper;
use App\Traits\ProductCommon;
use Auth;
use Carbon\Carbon;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

/**
 * App\Order
 *
 * @property int                                                                      $id
 * @property int|null                                                                 $user_id              آیدی مشخص
 *           کننده کاربر سفارش دهنده
 * @property int|null                                                                 $orderstatus_id       آیدی مشخص
 *           کننده وضعیت سفارش
 * @property int|null                                                                 $paymentstatus_id     آیدی مشخص
 *           کننده وضعیت پرداخت سفارش
 * @property int|null                                                                 $coupon_id            آیدی مشخص
 *           کننده کپن استفاده شده برای سفارش
 * @property float                                                                    $couponDiscount       میزان تخفیف
 *           کپن برای سفارش به درصد
 * @property int                                                                      $couponDiscountAmount میزان تخفیف
 *           کپن(به تومان)
 * @property int|null                                                                 $cost                 مبلغ قابل
 *           پرداخت توسط کاربر
 * @property int|null                                                                 $costwithoutcoupon    بخشی از
 *           قیمت که مشمول کپن تخفیف نمی شود
 * @property int                                                                      $discount             تخفیف خاص
 *           برای این سفارش به تومان
 * @property string|null                                                              $customerDescription  توضیحات
 *           مشتری درباره سفارش
 * @property string|null                                                              $customerExtraInfo    اطلاعات
 *           تکمیلی مشتری برای این سفارش
 * @property string|null                                                              $checkOutDateTime     تاریخ تسویه
 *           حساب کامل
 * @property \Carbon\Carbon|null                                                      $created_at
 * @property \Carbon\Carbon|null                                                      $updated_at
 * @property string|null                                                              $completed_at         مشخص کننده
 *           زمان تکمیل سفارش کاربر
 * @property \Carbon\Carbon|null                                                      $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $archivedSuccessfulTransactions
 * @property-read \App\Coupon|null                                                    $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Orderfile[]           $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Orderproduct[]        $normalOrderproducts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $onlinetransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ordermanagercomment[] $ordermanagercomments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Orderpostinginfo[]    $orderpostinginfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Orderproduct[]        $orderproducts
 * @property-read \App\Orderstatus|null                                               $orderstatus
 * @property-read \App\Paymentstatus|null                                             $paymentstatus
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $pendingTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $successfulTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $suspendedTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[]         $unpaidTransactions
 * @property-read \App\User|null                                                      $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Order onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCheckOutDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCostwithoutcoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCouponDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCouponDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderstatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePaymentstatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Order withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @property-read array|bool $coupon_discount_type
 * @property-read mixed $number_of_products
 */
class Order extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Traits methods
    |--------------------------------------------------------------------------
    */

    use SoftDeletes, CascadeSoftDeletes;
    use Helper;
    use DateTrait;
    use ProductCommon;

    /*
    |--------------------------------------------------------------------------
    | Properties methods
    |--------------------------------------------------------------------------
    */

    protected $cascadeDeletes = [
        'transactions',
        'files',
    ];

    /** The attributes that should be mutated to dates */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'orderstatus_id',
        'paymentstatus_id',
        'coupon_id',
        'couponDiscount',
        'couponDiscountAmount',
        'cost',
        'costwithoutcoupon',
        'discount',
        'customerDescription',
        'customerExtraInfo',
        'checkOutDateTime',
        'completed_at',
    ];

    protected $appends = [
        "invoice"
    ];

    const  OPEN_ORDER_STATUSES = [
        1,
        4,
        8,
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new OrderCollections($models);
    }

    public static function orderStatusFilter($orders, $orderStatusesId)
    {
        return $orders->whereIn('orderstatus_id', $orderStatusesId);
    }

    public static function UserMajorFilter($orders, $majorsId)
    {
        if (in_array(0, $majorsId))
            $orders = $orders->whereHas('user', function ($q) use ($majorsId) {
                $q->whereDoesntHave("major");
            });
        else
            $orders = $orders->whereHas('user', function ($q) use ($majorsId) {
                $q->whereIn("major_id", $majorsId);
            });
        return $orders;
    }

    public static function paymentStatusFilter($orders, $paymentStatusesId)
    {
        return $orders->whereIn('paymentstatus_id', $paymentStatusesId);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderstatus()
    {
        return $this->belongsTo('App\Orderstatus');
    }

    public function paymentstatus()
    {
        return $this->belongsTo('App\Paymentstatus');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Coupon');
    }

    public function onlinetransactions()
    {
        return $this->hasMany('App\Transaction')
                    ->where('paymentmethod_id', 1);
    }

    public function successfulTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where(function ($q) {
                $q->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_SUCCESSFUL"))
                    ->orWhere("transactionstatus_id", config("constants.TRANSACTION_STATUS_SUSPENDED"));
            });
    }

    public function pendingTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_PENDING"));
    }

    public function unpaidTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_UNPAID"));
    }

    public function suspendedTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_SUSPENDED"));
    }

    public function archivedSuccessfulTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_ARCHIVED_SUCCESSFUL"));
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function files()
    {
        return $this->hasMany('\App\Orderfile');
    }

    public function orderpostinginfos()
    {
        return $this->hasMany('\App\Orderpostinginfo');
    }

    public function normalOrderproducts()
    {
        return $this->hasMany('App\Orderproduct')
                    ->where(function ($q) {
                        $q->whereNull("orderproducttype_id")
                            ->orWhere("orderproducttype_id", config("constants.ORDER_PRODUCT_TYPE_DEFAULT"));
                    });

    }

    public function debt()
    {
        $cost = $this->obtainOrderCost()["totalCost"];
        if ( $this->orderstatus_id == config("constants.ORDER_STATUS_REFUNDED"))
            return -($this->totalPaidCost() + $this->totalRefund());
        else
            return $cost - ($this->totalPaidCost() + $this->totalRefund());
    }

    /**
     * Obtain order total cost
     *
     * @param boolean $calculateOrderCost
     * @param boolean $calculateOrderproductCost
     *
     * @return array
     */
    public function obtainOrderCost($calculateOrderCost = false, $calculateOrderproductCost = true, $mode = "DEFAULT")
    {
        if($calculateOrderCost) {
            $orderproductsToCalculateFromBaseIds = [];
            if($calculateOrderproductCost)
            {
                $orderproductsToCalculateFromBaseIds = $this->normalOrderproducts->pluck("id")->toArray();
            }

            $reCheckIncludedOrderproductsInCoupon = false;
            if($this->hasCoupon())
                $reCheckIncludedOrderproductsInCoupon = ($mode=="REOBTAIN")?false:true;
            $alaaCashierFacade = new OrderCheckout($this , $orderproductsToCalculateFromBaseIds , $reCheckIncludedOrderproductsInCoupon );
        }
        else{
            $alaaCashierFacade = new ReObtainOrderFromRecords($this);
        }

        $priceInfo = $alaaCashierFacade->checkout();

        return [
            "sumOfOrderproductsRawCost" => $priceInfo["totalPriceInfo"]["sumOfOrderproductsRawCost"],
            "rawCostWithDiscount"       => $priceInfo["totalPriceInfo"]["totalRawPriceWhichHasDiscount"],
            'rawCostWithoutDiscount'    =>  $priceInfo["totalPriceInfo"]["totalRawPriceWhichDoesntHaveDiscount"],
            "totalCost"                 => $priceInfo["totalPriceInfo"]["finalPrice"],
            "priceToPay"                => $priceInfo["totalPriceInfo"]["priceToPay"],
            "amountPaidByWallet"        => $priceInfo["totalPriceInfo"]["amountPaidByWallet"],
            "calculatedOrderproducts"   => $priceInfo["orderproductsInfo"]["calculatedOrderproducts"],
        ];
    }

    /**
     * Determines this order's coupon discount type
     * Note: In case it has any coupons returns false
     *
     * @return array|bool
     */
    public function getCouponDiscountTypeAttribute()
    {
        if ($this->hasCoupon()) {
            if ($this->couponDiscount > 0) {
                return [
                    "type"     => config("constants.DISCOUNT_TYPE_PERCENTAGE"),
                    "discount" => $this->couponDiscount,
                ];
            } else {
                return [
                    "type"     => config("constants.DISCOUNT_TYPE_COST"),
                    "discount" => $this->couponDiscountAmount,
                ];
            }
        }
        return
            false;
    }

    /**
     * Determines whether order has coupon or not
     * @return bool
     */
    public function hasCoupon()
    {
        if (isset($this->coupon->id))
            return true;
        else
            return false;
    }

    /**
     * Determines whether the coupon is usable for this order or not
     *
     * @return bool
     */
    public function hasProductsThatUseItsCoupon()
    {
        $flag = false;
        if (isset($this->coupon->id)) {
            if ($this->coupon->coupontype->id == config("constants.COUPON_TYPE_PARTIAL")) {
                foreach ($this->orderproducts(config("constants.ORDER_PRODUCT_TYPE_DEFAULT"))->get() as $orderproduct) {
                    $hasCoupon = true;
                    if (!in_array($this->coupon->id, $orderproduct->product->coupons->pluck('id')->toArray())) {
                        $hasCoupon = false;
                        $parentsArray = $this->makeParentArray($orderproduct->product);
                        foreach ($parentsArray as $parent) {
                            if (in_array($this->coupon->id, $parent->coupons->pluck('id')->toArray())) {
                                $hasCoupon = true;
                                break;
                            }
                        }
                    }

                    if ($hasCoupon) {
                        $flag = true;
                        break;
                    }
                }
            } else {
                $flag = true;
            }
        }

        return $flag;
    }

    /**
     * Determines if this order has given products
     *
     * @param array $products
     * @return array
     */
    public function hasTheseProducts(array $products)
    {
        return $this->orderproducts
            ->whereIn("product_id", $products)
            ->isNotEmpty();
    }

    /**
     * Calculates the discount amount of totalCost relevant to this order's coupon
     * @param int $totalCost
     * @return float|int|mixed
     */
    public function obtainCouponDiscount(int $totalCost = 0)
    {
        $couponType = $this->coupon_discount_type;
        if ($couponType !== false) {
            if ($couponType["type"] == config("constants.DISCOUNT_TYPE_PERCENTAGE"))
                $totalCost = ((1 - ($couponType["discount"] / 100)) * $totalCost);
            else if ($couponType["type"] == config("constants.DISCOUNT_TYPE_COST"))
                $totalCost = $totalCost - $couponType["discount"];
        }
        return $totalCost;
    }

    public function determineCoupontype()
    {
        if ($this->hasCoupon()) {
            if ($this->couponDiscount > 0) {
                return [
                    "type"     => config("constants.DISCOUNT_TYPE_PERCENTAGE"),
                    "discount" => $this->couponDiscount,
                ];
            } else {
                return [
                    "type"     => config("constants.DISCOUNT_TYPE_COST"),
                    "discount" => $this->couponDiscountAmount,
                ];
            }
        }
        return false;
    }

    public function totalPaidCost()
    {
        if ($this->transactions->isEmpty())
            return 0;
        else return $this->successfulTransactions->where('cost', '>', 0)
                                                 ->sum("cost");
    }

    public function totalRefund()
    {
        if ($this->transactions->isEmpty())
            return 0;
        else return $this->successfulTransactions->where('cost', '<', 0)
                                                 ->sum("cost");
    }


    public function CompletedAt_Jalali()
    {
        $explodedDateTime = explode(" ", $this->completed_at);
        //        $explodedTime = $explodedDateTime[1] ;
        return $this->convertDate($this->completed_at, "toJalali");
    }

    public function ordermanagercomments()
    {
        return $this->hasMany('App\Ordermanagercomment');
    }

    public function usedBonSum()
    {
        $bonSum = 0;
        if (isset($this->orderproducts))
            foreach ($this->orderproducts as $orderproduct) {
                $bonSum += $orderproduct->userbons->sum("pivot.usageNumber");
            }
        return $bonSum;
    }

    public function addedBonSum($intendedUser = null)
    {
        $bonSum = 0;
        if (isset($intendedUser)) {
            $user = $intendedUser;
        } else if (Auth::check()) {
            $user = Auth::user();
        }

        if (isset($user)) {
            foreach ($this->orderproducts as $orderproduct) {
                if (!$user->userbons->where("orderproduct_id", $orderproduct->id)
                                    ->isEmpty())
                    $bonSum += $user->userbons->where("orderproduct_id", $orderproduct->id)
                                              ->sum("totalNumber");
            }
        }
        return $bonSum;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function reviewCouponProducts(){
        $orderproducts = $this->orderproducts->whereType([config("constants.ORDER_PRODUCT_TYPE_DEFAULT")]);

        $coupon = $this->coupon;
        $notIncludedProducts = new  ProductCollection();
        if(isset($coupon))
            foreach ($orderproducts->products as $product)
            {
                if(!$coupon->hasProduct($product))
                    $notIncludedProducts->push($product);
            }

        return $notIncludedProducts;
    }

    public function totalCost()
    {
        return $this->obtainOrderCost()["totalCost"];
    }


    public function getNumberOfProductsAttribute()
    {
        return $this->orderproducts->count();
    }

    public function orderproducts($type = null , $filters = [])
    {
        if (isset($type))
            if ($type == config("constants.ORDER_PRODUCT_TYPE_DEFAULT")) {
                $relation =  $this->hasMany('App\Orderproduct')
                            ->where(function ($q) use ($type) {
                                $q->where("orderproducttype_id", $type)
                                  ->orWhereNull("orderproducttype_id");
                            });
            } else {
                $relation =  $this->hasMany('App\Orderproduct')
                            ->where("orderproducttype_id", $type);
            }
        else
            $relation =  $this->hasMany('App\Orderproduct');

        foreach ($filters as $filter)
        {
            if(isset($filter["isArray"]))
                $relation->whereIn($filter["attribute"] , $filter["value"]);
            else
                $relation->where($filter["attribute"] , $filter["value"]);
        }

        return $relation;
    }

    /**
     * Gets this order's products
     *
     * @param array $orderproductTypes
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function products(array $orderproductTypes=[]){
        $result = DB::table('products')
            ->join('orderproducts', function ($join) use ($orderproductTypes) {
                if(empty($orderproductTypes))
                    $join->on('products.id', '=', 'orderproducts.product_id')
                        ->whereNull('orderproducts.deleted_at');
                else
                    $join->on('products.id', '=', 'orderproducts.product_id')
                        ->whereNull('orderproducts.deleted_at')
                        ->whereIn("orderproducttype_id" , $orderproductTypes );
            })
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'orderproducts.order_id')
                    ->whereNull('orders.deleted_at');
            })
            ->select([
                "products.*",
            ])
            ->where('orders.id', '=', $this->getKey())
            ->whereNull('products.deleted_at')
            ->distinct()
            ->get();
        $result = Product::hydrate($result->toArray());

        return $result;
    }

    /**
     * Detaches coupon from this order
     *
     * @return bool
     */
    public function detachCoupon():bool
    {
        $done = false;
        if (isset($this->coupon)) {
            $coupon = $this->coupon;
            $coupon->usageNumber--;
            if ($coupon->update())
            {
                $this->coupon_id = null;
                $this->couponDiscount = 0;
                $this->couponDiscountAmount = 0;
                $this->timestamps = false;
                if($this->update())
                {
                    $done = true;
                }
                else{
                    $coupon->usageNumber++;
                    $coupon->update();
                }
                $this->timestamps = true;
            }
        }
        return $done;
    }

    public function cancelOpenOnlineTransactions()
    {
        $openOnlineTransactions = $this->onlinetransactions->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_TRANSFERRED_TO_PAY"));
        if ($openOnlineTransactions->isNotEmpty()) {
            foreach ($openOnlineTransactions as $openOnlineTransaction) {
                $openOnlineTransaction->transactionstatus_id = config("constants.TRANSACTION_STATUS_UNSUCCESSFUL");
                $openOnlineTransaction->update();
            }
        }
    }

    /**
     * Recalculates order's cost and updates it's cost
     *
     * @return array
     */
    public function refreshCost()
    {
        $orderCost = $this->obtainOrderCost(true);
        $calculatedOrderproducts = $orderCost["calculatedOrderproducts"];
        $calculatedOrderproducts->updateCostValues();

        $calculatedOrderproducts = $orderCost["calculatedOrderproducts"];
        foreach ($calculatedOrderproducts as $orderproduct)
        {
            $newPriceInfo = $orderproduct->newPriceInfo ;
            $orderproduct->fillCostValues($newPriceInfo);
            $this->timestamps = false;
            $orderproduct->update();
            $this->timestamps = true;
        }

        $this->cost = $orderCost["rawCostWithDiscount"];
        $this->costwithoutcoupon = $orderCost["rawCostWithoutDiscount"];
        $this->timestamps = false;
        $this->update();
        $this->timestamps = true;
        return ["newCost" => $orderCost];
    }

    /**
     * Gives order bons to user
     *
     * @param string $bonName
     *
     * @return array
     */
    public function giveUserBons($bonName)
    {
        $totalSuccessfulBons = 0;
        $totalFailedBons = 0;
        $checkedProducts = [];
        $user = $this->user;

        $orderproducts = $this->orderproducts(config("constants.ORDER_PRODUCT_TYPE_DEFAULT"))
                              ->get();
        foreach ($orderproducts as $orderproduct) {
            if (!isset($user))
                break;
            if ($user->userbons->where("orderproduct_id", $orderproduct->id)
                               ->isNotEmpty())
                continue;
            $simpleProduct = $orderproduct->product;
            $bons = $simpleProduct->bons->where("name", $bonName);
            if ($bons->isEmpty()) {
                $grandParent = $simpleProduct->getGrandParent();
                if ($grandParent !== false) {
                    $simpleProduct = $grandParent;
                    $bons = $grandParent->bons->where("name", $bonName)
                                              ->where("isEnable", 1);
                }
            }
            if (in_array($simpleProduct->id, $checkedProducts))
                continue;
            if ($bons->isNotEmpty()) {
                $bon = $bons->first();
                $bonPlus = $bon->pivot->bonPlus;
                if ($bonPlus) {
                    $userbon = new Userbon();
                    $userbon->user_id = $user->id;
                    $userbon->bon_id = $bon->id;
                    $userbon->totalNumber = $bon->pivot->bonPlus;
                    $userbon->userbonstatus_id = config("constants.USERBON_STATUS_ACTIVE");
                    $userbon->orderproduct_id = $orderproduct->id;
                    if ($userbon->save())
                        $totalSuccessfulBons += $userbon->totalNumber;
                    else
                        $totalFailedBons += $bon->pivot->bonPlus;
                }
            }
        }

        return [
            $totalSuccessfulBons,
            $totalFailedBons,
        ];
    }

    /**
     * Closes this order
     *
     * @param string $paymentStatus
     *
     * @param int $orderStatus
     * @return void
     */
    public function close($paymentStatus, int $orderStatus = null)
    {
        if (!isset($orderStatus))
            $orderStatus = config("constants.ORDER_STATUS_CLOSED");

        $this->orderstatus_id = $orderStatus;
        $this->paymentstatus_id = $paymentStatus;
        $this->completed_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())
                                    ->timezone('Asia/Tehran');

    }


    public function closeWalletPendingTransactions()
    {
        $walletTransactions = $this->suspendedTransactions
            ->where("paymentmethod_id", config("constants.PAYMENT_METHOD_WALLET"));
        foreach ($walletTransactions as $transaction) {
            $transaction->transactionstatus_id = config("constants.TRANSACTION_STATUS_SUCCESSFUL");
            $transaction->update();
        }
    }

    public function getInvoiceAttribute()
    {
        $invoiceGenerator = new AlaaInvoiceGenerator($this);
        $invoiceInfo = $invoiceGenerator->generateInvoice();
        return $invoiceInfo;
    }


    /**
     * @param $products
     * @return ProductCollection
     */
    public function checkProductsExistInOrderProducts($products): ProductCollection {
        $notDuplicateProduct = new ProductCollection();
        foreach ($products as $product) {
            if($this->hasTheseProducts([$product->id])) {
                // can increase amount of product
            } else {
                $notDuplicateProduct->push($product);
            }
        }
        return $notDuplicateProduct;
    }

    /**
     * @return int
     */
    public function getDonateCost(): int
    {
        $donateCost = 0;
        if ($this->hasTheseProducts(Product::DONATE_PRODUCT)) {
            $donateCost = Product::getDonateProductCost();
        }
        return $donateCost;
    }

    public function closeOrderWithIndebtedStatus()
    {
        $this->close(config("constants.PAYMENT_STATUS_INDEBTED"));
        $this->timestamps = false;
        $this->update();
        $this->timestamps = true;
    }

    public function detachUnusedCoupon()
    {
        $usedCoupon = $this->hasProductsThatUseItsCoupon();
        if (!$usedCoupon) {
            /** if order has not used coupon reverse it    */
            $this->detachCoupon();
        }
    }


    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function changePaymentStatusToPaidOrIndebted(Transaction $transaction)
    {
        $this->close(config("constants.PAYMENT_STATUS_PAID"));
        if ($transaction->cost < (int)$this->totalCost()) {
            if ((int)$this->totalPaidCost() < (int)$this->totalCost())
                $this->paymentstatus_id = config("constants.PAYMENT_STATUS_INDEBTED");
        }

        $this->timestamps = false;
        $updateStatus = $this->update();
        $this->timestamps = true;
        return $updateStatus;
    }


    /**
     * @return bool
     */
    public function setCanceledAndUnpaid()
    {
        $this->close(config("constants.PAYMENT_STATUS_UNPAID"), config("constants.ORDER_STATUS_CANCELED"));
        $this->timestamps = false;
        $updateStatus = $this->update();
        $this->timestamps = true;
        return $updateStatus;
    }

    /**
     * @return array
     */
    public function refundWalletTransaction(): array
    {
        $walletTransactions = $this->suspendedTransactions()
            ->walletMethod();
        $totalWalletRefund = 0;
        $closeOrderFlag = false;
        foreach ($walletTransactions as $transaction) {
            $wallet = $transaction->wallet;
            $amount = $transaction->cost;
            if (isset($wallet)) {
                $response = $wallet->deposit($amount);
                if ($response["result"]) {
                    $transaction->delete();
                    $totalWalletRefund += $amount;
                }/*else {}*/
            }/*else {
                $response = $user->deposit($amount, config("constants.WALLET_TYPE_GIFT"));
                if ($response["result"]) {
                    $transaction->delete();
                    $totalWalletRefund += $amount;
                } else {}
            }*/
            $closeOrderFlag = true;
        }

        return array(
            'totalWalletRefund'=>$totalWalletRefund,
            'closeOrderFlag'=>$closeOrderFlag
        );
    }
}
