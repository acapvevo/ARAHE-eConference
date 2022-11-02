<?php

namespace App\Traits;

use App\Models\Category;
use Tarsoft\Toyyibpay\ToyyibpayFacade as Toyyibpay;

trait CategoryTrait
{
    public function createCategory($form)
    {
        $name = 'ARAHE' . $form->session->year;
        $description = "Payment for participation of ARAHE". $form->session->year;

        $response = Toyyibpay::createCategory($name, $description);

        Category::create([
            'form_id' => $form->id,
            'name' => $name,
            'description' => $description,
            'code' => $response->CategoryCode,
        ]);
    }
}
