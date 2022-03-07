<?php
namespace App\Http\Controllers;

use App\Models\Link;
use App\Jobs\LinkCreated;
use App\Models\LinkProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\LinkResource;
use InfluencerMicroservices\UserService;

class LinkController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request)
    {
        $user = $this->userService->getUser();

        $link = Link::create([
            'user_id' => $request->user('api')->id,
            'code' => Str::random(6)
        ]);
        $linkProducts = [];

        foreach ($request->input('products') as $product_id) {
            $linkProduct = LinkProduct::create([
                'link_id' => $link->id,
                'product_id' => $product_id
            ]);
            $linkProducts[] = $linkProduct->toArray();
        }
        LinkCreated::dispatch($link, $linkProducts)->onQueue('checkout_queue');
        return new LinkResource($link);
    }
}
