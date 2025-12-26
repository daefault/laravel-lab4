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
        <!-- Проверяем, есть ли удаленные персонажи и является ли пользователь админом -->
        @php
            $trashedCount = $characters->whereNotNull('deleted_at')->count();
            $activeCount = $characters->whereNull('deleted_at')->count();
            $showTabs = auth()->check() && auth()->user()->is_admin && $trashedCount > 0;
        @endphp
        
        @if($showTabs)
            <!-- Табы для администратора -->
            <ul class="nav nav-tabs mb-4" id="charactersTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" 
                            data-bs-target="#active" type="button" role="tab">
                        Активные ({{ $activeCount }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="trashed-tab" data-bs-toggle="tab" 
                            data-bs-target="#trashed" type="button" role="tab">
                        Удаленные ({{ $trashedCount }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="charactersTabContent">
                <!-- Вкладка активных персонажей -->
                <div class="tab-pane fade show active" id="active" role="tabpanel">
                    <div class="row">
                        @foreach($characters->whereNull('deleted_at') as $character)
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
                                                    @csrf @method('DELETE')
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
                </div>
                
                <!-- Вкладка удаленных персонажей -->
                <div class="tab-pane fade" id="trashed" role="tabpanel">
                    <div class="row">
                        @foreach($characters->whereNotNull('deleted_at') as $character)
                            <div class="col-md-4 mb-4">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Удален</span>
                                            <small>{{ $character->deleted_at->format('d.m.Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <img src="{{ $character->image }}" class="card-img-top" alt="{{ $character->name }}" 
                                         style="height: 200px; object-fit: cover; opacity: 0.7;">
                                    <div class="card-body">
                                        <h5 class="card-title text-decoration-line-through">{{ $character->name }}</h5>
                                        <p class="card-text">{{ Str::limit($character->description, 100) }}</p>
                                        <p class="card-text"><small class="text-muted">{{ $character->type }}</small></p>
                                        
                                        <div class="btn-group">
                                            <form action="{{ route('admin.characters.restore', $character->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Восстановить</button>
                                            </form>
                                            <form action="{{ route('admin.characters.forceDelete', $character->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Удалить навсегда?')">
                                                    Удалить навсегда
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Обычный список (без табов) -->
            <div class="row">
                @foreach($characters->whereNull('deleted_at') as $character)
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
                                            @csrf @method('DELETE')
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
    @endif
    
    <div class="mt-4">
        <p>Всего персонажей: {{ $characters->count() }}</p>
        <p>Активных: {{ $characters->whereNull('deleted_at')->count() }}</p>
        @if(auth()->check() && auth()->user()->is_admin)
            <p>Удаленных: {{ $characters->whereNotNull('deleted_at')->count() }}</p>
        @endif
        <p>Email пользователя: {{ $user->email }}</p>
        <p>Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</p>
    </div>
</div>

<!-- Bootstrap JS для табов -->
@if(isset($showTabs) && $showTabs)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tabEl = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEl.forEach(function(tab) {
        tab.addEventListener('click', function (event) {
            event.preventDefault();
            new bootstrap.Tab(this).show();
        });
    });
});
</script>
@endif

@include('friends._actions', ['user' => $user])
@endsection