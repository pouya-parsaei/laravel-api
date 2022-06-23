<?php

namespace App\Http\Controllers;

use App\Http\Requests\Articles\StoreRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']);
    }

    public function index()
    {
        $articles = Article::paginate(5);

        return response()->json(new ArticleCollection($articles));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        Article::create([
            'user_id' => 1,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $this->uploadImage($request)
        ]);

        return response()->json([
            'message' => 'created'
        ],201);
    }


    public function show($id)
    {
        $article = Article::findOrFail($id);

        return response()->json([
            'data' => new ArticleResource($article)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $article->update($request->all());

        return response()->json([
           'message' => 'updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            'message' => 'deleted'
    ]);
    }

    private function uploadImage($request)
    {
        return $request->hasFile('image')
            ? $request->image->store('public')
            : null;
    }
}
