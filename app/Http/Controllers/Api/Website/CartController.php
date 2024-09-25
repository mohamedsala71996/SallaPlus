<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\OurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Add a service to the cart.
     */
    public function add(StoreCartRequest $request)
    {
        $validated = $request->validated();
        $service = OurService::find($validated['service_id']);

        // Check if the service exists
        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        // Retrieve or generate cart token
        $cartToken = $request->cookie('cart_token');

        if (!$cartToken) {
            $cartToken = Str::random(32);
            // Store cart token in a cookie
            Cookie::queue('cart_token', $cartToken, 60 * 24 * 30); // Cookie expires in 30 days
        }

        // Check if the service already exists in the cart for the user or token
        $cartItem = Cart::where('cart_token', $cartToken)
            ->where('service_id', $validated['service_id'])
            ->first();

        if ($cartItem) {
            // If it exists, do nothing or update if needed
            return response()->json(['message' => 'Service is already in the cart.']);
        } else {
            // Otherwise, create a new cart item
            Cart::create([
                'cart_token' => $cartToken,
                'service_id' => $validated['service_id'],
            ]);
        }

        return response()->json(['message' => 'Service added to cart successfully.']);
    }
    /**
     * View the cart items for the user.
     */
    public function view(Request $request)
    {
        $cartToken = $request->cookie('cart_token');

        if (!$cartToken) {
            return response()->json(['message' => 'Cart is empty.'], 404);
        }

        $cartItems = Cart::with('service')
            ->where('cart_token', $cartToken)
            ->get();

        return response()->json([
            'cart_items' => CartResource::collection($cartItems),
        ]);
    }

    /**
     * Remove a service from the cart.
     */
    public function remove($id, Request $request)
    {
        $cartToken = $request->cookie('cart_token');
        if (!$cartToken) {
            return response()->json(['message' => 'Cart token not found.'], 404);
        }

        $cartItem = Cart::where('id', $id)
            ->where('cart_token', $cartToken)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Service removed from cart successfully.']);
    }

    public function generateCartSummary($cartItems)
    {
        $totalAmount = 0;
        $summary = "ملخص سلة التسوق الخاصة بك:\n\n";

        foreach ($cartItems as $item) {
            $service = $item->service;
            $summary .= "الخدمة: {$service->name}\n";
            $summary .= "السعر: {$service->price} \n";
            $totalAmount += $service->price; // Add the price to the total amount
            $summary .= "--------------------\n";
        }

        $summary .= "إجمالي المبلغ: {$totalAmount}\n";
        $summary .= "شكراً لتسوقك معنا!";

        return $summary;
    }

    public function generateWhatsAppLink(Request $request)
    {
        $cartToken = $request->cookie('cart_token');

        if (!$cartToken) {
            return response()->json(['message' => 'لم يتم العثور على رمز السلة.'], 404);
        }

        $cartItems = Cart::with('service')
            ->where('cart_token', $cartToken)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'السلة فارغة.'], 404);
        }

        $summary = $this->generateCartSummary($cartItems);
        $encodedMessage = urlencode($summary);

        // Replace with the recipient's phone number (include country code)
        $recipientPhoneNumber = $request->input('phone_number');

        $whatsappLink = "https://wa.me/$recipientPhoneNumber?text=$encodedMessage";

        return response()->json([
            'whatsapp_link' => $whatsappLink
        ]);
    }


}
