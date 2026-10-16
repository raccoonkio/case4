<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RentalReminder;
use App\Notifications\RentalEndingSoon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['book', 'user'])
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function remind(Order $order): RedirectResponse
    {
        if (! $order->isRental()) {
            return back()->withErrors(['order' => 'Напоминание доступно только для аренды.']);
        }

        $order->load(['book', 'user']);

        if ($order->user === null && $order->guest_email === null) {
            return back()->withErrors(['order' => 'У заказа нет адресата для уведомления.']);
        }

        $sentAt = now();
        $message = sprintf(
            'Аренда книги «%s» заканчивается %s.',
            $order->book->title,
            $order->ends_at->format('d.m.Y'),
        );

        DB::transaction(function () use ($order, $message, $sentAt): void {
            RentalReminder::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->user_id,
                    'message' => $message,
                    'sent_at' => $sentAt,
                ],
            );

            $order->update(['reminder_sent_at' => $sentAt]);
        });

        if ($order->user !== null) {
            $order->user->notify(new RentalEndingSoon($order));
        } else {
            Notification::route('mail', $order->guest_email)
                ->notify(new RentalEndingSoon($order));
        }

        return back()->with('status', 'Напоминание об окончании аренды отправлено.');
    }
}
