<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php if (! empty(trim($__env->yieldContent('meta_tags')))): ?>
        <?php echo $__env->yieldContent('meta_tags'); ?>
    <?php else: ?>
        <title><?php echo e(config('app.name', 'Laravel')); ?> - Кулинарная книга</title>
        <meta name="description" content="Лучшие кулинарные рецепты с подробным описанием, фото и пошаговыми инструкциями. Готовьте с удовольствием!">
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
  />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
  
    <!-- Schema.org разметка -->
    <?php if (! empty(trim($__env->yieldContent('schema_org')))): ?>
        <?php echo $__env->yieldContent('schema_org'); ?>
    <?php endif; ?>
    
    <!-- Open Graph / Facebook -->
    <?php if (! empty(trim($__env->yieldContent('meta_tags')))): ?>
    <?php else: ?>
        <meta property="og:title" content="<?php echo e(config('app.name')); ?> - Кулинарная книга">
        <meta property="og:description" content="Лучшие кулинарные рецепты с подробным описанием, фото и пошаговыми инструкциями">
        <meta property="og:url" content="<?php echo e(url('/')); ?>">
        <meta property="og:type" content="website">
    <?php endif; ?>
    
    <!-- Twitter -->
    <?php if (! empty(trim($__env->yieldContent('meta_tags')))): ?>
    <?php else: ?>
        <meta name="twitter:card" content="summary">
    <?php endif; ?>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
 
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?php echo e(url('/')); ?>">
                    <i class="fas fa-utensils me-2 text-primary"></i>
                    <span><?php echo e(config('app.name', 'Laravel')); ?></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('recipes.index') ? 'active' : ''); ?>" href="<?php echo e(route('recipes.index')); ?>">
                                <i class="fas fa-book-open me-1"></i> <?php echo e(__('Рецепты')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('categories.index') ? 'active' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">
                                <i class="fas fa-tags me-1"></i> <?php echo e(__('Категории')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('search') ? 'active' : ''); ?>" href="<?php echo e(route('search')); ?>">
                                <i class="fas fa-search me-1"></i> <?php echo e(__('Поиск')); ?>

                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                       

                        <!-- Authentication Links -->
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('login')); ?>">
                                        <i class="fas fa-sign-in-alt me-1"></i> <?php echo e(__('Войти')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>">
                                        <i class="fas fa-user-plus me-1"></i> <?php echo e(__('Регистрация')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.recipes.index')); ?>">
                                    <i class="fas fa-cog me-1"></i> <?php echo e(__('Админка')); ?>

                                </a>
                            </li>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo e(request()->routeIs('admin.parser.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.parser.index')); ?>">
                                            <i class="fas fa-code me-1"></i> <?php echo e(__('Парсер')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-user-circle me-1"></i> <?php echo e(Auth::user()->name); ?>

                                        <?php if(auth()->user()->isAdmin()): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php endif; ?>
                                    </a>
                                    <!-- ...existing code... -->
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <?php if(session('success')): ?>
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        
        <footer class="bg-light py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="mb-3">О проекте</h5>
                        <p class="text-muted">Яедок.ру - ваша онлайн кулинарная книга с тысячами разнообразных рецептов.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Быстрые ссылки</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo e(route('recipes.index')); ?>" class="text-decoration-none">Все рецепты</a></li>
                            <li><a href="<?php echo e(route('categories.index')); ?>" class="text-decoration-none">Категории</a></li>
                            <li><a href="<?php echo e(route('search')); ?>" class="text-decoration-none">Поиск</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Связаться с нами</h5>
                        <p class="text-muted">
                            <i class="fas fa-envelope me-2"></i> <a href="mailto:w1nishko@yandex.ru">w1nishko@yandex.ru</a><br>
                            <i class="fas fa-phone me-2"></i> <a href="tel:+7 904 448-22-83
">+7 904 448-22-83
</a>
                        </p>
                        
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="mb-0 text-muted">&copy; <?php echo e(date('Y')); ?> Я едок. Все права защищены.</p>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Добавление футера с правовыми ссылками -->
        <footer class="footer mt-auto py-3 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Я едок')); ?>. Все права защищены.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.terms')); ?>" class="text-muted">Пользовательское соглашение</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.disclaimer')); ?>" class="text-muted">Отказ от ответственности</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.dmca')); ?>" class="text-muted">Правообладателям</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Кнопка прокрутки вверх -->
        <button id="back-to-top" class="btn btn-primary rounded-circle position-fixed" style="bottom: 20px; right: 20px; display: none; width: 45px; height: 45px;">
            <i class="fas fa-arrow-up"></i>
        </button>
        
        <script>
            // Скрипт для кнопки прокрутки вверх
            document.addEventListener('DOMContentLoaded', function() {
                const backToTopButton = document.getElementById('back-to-top');
                
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopButton.style.display = 'flex';
                        backToTopButton.style.alignItems = 'center';
                        backToTopButton.style.justifyContent = 'center';
                    } else {
                        backToTopButton.style.display = 'none';
                    }
                });
                
                backToTopButton.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
                
                // Инициализация бургер-меню для мобильной версии
                const navbarToggler = document.querySelector('.navbar-toggler');
                if (navbarToggler) {
                    navbarToggler.addEventListener('click', function() {
                        const target = document.querySelector(this.getAttribute('data-bs-target'));
                        if (target) {
                            if (target.classList.contains('show')) {
                                target.classList.remove('show');
                            } else {
                                target.classList.add('show');
                            }
                        }
                    });
                }
                
                // Инициализация всех выпадающих меню, включая navbarDropdown
                const dropdownToggleElements = document.querySelectorAll('.dropdown-toggle');
                if (dropdownToggleElements.length > 0) {
                    dropdownToggleElements.forEach(dropdown => {
                        dropdown.addEventListener('click', function(event) {
                            event.preventDefault();
                            const dropdownMenu = this.nextElementSibling;
                            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                                if (dropdownMenu.classList.contains('show')) {
                                    dropdownMenu.classList.remove('show');
                                } else {
                                    // Закрываем все открытые меню перед открытием нового
                                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                                        menu.classList.remove('show');
                                    });
                                    dropdownMenu.classList.add('show');
                                }
                            }
                        });
                    });
                    
                    // Закрываем выпадающие меню при клике вне них
                    document.addEventListener('click', function(event) {
                        if (!event.target.closest('.dropdown')) {
                            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                                menu.classList.remove('show');
                            });
                        }
                    });
                }
            });
        </script>
        
        <!-- Добавляем поддержку Bootstrap JS напрямую, на случай если Vite не загрузил его корректно -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
                crossorigin="anonymous"></script>
                
        <!-- Добавляем необходимые JS библиотеки после основных скриптов -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // Инициализация интерактивного поиска на всех страницах
            document.addEventListener('DOMContentLoaded', function() {
                // Инициализация автозаполнения для поиска в хедере
                const searchInput = document.getElementById('global-search-input');
                if (searchInput) {
                    initAutocomplete(searchInput, '<?php echo e(route('search.autocomplete')); ?>');
                }
                
                // Track search result clicks
                const searchResultLinks = document.querySelectorAll('.search-result-link');
                if (searchResultLinks.length > 0) {
                    searchResultLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            const recipeId = this.dataset.recipeId;
                            const searchQuery = this.dataset.searchQuery;
                            
                            if (recipeId && searchQuery) {
                                // Send async request to record click
                                fetch('<?php echo e(route('search.record-click')); ?>', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        recipe_id: recipeId,
                                        query: searchQuery
                                    })
                                });
                            }
                        });
                    });
                }
                
                // Функция для инициализации автозаполнения
                function initAutocomplete(inputElement, url) {
                    if (!inputElement) return;
                    
                    // Создаем контейнер для подсказок
                    const autocompleteContainer = document.createElement('div');
                    autocompleteContainer.className = 'autocomplete-results d-none';
                    inputElement.parentNode.appendChild(autocompleteContainer);
                    
                    let timer = null;
                    
                    // Обработчик ввода
                    inputElement.addEventListener('input', function() {
                        const query = this.value.trim();
                        
                        if (timer) {
                            clearTimeout(timer);
                        }
                        
                        if (query.length < 2) {
                            autocompleteContainer.innerHTML = '';
                            autocompleteContainer.classList.add('d-none');
                            return;
                        }
                        
                        // Задержка для уменьшения количества запросов
                        timer = setTimeout(() => {
                            fetch(`${url}?query=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(data => {
                                    autocompleteContainer.innerHTML = '';
                                    
                                    if (data.length > 0) {
                                        autocompleteContainer.classList.remove('d-none');
                                        
                                        data.forEach(item => {
                                            const suggestionElement = document.createElement('div');
                                            suggestionElement.className = 'autocomplete-item';
                                            
                                            let iconClass = 'fa-book';
                                            if (item.type === 'category') {
                                                iconClass = 'fa-tag';
                                            } else if (item.type === 'ingredient') {
                                                iconClass = 'fa-mortar-pestle';
                                            }
                                            
                                            suggestionElement.innerHTML = `
                                                <a href="${item.url}" class="d-flex align-items-center p-2 text-decoration-none text-dark">
                                                    <i class="fas ${iconClass} me-2 text-primary"></i>
                                                    <span>${highlightMatch(item.text, query)}</span>
                                                </a>
                                            `;
                                            
                                            autocompleteContainer.appendChild(suggestionElement);
                                        });
                                        
                                        // Добавляем "Показать все результаты"
                                        const showAllElement = document.createElement('div');
                                        showAllElement.className = 'autocomplete-item show-all';
                                        showAllElement.innerHTML = `
                                            <a href="<?php echo e(route('search')); ?>?query=${encodeURIComponent(query)}" class="d-flex align-items-center p-2 text-decoration-none text-primary">
                                                <i class="fas fa-search me-2"></i>
                                                <span>Показать все результаты для "${query}"</span>
                                            </a>
                                        `;
                                        autocompleteContainer.appendChild(showAllElement);
                                    } else {
                                        autocompleteContainer.classList.add('d-none');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching autocomplete suggestions:', error);
                                });
                        }, 300);
                    });
                    
                    // Закрыть автозаполнение при клике вне
                    document.addEventListener('click', function(e) {
                        if (!inputElement.contains(e.target) && !autocompleteContainer.contains(e.target)) {
                            autocompleteContainer.classList.add('d-none');
                        }
                    });
                    
                    // Подсветка совпадений
                    function highlightMatch(text, query) {
                        const regex = new RegExp(query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'gi');
                        return text.replace(regex, match => `<mark>${match}</mark>`);
                    }
                }
            });
        </script>
        
        <style>
            .autocomplete-results {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                max-height: 300px;
                overflow-y: auto;
                background: #fff;
                border: 1px solid #ced4da;
                border-radius: 0 0 .25rem .25rem;
                z-index: 1050;
                box-shadow: 0 4px 6px rgba(0,0,0,.1);
            }
            
            .autocomplete-item:hover {
                background-color: #f8f9fa;
            }
            
            .autocomplete-item:not(:last-child) {
                border-bottom: 1px solid #f1f1f1;
            }
            
            .autocomplete-item.show-all {
                background-color: #f8f9fa;
                font-weight: 500;
            }
            
            mark {
                background-color: #ffffa0;
                padding: 0;
            }
        </style>
    </div>
    

</body>
</html>
<?php /**PATH C:\OSPanel\domains\eats\resources\views/layouts/app.blade.php ENDPATH**/ ?>