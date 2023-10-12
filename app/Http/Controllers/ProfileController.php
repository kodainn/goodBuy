<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\TblFollow;
use App\Models\TblFollower;
use App\Models\TblPost;
use App\Models\TblTypeDiv;
use App\Models\TblUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loginUser = Auth::user();
        $typeGenreDiv = TblTypeDiv::where('type_name', '=', 'genre')->get();
        $typeGenreDivKv = [];
        foreach($typeGenreDiv as $col) {
            $typeGenreDivKv[$col['type_detail_div']] = $col['type_detail_name'];
        }
        $user = TblUser::with(['follows', 'followers'])->withCount(['follows', 'followers'])->where('user_uuid', '=', Auth::id())->first();
        $posts = TblPost::with(['images' => function($query) {
                    $query->orderBy('image_sort', 'asc');
                }, 'goods'])
                ->withCount('goods')
                ->where('user_uuid', '=', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        $postCount = TblPost::where('user_uuid', '=', Auth::id())->count();
        $follows = TblFollow::with('users')->withCount('users')->where('user_uuid', '=', Auth::id())->get();
        $followers = TblFollower::with('users')->withCount('users')->where('user_uuid', '=', Auth::id())->get();
        //$posts['postCount'] = $postCount;
        return Inertia::render('Profile/Index', [
            'selfProfile' => true,
            'loginUser' => $loginUser,
            'user' => $user,
            'posts' => $posts,
            'typeGenreDivKv' => $typeGenreDivKv,
            'follows' => $follows,
            'followers' => $followers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        if($request->updateIconFlg) {
            $newFilePath = $request->iconPath->store('public/icon');
            $newFilePath = '/storage/icon/' . explode('/', $newFilePath)[2];
            DB::table('tbl_user')->where('user_uuid', '=', Auth::id())->update([
                'nick_name' => $request->nickName,
                'pr' => $request->pr,
                'icon_path' => $newFilePath
            ]);
            return;
        }
        DB::table('tbl_user')->where('user_uuid', '=', Auth::id())->update([
            'nick_name' => $request->nickName,
            'pr' => $request->pr,
        ]);
    }

    public function followStore($user_uuid)
    {
        DB::table('tbl_follow')->insert([
            'follow_uuid' => Uuid::uuid4(),
            'user_uuid' => Auth::id(),
            'follow_user_uuid' => $user_uuid
        ]);
        DB::table('tbl_follower')->insert([
            'follower_uuid' => Uuid::uuid4(),
            'user_uuid' => $user_uuid,
            'follower_user_uuid' => Auth::id()
        ]);

        $followers = TblFollower::with('users')->withCount('users')->where('user_uuid', '=', $user_uuid)->get();

        return response()->json($followers);
    }

    public function followDelete($user_uuid)
    {
        DB::table('tbl_follow')->where('user_uuid', '=', Auth::id())->where('follow_user_uuid', '=', $user_uuid)->delete();
        DB::table('tbl_follower')->where('user_uuid', '=', $user_uuid)->where('follower_user_uuid', '=', Auth::id())->delete();

        $followers = TblFollower::with('users')->withCount('users')->where('user_uuid', '=', $user_uuid)->get();

        return response()->json($followers);
    }

    public function show($user_uuid)
    {
        if($user_uuid === Auth::id()) {
            return redirect()->route('profile.index');
        }
        $loginUser = Auth::user();
        $typeGenreDiv = TblTypeDiv::where('type_name', '=', 'genre')->get();
        $typeGenreDivKv = [];
        foreach($typeGenreDiv as $col) {
            $typeGenreDivKv[$col['type_detail_div']] = $col['type_detail_name'];
        }
        $user = TblUser::with(['follows', 'followers'])->withCount(['follows', 'followers'])->where('user_uuid', '=', $user_uuid)->first();
        $posts = TblPost::with('images')->where('user_uuid', '=', $user_uuid)->get();
        $postCount = TblPost::where('user_uuid', '=', $user_uuid)->count();
        $follows = TblFollow::with('users')->withCount('users')->where('user_uuid', '=', $user_uuid)->get();
        $allFollows = TblFollow::all();
        $followers = TblFollower::with('users')->withCount('users')->where('user_uuid', '=', $user_uuid)->get();
        return Inertia::render('Profile/Index', [
            'loginUser'=> $loginUser,
            'user' => $user,
            'posts' => $posts,
            'typeGenreDivKv' => $typeGenreDivKv,
            'follows' => $follows,
            'allFollows' => $allFollows,
            'followers' => $followers
        ]);
    }
}
