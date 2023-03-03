<?php

namespace App\Plugins;

use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

class Stripes
{
    static function setSecretKey()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    static function getClient()
    {
        return new StripeClient(env('STRIPE_SECRET'));
    }

    static function createProduct($name, $description)
    {
        $stripe = self::getClient();

        $product = $stripe->products->create([
            'name' => $name,
            'description' => $description,
        ]);

        return $product;
    }

    static function updateProduct($product_id, $name, $description)
    {
        $stripe = self::getClient();

        $stripe->products->update(
            $product_id,
            [
                'name' => $name,
                'description' => $description,
            ]
        );
    }

    static function createPrice($amount, $currency, $product_id)
    {
        $stripe = self::getClient();

        $price = $stripe->prices->create([
            'unit_amount' => $amount * 100,
            'currency' => $currency,
            'product' => $product_id,
        ]);

        return $price->id;
    }

    static function createCheckoutSession($line_items, $summary)
    {
        $stripe = self::getClient();

        return $stripe->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('participant.payment.pay.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('participant.payment.pay.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'summary_id' => $summary->id,
            ]
        ]);
    }
}
