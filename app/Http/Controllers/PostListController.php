<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Repositories\Post\TblPostGoodRepository;
use App\Repositories\Post\TblPostMessageRepository;
use App\Repositories\Post\TblPostRepository;
use App\Repositories\TypeDiv\TblTypeDivRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostListController extends Controller
{

    private $tblPostRepository;
    private $tblPostGoodRepository;
    private $tblTypeDivRepository;
    private $tblPostMessageRepository;

    public function __construct(
        TblTypeDivRepository $tblTypeDivRepository,
        TblPostGoodRepository $tblPostGoodRepository,
        TblPostRepository $tblPostRepository,
        TblPostMessageRepository $tblPostMessageRepository
    )
    {
        $this->tblPostGoodRepository = $tblPostGoodRepository;
        $this->tblTypeDivRepository = $tblTypeDivRepository;
        $this->tblPostRepository = $tblPostRepository;
        $this->tblPostMessageRepository = $tblPostMessageRepository;
    }


    public function index($page = 1)
    {
        $typeDivKv = $this->tblTypeDivRepository->getGenreKvAll();
        $posts = $this->tblPostRepository->getPostWithChildPaginate($page);
        $postCount = $this->tblPostRepository->getPostCount();
        $maxPage = intval($postCount / 50);
        if($postCount % 50 > 0) {
            $maxPage += 1;
        }

        return Inertia::render('PostList/Index', [
            'loginUser' => Auth::user(),
            'typeDivKv' => $typeDivKv,  
            'posts' => $posts,
            'currentPage' => (int) $page,
            'maxPage' => $maxPage
        ]);
    }


    public function getSearchPostPaginate($page, $genre)
    {
        $typeDivKv = $this->tblTypeDivRepository->getGenreKvAll();
        $searchPosts = $this->tblPostRepository->getSearchPostWithChildPaginate($genre, $page);
        $searchPostCount = $this->tblPostRepository->getSearchPostCount($genre);
        $maxPage = intval($searchPostCount / 50);
        if($searchPostCount % 50 > 0) {
            $maxPage += 1;
        }

        return Inertia::render('PostList/Index', [
            'loginUser' => Auth::user(),
            'typeDivKv' => $typeDivKv,  
            'posts' => $searchPosts,
            'currentPage' => (int) $page,
            'maxPage' => $maxPage,
            'genre' => (int) $genre,
        ]);
    }


    public function searchGenre($genre)
    {
        if((int) $genre === 0) {
            $posts = $this->tblPostRepository->getPostWithChildPaginate();
            $postCount = $this->tblPostRepository->getPostCount();
            $maxPage = intval($postCount / 50);
            if($postCount % 50 > 0) {
                $maxPage += 1;
            }

            return response()->json([
                'searchPosts' => $posts,
                'maxPage' => $maxPage
            ]);
        }

        $searchPosts = $this->tblPostRepository->getSearchPostWithChildPaginate($genre);
        $searchPostCount = $this->tblPostRepository->getSearchPostCount($genre);
        $maxPage = intval($searchPostCount / 50);
        if($searchPostCount % 50 > 0) {
            $maxPage += 1;
        }

        return response()->json([
            'searchPosts' => $searchPosts,
            'maxPage' => $maxPage,
            'genre' => (int) $genre
        ]);
    }


    public function getPost()
    {
        $posts = $this->tblPostRepository->getPostWithChildPaginate();

        return response()->json($posts);
    }


    public function store(PostRequest $request)
    {
        $request['user_uuid'] = Auth::id();
        $this->tblPostRepository->insertPostWithImages($request);
    }


    public function goodStore($post_uuid)
    {
        $postGood = $this->tblPostGoodRepository->getSearchPostGoodFirst($post_uuid, Auth::id());
        if(empty($postGood)) {
            $this->tblPostGoodRepository->insertPostGood($post_uuid, Auth::id());
        }
    }


    public function goodDelete($post_uuid)
    {
        $postGood = $this->tblPostGoodRepository->getSearchPostGoodFirst($post_uuid, Auth::id());
        if(!empty($postGood)) {
            $this->tblPostGoodRepository->deletePostGood($post_uuid, Auth::id());
        }
    }


    public function messageStore(Request $request, $post_uuid)
    {

        $this->tblPostMessageRepository->insertPostMessage($post_uuid, Auth::id(), $request['inputMessage']);
        $request->session()->regenerateToken();
        $message = $this->tblPostMessageRepository->getPostMessageWithUser($post_uuid);

        return response()->json($message);
    }


    public function show($post_uuid)
    {
        $post = $this->tblPostRepository->getPostWithChildFirst($post_uuid);
        $message = $this->tblPostMessageRepository->getPostMessageWithUser($post_uuid);
        $post['messages_count'] = $this->tblPostMessageRepository->getPostMessageCount($post_uuid);

        return Inertia::render('PostList/Show', [
            'post' => $post,
            'loginUser' => Auth::user(),
            'message' => $message,
            'backUrl' => url()->previous()
        ]);
    }


    public function delete($post_uuid)
    {
        $this->tblPostRepository->deletePost($post_uuid, Auth::id());
        $posts = $this->tblPostRepository->getPostWithChildPaginate();

        return response()->json($posts);
    }
}
