<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::with(['user', 'character'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'character_id' => 'required|exists:characters,id',
        ]);

        // ЧЕРЕЗ DB (работает)
        $id = \DB::table('comments')->insertGetId([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id ?? 1,
            'character_id' => $request->input('character_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $comment = Comment::with(['user', 'character'])->find($id);
        
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'character'])->findOrFail($id);
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);
        
        $request->validate([
            'content' => 'sometimes|string|min:3|max:1000',
        ]);
        
        \DB::table('comments')
            ->where('id', $id)
            ->update([
                'content' => $request->input('content', $comment->content),
                'updated_at' => now(),
            ]);
            
        $comment = Comment::with(['user', 'character'])->find($id);
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Comment::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Комментарий удален'
        ]);
    }
}