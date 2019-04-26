<?php

namespace App\Http\Controllers\Web;

use App\Block;
use App\Classes\SEO\SeoDummyTags;
use App\Http\Controllers\Controller;
use App\Slideshow;
use App\Traits\MetaCommon;
use App\Websitesetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopPageController extends Controller
{
    use MetaCommon;

    public function __construct(Websitesetting $setting)
    {
        $this->setting = $setting->setting;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {

        $url = $request->url();
        $this->generateSeoMetaTags(new SeoDummyTags($this->setting->site->seo->homepage->metaTitle, $this->setting->site->seo->homepage->metaDescription, $url,
            $url, route('image', [
                'category' => '11',
                'w' => '100',
                'h' => '100',
                'filename' => $this->setting->site->siteLogo,
            ]), '100', '100', null));


        $blocks = Block::getShopBlocks();
        $slides = Slideshow::getShopBanner();

        if (request()->expectsJson()) {
            return response()->json([
                'mainBanner' => $slides,
                'block' => [
                    'current_page' => 1,
                    'data' => $blocks,
                    'first_page_url' => null,
                    'from' => 1,
                    'last_page' => 1,
                    'last_page_url' => null,
                    'next_page_url' => null,
                    'path' => $url,
                    'per_page' => $blocks->count() + 1,
                    'prev_page_url' => null,
                    'to' => $blocks->count(),
                    'total' => $blocks->count(),
                ],
            ]);
        }
        $pageName = "shop";
        return view('pages.shop', compact('pageName', 'blocks', 'slides'));
    }
}
