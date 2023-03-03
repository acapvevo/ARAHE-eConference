<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Package;
use App\Models\Category;
use App\Models\Duration;
use App\Traits\FormTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    use FormTrait;

    public function view($form_id)
    {
        $form = $this->getForm($form_id);

        return view('admin.competition.package.view')->with([
            'form' => $form
        ]);
    }

    public function modify(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'categories' => 'array|required',
            'categories.*.name' => 'required|string',
            'categories.*.code' => 'required|alpha',
            'categories.*.locality' => 'required|string|exists:locality,code',
            'categories.*.needProof' => 'sometimes|nullable|boolean',
            'categories.*.needLink' => 'sometimes|nullable|boolean',
            'durations' => 'array|required',
            'durations.*.name' => 'required|string',
            'durations.*.locality' => 'required|string|exists:locality,code',
            'durations.*.start' => 'required|date',
            'durations.*.end' => 'required|date',
        ]);

        $form = $this->getForm($request->form_id);

        //Category
        if (count($request['categories']) >= $form->categories->count()) {
            for ($i = 0; $i < count($request['categories']); $i++) {

                if ($form->categories->has($i)) {
                    $category = $form->categories[$i];
                } else {
                    $category = new Category;
                    $category->form_id = $form->id;
                }

                $category->name = $request['categories'][$i]['name'];
                $category->code = $request['categories'][$i]['code'];
                $category->locality = $request['categories'][$i]['locality'];
                $category->needProof = $request['categories'][$i]['needProof'] ?? false;
                $category->needLink = $request['categories'][$i]['needLink'] ?? false;

                $category->save();
            }
        } else {
            for ($i = 0; $i < count($request['categories']); $i++) {

                $category = $form->categories[$i];

                $category->name = $request['categories'][$i]['name'];
                $category->code = $request['categories'][$i]['code'];
                $category->locality = $request['categories'][$i]['locality'];
                $category->needProof = $request['categories'][$i]['needProof'] ?? false;
                $category->needLink = $request['categories'][$i]['needLink'] ?? false;

                $category->save();
            }

            for ($i = (count($request['categories']) - 1); $i < $form->categories->count(); $i++) {
                $category = $form->categories[$i];
                $category->delete();
            }
        }

        //Duration
        if (count($request['durations']) >= $form->durations->count() - 1) {
            for ($i = 0; $i < count($request['durations']); $i++) {

                if ($form->durations->has($i)) {
                    $duration = $form->durations[$i];
                } else {
                    $duration = new Duration;
                    $duration->form_id = $form->id;
                }

                $duration->name = $request['durations'][$i]['name'];
                $duration->locality = $request['durations'][$i]['locality'];
                $duration->start = $request['durations'][$i]['start'];
                $duration->end = $request['durations'][$i]['end'];

                $duration->save();
            }
        } else {
            for ($i = 0; $i < count($request['durations']); $i++) {

                $duration = $form->durations[$i];

                $duration->name = $request['durations'][$i]['name'];
                $duration->locality = $request['durations'][$i]['locality'];
                $duration->start = $request['durations'][$i]['start'];
                $duration->end = $request['durations'][$i]['end'];

                $duration->save();
            }

            for ($i = (count($request['durations']) - 1); $i < $form->durations->count() - 1; $i++) {
                $duration = $form->durations[$i];
                $duration->delete();
            }
        }

        // create @ update Duration for On-site for Local and International
        $OnSiteDurationLocal = $form->durations->where('locality', 'L')->first(function($duration){
            return $duration->name == 'On-site';
        });
        $latestDurationLocal = $form->durations->where('locality', 'L')->sortByDesc('end')->first();

        if(!isset($OnSiteDurationLocal)){
            $OnSiteDurationLocal = new Duration;

            $OnSiteDurationLocal->name = 'On-site';
            $OnSiteDurationLocal->locality = 'L';
            $OnSiteDurationLocal->form_id = $form->id;
        }

        $OnSiteDurationLocal->start = $latestDurationLocal->end->addDay();
        $OnSiteDurationLocal->end = $form->session->returnDateObj('congress', 'end');

        $OnSiteDurationLocal->save();

        $OnSiteDurationInternational = $form->durations->where('locality', 'I')->first(function($duration){
            return $duration->name == 'On-site';
        });
        $latestDurationInternational = $form->durations->where('locality', 'I')->sortByDesc('end')->first();

        if(!isset($OnSiteDurationInternational)){
            $OnSiteDurationInternational = new Duration;

            $OnSiteDurationInternational->name = 'On-site';
            $OnSiteDurationInternational->locality = 'I';
            $OnSiteDurationInternational->form_id = $form->id;
        }

        $OnSiteDurationInternational->start = $latestDurationInternational->end->addDay();
        $OnSiteDurationInternational->end = $form->session->returnDateObj('congress', 'end');

        $OnSiteDurationInternational->save();

        $form->updateDurationsInStripe();

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Durations and Categories details for Year ' . $form->session->year . ' was updated successfully');
    }

    public function update(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'packages' => 'required|array',
            'packages.*.category_id' => 'required|integer|exists:categories,id',
            'packages.*.description' => 'required|string|max:255',
            'packages.*.code' => 'required|string|max:3',
            'packages.*.fullPackage' => 'sometimes|nullable|boolean',
        ]);

        $form = $this->getForm($request->form_id);

        if (count($request['packages']) >= $form->getPackages()->count()) {
            for ($i = 0; $i < count($request['packages']); $i++) {

                if ($form->getPackages()->has($i)) {
                    $package = $form->getPackages()[$i];
                } else {
                    $package = new Package;
                }

                $package->category_id = $request['packages'][$i]['category_id'];
                $package->description = $request['packages'][$i]['description'];
                $package->code = $request['packages'][$i]['code'];
                $package->fullPackage = $request['packages'][$i]['fullPackage'] ?? false;

                $package->save();
            }
        } else {
            for ($i = 0; $i < count($request['packages']); $i++) {

                $package = $form->getPackages()[$i];

                $package->category_id = $request['packages'][$i]['category_id'];
                $package->description = $request['packages'][$i]['description'];
                $package->code = $request['packages'][$i]['code'];
                $package->fullPackage = $request['packages'][$i]['fullPackage'] ?? false;

                $package->save();
            }

            for ($i = (count($request['packages']) - 1); $i < $form->getPackages()->count(); $i++) {
                $package = $form->getPackages()[$i];
                $package->delete();
            }
        }

        $form->updateCategoiesInStripe();

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Packages details for Year ' . $form->session->year . ' was updated successfully');
    }
}
