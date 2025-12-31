<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::insert([
            // Kantin Bu Ani (tenant_id: 1)
            ['menu_name' => 'Es Teh Manis', 'price' => 5000, 'description' => 'Teh manis dingin segar', 'image' => 'https://source.unsplash.com/featured/?iced-tea', 'tenant_id' => 1, 'category_id' => 1],
            ['menu_name' => 'Nasi Goreng Spesial', 'price' => 15000, 'description' => 'Nasi goreng dengan telur dan ayam', 'image' => 'https://source.unsplash.com/featured/?fried-rice', 'tenant_id' => 1, 'category_id' => 2],
            ['menu_name' => 'Mie Ayam', 'price' => 12000, 'description' => 'Mie ayam dengan pangsit', 'image' => 'https://source.unsplash.com/featured/?chicken-noodles', 'tenant_id' => 1, 'category_id' => 2],
            ['menu_name' => 'Bakso Malang', 'price' => 18000, 'description' => 'Bakso dengan tahu dan pangsit', 'image' => 'https://source.unsplash.com/featured/?meatball-soup', 'tenant_id' => 1, 'category_id' => 2],
            
            // Kopi Corner (tenant_id: 2)
            ['menu_name' => 'Kopi Susu Gula Aren', 'price' => 15000, 'description' => 'Kopi susu dengan gula aren asli', 'image' => 'https://source.unsplash.com/featured/?iced-coffee', 'tenant_id' => 2, 'category_id' => 10],
            ['menu_name' => 'Espresso', 'price' => 12000, 'description' => 'Espresso murni', 'image' => 'https://source.unsplash.com/featured/?espresso', 'tenant_id' => 2, 'category_id' => 10],
            ['menu_name' => 'Cappuccino', 'price' => 18000, 'description' => 'Cappuccino dengan foam susu', 'image' => 'https://source.unsplash.com/featured/?cappuccino', 'tenant_id' => 2, 'category_id' => 10],
            ['menu_name' => 'Latte', 'price' => 20000, 'description' => 'Latte dengan susu steamed', 'image' => 'https://source.unsplash.com/featured/?latte', 'tenant_id' => 2, 'category_id' => 10],
            
            // Snack Bar (tenant_id: 3)
            ['menu_name' => 'Keripik Kentang', 'price' => 8000, 'description' => 'Keripik kentang renyah', 'image' => 'https://source.unsplash.com/featured/?potato-chips', 'tenant_id' => 3, 'category_id' => 7],
            ['menu_name' => 'Donat Coklat', 'price' => 10000, 'description' => 'Donat dengan topping coklat', 'image' => 'https://source.unsplash.com/featured/?chocolate-donut', 'tenant_id' => 3, 'category_id' => 7],
            ['menu_name' => 'Roti Bakar', 'price' => 12000, 'description' => 'Roti bakar dengan selai', 'image' => 'https://source.unsplash.com/featured/?toast-bread', 'tenant_id' => 3, 'category_id' => 7],
            ['menu_name' => 'Pisang Goreng', 'price' => 8000, 'description' => 'Pisang goreng crispy', 'image' => 'https://source.unsplash.com/featured/?banana-fritter', 'tenant_id' => 3, 'category_id' => 7],
            
            // Warung Makan Sederhana (tenant_id: 4)
            ['menu_name' => 'Nasi Campur', 'price' => 20000, 'description' => 'Nasi dengan lauk pilihan', 'image' => 'https://source.unsplash.com/featured/?mixed-rice', 'tenant_id' => 4, 'category_id' => 2],
            ['menu_name' => 'Soto Ayam', 'price' => 15000, 'description' => 'Soto ayam hangat', 'image' => 'https://source.unsplash.com/featured/?chicken-soup', 'tenant_id' => 4, 'category_id' => 5],
            ['menu_name' => 'Rawon', 'price' => 18000, 'description' => 'Rawon daging sapi', 'image' => 'https://source.unsplash.com/featured/?beef-soup', 'tenant_id' => 4, 'category_id' => 5],
            
            // Bakso Malang (tenant_id: 5)
            ['menu_name' => 'Bakso Urat', 'price' => 20000, 'description' => 'Bakso dengan urat sapi', 'image' => 'https://source.unsplash.com/featured/?meatball-soup', 'tenant_id' => 5, 'category_id' => 5],
            ['menu_name' => 'Bakso Mercon', 'price' => 22000, 'description' => 'Bakso pedas mercon', 'image' => 'https://source.unsplash.com/featured/?spicy-meatball', 'tenant_id' => 5, 'category_id' => 5],
            
            // Ice Cream Shop (tenant_id: 6)
            ['menu_name' => 'Ice Cream Vanilla', 'price' => 15000, 'description' => 'Ice cream vanilla premium', 'image' => 'https://source.unsplash.com/featured/?vanilla-ice-cream', 'tenant_id' => 6, 'category_id' => 3],
            ['menu_name' => 'Ice Cream Coklat', 'price' => 15000, 'description' => 'Ice cream coklat premium', 'image' => 'https://source.unsplash.com/featured/?chocolate-ice-cream', 'tenant_id' => 6, 'category_id' => 3],
            ['menu_name' => 'Ice Cream Strawberry', 'price' => 15000, 'description' => 'Ice cream strawberry segar', 'image' => 'https://source.unsplash.com/featured/?strawberry-ice-cream', 'tenant_id' => 6, 'category_id' => 3],
            
            // Pizza Corner (tenant_id: 7)
            ['menu_name' => 'Pizza Margherita', 'price' => 45000, 'description' => 'Pizza dengan tomat dan mozzarella', 'image' => 'https://source.unsplash.com/featured/?margherita-pizza', 'tenant_id' => 7, 'category_id' => 15],
            ['menu_name' => 'Pizza Pepperoni', 'price' => 50000, 'description' => 'Pizza dengan pepperoni', 'image' => 'https://source.unsplash.com/featured/?pepperoni-pizza', 'tenant_id' => 7, 'category_id' => 15],
            
            // Sushi Bar (tenant_id: 8)
            ['menu_name' => 'Sushi Roll', 'price' => 35000, 'description' => 'Sushi roll dengan salmon', 'image' => 'https://source.unsplash.com/featured/?sushi-roll', 'tenant_id' => 8, 'category_id' => 15],
            ['menu_name' => 'Sashimi Set', 'price' => 60000, 'description' => 'Set sashimi premium', 'image' => 'https://source.unsplash.com/featured/?sashimi', 'tenant_id' => 8, 'category_id' => 15],
        ]);
    }
}