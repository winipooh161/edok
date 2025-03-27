<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\RecipeParserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Страницы рецептов
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');

// Страницы категорий
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Поиск
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
Route::post('/search/record-click', [SearchController::class, 'recordClick'])->name('search.record-click');
Route::get('/home/autocomplete', [HomeController::class, 'autocomplete'])->name('home.autocomplete');

// Правовые страницы
Route::get('/disclaimer', [LegalController::class, 'disclaimer'])->name('legal.disclaimer');
Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/dmca', [LegalController::class, 'dmca'])->name('legal.dmca');
Route::post('/dmca/submit', [LegalController::class, 'dmcaSubmit'])->name('legal.dmca.submit');

// Генерация Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-main.xml', [App\Http\Controllers\SitemapController::class, 'main'])->name('sitemap.main');
Route::get('/sitemap-categories.xml', [App\Http\Controllers\SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-recipes.xml', [App\Http\Controllers\SitemapController::class, 'recipes'])->name('sitemap.recipes');
Route::get('/sitemap-users.xml', [App\Http\Controllers\SitemapController::class, 'users'])->name('sitemap.users');

// Аутентификация
Auth::routes();

// Профиль пользователя (доступно только авторизованным пользователям)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/user/{user}', [ProfileController::class, 'show'])->name('user.profile');
    
    // Добавляем маршрут для комментариев
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Админка: базовые функции для всех пользователей
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.recipes.index');
    });
    
    // Управление рецептами для всех авторизованных пользователей
    Route::resource('recipes', AdminRecipeController::class);
    
    // Функции только для администраторов
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', AdminCategoryController::class);
        
        // Функционал парсера только для администратора
        Route::get('/parser', [RecipeParserController::class, 'index'])->name('parser.index');
        Route::post('/parser/parse', [RecipeParserController::class, 'parse'])->name('parser.parse');
        Route::post('/parser/store', [RecipeParserController::class, 'store'])->name('parser.store');
        
        // Пакетный парсинг
        Route::get('/parser/batch', [RecipeParserController::class, 'batchIndex'])->name('parser.batch');
        Route::post('/parser/batch', [RecipeParserController::class, 'batchParse'])->name('parser.batchParse');
        Route::get('/parser/process-batch', [RecipeParserController::class, 'processBatch'])->name('parser.processBatch');
        
        // Сбор ссылок
        Route::get('/parser/collect-links', [RecipeParserController::class, 'collectLinksForm'])->name('parser.collectLinksForm');
        Route::post('/parser/collect-links', [RecipeParserController::class, 'collectLinks'])->name('parser.collectLinks');
    });
});
