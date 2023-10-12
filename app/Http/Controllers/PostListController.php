<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\TblPost;
use App\Models\TblTypeDiv;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class PostListController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loginUser = Auth::user();
        $typeDiv = TblTypeDiv::where('type_name', '=', 'genre')->get();
        $typeDivKv = [];
        foreach($typeDiv as $col) {
            $typeDivKv[$col['type_detail_div']] = $col['type_detail_name'];
        }
        $posts = TblPost::with(['images' => function ($query) {
            $query->orderBy('image_sort', 'asc');
        }, 'goods'])->withCount('goods')->get();
        return Inertia::render('PostList/Index', [
            'loginUser' => $loginUser,
            'typeDivKv' => $typeDivKv,
            'posts' => $posts
        ]);
    }

    public function searchGenre($genre)
    {
        $posts = TblPost::where('genre_div', '=', $genre)->with(['images' => function ($query) {
            $query->orderBy('image_sort', 'asc');
        }, 'goods'])->withCount('goods')->get();

        return response()->json($posts);
    }

    public function getPost()
    {
        $posts = TblPost::with(['images' => function ($query) {
            $query->orderBy('image_sort', 'asc');
        }, 'goods'])->withCount('goods')->get();

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        try {
            DB::beginTransaction();

            $post_uuid = Uuid::uuid4();
            DB::table('tbl_post')->insert([
            'post_uuid' => $post_uuid,
            'user_uuid' => Auth::id(),
            'title' => $request->title,
            'review' => $request->review,
            'genre_div' => $request->genreDiv
            ]);
            if(!empty($request->imageList)) {
                $insertList = [];
                $image_sort = 1;
                foreach($request->imageList as $image) {
                    $newFilePath = $image[0]->store('public/img');
                    $newFileName = explode('/', $newFilePath)[2];
                    $insertList[] = [
                        'post_image_uuid' => Uuid::uuid4(),
                        'post_uuid' => $post_uuid,
                        'post_image_path' => '/storage/img/' . $newFileName,
                        'image_sort' => $image_sort,
                    ];
                    $image_sort++;
                }
                DB::table('tbl_post_image')->insert($insertList);
            }

            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
            echo 'エラーメッセージ' . $e->getMessage();
            var_dump($e->getMessage());exit;
        }
    }

    public function goodStore($post_uuid)
    {
        DB::table('tbl_post_good')->insert([
            'post_good_uuid' => Uuid::uuid4(),
            'post_uuid' => $post_uuid,
            'user_uuid' => Auth::id()
        ]);
    }

    public function goodDelete($post_uuid)
    {
        DB::table('tbl_post_good')->where('post_uuid', '=', $post_uuid)->where('user_uuid', '=', Auth::id())->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($post_uuid)
    {
        $loginUser = Auth::user();
        $post = TblPost::where('post_uuid', '=', $post_uuid)->with(['images', 'user', 'goods'])->withCount('goods')->first();
        return Inertia::render('PostList/Show', [
            'post' => $post,
            'loginUser' => $loginUser
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($post_uuid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
