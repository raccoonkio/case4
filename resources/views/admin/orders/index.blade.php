@extends('layouts.app')

@section('title', 'Заказы · Книжная полка')

@section('content')
    <section class="admin-heading">
        <div>
            <p class="eyebrow">Панель администратора</p>
            <h1>Заказы и аренда</h1>
            <p>Все покупки и аренды книг, оформленные пользователями и гостями.</p>
        </div>
        <a class="button button-secondary" href="{{ route('admin.books.index') }}">К библиотеке</a>
    </section>

    <section class="admin-table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Книга</th>
                        <th>Клиент</th>
                        <th>Тип</th>
                        <th>Цена</th>
                        <th>Срок</th>
                        <th>Статус</th>
                        <th>Уведомление</th>
                        <th>Дата оформления</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->book->title }}</strong>
                                <small>{{ $order->book->author }}</small>
                            </td>
                            <td>
                                <strong>{{ $order->user?->name ?? $order->guest_name ?? 'Гостевой заказ' }}</strong>
                                <small>{{ $order->user?->email ?? $order->guest_email ?? 'E-mail не указан' }}</small>
                            </td>
                            <td>{{ $order->isRental() ? 'Аренда' : 'Покупка' }}</td>
                            <td>{{ number_format((float) $order->price, 0, ',', ' ') }} ₽</td>
                            <td>
                                @if ($order->isRental())
                                    {{ match ($order->rental_term_days) {
                                        14 => '2 недели',
                                        30 => '1 месяц',
                                        90 => '3 месяца',
                                        default => $order->rental_term_days.' дней',
                                    } }}
                                    <small>до {{ $order->ends_at?->format('d.m.Y') }}</small>
                                @else
                                    Постоянный доступ
                                @endif
                            </td>
                            <td>
                                <span class="table-status status-{{ $order->status }}">
                                    {{ match ($order->status) {
                                        'active' => 'Активен',
                                        'completed' => 'Завершён',
                                        'cancelled' => 'Отменён',
                                        default => $order->status,
                                    } }}
                                </span>
                            </td>
                            <td>
                                @if ($order->isRental())
                                    <div class="reminder-actions">
                                        <small>
                                            {{ $order->reminder_sent_at?->format('d.m.Y H:i') ?? 'Не отправлялось' }}
                                        </small>
                                        <form method="POST" action="{{ route('admin.orders.remind', $order) }}">
                                            @csrf
                                            <button class="reminder-button" type="submit">
                                                {{ $order->reminder_sent_at ? 'Напомнить ещё раз' : 'Напомнить' }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $order->created_at?->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state-inline">Заказов пока нет.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
