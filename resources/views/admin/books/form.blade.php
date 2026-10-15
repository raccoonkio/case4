@extends('layouts.app')

@section('title', $formTitle.' · Книжная полка')

@section('content')
    <a class="back-link" href="{{ route('admin.books.index') }}">← Вернуться к списку книг</a>
    <section class="form-page">
        <div>
            <p class="eyebrow">Каталог</p>
            <h1>{{ $formTitle }}</h1>
            <p>Заполните основные сведения об издании. Поля с отметкой обязательны.</p>
        </div>
        <form class="form-card form-card-wide" method="POST" action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}">
            @csrf
            @if ($book->exists)
                @method('PUT')
            @endif
            <div class="form-grid">
                <div class="field-wide">
                    <label for="title">Название *</label>
                    <input id="title" name="title" value="{{ old('title', $book->title) }}" required>
                </div>
                <div>
                    <label for="author">Автор *</label>
                    <input id="author" name="author" value="{{ old('author', $book->author) }}" required>
                </div>
                <div>
                    <label for="category">Категория *</label>
                    <input id="category" name="category" value="{{ old('category', $book->category) }}" placeholder="Например, Поэзия" required>
                </div>
                <div>
                    <label for="published_year">Год написания *</label>
                    <input id="published_year" name="published_year" type="number" min="1000" max="2100" value="{{ old('published_year', $book->published_year) }}" required>
                </div>
                <div>
                    <label for="price">Цена, ₽ *</label>
                    <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $book->price) }}" required>
                </div>
                <div class="field-wide">
                    <p class="field-label">Цены аренды, ₽ *</p>
                    <div class="rental-price-fields">
                        <label>
                            <span>2 недели</span>
                            <input name="rental_price_14_days" type="number" min="0" step="0.01" value="{{ old('rental_price_14_days', $book->rental_price_14_days) }}" required>
                        </label>
                        <label>
                            <span>1 месяц</span>
                            <input name="rental_price_30_days" type="number" min="0" step="0.01" value="{{ old('rental_price_30_days', $book->rental_price_30_days) }}" required>
                        </label>
                        <label>
                            <span>3 месяца</span>
                            <input name="rental_price_90_days" type="number" min="0" step="0.01" value="{{ old('rental_price_90_days', $book->rental_price_90_days) }}" required>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="status">Статус *</label>
                    <select id="status" name="status" required>
                        <option value="published" @selected(old('status', $book->status ?: 'published') === 'published')>Опубликована</option>
                        <option value="draft" @selected(old('status', $book->status) === 'draft')>Черновик</option>
                        <option value="archived" @selected(old('status', $book->status) === 'archived')>Архив</option>
                    </select>
                </div>
                <div class="field-wide">
                    <label for="description">Описание *</label>
                    <textarea id="description" name="description" rows="5" required>{{ old('description', $book->description) }}</textarea>
                </div>
                <div class="field-wide">
                    <label for="preview_path">Путь к картинке-превью</label>
                    <input id="preview_path" name="preview_path" value="{{ old('preview_path', $book->preview_path) }}" placeholder="images/previews/example.svg"><small class="field-hint">Можно оставить пустым — тогда используется цветной вариант карточки.</small>
                </div>
                <label class="checkbox-field field-wide">
                    <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $book->exists ? $book->is_available : true))>
                    <span>Книга доступна для покупки и аренды</span>
                </label>
            </div>
            <button class="button button-primary" type="submit">Сохранить книгу</button>
        </form>
    </section>
@endsection
