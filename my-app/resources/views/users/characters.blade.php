@extends('layouts.app')

@section('title', 'Персонажи пользователя ' . $user->name)

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Персонажи пользователя: {{ $user->name }}</h1>
        <a href="javascript:history.back()" class="btn btn-secondary">Назад</a>
    </div>
    <div>
        @auth
            @if(auth()->user()->isFriendWith($user))
                <span class="badge bg-success">Ваш друг</span>
            @endif
        @endauth
    </div>
    @if($characters->isEmpty())
        <div class="alert alert-info">
            У пользователя {{ $user->name }} пока нет персонажей.
        </div>
    @else
        <div class="row">
            @foreach($characters as $character)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ $character->image }}" class="card-img-top" alt="{{ $character->name }}" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $character->name }}</h5>
                            <p class="card-text">{{ Str::limit($character->description, 100) }}</p>
                            <p class="card-text"><small class="text-muted">{{ $character->type }}</small></p>
                            
                            <div class="btn-group">
                                <a href="{{ route('characters.show', $character) }}" class="btn btn-sm btn-info">
                                    Подробнее
                                </a>
                                
                                @can('update-character', $character)
                                    <a href="{{ route('characters.edit', $character) }}" class="btn btn-sm btn-warning">
                                        Редактировать
                                    </a>
                                    <form action="{{ route('characters.destroy', $character) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">
                                            Удалить
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    <div class="mt-4">
        <p>Всего персонажей: {{ $characters->count() }}</p>
        <p>Email пользователя: {{ $user->email }}</p>
        <p>Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</p>
    </div>
</div>
@include('friends._actions', ['user' => $user])
@endsection