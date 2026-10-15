@extends('layouts.app')

@section('title', 'Администрирование · Книжная полка')

@section('content')
    <section class="admin-heading">
        <div>
            <p class="eyebrow">Панель администратора</p>
            <h1>Управление библиотекой</h1>
            <p>Изменяйте каталог, цены, статусы и доступность книг.</p>
        </div>
        <div class="admin-heading-actions">
            <a class="button button-secondary" href="{{ route('admin.orders.index') }}">Заказы</a>
            <a class="button button-primary" href="{{ route('admin.books.create') }}">Добавить книгу</a>
        </div>
    </section>

    @if ($expiringRentals->isNotEmpty())
        <section class="reminder-panel admin-reminders">
            <div class="section-heading compact">
                <div>
                    <p class="eyebrow">Автоматический контроль</p>
                    <h2>Ближайшие окончания аренды</h2>
                </div>
                <span class="muted-text">Напоминания создаются ежедневно в 09:00</span>
            </div>
            @foreach ($expiringRentals as $rental)
                <p>
                    <strong>{{ $rental->book->title }}</strong> —
                    {{ $rental->user?->name ?? $rental->guest_name ?? $rental->guest_email }},
                    до {{ $rental->ends_at->format('d.m.Y') }}
                    @if ($rental->user === null && $rental->guest_email)
                        ({{ $rental->guest_email }})
                    @endif
                </p>
            @endforeach
        </section>
    @endif

    <section class="admin-table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Книга</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Доступность</th>
                        <th><span class="sr-only">Действия</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td><strong>{{ $book->title }}</strong><small>{{ $book->published_year }}</small></td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->category }}</td>
                            <td>{{ number_format((float) $book->price, 0, ',', ' ') }} ₽</td>
                            <td><span class="table-status status-{{ $book->status }}">{{ $book->status === 'published' ? 'Опубликована' : ($book->status === 'draft' ? 'Черновик' : 'Архив') }}</span></td>
                            <td>{{ $book->is_available ? 'Да' : 'Нет' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="text-link" href="{{ route('admin.books.edit', $book) }}">Изменить</a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Удалить книгу?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-link" type="submit">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
