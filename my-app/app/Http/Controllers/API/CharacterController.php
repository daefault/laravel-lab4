<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CharacterResource;
class CharacterController extends Controller
{

    public function index(Request $request)
    {
        // Если передан user_id, показываем персонажей конкретного пользователя
        if ($request->has('user_id')) {
            $characters = Character::where('user_id', $request->user_id)->get();
        } else {
            // Иначе показываем всех персонажей
            $characters = Character::all();
        }

    return CharacterResource::collection($characters);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $character = Character::create([
            'name' => $request->name,
            'image' => $request->image,
            'type' => $request->type,
            'description' => $request->description,
            'user_id' => Auth::id(), // Привязываем к текущему пользователю
        ]);

        return response()->json([
            'message' => 'Персонаж успешно создан!',
            'data' => $character,
        ], 201);
    }


    public function show(Character $character)
    {
        // Проверяем, является ли владелец персонажа другом текущего пользователя
        $isFriend = false;
        if (Auth::check()) {
            $isFriend = Auth::user()->isFriendWith($character->user);
        }

        return (new CharacterResource($character->load('comments.user')))
        ->additional([
            'is_friend' => $isFriend,
            'comments_count' => $character->comments->count(),
        ]);
    }


    public function update(Request $request, Character $character)
    {
        // Проверка прав: только владелец или админ может редактировать
        if ($character->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на редактирование этого персонажа',
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $character->update($request->only(['name', 'image', 'type', 'description']));

        return response()->json([
            'message' => 'Персонаж успешно обновлен!',
            'data' => $character,
        ]);
    }

    public function destroy(Character $character)
    {
        // Проверка прав: только владелец или админ может удалять
        if ($character->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на удаление этого персонажа',
            ], 403);
        }

        $character->delete();

        return response()->json([
            'message' => 'Персонаж успешно удален (мягкое удаление)!',
        ]);
    }
}