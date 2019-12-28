<?php

namespace App\Http\Controllers\Api;

use App\Collection\OrderproductCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProduct\OrderProductStoreRequest;
use App\Orderproduct;
use App\Product;
use App\Traits\OrderCommon;
use App\Traits\ProductCommon;
use Cache;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderproductController extends Controller
{
    use OrderCommon;
    use ProductCommon;

    private $orderproductController;

    function __construct(\App\Http\Controllers\Web\OrderproductController $orderproductController)
    {
        $this->middleware(['CheckPermissionForSendOrderId',], ['only' => ['store', 'storeV2'],]);
        $this->orderproductController = $orderproductController;
    }

    public function store(OrderProductStoreRequest $request)
    {
        if ($request->has('extraAttribute')) {
            if (!$request->user()
                ->can(config("constants.ATTACH_EXTRA_ATTRIBUTE_ACCESS"))) {
                $productId = $request->get('product_id');
                $product = Product::findOrFail($productId);
                $attributesValues = $this->orderproductController->getAttributesValuesFromProduct($request, $product);
                $this->orderproductController->syncExtraAttributesCost($request, $attributesValues);
                $request->offsetSet('parentProduct', $product);
            }
        }

        $result        = $this->orderproductController->new($request->all());
        $orderproducts = new OrderproductCollection();
        if (isset($result['data']['storedOrderproducts'])) {
            $orderproducts = $result['data']['storedOrderproducts'];
        }

        if ($orderproducts->isEmpty()) {
            $responseContent = [
                'error' => [
                    'code'    => Response::HTTP_NOT_MODIFIED,
                    'message' => 'No orderproducts added to the order',
                ],
            ];
        }
        else {
            $responseContent = [
                'orderproducts' => $orderproducts,
            ];
        }

        return response($responseContent, Response::HTTP_OK);
    }

    public function storeV2(OrderProductStoreRequest $request)
    {
        if ($request->has('extraAttribute')) {
            if (!$request->user()
                ->can(config("constants.ATTACH_EXTRA_ATTRIBUTE_ACCESS"))) {
                $productId = $request->get('product_id');
                $product = Product::findOrFail($productId);
                $attributesValues = $this->orderproductController->getAttributesValuesFromProduct($request, $product);
                $this->orderproductController->syncExtraAttributesCost($request, $attributesValues);
                $request->offsetSet('parentProduct', $product);
            }
        }

        $this->orderproductController->new($request->all());

        return response()->json(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request      $request
     * @param Orderproduct $orderproduct
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function destroy(Request $request, Orderproduct $orderproduct)
    {
        $authenticatedUser = $request->user('api');
        $orderUser         = optional(optional($orderproduct)->order)->user;

        if ($authenticatedUser->id != $orderUser->id) {
            abort(Response::HTTP_FORBIDDEN, 'Orderproduct does not belong to this user.');
        }

        $orderproduct_userbons = $orderproduct->userbons;
        foreach ($orderproduct_userbons as $orderproduct_userbon) {
            $orderproduct_userbon->usedNumber       = $orderproduct_userbon->usedNumber - $orderproduct_userbon->pivot->usageNumber;
            $orderproduct_userbon->userbonstatus_id = config("constants.USERBON_STATUS_ACTIVE");
            if ($orderproduct_userbon->usedNumber >= 0) {
                $orderproduct_userbon->update();
            }
        }

        if ($orderproduct->delete()) {
            foreach ($orderproduct->children as $child) {
                $child->delete();
            }

            Cache::tags([
                'order_'.$orderproduct->order_id.'_products' ,
                'order_'.$orderproduct->order_id.'_orderproducts' ,
                'order_'.$orderproduct->order_id.'_cost' ,
                'order_'.$orderproduct->order_id.'_bon' ])->flush();

            return response([
                'message' => 'Orderproduct removed successfully',
            ], Response::HTTP_OK);
        } else {
            return response([
                'message' => 'Database error on removing orderproduct',
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    public function destroyV2(Request $request, Orderproduct $orderproduct)
    {
        $authenticatedUser = $request->user('api');
        $orderUser = optional(optional($orderproduct)->order)->user;

        if ($authenticatedUser->id != $orderUser->id) {
            abort(Response::HTTP_FORBIDDEN, 'Orderproduct does not belong to this user.');
        }

        $orderproduct_userbons = $orderproduct->userbons;
        foreach ($orderproduct_userbons as $orderproduct_userbon) {
            $orderproduct_userbon->usedNumber = $orderproduct_userbon->usedNumber - $orderproduct_userbon->pivot->usageNumber;
            $orderproduct_userbon->userbonstatus_id = config("constants.USERBON_STATUS_ACTIVE");
            if ($orderproduct_userbon->usedNumber >= 0) {
                $orderproduct_userbon->update();
            }
        }

        if ($orderproduct->delete()) {
            foreach ($orderproduct->children as $child) {
                $child->delete();
            }

            Cache::tags([
                'order_' . $orderproduct->order_id . '_products',
                'order_' . $orderproduct->order_id . '_orderproducts',
                'order_' . $orderproduct->order_id . '_cost',
                'order_' . $orderproduct->order_id . '_bon',
            ])->flush();

            return response()->json(null);
        } else {
            return response([
                'error' => [
                    'message' => 'Database error on removing orderproduct',
                ],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
