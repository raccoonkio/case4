<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\RentalReminder;
use App\Notifications\RentalEndingSoon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

#[Signature('app:send-rental-reminders')]
#[Description('Создаёт автоматические напоминания об окончании аренды')]
class SendRentalReminders extends Command
{
    public function handle(): int
    {
        $remindersCreated = 0;

        Order::query()
            ->with(['book', 'user'])
            ->where('order_type', 'rental')
            ->where('status', 'active')
            ->whereNull('reminder_sent_at')
            ->whereBetween('ends_at', [now(), now()->addDays(3)])
            ->chunkById(100, function ($orders) use (&$remindersCreated): void {
                foreach ($orders as $order) {
                    DB::transaction(function () use ($order, &$remindersCreated): void {
                        RentalReminder::create([
                            'user_id' => $order->user_id,
                            'order_id' => $order->id,
                            'message' => sprintf(
                                'Аренда книги «%s» заканчивается %s.',
                                $order->book->title,
                                $order->ends_at->format('d.m.Y'),
                            ),
                            'sent_at' => now(),
                        ]);

                        $order->update(['reminder_sent_at' => now()]);
                        $remindersCreated++;
                    });

                    if ($order->user !== null) {
                        $order->user->notify(new RentalEndingSoon($order));
                    } elseif ($order->guest_email !== null) {
                        Notification::route('mail', $order->guest_email)
                            ->notify(new RentalEndingSoon($order));
                    }
                }
            });

        $this->info("Создано напоминаний: {$remindersCreated}.");

        return self::SUCCESS;
    }
}
