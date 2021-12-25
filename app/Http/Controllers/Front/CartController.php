<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use Illuminate\Http\Request;
use Validator;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.cart.index');
    }
    public function getData()
    {
        $cart = session("cart");
        $data['view'] = view("components.front.cartTable", compact("cart"))->render();
        $data['oneTotal'] = "$" . formatNumber($cart->onetimeTotalPrice?? 0);
        $data['subTotal'] = "$" . formatNumber($cart->subTotalPrice?? 0);
        $data['total'] = "$" . formatNumber($cart->totalPrice?? 0);

        return response()->json(['status'=>1, 'data'=>$data]);
    }
    public function remove(Request $request)
    {
        $oldCart = session("cart");
        $cart = new Cart($oldCart);
        $cart->removeOne($request->id);

        session()->put(['cart'=>$cart]);

        return response()->json([
            'status'=>1,
            'data'=>tenant()->getHeader()
        ]);
    }
    public function empty()
    {
        session()->forget("cart");

        foreach(session()->all() as $key => $value)
        {
            if (strpos($key, 'paypal') === 0)
            {
                session()->forget($key);
            }
        }

        return response()->json([
            'status'=>1,
            'data'=>tenant()->getHeader()
        ]);
    }
    public function update(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'items'=>'required',
                'items.*'=>'required|integer|min:1'
            ], ['items.*.*'=>'Choose correct quantity', 'items.*'=>'Cart is empty!']);

            if($validation->fails()) return response()->json(['status'=>0, 'data'=>$validation->errors()]);

            $oldCart = session("cart");
            $cart = new Cart($oldCart);
            $cart->updateCart($request->items);

            session()->put(["cart"=>$cart]);

            return response()->json([
                'status'=>1,
                'data'=>tenant()->getHeader()
            ]);

        }catch(\Exception $e)
        {
            return response()->json([
                'status'=>0,
                'data'=>[json_encode($e->getMessage())]
            ]);
        }
    }
    public function checkout()
    {
        $cart = session("cart");
        if($cart==null||$cart->totalQty==0) return redirect()->route('cart.index')->with("info", "Your cart is empty1");

        $gateway = option("gateway", []);

        $stripe = option("stripe", null);
        $stripe_pk = optional($stripe)['public'];

        return view('frontend.cart.checkout', compact("cart", "gateway", "stripe_pk"));
    }
}
