@extends('layouts.app')

@section('title', 'Каталог · Книжная полка')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow">Книжный магазин</p>
            <h1>Каталог книг</h1>
        </div>
    </section>

    <section class="catalog-section" id="catalog">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Библиотека</p>
                <h2>Выберите следующую историю</h2>
            </div>
            <form class="sort-form" method="GET" action="{{ route('store.index') }}">
                <label for="sort">Сортировать</label>
                <select id="sort" name="sort" onchange="this.form.submit()">
                    <option value="" @selected($sort === '')>По названию</option>
                    <option value="category" @selected($sort === 'category')>По категории</option>
                    <option value="author" @selected($sort === 'author')>По автору</option>
                    <option value="year" @selected($sort === 'year')>По году написания</option>
                </select>
            </form>
        </div>

        <div class="book-grid">
            @forelse ($books as $book)
                <article class="book-card">
                    <a class="book-cover {{ $book->preview_path ? 'has-image' : '' }} cover-{{ $loop->iteration % 5 }}" href="{{ route('store.show', $book) }}" aria-label="Открыть книгу {{ $book->title }}">
                        @if ($book->preview_path)
                            <img class="book-cover-image" src="{{ asset($book->preview_path) }}" alt="Картинка-превью книги {{ $book->title }}" loading="lazy" onerror="this.hidden=true; this.closest('.book-cover').classList.remove('has-image')">
                        @endif
                        <span class="cover-kicker">{{ $book->category }}</span>
                        <strong>{{ $book->title }}</strong>
                        <span class="cover-author">{{ $book->author }}</span>
                    </a>
                    <div class="book-card-body">
                        <div class="book-card-meta">
                            <span class="book-category">{{ $book->category }}</span>
                            <span class="book-availability">
                                <span class="status-dot {{ $book->is_available ? 'is-available' : 'is-unavailable' }}"></span>
                                {{ $book->is_available ? 'Доступна' : 'Недоступна' }}
                            </span>
                        </div>
                        <h3><a href="{{ route('store.show', $book) }}">{{ $book->title }}</a></h3>
                        <p class="book-author">{{ $book->author }}</p>
                        <div class="book-year">
                            <span>Год написания</span>
                            <strong>{{ $book->published_year }}</strong>
                        </div>
                        <p class="book-description">{{ $book->description }}</p>
                        <a class="text-link" href="{{ route('store.show', $book) }}">Подробнее о книге →</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h3>В каталоге пока нет книг</h3>
                    <p>Добавьте первое издание через интерфейс администратора.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
