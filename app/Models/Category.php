<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image_path'];

    /**
     * Автоматически создаем slug при создании категории, если он не указан
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Получить все рецепты, принадлежащие к этой категории
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
    
    /**
     * Количество опубликованных рецептов в категории
     */
    public function getPublishedRecipesCountAttribute()
    {
        return $this->recipes()->where('is_published', true)->count();
    }
    
    /**
     * Получить URL изображения категории или вернуть изображение по умолчанию
     */
    public function getImageUrl()
    {
        if (!empty($this->image_path)) {
            return asset($this->image_path);
        }
        
        // Возвращаем стандартные иконки для категорий
        $categoryIcons = [
            'завтрак' => 'breakfast.jpg',
            'обед' => 'lunch.jpg',
            'ужин' => 'dinner.jpg',
            'десерт' => 'dessert.jpg',
            'суп' => 'soup.jpg',
            'салат' => 'salad.jpg',
            'закуска' => 'appetizer.jpg',
            'напиток' => 'drink.jpg',
            'выпечка' => 'baking.jpg',
            'мясо' => 'meat.jpg',
            'рыба' => 'fish.jpg',
            'овощи' => 'vegetables.jpg',
        ];
        
        // Проверяем по ключевым словам в названии категории
        foreach ($categoryIcons as $keyword => $icon) {
            if (Str::contains(mb_strtolower($this->name), $keyword)) {
                return asset('images/categories/' . $icon);
            }
        }
        
        // Если ничего не подошло, возвращаем изображение по умолчанию
        return asset('images/categories/default.jpg');
    }
    
    /**
     * Получить цвет фона для категории
     */
    public function getColorClass()
    {
        $colors = [
            'bg-primary', 'bg-success', 'bg-info', 'bg-warning', 
            'bg-danger', 'bg-secondary', 'bg-dark', 'bg-indigo'
        ];
        
        // Используем ID категории для выбора цвета (чтобы один и тот же цвет 
        // всегда использовался для одной и той же категории)
        return $colors[$this->id % count($colors)];
    }

    /**
     * Возвращает класс иконки Font Awesome в зависимости от категории
     * 
     * @return string
     */
    public function getIconClass()
    {
        // Расширенный набор иконок для категорий
        $icons = [
            // Кухни национальные
            'абхазская' => 'fa-globe-europe',
            'австралийская' => 'fa-globe-oceania',
            'австрийская' => 'fa-mountain',
            'авторская' => 'fa-user-chef',
            'азербайджанская' => 'fa-mosque',
            'американская' => 'fa-flag-usa',
            'арабская' => 'fa-kaaba',
            'армянская' => 'fa-church',
            'африканская' => 'fa-globe-africa',
            'башкирская' => 'fa-horse',
            'белорусская' => 'fa-tractor',
            'бельгийская' => 'fa-beer',
            'болгарская' => 'fa-pepper-hot',
            'британская' => 'fa-crown',
            'бурятская' => 'fa-dharmachakra',
            'венгерская' => 'fa-seedling',
            'вьетнамская' => 'fa-bowl-rice',
            'греческая' => 'fa-columns',
            'грузинская' => 'fa-wine-bottle',
            'еврейская' => 'fa-star-of-david',
            'европейская' => 'fa-euro-sign',
            'египетская' => 'fa-pyramid',
            'индийская' => 'fa-om',
            'ирландская' => 'fa-clover',
            'испанская' => 'fa-fan',
            'итальянская' => 'fa-pizza-slice',
            'кавказская' => 'fa-mountain-sun',
            'кипрская' => 'fa-sun',
            'китайская' => 'fa-yin-yang',
            'корейская' => 'fa-pepper-hot',
            'крымская' => 'fa-bridge-water',
            'латвийская' => 'fa-monument',
            'марокканская' => 'fa-map',
            'мексиканская' => 'fa-pepper-hot',
            'молдавская' => 'fa-wine-glass',
            'немецкая' => 'fa-beer-mug-empty',
            'одесская' => 'fa-anchor',
            'паназиатская' => 'fa-utensils-alt',
            'перуанская' => 'fa-mountain-city',
            'португальская' => 'fa-ship',
            'русская' => 'fa-snowflake',
            'сирийская' => 'fa-pastafarianism',
            'скандинавская' => 'fa-mountain',
            'советская' => 'fa-hammer-sickle',
            'средиземноморская' => 'fa-water',
            'тайская' => 'fa-pepper-hot',
            'таджикская' => 'fa-mountain-sun',
            'татарская' => 'fa-mosque',
            'турецкая' => 'fa-moon',
            'узбекская' => 'fa-bread-slice',
            'украинская' => 'fa-wheat',
            'французская' => 'fa-croissant',
            'чешская' => 'fa-beer',
            'шведская' => 'fa-chair',
            'эстонская' => 'fa-tree',
            'югославская' => 'fa-landmark',
            'японская' => 'fa-fish',

            // Типы блюд
            'аджапсандал' => 'fa-mortar-pestle',
            'азу' => 'fa-meat',
            'безглютеновая' => 'fa-wheat-slash',
            'бездрожжевой' => 'fa-bread-loaf',
            'бефстроганов' => 'fa-meat',
            'блины' => 'fa-pancakes',
            'борщ' => 'fa-soup',
            'брускетта' => 'fa-bread-slice',
            'булочки' => 'fa-cookie',
            'бульон' => 'fa-mug-hot',
            'буррито' => 'fa-burrito',
            'вареники' => 'fa-dumpling',
            'веганская' => 'fa-leaf',
            'вегетарианская' => 'fa-seedling',
            'видеорецепты' => 'fa-video',
            'выпечка' => 'fa-cookie',
            'гамбургер' => 'fa-hamburger',
            'голубцы' => 'fa-cabbage',
            'бутерброд' => 'fa-sandwich',
            'гратен' => 'fa-cheese',
            'гренки' => 'fa-bread-slice',
            'салат' => 'fa-salad',
            'грибной' => 'fa-mushroom',
            'гуляш' => 'fa-meat',
            'десерт' => 'fa-ice-cream',
            'диетические' => 'fa-weight',
            'жаркое' => 'fa-fire',
            'желе' => 'fa-candy',
            'завтрак' => 'fa-egg',
            'заготовки' => 'fa-jar',
            'закуски' => 'fa-cookie',
            'здоровье' => 'fa-heart-pulse',
            'зеленый борщ' => 'fa-soup',
            'из яиц' => 'fa-egg',
            'канапе' => 'fa-cheese-swiss',
            'картошка' => 'fa-potato',
            'каши' => 'fa-bowl-food',
            'кексы' => 'fa-cupcake',
            'кето' => 'fa-bacon',
            'котлеты' => 'fa-meat',
            'крабовый' => 'fa-shrimp',
            'крем-суп' => 'fa-blender',
            'куличи' => 'fa-church',
            'курица' => 'fa-drumstick-bite',
            'лапша' => 'fa-noodle',
            'летние' => 'fa-sun',
            'лечо' => 'fa-pepper-hot',
            'луковый' => 'fa-onion',
            'макароны' => 'fa-pasta',
            'мармелад' => 'fa-candy',
            'мастер-класс' => 'fa-chalkboard-teacher',
            'меню при диабете' => 'fa-tablets',
            'мимоза' => 'fa-flower',
            'мировая кухня' => 'fa-globe',
            'мусака' => 'fa-eggplant',
            'мясной' => 'fa-bacon',
            'напитки' => 'fa-glass-martini-alt',
            'низкокалорийная' => 'fa-weight-scale',
            'новогодние' => 'fa-snowflake',
            'овощи' => 'fa-carrot',
            'оладьи' => 'fa-cookie',
            'омлет' => 'fa-egg',
            'основные блюда' => 'fa-utensils',
            'острые' => 'fa-fire',
            'паста' => 'fa-pasta',
            'пицца' => 'fa-pizza-slice',
            'паэлья' => 'fa-rice',
            'перцы' => 'fa-pepper-hot',
            'печенье' => 'fa-cookie',
            'пирог' => 'fa-pie',
            'пироги' => 'fa-pie',
            'пирожки' => 'fa-cookie',
            'пирожное' => 'fa-cake-slice',
            'плов' => 'fa-rice',
            'помидоры' => 'fa-tomato',
            'постная' => 'fa-seedling',
            'постное' => 'fa-leaf',
            'праздничные' => 'fa-party-horn',
            'пудинг' => 'fa-bowl-food',
            'путешествия' => 'fa-plane',
            'пышные' => 'fa-bread-slice',
            'пюре' => 'fa-mortar-pestle',
            'рагу' => 'fa-hotpot',
            'рататуй' => 'fa-eggplant',
            'рестораны' => 'fa-utensils',
            'индейка' => 'fa-turkey',
            'рисовый' => 'fa-bowl-rice',
            'ростбиф' => 'fa-steak',
            'рулет' => 'fa-scroll',
            'рыба' => 'fa-fish',
            'соленья' => 'fa-jar',
            'соус' => 'fa-bottle-droplet',
            'песто' => 'fa-mortar-pestle',
            'цезарь' => 'fa-crown',
            'стейки' => 'fa-steak',
            'суп' => 'fa-soup',
            'клецки' => 'fa-ball',
            'фасоль' => 'fa-bean',
            'фрикадельки' => 'fa-meatball',
            'суп-пюре' => 'fa-blender',
            'сырники' => 'fa-cheese',
            'творог' => 'fa-cheese',
            'сырные' => 'fa-cheese',
            'сэндвичи' => 'fa-bread-slice',
            'тартар' => 'fa-fish',
            'творожные' => 'fa-cheese',
            'теплые' => 'fa-temperature-high',
            'терияки' => 'fa-bottle-droplet',
            'томатный' => 'fa-tomato',
            'тонкие' => 'fa-pancake',
            'торт' => 'fa-cake',
            'зебра' => 'fa-candy-cane',
            'наполеон' => 'fa-layer-group',
            'тортилья' => 'fa-taco',
            'торты' => 'fa-cake-candles',
            'тосты' => 'fa-bread-slice',
            'тыквенный' => 'fa-pumpkin',
            'фруктовые' => 'fa-apple-whole',
            'хачапури' => 'fa-cheese',
            'хинкали' => 'fa-pouch',
            'хлеб' => 'fa-bread-loaf',
            'холодные' => 'fa-temperature-low',
            'холодный' => 'fa-snowflake',
            'чесночный' => 'fa-mortar-pestle',
            'чизкейк' => 'fa-cheese',
            'шакшука' => 'fa-egg',
            'шоколадный' => 'fa-candy-bar',
            'яичница' => 'fa-egg'
        ];
        
        // Альтернативные иконки для случайного выбора
        $alternativeIcons = [
            'fa-utensils', 'fa-bowl-food', 'fa-kitchen-set', 'fa-plate-utensils', 
            'fa-mug-hot', 'fa-burger', 'fa-cookie', 'fa-martini-glass-citrus',
            'fa-bowl-rice', 'fa-apple-whole', 'fa-wheat-awn'
        ];
        
        // Проверяем, содержит ли название категории какое-либо ключевое слово
        $name = mb_strtolower($this->name);
        foreach ($icons as $keyword => $icon) {
            if (mb_strpos($name, $keyword) !== false) {
                return $icon;
            }
        }
        
        // Если не найдено соответствий, выбираем случайную иконку из альтернативных
        // Но для одной и той же категории всегда будет одна и та же иконка
        $hash = crc32($this->name); // Создаем хеш из имени категории
        $index = abs($hash) % count($alternativeIcons);
        return $alternativeIcons[$index];
    }
}
