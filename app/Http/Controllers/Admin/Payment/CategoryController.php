<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Models\Category;
use Illuminate\Http\Request;
use Tarsoft\Toyyibpay\ToyyibpayFacade as Toyyibpay;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function list()
    {
        $categories = Category::all();

        return view('admin.payment.category.list')->with([
            'categories' => $categories,
        ]);
    }

    public function view($id)
    {
        $category = Category::find($id);
        $infoToyyibpay = Toyyibpay::getCategory($category->code);

        return view('admin.payment.category.view')->with([
            'category' => $category,
            'infoToyyibpay' => $infoToyyibpay,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'standardAmount' => 'required|numeric|min:10|max:1000'
        ]);

        $category = Category::find($id);

        $category->standardAmount = $request->standardAmount;

        $category->save();

        return redirect(route('admin.payment.category.view', ['id' => $category->id]))->with('success', 'This Category has been successfully updated');
    }
}
