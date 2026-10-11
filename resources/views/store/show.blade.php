@extends('layouts.app')

@section('title', $book->title.' · Книжная полка')

@section('content')
    <a class="back-link" href="{{ route('store.index') }}">← Вернуться в каталог</a>
    <section class="book-detail">
        <div class="book-cover book-cover-large {{ $book->preview_path ? 'has-image' : '' }} cover-{{ $book->id % 5 }}">
            @if ($book->preview_path)
                <img class="book-cover-image" src="{{ asset($book->preview_path) }}" alt="Картинка-превью книги {{ $book->title }}" onerror="this.hidden=true; this.closest('.book-cover').classList.remove('has-image')">
            @endif
            <span class="cover-kicker">{{ $book->category }}</span>
            <strong>{{ $book->title }}</strong>
            <span class="cover-author">{{ $book->author }}</span>
        </div>
        <div class="detail-copy">
            <p class="eyebrow">{{ $book->category }} · {{ $book->published_year }}</p>
            <h1>{{ $book->title }}</h1>
            <p class="detail-author">{{ $book->author }}</p>
            <div class="detail-facts">
                <div><span>Категория</span><strong>{{ $book->category }}</strong></div>
                <div><span>Год написания</span><strong>{{ $book->published_year }}</strong></div>
                <div><span>Доступность</span><strong>{{ $book->is_available ? 'Сейчас доступна' : 'Временно недоступна' }}</strong></div>
            </div>
            <p class="detail-description">{{ $book->description }}</p>
            <div class="detail-purchase-price">
                <span>Цена:</span>
                <strong>{{ number_format((float) $book->price, 0, ',', ' ') }} руб</strong>
                @if ($book->is_available)
                    <button class="button button-primary" type="button" data-order-form-target="purchase-order-form">Купить</button>
                @else
                    <span class="price-unavailable">Недоступна</span>
                @endif
            </div>
            <h2 class="detail-rental-heading">Аренда</h2>
            <div class="rental-offer-row">
                <div class="rental-options" role="radiogroup" aria-label="Срок и цена аренды книги">
                    <label class="rental-option">
                        <input type="radio" name="rental_term_preview" value="14" data-rental-term-option @checked(old('rental_term_days', '14') === '14')>
                        <span class="rental-option-content">
                            <span class="rental-option-term">2 недели</span>
                            <strong class="rental-option-price">{{ number_format($book->rentalPrice(14), 0, ',', ' ') }} руб</strong>
                        </span>
                    </label>
                    <label class="rental-option">
                        <input type="radio" name="rental_term_preview" value="30" data-rental-term-option @checked(old('rental_term_days') === '30')>
                        <span class="rental-option-content">
                            <span class="rental-option-term">1 месяц</span>
                            <strong class="rental-option-price">{{ number_format($book->rentalPrice(30), 0, ',', ' ') }} руб</strong>
                        </span>
                    </label>
                    <label class="rental-option">
                        <input type="radio" name="rental_term_preview" value="90" data-rental-term-option @checked(old('rental_term_days') === '90')>
                        <span class="rental-option-content">
                            <span class="rental-option-term">3 месяца</span>
                            <strong class="rental-option-price">{{ number_format($book->rentalPrice(90), 0, ',', ' ') }} руб</strong>
                        </span>
                    </label>
                </div>
                <div class="order-choice-buttons">
                    @if ($book->is_available)
                        <button class="button button-secondary" type="button" data-order-form-target="rental-order-form">Арендовать</button>
                    @else
                        <p class="price-unavailable">Книга недоступна для покупки и аренды.</p>
                    @endif
                </div>
            </div>
            <div id="order">
                @include('store.partials.order-actions', ['book' => $book])
            </div>
        </div>
    </section>
@endsection
