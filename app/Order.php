<?php

namespace App;

use App\Classes\Checkout\Alaa\OrderCheckout;
use App\Classes\Checkout\Alaa\ReObtainOrderFromRecords;
use App\Collection\OrderCollections;
use App\Collection\OrderproductCollection;
use App\Collection\ProductCollection;
use App\Collection\TransactionCollection;
use App\Repositories\OrderproductRepo;
use App\Repositories\ProductRepository;
use App\Traits\Helper;
use App\Traits\ProductCommon;
use Carbon\Carbon;
use DB;
use Doctrine\DBAL\Query\QueryBuilder;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * App\Order
 *
 * @property int                                                                 $id
 * @property int|null                                                            $user_id              آیدی مشخص
 *           کننده کاربر سفارش دهنده
 * @property int|null                                                            $orderstatus_id       آیدی مشخص
 *           کننده وضعیت سفارش
 * @property int|null                                                            $paymentstatus_id     آیدی مشخص
 *           کننده وضعیت پرداخت سفارش
 * @property int|null                                                            $coupon_id            آیدی مشخص
 *           کننده کپن استفاده شده برای سفارش
 * @property float                                                               $couponDiscount       میزان تخفیف
 *           کپن برای سفارش به درصد
 * @property int                                                                 $couponDiscountAmount میزان تخفیف
 *           کپن(به تومان)
 * @property int|null                                                            $cost                 مبلغ قابل
 *           پرداخت توسط کاربر
 * @property int|null                                                            $costwithoutcoupon    بخشی از
 *           قیمت که مشمول کپن تخفیف نمی شود
 * @property int                                                                 $discount             تخفیف خاص
 *           برای این سفارش به تومان
 * @property string|null                                                         $customerDescription  توضیحات
 *           مشتری درباره سفارش
 * @property string|null                                                         $customerExtraInfo    اطلاعات
 *           تکمیلی مشتری برای این سفارش
 * @property string|null                                                         $checkOutDateTime     تاریخ تسویه
 *           حساب کامل
 * @property Carbon|null                                                         $created_at
 * @property Carbon|null                                                         $updated_at
 * @property string|null                                                         $completed_at         مشخص کننده
 *           زمان تکمیل سفارش کاربر
 * @property Carbon|null                                                         $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $archivedSuccessfulTransactions
 * @property-read Coupon|null                                                    $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection|Orderfile[]           $files
 * @property-read \Illuminate\Database\Eloquent\Collection|Orderproduct[]        $normalOrderproducts
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $onlinetransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Ordermanagercomment[] $ordermanagercomments
 * @property-read \Illuminate\Database\Eloquent\Collection|Orderpostinginfo[]    $orderpostinginfos
 * @property-read \Illuminate\Database\Eloquent\Collection|Orderproduct[]        $orderproducts
 * @property-read Orderstatus|null                                               $orderstatus
 * @property-read Paymentstatus|null                                             $paymentstatus
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $pendingTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $successfulTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $suspendedTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[]         $unpaidTransactions
 * @property-read User|null                                                      $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Order whereCheckOutDateTime($value)
 * @method static Builder|Order whereCompletedAt($value)
 * @method static Builder|Order whereCost($value)
 * @method static Builder|Order whereCostwithoutcoupon($value)
 * @method static Builder|Order whereCouponDiscount($value)
 * @method static Builder|Order whereCouponDiscountAmount($value)
 * @method static Builder|Order whereCouponId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCustomerDescription($value)
 * @method static Builder|Order whereCustomerExtraInfo($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereDiscount($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereOrderstatusId($value)
 * @method static Builder|Order wherePaymentstatusId($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @property-read array|bool                                                     $coupon_discount_type
 * @property-read mixed                                                          $number_of_products
 * @method static Builder|Order query()
 * @method static Builder|BaseModel disableCache()
 * @method static Builder|BaseModel withCacheCooldownSeconds($seconds)
 * @property-read mixed                                                          $invoice
 * @property-read mixed                                                          $info
 * @property mixed                                                               donates
 * @property mixed                                                               donate_amount
 * @property-read mixed                                                          $added_bon_sum
 * @property-read mixed                                                          $coupon_info
 * @property-read mixed                                                          $debt
 * @property-read mixed                                                          $edit_order_link
 * @property-read mixed                                                          $jalali_completed_at
 * @property-read mixed                                                          $jalali_created_at
 * @property-read mixed                                                          $jalali_updated_at
 * @property-read mixed                                                          $manager_comment
 * @property-read mixed                                                          $order_posting_info
 * @property-read int                                                            $paid_price
 * @property-read mixed                                                          $pending_transactions
 * @property-read mixed                                                          $posting_info
 * @property-read mixed                                                          $price
 * @property-read int                                                            $refund_price
 * @property-read mixed                                                          $remove_order_link
 * @property-read mixed                                                          $successful_transactions
 * @property-read mixed                                                          $unpaid_transactions
 * @property-read mixed                                                          $used_bon_sum
 * @property-read mixed                                                          $cache_cooldown_seconds
 * @property-read int|null                                                       $archived_successful_transactions_count
 * @property-read int|null                                                       $files_count
 * @property-read mixed                                                          $edit_link
 * @property-read mixed                                                          $none_wallet_successful_transactions
 * @property-read mixed                                                          $purchased_orderproducts
 * @property-read mixed                                                          $purchased_orderproducts_count
 * @property-read mixed                                                          $remove_link
 * @property-read mixed                                                          $wallet_successful_transactions
 * @property-read int|null                                                       $normal_orderproducts_count
 * @property-read int|null                                                       $onlinetransactions_count
 * @property-read int|null                                                       $ordermanagercomments_count
 * @property-read int|null                                                       $orderpostinginfos_count
 * @property-read int|null                                                       $pending_transactions_count
 * @property-read int|null                                                       $successful_transactions_count
 * @property-read int|null                                                       $suspended_transactions_count
 * @property-read int|null                                                       $transactions_count
 * @property-read int|null                                                       $unpaid_transactions_count
 */
class Order extends BaseModel
{
    const ORDER_STATUS_OPEN = 1;
    const ORDER_STATUS_OPEN_BY_ADMIN = 4;
    const ORDER_STATUS_OPEN_DONATE = 8;


    const OPEN_ORDER_STATUSES = [
        self::ORDER_STATUS_OPEN,
        self::ORDER_STATUS_OPEN_BY_ADMIN,
        self::ORDER_STATUS_OPEN_DONATE,
    ];

    /*
    |--------------------------------------------------------------------------
    | Traits methods
    |--------------------------------------------------------------------------
    */
    use ProductCommon;
    use Helper;

    /*
    |--------------------------------------------------------------------------
    | Properties methods
    |--------------------------------------------------------------------------
    */
    protected $table = 'orders';
    protected $cascadeDeletes = [
        'transactions',
        'files',
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
        'price',
        'orderstatus',
        'paymentstatus',
        'orderproducts',
        'couponInfo',
        'paidPrice',
        'refundPrice',
        'successfulTransactions',
        'pendingTransactions',
        'unpaidTransactions',
        'orderPostingInfo',
        'debt',
        'usedBonSum',
        'addedBonSum',
        'user',
        'jalaliCreatedAt',
        'jalaliUpdatedAt',
        'jalaliCompletedAt',
        'postingInfo',
        'managerComment',
        'editLink',
        'removeLink',
        'cacheClearUrl'
    ];
    protected $hidden = [
        'id',
        'couponDiscount',
        'coupon',
        'orderstatus_id',
        'paymentstatus_id',
        'checkOutDateTime',
        'couponDiscountAmount',
        'coupon_id',
        'cost',
        'costwithoutcoupon',
        'normalOrderproducts',
        'user_id',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new OrderCollections($models);
    }

    public function CompletedAt_Jalali()
    {
        /**
         * Unnecessary variable
         */ /*$explodedDateTime = explode(" ", $this->completed_at);*/
        //        $explodedTime = $explodedDateTime[1] ;
        return $this->convertDate($this->completed_at, "toJalali");
    }

    public function onlinetransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where('paymentmethod_id', 1);
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

    public function normalOrderproducts()
    {
        return $this->hasMany('App\Orderproduct')
            ->where(function ($q) {
                /** @var QueryBuilder $q */
                $q->Where("orderproducttype_id", config("constants.ORDER_PRODUCT_TYPE_DEFAULT"));
            });
    }

    public function giftOrderproducts()
    {
        return $this->orderproducts(config('constants.ORDER_PRODUCT_GIFT'));
    }

    /**
     * @param null  $type
     * @param array $filters
     *
     * @return HasMany|Orderproduct
     */
    public function orderproducts($type = null, $filters = [])
    {
        if (isset($type)) {
            if ($type == config("constants.ORDER_PRODUCT_TYPE_DEFAULT")) {
                $relation = $this->hasMany('App\Orderproduct')
                    ->where(function ($q) use ($type) {
                        /** @var QueryBuilder $q */
                        $q->where("orderproducttype_id", $type);
                    });
            } else {
                $relation = $this->hasMany('App\Orderproduct')
                    ->where("orderproducttype_id", $type);
            }
        } else {
            $relation = $this->hasMany('App\Orderproduct');
        }

        foreach ($filters as $filter) {
            if (isset($filter["isArray"])) {
                $relation->whereIn($filter["attribute"], $filter["value"]);
            } else {
                $relation->where($filter["attribute"], $filter["value"]);
            }
        }

        return $relation;
    }

    /**
     * Determines this order's coupon discount type
     * Note: In case it has any coupons returns false
     *
     * @return array|bool
     */
    public function getCouponDiscountTypeAttribute()
    {
        if ($this->couponDiscount > 0) {
            return [
                'type'     => config('constants.DISCOUNT_TYPE_PERCENTAGE'),
                //ToDo constants
                'typeHint' => 'percentage',
                'discount' => $this->couponDiscount,
            ];
        } else {
            if ($this->couponDiscountAmount > 0) {
                return [
                    'type'     => config('constants.DISCOUNT_TYPE_COST'),
                    'typeHint' => 'amount',
                    'discount' => $this->couponDiscountAmount,
                ];
            }
        }

        return false;
    }

    /**
     * Indicated whether order cost has been determined or not
     *
     * @return bool
     */
    public function hasCost(): bool
    {
        return (isset($this->cost) || isset($this->costwithoutcoupon));
    }

    /**
     * @param $user
     *
     * @return bool
     */
    public function doesBelongToThisUser($user): bool
    {
        return optional($this->user)->id == optional($user)->id;
    }

    /**
     * Calculates the discount amount of totalCost relevant to this order's coupon
     *
     * @param int $totalCost
     *
     * @return float|int|mixed
     */
    public function obtainCouponDiscount(int $totalCost = 0)
    {
        $couponType = $this->coupon_discount_type;
        if ($couponType !== false) {
            if ($couponType["type"] == config("constants.DISCOUNT_TYPE_PERCENTAGE")) {
                $totalCost = ((1 - ($couponType["discount"] / 100)) * $totalCost);
            } else {
                if ($couponType["type"] == config("constants.DISCOUNT_TYPE_COST")) {
                    $totalCost = $totalCost - $couponType["discount"];
                }
            }
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

    /**
     * Determines whether order has coupon or not
     *
     * @return bool
     */
    public function hasCoupon()
    {
        if (isset($this->coupon->id)) {
            return true;
        } else {
            return false;
        }
    }

    public function getNumberOfProductsAttribute()
    {
        return $this->orderproducts->count();
    }

    /**
     * Gets this order's products
     *
     * @param array $orderproductTypes
     *
     * @return \Illuminate\Database\Eloquent\Collection|Collection
     */
    public function products(array $orderproductTypes = [])
    {
        $order = $this;
        $key   = 'order:products:' . $order->cacheKey();

        return Cache::tags(['order', 'product', 'order_' . $this->id, 'order_' . $this->id . '_products'])
            ->remember($key, config("constants.CACHE_5"), function () use ($order, $orderproductTypes) {
                $result = DB::table('products')
                    ->join('orderproducts', function ($join) use ($orderproductTypes) {
                        if (empty($orderproductTypes)) {
                            $join->on('products.id', '=', 'orderproducts.product_id')
                                ->whereNull('orderproducts.deleted_at');
                        } else {
                            $join->on('products.id', '=', 'orderproducts.product_id')
                                ->whereNull('orderproducts.deleted_at')
                                ->whereIn("orderproducttype_id",
                                    $orderproductTypes);
                        }
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

            });
    }

    public function refreshCost()
    {
        $orderCost = $this->obtainOrderCost(true);
        /** @var OrderproductCollection $calculatedOrderproducts */
        $calculatedOrderproducts = $orderCost["calculatedOrderproducts"];
        $calculatedOrderproducts->updateCostValues();

        $this->cost              = $orderCost["rawCostWithDiscount"];
        $this->costwithoutcoupon = $orderCost["rawCostWithoutDiscount"];
        $this->updateWithoutTimestamp();

        return ["newCost" => $orderCost];
    }

    /**
     * Obtain order total cost
     *
     * @param boolean $calculateOrderCost
     * @param boolean $calculateOrderproductCost
     *
     * @param string  $mode
     *
     * @return array
     */
    public function obtainOrderCost($calculateOrderCost = false, $calculateOrderproductCost = true, $mode = "DEFAULT")
    {
        if ($calculateOrderCost) {
            $this->load('user', 'user.wallets', 'normalOrderproducts', 'normalOrderproducts.product',
                'normalOrderproducts.product.parents',
                'normalOrderproducts.userbons', 'normalOrderproducts.attributevalues',
                'normalOrderproducts.product.attributevalues');
            $orderproductsToCalculateFromBaseIds = [];
            if ($calculateOrderproductCost) {
                $orderproductsToCalculateFromBaseIds = $this->normalOrderproducts->pluck("id")
                    ->toArray();
            }

            $reCheckIncludedOrderproductsInCoupon = false;
            if ($this->hasCoupon()) {
                $reCheckIncludedOrderproductsInCoupon = ($mode == 'REOBTAIN') ? false : true;
            }
            $alaaCashierFacade = new OrderCheckout($this, $orderproductsToCalculateFromBaseIds,
                $reCheckIncludedOrderproductsInCoupon);
        } else {
            $this->load('normalOrderproducts', 'normalOrderproducts.product',
                'normalOrderproducts.product.parents', 'normalOrderproducts.userbons',
                'normalOrderproducts.attributevalues', 'normalOrderproducts.product.attributevalues');
            $alaaCashierFacade = new ReObtainOrderFromRecords($this);
        }

        $priceInfo = $alaaCashierFacade->checkout();

        return [
            'sumOfOrderproductsRawCost'     => $priceInfo['totalPriceInfo']['sumOfOrderproductsRawCost'],
            'rawCostWithDiscount'           => $priceInfo['totalPriceInfo']['totalRawPriceWhichHasDiscount'],
            'rawCostWithoutDiscount'        => $priceInfo['totalPriceInfo']['totalRawPriceWhichDoesntHaveDiscount'],
            'totalCost'                     => $priceInfo['totalPriceInfo']['finalPrice'],
            'totalCostWithoutOrderDiscount' => $priceInfo['totalPriceInfo']['totalPrice'],
            'payableAmountByWallet'         => $priceInfo['totalPriceInfo']['payableAmountByWallet'],
            'calculatedOrderproducts'       => $priceInfo['orderproductsInfo']['calculatedOrderproducts'],
        ];
    }

    /**
     * Gives order bons to user
     *
     * @param string $bonName
     *
     * @return array [$totalSuccessfulBons, $totalFailedBons]
     */
    public function giveUserBons($bonName)
    {
        $totalSuccessfulBons = 0;
        $totalFailedBons     = 0;
        $user                = $this->user;
        if (!isset($user)) {
            return [0, 0];
        }
        $orderproducts = $this->orderproducts(config("constants.ORDER_PRODUCT_TYPE_DEFAULT"))->get();
        foreach ($orderproducts as $orderproduct) {
            if ($user->userbons->where("orderproduct_id", $orderproduct->id)
                ->isNotEmpty()) {
                continue;
            }
            /** @var Product $simpleProduct */
            $simpleProduct = $orderproduct->product;
            $bons          = $simpleProduct->bons->where("name", $bonName)->where("isEnable", 1);
            if ($bons->isEmpty()) {
                $grandParent = $simpleProduct->grand_parent;
                if (isset($grandParent)) {
                    $bons = $grandParent->bons->where("name", $bonName)->where("isEnable", 1);
                }
            }

            if ($bons->isNotEmpty()) {
                $bon     = $bons->first();
                $bonPlus = $bon->pivot->bonPlus;
                if ($bonPlus) {
                    $userbon = Userbon::create([
                        'user_id'          => $user->id,
                        'bon_id'           => $bon->id,
                        'totalNumber'      => $bon->pivot->bonPlus,
                        'userbonstatus_id' => config('constants.USERBON_STATUS_ACTIVE'),
                        'orderproduct_id'  => $orderproduct->id,
                    ]);
                    if (isset($userbon)) {
                        $totalSuccessfulBons += $userbon->totalNumber;
                    } else {
                        $totalFailedBons += $bon->pivot->bonPlus;
                    }
                }
            }
        }

        return [
            $totalSuccessfulBons,
            $totalFailedBons,
        ];
    }

    public function closeWalletPendingTransactions()
    {
        /**
         * for reduce query
         */
        /*$walletTransactions = $this->suspendedTransactions*/
        $walletTransactions =
            $this->suspendedTransactions()->where("paymentmethod_id", config("constants.PAYMENT_METHOD_WALLET"))->get();

        foreach ($walletTransactions as $transaction) {
            /** @var Transaction $transaction */
            $transaction->transactionstatus_id = config("constants.TRANSACTION_STATUS_SUCCESSFUL");
            $transaction->update();
        }
    }

    /**
     * @return HasMany|Transaction
     */
    public function suspendedTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_SUSPENDED"));
    }

    /**
     * @param ProductCollection $products
     *
     * @return ProductCollection
     */
    public function checkProductsExistInOrderProducts(ProductCollection $products): ProductCollection
    {
        $notDuplicateProduct = new ProductCollection();
        foreach ($products as $product) {
            if ($this->hasTheseProducts([$product->id])) {
                // can increase amount of product
            } else {
                $notDuplicateProduct->push($product);
            }
        }

        return $notDuplicateProduct;
    }

    /**
     * Determines if this order has given products
     *
     * @param array $products
     *
     * @return bool
     */
    public function hasTheseProducts(array $products): bool
    {
        return $this->orderproducts->whereIn("product_id", $products)
            ->isNotEmpty();
    }

    /**
     * @return int
     */
    public function getDonateCost(): int
    {
        $donateCost    = 0;
        $orderProducts = $this->orderproducts->whereIn('product_id', [
            Product::CUSTOM_DONATE_PRODUCT,
            Product::DONATE_PRODUCT_5_HEZAR,
        ]);

        foreach ($orderProducts as $orderProduct) {
            $donateCost += $orderProduct->cost;
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

    /**
     * Closes this order
     *
     * @param string $paymentStatus
     *
     * @param int    $orderStatus
     *
     * @return void
     */
    public function close($paymentStatus = null, int $orderStatus = null)
    {
        if (is_null($orderStatus)) {
            // You can't put config() in method signature
            $orderStatus = config('constants.ORDER_STATUS_CLOSED');
        }

        $this->orderstatus_id = $orderStatus;

        if (isset($paymentStatus)) {
            $this->paymentstatus_id = $paymentStatus;
        }

        $this->completed_at = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now('Asia/Tehran'));
    }

    public function detachUnusedCoupon()
    {
        $usedCoupon = $this->hasProductsThatUseItsCoupon();
        if (!$usedCoupon) {
            /** if order has not used coupon reverse it    */
            $coupon = $this->coupon;
            if (isset($coupon)) {
                $this->detachCoupon();
                if ($this->updateWithoutTimestamp()) {
                    $coupon->decreaseUseNumber();
                    $coupon->update();
                }
            }
        }
    }

    /**
     * Determines whether the coupon is usable for this order or not
     *
     * @return bool
     */
    public function hasProductsThatUseItsCoupon(): bool
    {
        $flag                = true;
        $notIncludedProducts = $this->reviewCouponProducts();
        $orderproductCount   = $this->orderproducts->whereType([config("constants.ORDER_PRODUCT_TYPE_DEFAULT")])
            ->count();
        if ($orderproductCount == optional($notIncludedProducts)->count()) {
            $flag = false;
        }

        return $flag;
    }

    /**
     * @return Collection|null
     */
    public function reviewCouponProducts(): ?Collection
    {
        $orderproducts = $this->orderproducts->whereType([config("constants.ORDER_PRODUCT_TYPE_DEFAULT")]);

        $coupon              = $this->coupon;
        $notIncludedProducts = new  ProductCollection();
        if (isset($coupon)) {
            /** @var OrderproductCollection $orderproducts */
            foreach ($orderproducts->getPurchasedProducts() as $product) {
                if (!$coupon->hasProduct($product)) {
                    $notIncludedProducts->push($product);
                }
            }
        }

        if ($notIncludedProducts->isNotEmpty()) {
            return $notIncludedProducts;
        } else {
            return null;
        }
    }

    /**
     * Detaches coupon from this order
     *
     */
    public function detachCoupon(): void
    {
        $this->coupon_id            = null;
        $this->couponDiscount       = 0;
        $this->couponDiscountAmount = 0;
    }

    public function attachCoupon(Coupon $coupon):self
    {
        $this->coupon_id = $coupon->id;
        if ($coupon->discounttype_id == config('constants.DISCOUNT_TYPE_COST')) {
            $this->couponDiscount       = 0;
            $this->couponDiscountAmount = (int)$coupon->discount;
        } else {
            $this->couponDiscount       = $coupon->discount;
            $this->couponDiscountAmount = 0;
        }

        return $this;
    }

    /**
     * @return int $totalWalletRefund
     */
    public function refundWalletTransaction(): int
    {
        $walletTransactions = $this->suspendedTransactions()
            ->walletMethod()
            ->get();

        $totalWalletRefund = 0;
        foreach ($walletTransactions as $transaction) {
            $response = $transaction->depositThisWalletTransaction();
            if ($response["result"]) {
                $transaction->delete();
                $totalWalletRefund += $transaction->cost;
            }
        }

        return $totalWalletRefund;
    }

    public function getOrderstatusAttribute()
    {
        $order = $this;
        $key   = "order:orderstatus:" . $order->cacheKey();

        return Cache::tags(['order', 'orderstatus', 'order_' . $order->id, 'order_' . $order->id . '_orderstatus'])
            ->remember($key, config("constants.CACHE_10"), function () use ($order) {
                return optional($order->orderstatus()
                    ->first())->setVisible([
                    'name',
                    'displayName',
                    'description',
                ]);
            });
    }

    public function orderstatus()
    {
        return $this->belongsTo('App\Orderstatus');
    }

    public function getPaymentstatusAttribute()
    {
        $order = $this;
        $key   = 'order:paymentstatus:' . $order->cacheKey();

        return Cache::tags(['order', 'paymentstatus', 'order_' . $order->id, 'order_' . $order->id . '_paymentstatus'])
            ->remember($key, config('constants.CACHE_10'), function () use ($order) {
                return optional($order->paymentstatus()
                    ->first())->setVisible([
                    'name',
                    'displayName',
                    'description',
                ]);
            });
    }

    public function paymentstatus()
    {
        return $this->belongsTo('App\Paymentstatus');
    }

    public function getCouponInfoAttribute()
    {
        $order = $this;
        $key   = 'order:coupon:' . $order->cacheKey();

        return Cache::tags(['order', 'coupon', 'order_' . $order->id, 'order_' . $order->id . '_coupon'])
            ->remember($key, config("constants.CACHE_10"), function () use ($order) {
                $coupon = $order->coupon()
                    ->first();
                if (!isset($coupon)) {
                    return null;
                }

                $coupon->setVisible([
                    'name',
                    'code',
                    //                            'discountType',
                ]);

                return array_merge($coupon->toArray(), $this->coupon_discount_type);
            });
    }

    public function coupon()
    {
        return $this->belongsTo('App\Coupon');
    }

    public function getWalletSuccessfulTransactionsAttribute()
    {
        return $this->transactions
            ->where('paymentmethod_id', config('constants.PAYMENT_METHOD_WALLET'))
            ->whereIn('transactionstatus_id', [config('constants.TRANSACTION_STATUS_SUCCESSFUL')])
            ->where('cost', '>', 0);
    }

    public function getNoneWalletSuccessfulTransactionsAttribute()
    {
        return $this->transactions
            ->where('paymentmethod_id', '<>', config('constants.PAYMENT_METHOD_WALLET'))
            ->where('transactionstatus_id', config('constants.TRANSACTION_STATUS_SUCCESSFUL'));
    }

    /**
     * Recalculates order's cost and updates it's cost
     *
     * @return array
     */
    //ToDo : must refresh donate product cost

    public function getPriceAttribute()
    {
        return $this->totalCost();
    }

    public function totalCost()
    {
        return (int)$this->obtainOrderCost()["totalCost"];
    }

    /**
     * @param Orderproduct $orderProduct
     * @param Product      $product
     */
    public function applyOrderGifts(Orderproduct $orderProduct, Product $product)
    {
        $giftsOfProduct = $product->getGifts();
        $orderGifts     = $this->giftOrderproducts;
        foreach ($giftsOfProduct as $giftItem) {
            if (!$orderGifts->contains($giftItem)) {
                $this->attachGift($giftItem, $orderProduct);
                $this->giftOrderproducts->push($giftItem);
            }
        }
    }

    /** Attaches a gift to the order of this orderproduct
     *
     * @param Product      $gift
     * @param Orderproduct $orderproduct
     *
     * @return Orderproduct|null
     */
    public function attachGift(Product $gift, Orderproduct $orderproduct): Orderproduct
    {
        $giftOrderproduct =
            OrderproductRepo::createGiftOrderproduct($this->id, $gift->id, $gift->calculatePayablePrice()["cost"]);

        $giftOrderproduct->parents()
            ->attach($orderproduct,
                ["relationtype_id" => config("constants.ORDER_PRODUCT_INTERRELATION_PARENT_CHILD")]);

        return $giftOrderproduct;
    }

    /**
     * @return Collection
     */
    public function getOrderproductsAttribute(): Collection
    {
        $order = $this;
        $key   = 'order:orderproducts:' . $order->cacheKey();

        return Cache::tags(['order', 'orderproduct', 'order_' . $order->id, 'order_' . $order->id . '_orderproducts'])
            ->remember($key, config("constants.CACHE_5"), function () use ($order) {
                /** @var OrderproductCollection $orderproducts */
                $orderproducts = $this->orderproducts()
                    ->get();
                if ($orderproducts->isNotEmpty()) {
                    $orderproducts->setVisible([
                        'id',
                        'cost',
                        'discountPercentage',
                        'discountAmount',
                        'quantity',
                        'orderproducttype',
                        'product',
                        'grandId',
                        'price',
                        'bons',
                        'attributevalues',
                        'photo',
                        'grandProduct',
                    ]);
                }

                return $orderproducts;
            });
    }

    /**
     * @return int
     */
    public function getPaidPriceAttribute(): int
    {
        return $this->totalPaidCost() + $this->totalRefund();
    }

    public function totalPaidCost()
    {
        $order = $this;
        $key   = 'order:totalPaidCost:' . $order->cacheKey();

        return (int)Cache::tags(['order', 'orderCost', 'cost', 'order_' . $order->id, 'order_' . $order->id . '_cost'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                $totalPaidCost          = 0;
                $successfulTransactions = $order->successfulTransactions;
                if ($successfulTransactions->isNotEmpty()) {
                    $totalPaidCost = $successfulTransactions->where('cost', '>', 0)
                        ->sum("cost");
                }

                return $totalPaidCost;
            });
    }

    public function totalRefund()
    {
        $order = $this;
        $key   = 'order:totalRefund:' . $order->cacheKey();

        return (int)Cache::tags(['order', 'orderCost', 'cost', 'order_' . $order->id, 'order_' . $order->id . '_cost'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                $totalRefund            = 0;
                $successfulTransactions = $order->successfulTransactions;
                if ($successfulTransactions->isNotEmpty()) {
                    $totalRefund = $successfulTransactions->where('cost', '<', 0)
                        ->sum("cost");
                }

                return $totalRefund;
            });
    }

    /**
     * @return int
     */
    public function getRefundPriceAttribute(): int
    {
        return $this->totalRefund();
    }

    /**
     * @return Collection
     */
    public function getDonatesAttribute(): Collection
    {
        $order = $this;
        $key   = 'order:donates:' . $order->cacheKey();

        return Cache::tags(['order', 'orderproduct', 'donate', 'order_' . $order->id, 'order_' . $order->id . '_orderproducts'])
            ->remember($key, config("constants.CACHE_10"), function () use ($order) {
                return $this->orderproducts->whereIn('product_id', [
                    Product::CUSTOM_DONATE_PRODUCT,
                    Product::DONATE_PRODUCT_5_HEZAR,
                ]);
            });
    }

    /**
     * @return int
     */
    public function getDonateAmountAttribute(): int
    {
        $donateOrderProducts = $this->donates;

        $donateCost = 0;
        if ($donateOrderProducts->isNotEmpty()) {
            $donateCost = $donateOrderProducts->sum("cost");
        }

        return $donateCost;
    }

    public function getSuccessfulTransactionsAttribute()
    {
        $order = $this;
        $key   = 'order:transactions:' . $order->cacheKey();

        return Cache::tags(['order', 'transaction', 'order_' . $order->id, 'order_' . $order->id . '_transactions'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                /** @var TransactionCollection $successfulTransactions */
                $successfulTransactions = $order->successfulTransactions()
                    ->get();
                $successfulTransactions->setVisible([
                    'cost',
                    'transactionID',
                    'traceNumber',
                    'referenceNumber',
                    'paycheckNumber',
                    'description',
                    'completed_at',
                    'paymentmethod',
                    'transactiongateway',
                    'managerComment',
                    'jalaliCompletedAt',
                    'editLink',
                ]);

                return $successfulTransactions;
            });
    }

    public function successfulTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where(function ($q) {
                /** @var QueryBuilder $q */
                $q->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_SUCCESSFUL"))
                    ->orWhere("transactionstatus_id",
                        config("constants.TRANSACTION_STATUS_SUSPENDED"));
            });
    }

    public function getPendingTransactionsAttribute()
    {
        $order = $this;
        $key   = 'order:pendingtransactions:' . $order->cacheKey();
        return Cache::tags(['order', 'transaction', 'pendingTransaction', 'order_' . $order->id, 'order_' . $order->id . '_pendingtransactions', 'order_' . $order->id . '_transactions'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                /** @var TransactionCollection $pendingTransaction */
                $pendingTransaction = $order->pendingTransactions()
                    ->get();
                $pendingTransaction->setVisible([
                    'cost',
                    'transactionID',
                    'traceNumber',
                    'referenceNumber',
                    'paycheckNumber',
                    'description',
                    'completed_at',
                    'paymentmethod',
                    'transactiongateway',
                    'managerComment',
                    'jalaliCompletedAt',
                    'editLink',
                ]);

                return $pendingTransaction;
            });
    }

    public function pendingTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_PENDING"));
    }

    public function getUnpaidTransactionsAttribute()
    {
        $order = $this;
        $key   = "order:unpaidtransactions:" . $order->cacheKey();
        return Cache::tags(['order', 'transaction', 'unpaidtransactions', 'order_' . $order->id, 'order_' . $order->id . '_unpaidtransactions', 'order_' . $order->id . '_transactions'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                /** @var TransactionCollection $unpaidTransaction */
                $unpaidTransaction = $order->unpaidTransactions()
                    ->get();
                $unpaidTransaction->setVisible([
                    'cost',
                    'transactionID',
                    'traceNumber',
                    'referenceNumber',
                    'paycheckNumber',
                    'description',
                    'completed_at',
                    'paymentmethod',
                    'transactiongateway',
                    'managerComment',
                    'jalaliCompletedAt',
                    'jalaliDeadlineAt',
                    'editLink',
                ]);

                return $unpaidTransaction;
            });
    }

    public function unpaidTransactions()
    {
        return $this->hasMany('App\Transaction')
            ->where("transactionstatus_id", config("constants.TRANSACTION_STATUS_UNPAID"));
    }

    public function getOrderPostingInfoAttribute()
    {
        $order = $this;
        $key   = "order:postInfo:" . $order->cacheKey();

        return Cache::tags(['order', 'postingInfo', 'order_' . $order->id, 'order_' . $order->id . '_postingInfo'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                return $order->orderpostinginfos()
                    ->get();
            });
    }

    public function orderpostinginfos()
    {
        return $this->hasMany(Orderpostinginfo::Class);
    }

    public function getDebtAttribute()
    {
        return $this->debt();
    }

    public function debt()
    {
        $order = $this;
        $key   = "order:debt:" . $order->cacheKey();
        return (int)Cache::tags(['order', 'transaction', 'orderDebt', 'order_' . $order->id, 'order_' . $order->id . '_orderDebt', 'order_' . $order->id . '_transactions'])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                if ($this->orderstatus_id == config("constants.ORDER_STATUS_REFUNDED")) {
                    return -($this->totalPaidCost() + $this->totalRefund());
                }

                $cost = $this->obtainOrderCost()["totalCost"];
                return $cost - ($this->totalPaidCost() + $this->totalRefund());
            });
    }

    public function getUsedBonSumAttribute()
    {
        return $this->usedBonSum();
    }

    public function usedBonSum()
    {
        $order = $this;
        $key   = 'order:usedBonSum:' . $order->cacheKey();

        return (int)Cache::tags(['order', 'bon', 'order_' . $order->id, 'order_' . $order->id . '_usedBon', 'order_' . $order->id . '_bon'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                $bonSum = 0;
                if (isset($this->orderproducts)) {
                    foreach ($this->orderproducts as $orderproduct) {
                        $bonSum += $orderproduct->userbons->sum("pivot.usageNumber");
                    }
                }

                return $bonSum;
            });
    }

    public function getAddedBonSumAttribute()
    {
        return $this->addedBonSum();
    }

    public function addedBonSum($intendedUser = null)
    {
        $order = $this;
        $key   = 'order:addedBonSum:' . $order->cacheKey();

        return Cache::tags(['order', 'bon', 'order_' . $order->id, 'order_' . $order->id . '_addedBon', 'order_' . $order->id . '_bon'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order, $intendedUser) {
                /** @var User $user */
                if (isset($intendedUser)) {
                    $user = $intendedUser;
                } else {
                    $user = $this->user;
                }

                $bonSum = 0;
                foreach ($this->orderproducts as $orderproduct) {
                    /** @var Collection $userbons */
                    $userbons = $user->userbons->where("orderproduct_id", $orderproduct->id);
                    if ($userbons->isNotEmpty()) {
                        $bonSum += $userbons->sum("totalNumber");
                    }
                }
                return $bonSum;
            });
    }

    public function getUserAttribute()
    {

        $order = $this;
        $key   = "order:user:" . $order->cacheKey();
        return Cache::tags(['order', 'user', 'order_' . $order->id, 'order_' . $order->id . '_user'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                $visibleColumns = [
                    'id',
                    'firstName',
                    'lastName',
                    'nationalCode',
                    'province',
                    'city',
                    'address',
                    'postalCode',
                    'school',
                    'info',
                    'userstatus',
                    'cacheClearUrl'
                ];

                if (hasAuthenticatedUserPermission(config('constants.SHOW_USER_MOBILE'))) {
                    $visibleColumns = array_merge($visibleColumns, ['mobile']);
                }

                if (hasAuthenticatedUserPermission(config('constants.SHOW_USER_EMAIL'))) {
                    $visibleColumns = array_merge($visibleColumns, ['email']);
                }

                if (hasAuthenticatedUserPermission(config('constants.EDIT_USER_ACCESS'))) {
                    $visibleColumns = array_merge($visibleColumns, ['editLink']);
                }

                return $order->user()
                    ->first()
                    ->setVisible($visibleColumns);
            });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getJalaliUpdatedAtAttribute()
    {
        $order = $this;
        $key   = 'order:jalaliUpdatedAt:' . $order->cacheKey();
        return Cache::tags(['order', 'jalaliUpdatedAt', 'order_' . $order->id, 'order_' . $order->id . '_jalaliUpdatedAt'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                if (isset($order->updated_at))
                    if (hasAuthenticatedUserPermission(config('constants.SHOW_ORDER_ACCESS'))) {
                        return $this->convertDate($order->updated_at, "toJalali");
                    }

                return null;
            });

    }

    public function getJalaliCreatedAtAttribute()
    {
        $order = $this;
        $key   = "order:jalaliCreatedAt:" . $order->cacheKey();
        return Cache::tags(['order', 'jalaliCreatedAt', 'order_' . $order->id, 'order_' . $order->id . '_jalaliCreatedAt'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                if (isset($order->created_at))
                    if (hasAuthenticatedUserPermission(config('constants.SHOW_ORDER_ACCESS'))) {
                        return $this->convertDate($order->created_at, "toJalali");
                    }

                return null;
            });

    }

    public function getJalaliCompletedAtAttribute()
    {
        $order = $this;
        $key   = "order:jalaliCompletedAt:" . $order->cacheKey();
        return Cache::tags(['order', 'jalaliCompletedAt', 'order_' . $order->id, 'order_' . $order->id . '_jalaliCompletedAt'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                if (isset($order->completed_at))
                    if (hasAuthenticatedUserPermission(config('constants.SHOW_ORDER_ACCESS'))) {
                        return $this->convertDate($order->completed_at, "toJalali");
                    }

                return null;
            });
    }

    public function getPostingInfoAttribute()
    {

        $order = $this;
        $key   = "order:postingInfo:" . $order->cacheKey();
        return Cache::tags(["order"])
            ->remember($key, config("constants.CACHE_60"), function () use ($order) {
                return $order->orderpostinginfos()
                    ->get();
            });

    }

    public function getManagerCommentAttribute()
    {
        $order = $this;
        $key   = "order:managerComment:" . $order->cacheKey();
        return Cache::tags(['order', 'managerComment', 'order_' . $order->id, 'order_' . $order->id . '_managerComment'])
            ->remember($key, config("constants.CACHE_600"), function () use ($order) {
                if (hasAuthenticatedUserPermission('constants.SHOW_ORDER_ACCESS')) {
                    return $order->ordermanagercomments()
                        ->get();
                }

                return null;
            });

    }

    public function ordermanagercomments()
    {
        return $this->hasMany('App\Ordermanagercomment');
    }

    public function getEditLinkAttribute()
    {
        if (hasAuthenticatedUserPermission(config('constants.EDIT_ORDER_ACCESS')))
            return action('Web\OrderController@edit', $this->id);

        return null;

    }

    public function getRemoveLinkAttribute()
    {
        if (hasAuthenticatedUserPermission(config('constants.REMOVE_ORDER_ACCESS')))
            return action('Web\OrderController@destroy', $this->id);

        return null;
    }

    public function getPurchasedOrderproductsAttribute()
    {
        return $this->normalOrderproducts->whereNotIn('product_id', ProductRepository::getUnPurchasableProducts());
    }

    public function getPurchasedOrderproductsCountAttribute()
    {
        return $this->purchased_orderproducts->count();
    }

    /**
     * @param Order $myOrder
     *
     * @return int
     */
    public function getDonateSum(): int
    {
        $key = 'getDonateSum:' . $this->cacheKey();
        return Cache::remember($key, config("constants.CACHE_5"), function () {
            return $this->orderproducts->whereIn('product_id', ProductRepository::getDonateProducts())->sum('cost');
        });
    }
}
