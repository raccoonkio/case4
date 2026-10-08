<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@bookstore.test'],
            ['name' => 'Администратор', 'password' => Hash::make('admin'), 'role' => 'admin'],
        );

        User::firstOrCreate(
            ['email' => 'reader@bookstore.test'],
            ['name' => 'Читатель', 'password' => Hash::make('reader'), 'role' => 'user'],
        );

        $books = [
            [
                'title' => 'Вот какой рассеянный',
                'author' => 'Самуил Маршак',
                'category' => 'Детская литература',
                'published_year' => 1930,
                'description' => 'Весёлые и ритмичные стихи для первого самостоятельного чтения.',
                'preview_path' => 'images/previews/book-1.jpg',
                'price' => 290,
                'rental_price_14_days' => 87,
                'rental_price_30_days' => 145,
                'rental_price_90_days' => 232,
            ],
            [
                'title' => 'Двенадцать месяцев',
                'author' => 'Самуил Маршак',
                'category' => 'Детская литература',
                'published_year' => 1943,
                'description' => 'Добрая пьеса-сказка о смелости, справедливости и встрече с чудом.',
                'preview_path' => 'images/previews/book-2.jpg',
                'price' => 360,
                'rental_price_14_days' => 108,
                'rental_price_30_days' => 180,
                'rental_price_90_days' => 288,
            ],
            [
                'title' => 'Стихи и сказки',
                'author' => 'Александр Пушкин',
                'category' => 'Классика',
                'published_year' => 1831,
                'description' => 'Знакомые с детства сказки и стихотворения одного из главных русских поэтов.',
                'preview_path' => 'images/previews/book-3.jpg',
                'price' => 450,
                'rental_price_14_days' => 135,
                'rental_price_30_days' => 225,
                'rental_price_90_days' => 360,
            ],
            [
                'title' => 'Евгений Онегин',
                'author' => 'Александр Пушкин',
                'category' => 'Классика',
                'published_year' => 1833,
                'description' => 'Роман в стихах о любви, выборе и взрослении на фоне русской жизни.',
                'preview_path' => 'images/previews/book-4.jpg',
                'price' => 520,
                'rental_price_14_days' => 156,
                'rental_price_30_days' => 260,
                'rental_price_90_days' => 416,
            ],
            [
                'title' => 'Стихи',
                'author' => 'Сергей Есенин',
                'category' => 'Поэзия',
                'published_year' => 1925,
                'description' => 'Сборник лирики о природе, родной земле, любви и человеческой судьбе.',
                'preview_path' => 'images/previews/book-5.jpg',
                'price' => 410,
                'rental_price_14_days' => 123,
                'rental_price_30_days' => 205,
                'rental_price_90_days' => 328,
            ],
            [
                'title' => 'Стихотворения',
                'author' => 'Марина Цветаева',
                'category' => 'Поэзия',
                'published_year' => 1921,
                'description' => 'Избранные стихотворения с узнаваемой интонацией и сильным ритмом.',
                'preview_path' => 'images/previews/book-6.jpg',
                'price' => 430,
                'rental_price_14_days' => 129,
                'rental_price_30_days' => 215,
                'rental_price_90_days' => 344,
            ],
            [
                'title' => 'Мастер и Маргарита',
                'author' => 'Михаил Булгаков',
                'category' => 'Проза',
                'published_year' => 1967,
                'description' => 'Роман о свободе, любви, ответственности и природе художественного слова.',
                'preview_path' => 'images/previews/book-7.jpg',
                'price' => 690,
                'rental_price_14_days' => 207,
                'rental_price_30_days' => 345,
                'rental_price_90_days' => 552,
            ],
            [
                'title' => 'Собачье сердце',
                'author' => 'Михаил Булгаков',
                'category' => 'Проза',
                'published_year' => 1925,
                'description' => 'Сатирическая повесть о научном эксперименте и границах человеческого вмешательства.',
                'preview_path' => 'images/previews/book-8.avif',
                'price' => 390,
                'rental_price_14_days' => 117,
                'rental_price_30_days' => 195,
                'rental_price_90_days' => 312,
            ],
            [
                'title' => 'Пикник на обочине',
                'author' => 'Аркадий и Борис Стругацкие',
                'category' => 'Фантастика',
                'published_year' => 1972,
                'description' => 'Фантастическая история о Зоне, человеческих желаниях и цене мечты.',
                'preview_path' => 'images/previews/book-9.avif',
                'price' => 570,
                'rental_price_14_days' => 171,
                'rental_price_30_days' => 285,
                'rental_price_90_days' => 456,
            ],
            [
                'title' => 'Понедельник начинается в субботу',
                'author' => 'Аркадий и Борис Стругацкие',
                'category' => 'Фантастика',
                'published_year' => 1965,
                'description' => 'Ироничная повесть о научном институте, где исследуют настоящую магию.',
                'preview_path' => 'images/previews/book-10.avif',
                'price' => 540,
                'rental_price_14_days' => 162,
                'rental_price_30_days' => 270,
                'rental_price_90_days' => 432,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book + ['status' => 'published', 'is_available' => true]);
        }
    }
}
