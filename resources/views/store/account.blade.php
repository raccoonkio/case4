@extends('layouts.app')

@section('title', 'Моя библиотека · Книжная полка')

@section('content')
    <section class="account-heading">
        <div>
            <p class="eyebrow">Личный кабинет</p>
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
        </div>
        <div class="account-stat"><strong>{{ $user->orders->count() }}</strong><span>книг в библиотеке</span></div>
    </section>

    @if ($user->rentalReminders->isNotEmpty())
        <section class="reminder-panel">
            <p class="eyebrow">Напоминания</p>
            @foreach ($user->rentalReminders as $reminder)
                <p>{{ $reminder->message }}</p>
            @endforeach
        </section>
    @endif

    <section class="account-section">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Мои книги</p>
                <h2>Покупки и аренда</h2>
            </div>
            <a class="text-link" href="{{ route('store.index') }}">В каталог →</a>
        </div>
        <div class="library-list">
            @forelse ($user->orders->sortByDesc('created_at') as $order)
                <article class="library-item">
                    <div>
                        <span class="item-type">{{ $order->order_type === 'rental' ? 'Аренда' : 'Покупка' }}</span>
                        <h3>{{ $order->book->title }}</h3>
                        <p>{{ $order->book->author }} · {{ number_format((float) $order->price, 0, ',', ' ') }} ₽</p>
                    </div>
                    <div class="library-date">
                        @if ($order->isRental())
                            <span>Доступ до</span>
                            <strong>{{ $order->ends_at->format('d.m.Y') }}</strong>
                        @else
                            <span>Куплена</span>
                            <strong>{{ $order->starts_at->format('d.m.Y') }}</strong>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h3>Библиотека пока пуста</h3>
                    <p>Выберите книгу в каталоге и оформите покупку или аренду.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
