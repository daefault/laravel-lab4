<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Character;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\FriendshipController;

Route::get('/my-characters', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $characters = auth()->user()->characters()->get();
    return view('characters.my', compact('characters'));
})->middleware('auth')->name('characters.my');

Route::get('/', function () {
    $characters = Character::all();
    return view('welcome', compact('characters'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/users/{user}/characters', function ($identifier) {
        $user = \App\Models\User::where('username', $identifier)->first();

        if (!$user) {
            $user = \App\Models\User::find($identifier);
        }

        if (!$user) {
            abort(404, 'Пользователь не найден');
        }

        $characters = $user->characters()->get();
        return view('users.characters', [
            'user' => $user,
            'characters' => $characters
        ]);
    })->name('users.characters');
    Route::get('/users', function () {
        $users = \App\Models\User::withCount('characters')->get();
        return view('users.index', compact('users'));
    })->name('users.index');

    Route::resource('characters', CharacterController::class)->except(['index']);

    Route::post('/characters/{character}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])
        ->name('comments.edit');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', [FriendshipController::class, 'friends'])->name('index');
        Route::get('/requests', [FriendshipController::class, 'requests'])->name('requests');
        Route::get('/feed', [FriendshipController::class, 'feed'])->name('feed');

        Route::post('/{user}/send', [FriendshipController::class, 'sendRequest'])->name('send');
        Route::post('/{user}/accept', [FriendshipController::class, 'acceptRequest'])->name('accept');
        Route::post('/{user}/reject', [FriendshipController::class, 'rejectRequest'])->name('reject');
        Route::delete('/{user}/remove', [FriendshipController::class, 'removeFriend'])->name('remove');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/trash', function () {
            if (!Gate::allows('view-trash', auth()->user())) {
                abort(403, 'Доступ запрещен');
            }
            $characters = Character::onlyTrashed()->with('user')->get();
            return view('admin.trash', compact('characters'));
        })->name('trash');

        Route::put('/characters/{id}/restore', [CharacterController::class, 'restore'])
            ->name('characters.restore');

        Route::delete('/characters/{id}/force-delete', [CharacterController::class, 'forceDelete'])
            ->name('characters.forceDelete');

        Route::put('/comments/{id}/restore', [CommentController::class, 'restore'])
            ->name('comments.restore');

        Route::delete('/comments/{id}/force-delete', [CommentController::class, 'forceDelete'])
            ->name('comments.forceDelete');
    });
    Route::prefix('profile')->group(function () {
        Route::get('/tokens', [App\Http\Controllers\API\TokenController::class, 'index'])
            ->name('tokens.index');
        Route::post('/tokens', [App\Http\Controllers\API\TokenController::class, 'store'])
            ->name('tokens.store');
        Route::delete('/tokens/{tokenId}', [App\Http\Controllers\API\TokenController::class, 'destroy'])
            ->name('tokens.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__ . '/auth.php';