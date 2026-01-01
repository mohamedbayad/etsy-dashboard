<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class UpdateOrderTimers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:update-timers'; // Samya dyal l-commande

    /**
     * The console command description.
     */
    protected $description = 'Increment days spent on active orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Calculating order overdue...');

        $orders = Order::where('status', '!=', 'completed')->get();

        foreach ($orders as $order) {

            $daysPassed = now()->diffInDays($order->order_date); // 4

            // daysPassed = order_date = 2025-12-31 => 2 days passed
            // main_days_allocated = 3
            // extra_days_allocated = 2

            if ($daysPassed < $order->main_days_allocated) {
                $order->status = 'main_time';
                $order->days_spent_main = $daysPassed;
                $order->days_spent_extra = 0;
                $order->days_retarded = 0;
            }
            else {
                $order->status = 'extra_time';
                $order->days_spent_main = $order->main_days_allocated;

                if ($daysPassed - $order->days_spent_main < $order->extra_days_allocated) {
                    $order->days_spent_extra = $daysPassed - $order->days_spent_main;
                    $order->days_retarded = 0;
                }
                else {
                    $order->days_spent_extra = $order->extra_days_allocated;
                    $order->days_retarded = $daysPassed;
                }
            }

            $order->save();
            $this->info("Order #{$order->id} updated. Total Days Passed: {$daysPassed}");
        }

        $this->info('All timers updated.');
    }
}
