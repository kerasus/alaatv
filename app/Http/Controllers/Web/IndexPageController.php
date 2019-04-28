<?php

namespace App\Http\Controllers\Web;

use App\Block;
use App\Classes\Format\BlockCollectionFormatter;
use App\Classes\SEO\SeoDummyTags;
use App\Http\Controllers\Controller;
use App\Slideshow;
use App\Traits\MetaCommon;
use App\Websitesetting;
use Illuminate\Http\Request;

class IndexPageController extends Controller
{
    use MetaCommon;
    
    private $setting;
    
    public function __construct(Websitesetting $setting)
    {
        $this->setting = $setting->setting;
    }
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request                      $request
     * @param  \App\Classes\Format\BlockCollectionFormatter  $blockCollectionFormatter
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, BlockCollectionFormatter $blockCollectionFormatter)
    {
       
        $url = $request->url();
        $this->generateSeoMetaTags(new SeoDummyTags($this->setting->site->seo->homepage->metaTitle,
            $this->setting->site->seo->homepage->metaDescription, $url,
            $url, route('image', [
                'category' => '11',
                'w'        => '100',
                'h'        => '100',
                'filename' => $this->setting->site->siteLogo,
            ]), '100', '100', null));
        
        $slides = Slideshow::getMainBanner();
        $blocks = Block::getMainBlocks();
        if (request()->expectsJson()) {
            return response()->json([
                'mainBanner' => $slides,
                'block'      => [
                    'current_page'   => 1,
                    'data'           => $blocks,
                    'first_page_url' => null,
                    'from'           => 1,
                    'last_page'      => 1,
                    'last_page_url'  => null,
                    'next_page_url'  => null,
                    'path'           => $url,
                    'per_page'       => $blocks->count() + 1,
                    'prev_page_url'  => null,
                    'to'             => $blocks->count(),
                    'total'          => $blocks->count(),
                ],
            ]);
        }
        $sections = $blockCollectionFormatter->format($blocks);
        $pageName = "dashboard";
        return view('pages.dashboard1', compact('pageName', 'sections', 'slides'));
    }
}
