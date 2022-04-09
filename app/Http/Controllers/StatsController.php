<?php
namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Order;
use Illuminate\Http\Request;
use InfluencerMicroservices\UserService;

class StatsController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $user = $this->userService->getUser();
        // dd($user);
        $links = Link::where('user_id', $user->id)->get();
        // dd(Order::where('code', 'E806TO')->get());
        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->get();

            return[
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function (Order $order) {
                    return $order->total;
                })
            ];
        });
    }

    public function rankings()
    {
        $users = collect($this->userService->all(-1));

        $users = $users->filter(function ($user) {
            $user = json_decode(json_encode($user));
            // dd($user);
            return $user->is_influencer;
        });

        $rankings = $users->map(function ($user) {
            $user = json_decode(json_encode($user));
            // dd($user);
            $orders = Order::where('user_id', $user->id)->get();

            return [
                'name' => $user->first_name . ' ' . $user->last_name,
                'revenue' => $orders->sum(function (Order $order) {
                    return (int) $order->total;
                }),
            ];
        });

        return $rankings->sortByDesc('revenue')->values();
    }
}
