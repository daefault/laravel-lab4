@extends('layouts.app')

@section('title', 'Мои API токены')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Управление API токенами</h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Создать новый токен</h5>
                        <form method="POST" action="{{ route('tokens.store') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" 
                                       name="name" 
                                       class="form-control" 
                                       placeholder="Название токена (например: Postman, Мобильное приложение)"
                                       required>
                                <button class="btn btn-primary" type="submit">Создать токен</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <h5>Мои токены</h5>
                        @if($tokens->isEmpty())
                            <div class="alert alert-info">
                                У вас нет созданных токенов.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Создан</th>
                                            <th>Последнее использование</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tokens as $token)
                                            <tr>
                                                <td>{{ $token->name }}</td>
                                                <td>{{ $token->created_at->format('d.m.Y H:i') }}</td>
                                                <td>{{ $token->last_used_at ? $token->last_used_at->format('d.m.Y H:i') : 'Никогда' }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('tokens.destroy', $token->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить токен?')">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h5>Как использовать API</h5>
                        <div class="alert alert-warning">
                            <strong>Внимание:</strong> Токен отображается только один раз при создании!
                        </div>
                        <p>Для доступа к API добавьте заголовок:</p>
                        <pre class="bg-light p-3 rounded"><code>Authorization: Bearer {ваш_токен}</code></pre>
                        
                        <p>Пример запроса через cURL:</p>
                        <pre class="bg-light p-3 rounded"><code>curl -H "Authorization: Bearer {ваш_токен}" \
     -H "Accept: application/json" \
     {{ url('/api/characters') }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection