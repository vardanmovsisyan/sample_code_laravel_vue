<?php

//A Resource controller of a Translatable Model, with Soft Deletes
//Using Laravel Eloquent

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\ServiceCenter;
use App\City;
use App\Brand;

class ServiceCentersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        City::enableAutoloadTranslations();
        $service_centers = ServiceCenter::withTranslation()->with('city')
                        ->whereHas('city', function ($query) {
                            $query->whereNull('deleted_at');
                        })->orderBy('id','asc')->get();
        return response()->json($service_centers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new \stdClass();
        $data->brands = Brand::orderBy('name','asc')->get();
        $data->cities = City::listsTranslations('name')->orderBy('name','asc')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service_center = ServiceCenter::create([
            'city_id' => $request->input('city_id'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'phone_number' => $request->input('phone_number'),
            'index' => $request->input('index')
        ]);

        foreach (config('translatable.locales') as $locale) {
            $name = $locale.'_name';
            $address = $locale.'_address';
            $slug = $locale.'_slug';

            if($request->input($name)) {
                $service_center->translateOrNew($locale)->name = $request->input($name);
                $service_center->translateOrNew($locale)->address = $request->input($address);
                $service_center->translateOrNew($locale)->slug = Str::slug($request->input($name).$request->input($address));
            }
        }
        $service_center->brands()->attach($request->brands);
        $service_center->save();

        return response()->json('1');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = new \stdClass();
        $data->service_center = ServiceCenter::withTranslation()->find($id);
        $data->service_center->brands = ServiceCenter::find($id)->brands()->select('brands.id as id')->get()->pluck('id');
        $data->brands = Brand::all();
        $data->cities = City::listsTranslations('name')->orderBy('name','asc')->get();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service_center = ServiceCenter::find($id);

        $service_center->city_id = $request->input('city_id');
        $service_center->latitude = $request->input('latitude');
        $service_center->longitude = $request->input('longitude');
        $service_center->phone_number = $request->input('phone_number');
        $service_center->index = $request->input('index');

        foreach (config('translatable.locales') as $locale) {
            if($service_center->hasTranslation($locale)) {
                $name = $locale . '_name';
                $address = $locale . '_address';
                $slug = $locale . '_slug';

                $service_center->getTranslation($locale)->name = $request->input($name);
                $service_center->getTranslation($locale)->address = $request->input($address);
                $service_center->getTranslation($locale)->slug = Str::slug($request->input($name).$request->input($address));
            }
        }
        $service_center->brands()->sync($request->brands);

        $service_center->save();
        return response()->json('1');
    }

    /**
     * Soft delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service_center = ServiceCenter::find($id);
        $service_center->delete();

        return response()->json('1');
    }
}
