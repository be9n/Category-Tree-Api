<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return auth()->user()->orders->load('products');
    }

    public function show(Order $order)
    {

    }
}
