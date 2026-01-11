<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$m = App\Models\Mood::whereRaw('LOWER(mood_name)=?', ['marah'])->first();
if (! $m) {
    echo "NO_MOOD\n";
    exit(0);
}
$id = $m->id;
echo "MOOD_ID: $id\n";
$menus_by_cat = App\Models\Menu::whereHas('category', function ($q) use ($id) { $q->where('mood_id', $id); })->count();
echo "MENUS_BY_CATEGORY: $menus_by_cat\n";
$terms = ['pedas', 'berbumbu', 'goreng'];
foreach ($terms as $t) {
    $menus_catname = App\Models\Menu::whereHas('category', function ($q) use ($t) { $q->whereRaw('LOWER(category_name) LIKE ?', ['%' . $t . '%']); })->count();
    echo "MENUS_CATNAME_$t: $menus_catname\n";
    $menus_name = App\Models\Menu::whereRaw('LOWER(menu_name) LIKE ?', ['%' . $t . '%'])->count();
    echo "MENUS_NAME_$t: $menus_name\n";
}
$cats = App\Models\Category::whereRaw('LOWER(category_name) LIKE ?', ['%pedas%'])->get();
foreach ($cats as $c) {
    echo "CAT:$c->id $c->category_name\n";
}
