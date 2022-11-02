<?php

namespace App\Traits;

use App\Models\Category;

trait CategoryTrait
{
    use PaymentTrait;

    public function generateCategory($form)
    {
        $name = 'ARAHE' . $form->session->year;
        $description = "Payment for participation of ARAHE". $form->session->year;

        $response = $this->createCategory($name, $description);

        Category::create([
            'form_id' => $form->id,
            'name' => $name,
            'description' => $description,
            'code' => $response->CategoryCode,
        ]);
    }
}
