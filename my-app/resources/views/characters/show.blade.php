<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $character->name }} - Детальная информация</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        .character-details {
            min-height: 400px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="https://avatars.mds.yandex.net/i?id=e5f9483464194c8bc568ccf408002b90_sr-8081694-images-thumbs&n=13"
                    alt="Логотип сайта">
                <span class="ms-2">Детальная информация</span>
            </a>
            <div class="d-flex align-items-center">
                <a href="/" class="btn btn-custom">На главную</a>
            </div>
        </div>
    </nav>

    <main class="container my-4 character-details">
        <div class="row">
            <div class="col-md-6">
<div class="text-center mb-4">
    <img src="{{ $character->image }}" alt="{{ $character->name }}" 
         class="img-fluid rounded character-detail-image">
</div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h1 class="card-title">{{ $character->name }}</h1>
                        <p class="text-muted fs-5">Тип: <span class="badge bg-primary">{{ $character->type }}</span></p>
                        
                        <div class="character-description mt-4">
                            <h4>Описание:</h4>
                            <p class="fs-5">{{ $character->description }}</p>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('characters.edit', $character) }}" class="btn btn-warning">Редактировать</a>
                            <form action="{{ route('characters.destroy', $character) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить персонажа?')">Удалить</button>
                            </form>
                            <a href="/" class="btn btn-secondary">На главную</a>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> Создан: {{ $character->created_at->format('d.m.Y H:i') }}<br>
                                <i class="fas fa-history"></i> Обновлен: {{ $character->updated_at->format('d.m.Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-custom py-3 mt-auto">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 fw-bold">Денисов Артём</p>
                <a href="/" class="btn btn-sm btn-outline-light">На главную</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>