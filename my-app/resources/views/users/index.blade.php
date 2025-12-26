@extends('layouts.app')

@section('title', 'Все пользователи')

@section('content')
<div class="container">
    <h1 class="mb-4">Все пользователи</h1>
    
    @if($users->isEmpty())
        <div class="alert alert-info">
            Пользователей пока нет
        </div>
    @else
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; font-size: 1.5rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ms-3">
                                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    @if($user->is_admin)
                                        <span class="badge bg-danger">Администратор</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-1">
                                    <strong>Персонажей:</strong> 
                                    <span class="badge bg-secondary">{{ $user->characters_count }}</span>
                                </p>
                                <p class="mb-1">
                                    <strong>Username:</strong> {{ $user->username }}
                                </p>
                                <p class="mb-0">
                                    <strong>Зарегистрирован:</strong> 
                                    {{ $user->created_at->format('d.m.Y') }}
                                </p>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.characters', $user) }}" 
                                   class="btn btn-primary btn-sm">
                                    Посмотреть персонажей
                                </a>
                                
                                @auth
                                    @if(auth()->id() === $user->id)
                                        <span class="btn btn-outline-secondary btn-sm disabled">
                                            Это вы
                                        </span>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            <p class="text-muted">
                Всего пользователей: <strong>{{ $users->count() }}</strong> | 
                Всего персонажей: <strong>{{ $users->sum('characters_count') }}</strong>
            </p>
        </div>
    @endif
</div>

<style>
.avatar-placeholder {
    font-weight: bold;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection