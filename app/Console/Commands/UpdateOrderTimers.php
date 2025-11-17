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
        $this->info('Starting order timer update...');

        $mainTimeOrders = Order::where('status', 'main_time')->get();

        foreach ($mainTimeOrders as $order) {
            if ($order->days_spent_main < $order->main_days_allocated) {

                $order->increment('days_spent_main');
                $this->info("Order #{$order->id} (Main) incremented.");

            } else {
                $order->status = 'extra_time';
                $order->save();
                $this->info("Order #{$order->id} moved to extra time.");
            }
        }

        $extraTimeOrders = Order::where('status', 'extra_time')->get();

        foreach ($extraTimeOrders as $order) {
            if ($order->days_spent_extra < $order->extra_days_allocated) {

                $order->increment('days_spent_extra'); // Zid nhar f l-idafi
                $this->info("Order #{$order->id} (Extra) incremented.");

            } else {
                $order->status = 'completed';
                $order->save();
                $this->info("Order #{$order->id} completed.");
            }
        }

        $this->info('Order timer update complete.');
        return 0;
    }
}
