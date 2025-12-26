@extends('layouts.app')

@section('title', 'Корзина удаленных персонажей')

@section('content')
<div class="container">
    <h1 class="mb-4">🗑 Корзина удаленных персонажей</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($characters->isEmpty())
        <div class="alert alert-info">
            Корзина пуста
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Тип</th>
                        <th>Владелец</th>
                        <th>Удален</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($characters as $character)
                        <tr>
                            <td>{{ $character->id }}</td>
                            <td>{{ $character->name }}</td>
                            <td>{{ $character->type }}</td>
                            <td>{{ $character->user->name ?? 'Неизвестно' }}</td>
                            <td>{{ $character->deleted_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('admin.characters.restore', $character->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success" title="Восстановить">
                                            Восстановить
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.characters.forceDelete', $character->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Удалить навсегда? Это действие нельзя отменить!')"
                                                title="Удалить навсегда">
                                            Удалить навсегда
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-secondary">На главную</a>
    </div>
</div>
@endsection