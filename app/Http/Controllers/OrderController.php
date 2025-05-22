<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('product')->get();
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('order.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        Order::create($request->all());

        return redirect()->route('order.index')->with('success','Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('order.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::all();
        return view('order.edit', compact('order','products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        $order->update($request->all());

        return redirect()->route('order.index')->with('success','Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('order.index')->with('success','Order deleted successfully.');
    }

    public function status(Request $request)
    {
        Order::where('id', $request->id)->update(['status' => 1]);
        return redirect()->route('order.index')->with('success','Status changed successfully.');
    }
}
