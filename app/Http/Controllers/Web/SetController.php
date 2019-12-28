<?php

namespace App\Http\Controllers\Web;

use App\Adapter\AlaaSftpAdapter;
use App\Classes\Search\ContentsetSearch;
use App\Content;
use App\Contentset;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContentsetIndexRequest;
use App\Http\Requests\InsertContentsetRequest;
use App\Http\Resources\ContentInSet2 as ContentResource;
use App\Http\Resources\Set as SetResource;
use App\Traits\FileCommon;
use App\Traits\MetaCommon;
use App\Traits\ProductCommon;
use App\Traits\RequestCommon;
use App\Websitesetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Traits
    |--------------------------------------------------------------------------
    */

    use ProductCommon;
    use RequestCommon;
    use MetaCommon;
    use FileCommon;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $setting;

    public function __construct(Websitesetting $setting)
    {
        $this->setting = $setting->setting;
        $authException = $this->getAuthExceptionArray();
        $this->callMiddlewares($authException);
    }

    /**
     * @return array
     */
    private function getAuthExceptionArray(): array
    {
        $authException = [
            'index',
            'show',
        ];

        return $authException;
    }

    /**
     * @param $authException
     */
    private function callMiddlewares($authException): void
    {
        $this->middleware('auth', ['except' => $authException]);
        $this->middleware('permission:' . config('constants.REMOVE_CONTENT_SET_ACCESS'), [
            'only' => [
                'destroy',
            ],
        ]);
        $this->middleware('permission:' . config('constants.EDIT_CONTENT_SET_ACCESS'), [
            'only' => [
                'update',
            ],
        ]);

        $this->middleware('permission:' . config('constants.INSERT_CONTENT_SET_ACCESS'), [
            'only' => [
                'store',
            ],
        ]);

        $this->middleware('permission:' . config('constants.LIST_CONTENT_SET_ACCESS'), [
            'only' => [
//                'index',
            ],
        ]);

        $this->middleware('permission:' . config('constants.LIST_CONTENTS_OF_CONTENT_SET_ACCESS'), [
            'only' => [
                'indexContent',
            ],
        ]);

        $this->middleware('permission:' . config('constants.SHOW_CONTENT_SET_ACCESS'), [
            'only' => [
                'edit',
                //                'show',
            ],
        ]);


    }

    /**
     * Display a listing of the resource.
     *
     * @param ContentsetIndexRequest $request
     * @param ContentsetSearch       $setSearch
     *
     * @return Response
     */
    public function index(ContentsetIndexRequest $request, ContentsetSearch $setSearch)
    {

        $tags     = $request->get('tags');
        $filters  = $request->all();
        $pageName = 'setPage';

        $sets = $setSearch->setPageName($pageName)
            ->get($filters);

        if (request()->expectsJson()) {
            return response()->json([
                'result' => $sets,
                'tags'   => $tags,
            ]);
        }
        return view('set.index', compact('sets', 'tags'));
    }

    public function indexContent(Request $request, Contentset $set)
    {
        $contents = optional($set->contents)->sortBy('order');
        return view('set.listContents', compact('set', 'contents'));
    }

    public function store(InsertContentsetRequest $request)
    {
        $contentSet = new Contentset();
        $this->fillContentFromRequest($request->all(), $contentSet);

        if ($contentSet->save()) {

            if ($request->has('products')) {
                $products = $request->get('products');
                if ($products === null) {
                    $products = [];
                }

                $this->syncProducts($products, $contentSet);
            }

            session()->put('success', 'دسته با موفقیت درج شد . شماره دسته : ' . $contentSet->id);
            return redirect()->back();
        }

        session()->put('error', 'خطای پایگاه داده');
        return redirect()->back();
    }

    /**
     * @param array      $inputData
     * @param Contentset $contentSet
     *
     * @return void
     */
    private function fillContentFromRequest(array $inputData, Contentset $contentSet): void
    {
        $enabled   = Arr::has($inputData, 'enable');
        $display   = Arr::has($inputData, 'display');
        $tagString = Arr::get($inputData, 'tags');

        $contentSet->fill($inputData);
        $contentSet->tags = convertTagStringToArray($tagString);

        $contentSet->enable  = $enabled ? 1 : 0;
        $contentSet->display = $display ? 1 : 0;

        if (Arr::has($inputData, 'photo')) {
            $this->storePhotoOfSet($contentSet, Arr::get($inputData, 'photo'));
        }

        if (isset($contentSet->redirectUrl)) {
            $contentSet->display = 0;
        }
    }

    private function storePhotoOfSet(Contentset $contentSet, $file): void
    {
        $extension = $file->getClientOriginalExtension();
        $fileName  =
            basename($file->getClientOriginalName(), '.' . $extension) . '_' . date('YmdHis') . '.' . $extension;
        $disk      = Storage::disk(config('constants.DISK23'));
        /** @var AlaaSftpAdapter $adaptor */
        if ($disk->put($fileName, File::get($file))) {
            $fullPath          = $disk->getAdapter()
                ->getRoot();
            $partialPath       = $this->getSubDirectoryInCDN($fullPath);
            $contentSet->photo =
                config('constants.DOWNLOAD_SERVER_PROTOCOL') . config('constants.CDN_SERVER_NAME') . '/' . $partialPath . $fileName;
        }
    }

    private function syncProducts(array $products, Contentset $contentSet)
    {
        foreach ($contentSet->products as $product) {
            Cache::tags(['product_' . $product->id . '_sets'])->flush();
        }

        $contentSet->products()->detach();
        $contentSet->products()->attach($products);

        foreach ($products as $productId) {
            Cache::tags(['product_' . $productId . '_sets'])->flush();
        }

    }

    public function update(Request $request, Contentset $contentSet)
    {
        $this->fillContentFromRequest($request->all(), $contentSet);

        if ($contentSet->update()) {

            if ($request->has('redirectAllContents')) {
                foreach ($contentSet->contents as $content) {
                    $content->update([
                        'redirectUrl' => $request->get('redirectUrl'),
                    ]);
                }
            }

            $products = $request->get('products');
            if (is_null($products))
                $products = [];

            if ($request->user()->can(config('constants.ADD_PRODUCT_TO_SET_ACCESS'))) {
                $this->syncProducts($products, $contentSet);
            }

            session()->put('success', 'دسته با موفقیت اصلاح شد');
            return redirect()->back();
        }

        session()->put('error', 'خطای پایگاه داده');
        return redirect()->back();
    }

    public function show(Request $request, Contentset $contentSet)
    {
        $user  = $request->user();
        $order = $request->get('order', 'asc');
        if (isset($contentSet->redirectUrl)) {
            return redirect($contentSet->redirectUrl, Response::HTTP_FOUND, $request->headers->all());
        }

        if ($request->expectsJson() && !$request->has('raheAbrisham')) {
            return response()->json($contentSet);
        }

        $contents = $contentSet->getActiveContents2();
        if ($order === 'desc') {
            $contents = $contents->sortByDesc('order');
        }


        // ToDo : To get sorted contents grouped by section
//        Note : can't add sortBy to this
//        $contents = $contentSet->active_contents_by_section;

        if ($contents->isEmpty()) {
            return redirect(route('web.home'));
        }

        $pamphlets = $contents->where('contenttype_id', Content::CONTENT_TYPE_PAMPHLET);
        $videos    = $contents->where('contenttype_id', Content::CONTENT_TYPE_VIDEO);

        if ($request->expectsJson()) {
            $files = [];
            if (isset($pamphlets) && $pamphlets->isNotEmpty()) {
                $files['pamphlets'] = ContentResource::collection($pamphlets);
            }

            if (isset($videos) && $videos->isNotEmpty()) {
                $files['videos'] = ContentResource::collection($videos);
            }

            return response()->json([
                'set'   => new SetResource($contentSet),
                'files' => $files,
            ]);
        }

        $jsonLdArray = $this->getJsonLdArray($videos, $pamphlets, $articles);

        $this->generateSeoMetaTags($contentSet);

        $isFavored =
            optional(optional(optional(optional($user)->favoredSets())->where('id', $contentSet->id))->get())->isNotEmpty();

        return view('set.show', compact('contentSet', 'videos', 'pamphlets', 'articles', 'jsonLdArray', 'order', 'isFavored'));
    }

    /**
     * @param $videos
     * @param $pamphlets
     * @param $articles
     *
     * @return array|null
     */
    private function getJsonLdArray($videos, $pamphlets, $articles): ?array
    {
        $jsonLdItems = [];
        if ($videos->isNotEmpty()) {
            foreach ($videos as $item) {
                $jsonLdItems[] = [
                    '@type'    => 'ListItem',
                    'position' => $item->order,
                    'url'      => action([ContentController::class, 'show'], $item),
                ];
            }
        } else if ($pamphlets->isNotEmpty()) {
            foreach ($pamphlets as $item) {
                $jsonLdItems[] = [
                    '@type'    => 'ListItem',
                    'position' => $item->order,
                    'url'      => action([ContentController::class, 'show'], $item),
                ];
            }
        } else if ($articles->isNotEmpty()) {
            foreach ($articles as $item) {
                $jsonLdItems[] = [
                    '@type'    => 'ListItem',
                    'position' => $item->order,
                    'url'      => action([ContentController::class, 'show'], $item),
                ];
            }
        }
        $jsonLdArray = null;
        if (!empty($jsonLdItems)) {
            $jsonLdArray = [
                '@context'        => 'https://schema.org',
                '@type'           => 'ItemList',
                'itemListElement' => $jsonLdItems,
            ];
        }
        return $jsonLdArray;
    }

    public function edit(Contentset $set)
    {
        $setProducts = $set->products()->whereNull('contentset_product.deleted_at')->get();
        $products    = $this->makeProductCollection();
        return view('set.edit', compact('set', 'setProducts', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $products = $this->makeProductCollection();
        return view('set.create', compact('products'));
    }
}
