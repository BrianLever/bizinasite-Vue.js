<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserEcommerceProduct;
use Illuminate\Http\Request;

class EcommerceController extends UserController
{
    public function __construct(UserEcommerceProduct $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(request()->wantsJson())
        {
            return $this->model->getUserDataTable();
        }
        return view(self::$viewDir . "ecommerce.index");
    }
    public function detail($id)
    {
        $item = $this->model->my()
            ->whereId($id)
            ->firstorfail();
        $detail = json_decode($item->item);

        return view(self::$viewDir . "ecommerce.detail", compact("item", "detail"));
    }
}
