<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('user_id');
        $guestRules = $userId === null
            ? ['required', 'string', 'max:255']
            : ['nullable', 'string', 'max:255'];
        $emailRules = $userId === null
            ? ['required', 'email', 'max:255']
            : ['nullable', 'email', 'max:255'];

        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'order_type' => ['required', Rule::in(['purchase', 'rental'])],
            'rental_term_days' => [
                'nullable',
                'integer',
                Rule::in([14, 30, 90]),
                Rule::requiredIf($request->string('order_type')->toString() === 'rental'),
            ],
            'guest_name' => $guestRules,
            'guest_email' => $emailRules,
        ]);

        $order = DB::transaction(function () use ($validated, $userId): ?Order {
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if ($book->status !== 'published' || ! $book->is_available) {
                return null;
            }

            $isRental = $validated['order_type'] === 'rental';
            $termDays = $isRental ? (int) $validated['rental_term_days'] : null;
            $startsAt = now();

            return Order::create([
                'user_id' => $userId,
                'guest_name' => $userId === null ? $validated['guest_name'] : null,
                'guest_email' => $userId === null ? $validated['guest_email'] : null,
                'book_id' => $book->id,
                'order_type' => $validated['order_type'],
                'rental_term_days' => $termDays,
                'price' => $isRental ? $book->rentalPrice($termDays) : $book->price,
                'starts_at' => $startsAt,
                'ends_at' => $isRental ? $startsAt->copy()->addDays($termDays) : null,
                'status' => 'active',
            ]);
        });

        if ($order === null) {
            return back()->withErrors(['book_id' => 'Книга сейчас недоступна для покупки или аренды.']);
        }

        $redirect = $userId === null
            ? redirect()->route('store.show', $order->book_id)
            : redirect()->route('store.account');

        return $redirect->with(
            'status',
            $userId === null
                ? 'Заказ оформлен без регистрации. E-mail сохранён для связи.'
                : ($order->order_type === 'rental'
                    ? 'Аренда оформлена. Срок действия отображается в личном кабинете.'
                    : 'Покупка оформлена. Книга добавлена в личный кабинет.'),
        );
    }
}
