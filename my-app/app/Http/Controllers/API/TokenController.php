<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class TokenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tokens = $user->tokens()->get();
        
        return view('profile.tokens', compact('tokens'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = $request->user()->createToken($request->name);

        return back()->with('success', 'Токен создан! Сохраните его: ' . $token->accessToken);
    }

    public function destroy($tokenId)
    {
        $token = Token::find($tokenId);
        
        if ($token && $token->user_id === Auth::id()) {
            $token->delete();
            return back()->with('success', 'Токен удален!');
        }

        return back()->with('error', 'Токен не найден или нет прав для удаления');
    }
}