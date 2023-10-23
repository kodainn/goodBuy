<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Repositories\Follow\TblFollowRepository;
use App\Repositories\Follower\TblFollowerRepository;
use App\Repositories\Post\TblPostRepository;
use App\Repositories\TypeDiv\TblTypeDivRepository;
use App\Repositories\User\TblUserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProfileController extends Controller
{

    private $tblPostRepository;
    private $tblUserRepository;
    private $tblFollowRepository;
    private $tblTypeDivRepository;
    private $tblFollowerRepository;

    public function __construct(
        TblPostRepository $tblPostRepository,
        TblUserRepository $tblUserRepository,
        TblFollowRepository $tblFollowRepository,
        TblTypeDivRepository $tblTypeDivRepository,
        TblFollowerRepository $tblFollowerRepository
    )
    {
        $this->tblPostRepository = $tblPostRepository;
        $this->tblUserRepository = $tblUserRepository;
        $this->tblFollowRepository = $tblFollowRepository;
        $this->tblTypeDivRepository = $tblTypeDivRepository;
        $this->tblFollowerRepository = $tblFollowerRepository;
    }

    public function index()
    {

        $typeGenreDivKv = $this->tblTypeDivRepository->getGenreKvAll();
        $user = $this->tblUserRepository->getUserWithChildFirst(Auth::id());
        $posts = $this->tblPostRepository->getPostWithChildOfUserClip(Auth::id());
        $follows = $this->tblFollowRepository->getFollowWithUser(Auth::id());
        $followers = $this->tblFollowerRepository->getFollowerWithUser(Auth::id());

        $counts = [
            'postCount' => $this->tblPostRepository->getPostCountOfUser(Auth::id()),
            'followCount' => $this->tblFollowRepository->getFollowCountOfUser(Auth::id()),
            'followerCount' => $this->tblFollowerRepository->getFollowerCountOfUser(Auth::id()) 
        ];

        return Inertia::render('Profile/Index', [
            'selfProfile' => true,
            'loginUser' => Auth::user(),
            'user' => $user,
            'posts' => $posts,
            'typeGenreDivKv' => $typeGenreDivKv,
            'follows' => $follows,
            'followers' => $followers,
            'counts' => $counts,
        ]);
    }

    
    public function getMorePost($user_uuid, $moreCount)
    {
        $posts = $this->tblPostRepository->getPostWithChildOfUserClip($user_uuid, $moreCount);

        return response()->json($posts);
    }


    public function update(ProfileRequest $request)
    {
        $request['userUuid'] = Auth::id();
        $this->tblUserRepository->updateUser($request);
        $request->session()->regenerateToken();
    }


    public function followStore($user_uuid)
    {
        $follow = $this->tblFollowRepository->getFollowFirst(Auth::id(), $user_uuid);
        if(!empty($follow)) {return $this->tblFollowerRepository->getFollowerWithUser($user_uuid);}
        try {
            $this->tblFollowRepository->insertFollow(Auth::id(), $user_uuid);
            $this->tblFollowerRepository->insertFollower($user_uuid, Auth::id());

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        } finally {
            return response()->json([
                'follower' => $this->tblFollowerRepository->getFollowerWithUser($user_uuid),
                'followerCount' => $this->tblFollowerRepository->getFollowerCountOfUser($user_uuid)
            ]);
        }
    }


    public function followDelete($user_uuid)
    {
        $follow = $this->tblFollowRepository->getFollowFirst(Auth::id(), $user_uuid);
        if(empty($follow)) {return $this->tblFollowerRepository->getFollowerWithUser($user_uuid);}

        try {
            $this->tblFollowRepository->deleteFollow(Auth::id(), $user_uuid);
            $this->tblFollowerRepository->deleteFollower($user_uuid, Auth::id());

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        } finally {
            return response()->json([
                'follower' => $this->tblFollowerRepository->getFollowerWithUser($user_uuid),
                'followerCount' => $this->tblFollowerRepository->getFollowerCountOfUser($user_uuid)
            ]);
        }
    }


    public function show($user_uuid)
    {
        if($user_uuid === Auth::id()) {
            return redirect()->route('profile.index');
        }

        $typeGenreDivKv = $this->tblTypeDivRepository->getGenreKvAll();
        $user = $this->tblUserRepository->getUserWithChildFirst($user_uuid);
        $posts = $this->tblPostRepository->getPostWithChildOfUserClip($user_uuid);
        $follows = $this->tblFollowRepository->getFollowWithUser($user_uuid);
        $allFollows = $this->tblFollowRepository->getFollowAll();
        $followers = $this->tblFollowerRepository->getFollowerWithUser($user_uuid);

        $counts = [
            'postCount' => $this->tblPostRepository->getPostCountOfUser($user_uuid),
            'followCount' => $this->tblFollowRepository->getFollowCountOfUser($user_uuid),
            'followerCount' => $this->tblFollowerRepository->getFollowerCountOfUser($user_uuid) 
        ];

        return Inertia::render('Profile/Index', [
            'loginUser'=> Auth::user(),
            'user' => $user,
            'posts' => $posts,
            'typeGenreDivKv' => $typeGenreDivKv,
            'follows' => $follows,
            'allFollows' => $allFollows,
            'followers' => $followers,
            'counts' => $counts
        ]);
    }
}
