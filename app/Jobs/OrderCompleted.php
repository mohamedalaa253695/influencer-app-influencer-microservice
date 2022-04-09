<?php
namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use InfluencerMicroservices\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderData ;
    private $orderItemsData ;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->orderData = $this->data[0];
        $this->orderItemsData = $this->data[1];
        // print_r($this->orderData);
        $order = Order::create([
            'id' => $this->orderData['id'],
            'code' => $this->orderData['code'],
            'user_id' => $this->orderData['user_id'],
            'created_at' => $this->orderData['created_at'],
            'updated_at' => $this->orderData['updated_at'],
        ]);

        foreach ($this->orderItemsData as $item) {
            $item['revenue'] = $item['influencer_revenue'];
            unset($item['influencer_revenue'], $item['admin_revenue']);

            OrderItem::create($item);
        }

        $revenue = $order->total;

        $userService = new UserService();
        $user = $userService->get($order->user_id);

        Redis::zincrby('rankings', $revenue, $user->fullName());
    }
}
