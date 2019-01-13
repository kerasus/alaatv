<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTransactionRequest;
use App\Http\Requests\InsertTransactionRequest;
use App\Order;
use App\Orderproduct;
use App\Paymentmethod;
use App\Product;
use App\Traits\Helper;
use App\Traits\OrderCommon;
use App\Transaction;
use App\Transactiongateway;
use App\Transactionstatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Zarinpal\Zarinpal;

class TransactionController extends Controller
{
    use OrderCommon;
    use Helper;
    protected $response;

    function __construct()
    {
        $this->response = new Response();
        $this->middleware('permission:' . Config::get('constants.LIST_TRANSACTION_ACCESS'), ['only' => 'index']);
        $this->middleware('permission:' . Config::get('constants.SHOW_TRANSACTION_ACCESS'), ['only' => 'edit']);
        $this->middleware('permission:' . Config::get('constants.EDIT_TRANSACTION_ACCESS'), ['only' => 'update']);
        $this->middleware('role:admin', ['only' => 'getUnverifiedTransactions']);
        //        $this->middleware('permission:'.Config::get('constants.INSERT_TRANSACTION_ACCESS'),['only'=>'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transactions = Transaction::orderBy('created_at', 'Desc');

            $createdSinceDate = Input::get('createdSinceDate');
            $createdTillDate = Input::get('createdTillDate');
            $createdTimeEnable = Input::get('createdTimeEnable');
            if (strlen($createdSinceDate) > 0 && strlen($createdTillDate) > 0 && isset($createdTimeEnable)) {
                $transactions = $this->timeFilterQuery($transactions, $createdSinceDate, $createdTillDate, 'completed_at');
            }

            $deadlineSinceDate = Input::get('DeadlineSinceDate');
            $deadlineTillDate = Input::get('DeadlineTillDate');
            $deadlineTimeEnable = Input::get('DeadlineTimeEnable');
            if (strlen($deadlineSinceDate) > 0 && strlen($deadlineTillDate) > 0 && isset($deadlineTimeEnable)) {
                $transactions = $this->timeFilterQuery($transactions, $deadlineSinceDate, $deadlineTillDate, 'deadline_at');
            }

            if (Input::has('transactionStatus')) {
                $transactionStatusFilter = Input::get('transactionStatus');
                $transactions = $transactions->where("transactionstatus_id", $transactionStatusFilter);
            }

            $transactionCode = trim(Input::get("transactionCode"));
            if (isset($transactionCode[0])) {
                $transactions = $transactions->where(function ($q) use ($transactionCode) {
                    $q->where("traceNumber", "like", "%" . $transactionCode . "%")
                      ->orWhere("referenceNumber", "like", "%" . $transactionCode . "%")
                      ->orWhere("paycheckNumber", "like", "%" . $transactionCode . "%")
                      ->orWhere("transactionID", "like", "%" . $transactionCode . "%");
                });
            }

            $transactionManagerComment = Input::get("transactionManagerComment");
            if (isset($transactionManagerComment[0])) {
                $transactions = $transactions->where(function ($q) use ($transactionManagerComment) {
                    $q->where("managerComment", "like", "%" . $transactionManagerComment . "%");
                });
            }

            $firstName = trim(Input::get('firstName'));
            if (isset($firstName) && strlen($firstName) > 0) {
                $transactions = $transactions->whereHas("order", function ($query) use ($firstName) {
                    $query->whereHas('user', function ($q) use ($firstName) {
                        $q->where('firstName', 'like', '%' . $firstName . '%');
                    });
                });
            }

            $lastName = trim(Input::get('lastName'));
            if (isset($lastName) && strlen($lastName) > 0) {
                $transactions = $transactions->whereHas("order", function ($query) use ($lastName) {
                    $query->whereHas('user', function ($q) use ($lastName) {
                        $q->where('lastName', 'like', '%' . $lastName . '%');
                    });
                });
            }

            $nationalCode = trim(Input::get('nationalCode'));
            if (isset($nationalCode) && strlen($nationalCode) > 0) {
                $transactions = $transactions->whereHas("order", function ($query) use ($nationalCode) {
                    $query->whereHas('user', function ($q) use ($nationalCode) {
                        $q->where('nationalCode', 'like', '%' . $nationalCode . '%');
                    });
                });
            }

            $mobile = trim(Input::get('mobile'));
            if (isset($mobile) && strlen($mobile) > 0) {
                $transactions = $transactions->whereHas("order", function ($query) use ($mobile) {
                    $query->whereHas('user', function ($q) use ($mobile) {
                        $q->where('mobile', 'like', '%' . $mobile . '%');
                    });
                });
            }


            $productsId = Input::get('products');
            $transactionOrderproductCost = collect();
            $transactionOrderproductTotalCost = 0;
            $transactionOrderproductTotalExtraCost = 0;
            if (isset($productsId) && !in_array(0, $productsId)) {
                $products = Product::whereIn('id', $productsId)
                                   ->get();
                foreach ($products as $product) {
                    if ($product->producttype_id == Config::get("constants.PRODUCT_TYPE_CONFIGURABLE"))
                        if ($product->hasChildren()) {
                            $productsId = array_merge($productsId, Product::whereHas('parents', function ($q) use ($productsId) {
                                $q->whereIn("parent_id", $productsId);
                            })
                                                                          ->pluck("id")
                                                                          ->toArray());
                        }
                }
                if (Input::has("checkoutStatusEnable")) {
                    $checkoutStatuses = Input::get("checkoutStatuses");
                    if (in_array(0, $checkoutStatuses)) {
                        $transactions = $transactions->whereIn('order_id', Orderproduct::whereNull("checkoutstatus_id")
                                                                                       ->whereIn('product_id', $productsId)
                                                                                       ->pluck('order_id'));
                    } else {
                        $transactions = $transactions->whereIn('order_id', Orderproduct::whereIn("checkoutstatus_id", $checkoutStatuses)
                                                                                       ->whereIn('product_id', $productsId)
                                                                                       ->pluck('order_id'));
                    }
                } else {
                    $transactions = $transactions->whereIn('order_id', Orderproduct::whereIn('product_id', $productsId)
                                                                                   ->pluck('order_id'));
                }
            } else if (Input::has("checkoutStatusEnable")) {
                $checkoutStatuses = Input::get("checkoutStatuses");
                if (in_array(0, $checkoutStatuses)) {
                    $transactions = $transactions->whereIn('order_id', Orderproduct::whereNull("checkoutstatus_id")
                                                                                   ->pluck('order_id'));
                } else {
                    $transactions = $transactions->whereIn('order_id', Orderproduct::whereIn("checkoutstatus_id", $checkoutStatuses)
                                                                                   ->pluck('order_id'));
                }
            }

            $extraAttributevaluesId = Input::get('extraAttributes');
            if (isset($extraAttributevaluesId)) {
                $transactions = $transactions->whereIn('order_id', Orderproduct::whereHas("attributevalues", function ($q) use ($extraAttributevaluesId) {
                    $q->whereIn('value_id', $extraAttributevaluesId);
                })
                                                                               ->pluck('order_id'));
            }

            //        if(isset($paymentMethodsId) && !in_array(0, $paymentMethodsId)){
            if (Input::has('paymentMethods')) {
                $paymentMethodsId = Input::get('paymentMethods');
                $transactions = $transactions->whereIn('paymentmethod_id', $paymentMethodsId);
            }

            if (Input::has('orderStatuses')) {
                $orderStatusesId = Input::get('orderStatuses');
                //            $orders = Order::orderStatusFilter($orders, $orderStatusesId);
                $transactions = $transactions->whereHas("order", function ($q) use ($orderStatusesId) {
                    $q->whereIn("orderstatus_id", $orderStatusesId);
                });
            }

            if (Input::has('paymentStatuses')) {
                $paymentStatusesId = Input::get('paymentStatuses');
                $transactions = $transactions->whereHas("order", function ($q) use ($paymentStatusesId) {
                    $q->whereIn("paymentstatus_id", $paymentStatusesId);
                });
            }

            $transactionType = Input::get("transactionType");
            if (isset($transactionType) && strlen($transactionType) > 0) {
                if ($transactionType == 0)
                    $transactions = $transactions->where("cost", ">", 0);
                else if ($transactionType == 1)
                    $transactions = $transactions->where("cost", "<", 0);
            }

            $transactions = $transactions->get();

            if (isset($productsId) && !in_array(0, $productsId)) {
                $checkedOrderproducts = [];
                foreach ($transactions as $transaction) {
                    if (Input::has("checkoutStatusEnable")) {
                        $checkoutStatuses = Input::get("checkoutStatuses");
                        if (in_array(0, $checkoutStatuses)) {
                            $transactionOrderproducts = $transaction->order
                                ->orderproducts(Config::get("constants.ORDER_PRODUCT_TYPE_DEFAULT"))
                                ->where(function ($q) use ($productsId) {
                                    $q->whereIn("product_id", $productsId)
                                      ->whereNull("checkoutstatus_id");
                                })
                                ->get();
                        } else {
                            $transactionOrderproducts = $transaction->order
                                ->orderproducts(Config::get("constants.ORDER_PRODUCT_TYPE_DEFAULT"))
                                ->where(function ($q) use ($productsId) {
                                    $q->whereIn("product_id", $productsId);
                                })
                                ->get();
                        }
                    } else {
                        $transactionOrderproducts = $transaction->order->orderproducts()
                                                                       ->WhereNull("orderproducttype_id")
                                                                       ->whereIn("product_id", $productsId)
                                                                       ->get();
                    }

                    $cost = 0;
                    $extraCost = 0;
                    if ($transactionOrderproducts->isNotEmpty()) {
                        $orderDiscount = $transaction->order->discount;
                        $donateProducts = array_merge(Product::DONATE_PRODUCT, [Product::CUSTOM_DONATE_PRODUCT]);
                        $numOfOrderproducts = $transaction->order
                            ->orderproducts(Config::get("constants.ORDER_PRODUCT_TYPE_DEFAULT"))
                            ->whereNotIn("product_id", $donateProducts)
                            ->count();
                        $orderDiscountPerItem = $orderDiscount / $numOfOrderproducts;
                        $orderSuccessfulTransaction = $transaction->order
                            ->transactions;

                        //ToDo : Main wallet
                        $giftPaymentMethods = [config("constants.PAYMENT_METHOD_WALLET")];
                        $orderChunk = 1; //For wallet
                        if (isset($paymentMethodsId)) {
                            $paymentMethodsDiff = array_diff($giftPaymentMethods, $paymentMethodsId);
                            if (empty($paymentMethodsDiff)) {
                                $paymentMethodsDiffReverse = array_diff($paymentMethodsId, $giftPaymentMethods);
                                if (empty($paymentMethodsDiffReverse)) {
                                    $orderChunk = $numOfOrderproducts;
                                }
                            }

                        }

                        $orderWalletTransactionSum = 0;
                        if (!empty($paymentMethodsDiff))
                            $orderWalletTransactionSum = $orderSuccessfulTransaction->where("paymentmethod_id", config("constants.PAYMENT_METHOD_WALLET"))
                                                                                    ->sum("cost");

                        if (isset($transactionStatusFilter))
                            $orderSuccessfulTransaction = $orderSuccessfulTransaction->whereIn("transactionstatus_id", $transactionStatusFilter);

                        if (isset($paymentMethodsId))
                            $orderSuccessfulTransaction = $orderSuccessfulTransaction->whereIn("paymentmethod_id", $paymentMethodsId);

                        $orderSuccessfulTransactionPaidSum = $orderSuccessfulTransaction->where("cost", ">", 0)
                                                                                        ->sum("cost");

                        $orderSuccessfulTransactionRefundSum = $orderSuccessfulTransaction->where("cost", "<", 0)
                                                                                          ->sum("cost");

                        $orderRefundPerItem = $orderSuccessfulTransactionRefundSum / $numOfOrderproducts; // it is a negative number
                        $orderWalletUsePerItem = $orderWalletTransactionSum / $numOfOrderproducts;

                        foreach ($transactionOrderproducts as $orderproduct) {
                            if (in_array($orderproduct->id, $checkedOrderproducts))
                                continue;
                            $orderproductCost = (int)($orderproduct->obtainOrderproductCost(false)["totalPrice"]);

                            $orderproductCost = $orderproductCost - $orderDiscountPerItem;
                            $orderproductCost = $orderproductCost + $orderRefundPerItem;
                            if (($orderSuccessfulTransactionPaidSum / $orderChunk) > $orderproductCost) {
                                $orderproductCost = $orderproductCost - $orderWalletUsePerItem;
                                $cost += $orderproductCost;
                                $orderSuccessfulTransactionPaidSum = $orderSuccessfulTransactionPaidSum - $orderproductCost;
                            } else {
                                $cost += ($orderSuccessfulTransactionPaidSum / $numOfOrderproducts);
                                $orderSuccessfulTransactionPaidSum = 0;
                            }

                            if (isset($extraAttributevaluesId))
                                $extraCost = $orderproduct->getExtraCost($extraAttributevaluesId);
                            else
                                $extraCost = $orderproduct->getExtraCost();

                            array_push($checkedOrderproducts, $orderproduct->id);
                        }
                    }
                    $transactionOrderproductCost->put($transaction->id, [
                        "cost"      => $cost,
                        "extraCost" => $extraCost,
                    ]);

                }
                $transactionOrderproductTotalCost = number_format($transactionOrderproductCost->sum("cost"));
                $transactionOrderproductTotalExtraCost = number_format($transactionOrderproductCost->sum("extraCost"));
            }

            $totaolCost = number_format($transactions->sum("cost"));
            return json_encode(
                [
                    'index'                      => View::make('transaction.index', compact('transactions', "transactionOrderproductCost"))
                                                        ->render(),
                    "totalCost"                  => $totaolCost,
                    "orderproductTotalCost"      => $transactionOrderproductTotalCost,
                    "orderproductTotalExtraCost" => $transactionOrderproductTotalExtraCost,
                ], JSON_UNESCAPED_UNICODE);
        }
        catch (\Exception    $e) {
            $message = "unexpected error";
            return $this->response
                ->setStatusCode(503)
                ->setContent([
                                 "message" => $message,
                                 "error"   => $e->getMessage(),
                                 "line"    => $e->getLine(),
                                 "file"    => $e->getFile(),
                             ]);
        }
    }

    /**
     * Show the form for creating a new resource  = making the request to payment gateway
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /** checks whether the order belongs to the user or not
     *
     * @param  \app\Order
     *
     * @return boolean
     */
    private function checkOrderAuthority(Order $order)
    {
        if ($order->user_id == Auth::user()->id)
            return true;
        else
            return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditTransactionRequest $request
     * @param  \App\Transaction                          $transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EditTransactionRequest $request, Transaction $transaction)
    {
        $transaction->fill($request->all());
        if (strlen($transaction->referenceNumber) == 0)
            $transaction->referenceNumber = null;
        if (strlen($transaction->traceNumber) == 0)
            $transaction->traceNumber = null;
        if (strlen($transaction->transactionID) == 0)
            $transaction->transactionID = null;
        if (strlen($transaction->authority) == 0)
            $transaction->authority = null;
        if (strlen($transaction->paycheckNumber) == 0)
            $transaction->paycheckNumber = null;
        if (strlen($transaction->managerComment) == 0)
            $transaction->managerComment = null;
        if (strlen($transaction->paymentmethod_id) == 0)
            $transaction->paymentmethod_id = null;

        if ($request->has("deadline_at") && strlen($request->get("deadline_at")) > 0) {
            $deadline_at = Carbon::parse($request->get("deadline_at"))
                                 ->addDay()
                                 ->format('Y-m-d');
            $transaction->deadline_at = $deadline_at;
        }

        if ($request->has("completed_at") && strlen($request->get("completed_at")) > 0) {
            $completed_at = Carbon::parse($request->get("completed_at"))
                                  ->addDay()
                                  ->format('Y-m-d');
            $transaction->completed_at = $completed_at;
        }

//        if ($transaction->update()) {
        if ($this->modify($transaction)) {
            if ($request->ajax() || $request->has("apirequest")) {
                return $this->response->setStatusCode(200);

            } else {
                session()->put("success", "تراکنش با موفقیت اصلاح شد");
                return redirect()->back();
            }
        } else {
            if ($request->ajax() || $request->has("apirequest")) {
                return $this->response->setStatusCode(503);
            } else {
                session()->put("success", "خطای پایگاه داده");
                return redirect()->back();
            }
        }
    }

    public function modify(Transaction $transaction) {
        return $transaction->update();
    }
    /**
     * Store a newly created resource in storage
     *
     * @param  \app\Http\Requests\InsertTransactionRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(InsertTransactionRequest $request)
    {
        $result = $this->storeTransaction($request->all());

        return response()->json([
            'error' => $result['message']
        ], $result['statusCode']);

        /*$result['statusCode'] = Response::HTTP_OK;
        $result['message'] = $transactionMessage;
        $result['transaction'] = $transaction;
        $result['order'] = $order;*/
    }

    public function storeTransaction($data)
    {
        $result = [
            'statusCode' => Response::HTTP_OK,
            'message' => '',
            'transaction' => null
        ];

        $order = Order::find($data["order_id"]);

        if(!isset($order)) {
            $result = [
                'statusCode' => Response::HTTP_NOT_FOUND,
                'message' => 'سفارش شما یافت نشد.',
                'transaction' => null
            ];
            return $result;
        }
        /**
         *  Check to find whether it comes from admin panel or user panel
         */
        $previousRoute = app('router')
            ->getRoutes()
            ->match(app('request')->create(URL::previous()))
            ->getName();
        if (strcmp($previousRoute, "order.edit") == 0) {
            $comesFromAdmin = true;
        } else {
            $comesFromAdmin = false;
        }
        /**
         *  end
         */

        if (isset($data["comesFromAdmin"])) {
            if (!Auth::user()->can(Config::get("constants.INSERT_TRANSACTION_ACCESS"))) {
                $result['statusCode'] = Response::HTTP_FORBIDDEN;
                $result['message'] = "سفارش مورد نظر متعلق به شما نمی باشد";
                return $result;
            }
            $comesFromAdmin = true;
        }

        /**
         * Check the order authority
         */
        if (!$comesFromAdmin && !$this->checkOrderAuthority($order)) {
            $result['statusCode'] = Response::HTTP_FORBIDDEN;
            $result['message'] = "سفارش مورد نظر متعلق به شما نمی باشد";
            return $result;
        }
        /**
         *  end
         */

        $newTransaction = true;
        /**
         *  For inserting online transactions
         */
        if (isset($data["authority"])) {
            $transaction = Transaction::where("authority", $data["authority"])->first();
            if (isset($transaction)) {
                $newTransaction = false;
            }
        }
        /**
         *  end
         */
        if ($newTransaction) {
            $transaction = new Transaction();
            $transaction->fill($data);
        } else {
            /**
             *  For inserting online transactions
             */

            if($result['statusCode'] != Response::HTTP_OK) {
                return $result;
            }

            if ($transaction->order->user->id != $order->user->id) {
                $result['statusCode'] = Response::HTTP_FORBIDDEN;
                $result['message'] = "تراکنشی با این شماره Authority قبلا برای شخص دیگری ثبت شده است";
                return $result;
            }
            if ($data["cost"] != $transaction->cost) {
                $result['statusCode'] = Response::HTTP_FORBIDDEN;
                $result['message'] = "مبلغ وارد شده با تراکنش تصدیق نشده ای که یافت شد همخوانی ندارد";
                return $result;
            }
            /**
             * Verifying transactions
             **/
            $zarinGate = Transactiongateway::where('name', 'zarinpal')->first();
            $merchant = $zarinGate->merchantNumber;
            $zarinPal = new Zarinpal($merchant, new SoapDriver(), "zarinGate");
            $result = $zarinPal->verifyWithExtra($transaction->cost, $transaction->authority);
            if (strcmp($result["Status"], "success") == 0) {
                $transaction->transactionID = $result["RefID"];
                $transaction->order_id = $order->id;
                $transaction->transactionstatus_id = Config::get("constants.TRANSACTION_STATUS_SUCCESSFUL");
                if ($transaction->update()) {
                    $result['statusCode'] = Response::HTTP_OK;
                    $result['message'] = "تراکنش با موفقیت تصدیق شد و اطلاعات آن ثبت گردید";
                    $result['transaction'] = $transaction;
                    return $result;
                } else {
                    $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $result['message'] = "خطای پایگاه داده در ثبت تراکنش";
                    return $result;
                }
            } else if (strcmp($result["Status"], "verified before") == 0) {
                $transaction->transactionID = $result["RefID"];
                $transaction->order_id = $order->id;
                $transaction->transactionstatus_id = Config::get("constants.TRANSACTION_STATUS_SUCCESSFUL");
                if ($transaction->update()) {
                    $result['statusCode'] = Response::HTTP_OK;
                    $result['message'] = "این تراکنش قبلا تصدیق شده بود. اطلاعات تراکنش ثبت شد";
                    $result['transaction'] = $transaction;
                    return $result;
                } else {
                    $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $result['message'] = "خطای پایگاه داده در ثبت تراکنش";
                    return $result;
                }
            } else if (strcmp($result["Status"], "error") == 0) {
                $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                $result['message'] = "پاسخ سرویس دهنده خطای " . $result["error"] . " می باشد";
                return $result;
            } else {
                $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                $result['message'] = "پاسخ نامعتبر از سرویس دهنده";
                return $result;
            }


            /**
             *  end
             */
        }

        if (strlen($transaction->referenceNumber) == 0)
            $transaction->referenceNumber = null;
        if (strlen($transaction->traceNumber) == 0)
            $transaction->traceNumber = null;
        if (strlen($transaction->transactionID) == 0)
            $transaction->transactionID = null;
        if (strlen($transaction->authority) == 0)
            $transaction->authority = null;
        if (strlen($transaction->paycheckNumber) == 0)
            $transaction->paycheckNumber = null;
        $gateway = $data["gateway"];//for requests coming from checkout/payment and user order list

        if (isset($data["completed_at"]) && strlen($data["completed_at"]) > 0) {
            $completed_at = Carbon::parse($data["completed_at"])
                ->format('Y-m-d');
            $transaction->completed_at = $completed_at;

        } else {
            $transaction->completed_at = Carbon::now();
        }

        if (isset($data["deadline_at"]) && strlen($data["deadline_at"]) > 0) {
            $deadline_at = Carbon::parse($data["deadline_at"])
                ->format('Y-m-d');
            $transaction->deadline_at = $deadline_at;
            $transaction->completed_at = null;
        }
        if ($transaction->save()) {
            /**
             *  An online transaction
             */
            if (isset($gateway)) {
                $result['statusCode'] = Response::HTTP_OK;
                $result['message'] = "تراکنش با موفقیت ثبت شد.";
                $result['transaction'] = $transaction;
                return $result;
            } /**
             *  An offline transaction
             */
            else {
                if (!$comesFromAdmin)
                    if ($order->totalPaidCost() >= (int)$order->totalCost()) {
                        $order->paymentstatus_id = Config::get("constants.PAYMENT_STATUS_PAID");
                        $transactionMessage = "تراکنش شما با موفقیت درج شد.مسئولین سایت در اسرع وقت اطلاعات بانکی ثبت شده را بررسی خواهند کرد  و سفارش شما را تایید خواهند نمود. سفارش شما در حال حاضر در وضعیت منتظر تایید می باشد.";
                    } else {
                        $order->paymentstatus_id = Config::get("constants.PAYMENT_STATUS_INDEBTED");
                        $transactionMessage = "تراکنش شما با موفقیت درج شد.مسئولین سایت در اسرع وقت اطلاعات بانکی ثبت شده را بررسی خواهند کرد  و تراکنش شما را تایید خواهند نمود.";
                    }
                else $transactionMessage = "تراکنش با موفقیت درج شد";
                $order->timestamps = false;
                if (!$order->update()) {
                    $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $result['message'] = "خطای پایگاه داده در به روز رسانی سفارش شما";
                    $result['transaction'] = $transaction;
                    return $result;
                }
                $order->timestamps = true;

                $result['statusCode'] = Response::HTTP_OK;
                $result['message'] = $transactionMessage;
                $result['transaction'] = $transaction;
                $result['order'] = $order;
                return $result;
            }
        } else {
            $result['statusCode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $result['message'] = "خطای پایگاه داده در ثبت تراکنش";
            return $result;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction $transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $transactionPaymentmethods = Paymentmethod::pluck('displayName', 'id')
                                                  ->toArray();
        $transactionStatuses = Transactionstatus::where("name", "<>", "transferredToPay")
                                                ->orderBy("order")
                                                ->pluck('displayName', 'id')
                                                ->toArray();
        if (isset($transaction->deadline_at)) {
            $deadlineAt = Carbon::parse($transaction->deadline_at)
                                ->format('Y-m-d');
        }
        if (isset($transaction->completed_at)) {
            $completedAt = Carbon::parse($transaction->completed_at)
                                 ->format('Y-m-d');
        }

        return view("transaction.edit", compact('transaction', 'transactionPaymentmethods', 'transactionStatuses', '$transactionStatuses', 'deadlineAt', 'completedAt'));
    }

    /**
     * Limited update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Transaction         $transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function limitedUpdate(Request $request, Transaction $transaction)
    {
        $order = Order::FindOrFail($request->get("order_id"));
        if (!$this->checkOrderAuthority($order))
            abort(404);
        if ($order->id != $transaction->order_id)
            abort(404);

        $editRequest = new EditTransactionRequest();

        $paymentImplied = false;
        if ($request->has("referenceNumber")) {
            $editRequest->offsetSet("referenceNumber", $request->get("referenceNumber"));
            $paymentImplied = true;
        }
        if ($request->has("traceNumber")) {
            $editRequest->offsetSet("traceNumber", $request->get("traceNumber"));
            $paymentImplied = true;
        }

        if ($request->has("paymentmethod_id")) {
            $editRequest->offsetSet("paymentmethod_id", $request->get("paymentmethod_id"));
        }

        if ($paymentImplied) {
            $editRequest->offsetSet("transactionstatus_id", Config::get("constants.TRANSACTION_STATUS_PENDING"));
            $editRequest->offsetSet("completed_at", Carbon::now());
            $editRequest->offsetSet("apirequest", true);
            $response = $this->update($editRequest, $transaction);
            if ($response->getStatusCode() == 200) {
                session()->put("success", "تراکنش با موفقیت ثبت شد");
            } else if ($response->getStatusCode() == 503) {
                session()->put("error", "خطای پایگاه داده ، لطفا مجددا اقدام نمایید.");
            } else {
                session()->put("error", "خطای نا مشخص");
            }
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \app\Transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->delete())
            session()->put('success', 'تراکنش با موفقیت حذف شد');
        else session()->put('error', 'خطای پایگاه داده');
        //        $transaction->delete();
        return response([
                            'sessionData' => session()->all(),
                        ]);
    }

    public function getUnverifiedTransactions()
    {
        try {
            $zarinGate = Transactiongateway::all()
                                           ->where('name', 'zarinpal')
                                           ->first();
            $merchant = $zarinGate->merchantNumber;
            $zarinPal = new Zarinpal($merchant, new SoapDriver(), "zarinGate");
            $result = $zarinPal->unverifiedTransactions();
            $transactions = collect();
            if (!isset($result["error"])) {
                $authorities = json_decode($result["Authorities"]);

                foreach ($authorities as $authority) {
                    $transaction = Transaction::where("authority", $authority->Authority)
                                              ->first();
                    $firstName = "";
                    $lastName = "";
                    $mobile = "";
                    $created_at = "";
                    if (isset($transaction)) {
                        $created_at = $transaction->created_at;
                        $user = $transaction->order->user;
                        if (isset($user)) {
                            $userId = $user->id;
                            $firstName = $user->firstName;
                            $lastName = $user->lastName;
                            $mobile = $user->mobile;
                        }
                    }

                    $transactions->push([
                                            "userId"     => (isset($userId)) ? $userId : null,
                                            "firstName"  => $firstName,
                                            "lastName"   => $lastName,
                                            "mobile"     => $mobile,
                                            "authority"  => $authority->Authority,
                                            "amount"     => $authority->Amount,
                                            "created_at" => $created_at,
                                        ]);
                }
            } else {
                $error = $result["error"];
            }
            $pageName = "admin";
            return view("transaction.unverifiedTransactions", compact("transactions", "error", 'pageName'));
        }
        catch (\Exception    $e) {
            $message = "unexpected error";
            return $this->response
                ->setStatusCode(503)
                ->setContent([
                                 "message" => $message,
                                 "error"   => $e->getMessage(),
                                 "line"    => $e->getLine(),
                                 "file"    => $e->getFile(),
                             ]);
        }
    }

    public function convertToDonate(Transaction $transaction)
    {
        if ($transaction->cost < 0 && !isset($transaction->traceNumber)) {
            $order = Order::FindOrFail($transaction->order->id);
            $donateOrderproduct = new Orderproduct();
            $donateOrderproduct->order_id = $order->id;
            $donateOrderproduct->product_id = 182;
            $donateOrderproduct->cost = -$transaction->cost;
            if ($donateOrderproduct->save()) {
                if ($transaction->forceDelete()) {
                    $newOrder = Order::where("id", $order->id)
                                     ->get()
                                     ->first();
                    $orderCostArray = $newOrder->obtainOrderCost(true, false, "REOBTAIN");
                    $newOrder->cost = $orderCostArray["rawCostWithDiscount"];
                    $newOrder->costwithoutcoupon = $orderCostArray["rawCostWithoutDiscount"];
                    if ($newOrder->update()) {
                        return $this->response->setStatusCode(200)
                                              ->setContent(["message" => "عملیات تبدیل با موفقیت انجام شد."]);
                    } else {
                        return $this->response->setStatusCode(503)
                                              ->setContent(["message" => "خطا در بروز رسانی سفارش . لطفا سفارش را دستی اصلاح نمایید."]);
                    }


                } else {
                    return $this->response->setStatusCode(503)
                                          ->setContent(["message" => "خطا در بروز رسانی تراکنش . لطفا تراکنش را دستی اصلاح نمایید."]);
                }
            } else {
                return $this->response->setStatusCode(503)
                                      ->setContent(["message" => "خطا در ایجاد آیتم کمک مالی . لطفا دوباره اقدام نمایید."]);
            }
        } else {
            return $this->response->setStatusCode(503)
                                  ->setContent(["message" => "این تراکنش بازگشت هزینه نمی باشد"]);
        }
    }

    public function completeTransaction(\Illuminate\Http\Request $request, Transaction $transaction)
    {
        if (!isset($transaction->traceNumber)) {
            $transaction->traceNumber = $request->get("traceNumber");
            $transaction->paymentmethod_id = Config::get("constants.PAYMENT_METHOD_ATM");
            $transaction->managerComment = $transaction->managerComment . "شماره کارت مقصد: \n" . $request->get("managerComment");
            if ($transaction->update()) {
                return $this->response->setStatusCode(200)
                                      ->setContent(["message" => "اطلاعات تراکنش با موفقیت ذخیره شد"]);
            } else {
                return $this->response->setStatusCode(503)
                                      ->setContent(["message" => "خطا در ذخیره اطلاعات . لفطا مجددا اقدام نمایید"]);
            }
        }
    }

}
