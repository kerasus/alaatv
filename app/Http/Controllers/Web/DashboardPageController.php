<?php

namespace App\Http\Controllers\Web;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class DashboardPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request, User $user)
    {
        if ($request->user()->id != $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'you can\'nt get user '.$user->id.' dashboard!.');
        }
        $pageName             = "shop";
        $userAssetsCollection = $user->getDashboardBlocks();
        $userFavoritesCollection = $user->getDashboardBlocks();

        if ($request->expectsJson()) {
            return response()->json([
                'user_id'   => $user->id,
                'data'      => $userAssetsCollection,
            ]);
        }

        return view('user.dashboard', compact('user', 'pageName', 'userAssetsCollection' , 'userFavoritesCollection'));
    }
}
