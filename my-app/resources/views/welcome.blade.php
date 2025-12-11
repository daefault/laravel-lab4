<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа 3</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="https://avatars.mds.yandex.net/i?id=e5f9483464194c8bc568ccf408002b90_sr-8081694-images-thumbs&n=13"
                    alt="Логотип сайта">
                <span class="ms-2">Персонажи из м/с "Губка Боб Квадратные штаны"</span>
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('characters.create') }}" class="btn btn-custom">Добавить персонажа</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container my-4">
            <h1 class="text-center mb-4 text-primary">Персонажи и их описание</h1>
            <div class="row g-4">
                @php
                    use App\Models\character;
                    $characters = Character::all();
                @endphp
                
                @foreach($characters as $character)
                <div class="col-12 col-xs-12 col-sm-6 col-md-6 col col-lg-4 col-xl-3 col-xxl-3 col-xxxl-2">
<div class="card card-custom-{{ ($loop->iteration - 1) % 5 + 1 }} character-card h-100">
                        <div class="position-relative">
                            <img src="{{ $character->image }}" alt="{{ $character->name }}"
                                class="card-img-top img-fluid character-image">
                            <span class="character-label">{{ $character->type }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $character->name }}</h5>
                            <p class="card-text">{{ Str::limit($character->description, 100) }}</p>
                            <a href="{{ route('characters.show', $character) }}" 
                               class="btn btn-outline-primary mt-auto">Подробнее</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <footer class="footer-custom mt-5 py-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 fw-bold">Денисов Артём</p>
                <div class="social-links d-flex gap-3">
                    <a href="#">
                        <img src="https://files.kick.com/images/channel-links/1989500/image/f6eae775-865c-4bf4-b5a3-2646337efbe2"
                            alt="Телеграм">
                    </a>
                    <a href="#">
                        <img src="https://i.pinimg.com/736x/df/1f/86/df1f86cc1471c2053b3f246f96b967a1.jpg"
                            alt="Вконтакте">
                    </a>
                    <a href="#">
                        <img src="https://apofiz-media.s3.amazonaws.com/media/5760e4de6eb19160/ddac3d97-bef8-4885-9815-61857774084c.jpeg"
                            alt="Эл.почта">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="/js/app.js"></script>
</body>
</html>