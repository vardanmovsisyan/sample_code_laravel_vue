<?php

//Using Laravel Query Builder

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Brand;
use App\ServiceCenter;

class ServiceDataController extends Controller
{
    public function get_distance($first_location, $second_location){
        $theta = $first_location->longitude - $second_location->longitude;
        $miles = (sin(deg2rad($first_location->latitude))
                * sin(deg2rad($second_location->latitude)))
                + (cos(deg2rad($first_location->latitude))
                * cos(deg2rad($second_location->latitude))
                * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;

        return $kilometers;
    }

    public function get_service_center_data($slug){
        $service_center = DB::table('service_centers')
            ->join('cities', 'service_centers.city_id', '=', 'cities.id')
            ->join('city_translations', 'cities.id', '=', 'city_translations.city_id')
            ->join('service_center_translations', 'service_centers.id', '=', 'service_center_translations.service_center_id')
            ->select(
                'service_centers.id as id',
                'service_center_translations.name as name',
                'service_centers.latitude as latitude',
                'service_centers.longitude as longitude',
                'service_centers.phone_number as phone_number',
                'service_center_translations.address as address',
                'service_centers.index as index',
                'city_translations.name as city',
                'cities.id as city_id',
                'service_center_translations.slug as slug'
            )
            ->where('service_center_translations.slug', $slug)
            ->where('service_center_translations.locale', app()->getLocale())
            ->where('city_translations.locale', app()->getLocale())
            ->whereNull('service_centers.deleted_at')
            ->whereNull('cities.deleted_at')
            ->get()
            ->first();

        $brand_ids = ServiceCenter::find($service_center->id)->brands()->select('brands.id as id')->get()->pluck('id');
        $service_center->brands = Brand::whereIn('id',$brand_ids)->get()->pluck('name');

        return response()->json($service_center);
    }

    public function get_cities(){
        $cities = DB::table('cities')
            ->join('city_translations', 'cities.id', '=', 'city_translations.city_id')
            ->join('service_centers', 'cities.id', '=', 'service_centers.city_id')
            ->select('cities.id as id','city_translations.name as name')
            ->groupBy('cities.id','city_translations.name')
            ->orderBy('city_translations.name','asc')
            ->get();
        return response()->json($cities);
    }

    public function get_brands(){
        $brands = Brand::select('id','name')->orderBy('name','asc')->get();
        return response()->json($brands);
    }

    public function get_locations(){
        $locations = ServiceCenter::select('latitude', 'longitude')->get()->toArray();
        return response()->json($locations);
    }

    public function get_locations_array($filter_criteria){
        $criteria = ($filter_criteria)?json_decode($filter_criteria):'';
        $items = DB::table('service_centers')
            ->join('service_center_translations', 'service_centers.id', '=', 'service_center_translations.service_center_id')
            ->join('cities', 'service_centers.city_id', '=', 'cities.id')
            ->join('city_translations', 'cities.id', '=', 'city_translations.city_id')
            ->select(
                'service_centers.id as id',
                'service_center_translations.name as name',
                'service_centers.latitude as latitude',
                'service_centers.longitude as longitude',
                'service_centers.phone_number as phone_number',
                'service_center_translations.address as address',
                'service_centers.index as index',
                'city_translations.name as city',
                'service_center_translations.slug as slug'
            );
        if($criteria->city){
            $items = $items->where('service_centers.city_id', $criteria->city);
        }
        $items = $items->where('service_center_translations.locale', app()->getLocale())
            ->where('city_translations.locale', app()->getLocale())
            ->whereNull('service_centers.deleted_at')
            ->whereNull('cities.deleted_at')
            ->get();
        if($criteria->brand){
            $updated_items = array();
            foreach ($items as $item) {
                $brands_by_service_center = ServiceCenter::find($item->id)
                    ->brands()
                    ->select('brands.id as id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
                if(in_array($criteria->brand, $brands_by_service_center)){
                    array_push($updated_items, $item);
                }
            }
            $items = $updated_items;
        }
        return $items;
    }

    public function get_service_centers(Request $request){
        $criteria = ($request->criteria)?json_decode($request->criteria):'';
        $items = DB::table('service_centers')
            ->join('service_center_translations', 'service_centers.id', '=', 'service_center_translations.service_center_id')
            ->join('cities', 'service_centers.city_id', '=', 'cities.id')
            ->join('city_translations', 'cities.id', '=', 'city_translations.city_id')
            ->select(
                'service_centers.id as id',
                'service_center_translations.name as name',
                'service_centers.latitude as latitude',
                'service_centers.longitude as longitude',
                'service_centers.phone_number as phone_number',
                'service_center_translations.address as address',
                'service_centers.index as index',
                'city_translations.name as city',
                'service_center_translations.slug as slug'
            );

        if($criteria->city){
            $items = $items->where('service_centers.city_id', $criteria->city);
        }
        $items = $items->where('service_center_translations.locale', app()->getLocale())
            ->where('city_translations.locale', app()->getLocale())
            ->whereNull('service_centers.deleted_at')
            ->whereNull('cities.deleted_at')
            ->orderBy('city_translations.name', 'asc')
            ->get();

        if($criteria->brand){
            $updated_items = array();
            foreach ($items as $item) {
                $brands_by_service_center = ServiceCenter::find($item->id)
                    ->brands()
                    ->select('brands.id as id')
                    ->get()
                    ->pluck('id')
                    ->toArray();
                if(in_array($criteria->brand, $brands_by_service_center)){
                    array_push($updated_items, $item);
                }
            }
            $items = $updated_items;
        }
        return response()->json($items);
    }

    public function insertion_sort($array) {
        for($i=0; $i < count($array); $i++){
            $val = $array[$i];
            $j = $i-1;
            while($j>=0 && $array[$j]->distance > $val->distance){
                $array[$j+1] = $array[$j];
                $j--;
            }
            $array[$j+1] = $val;
        }
        return $array;
    }

    public function get_nearest_service_centers(Request $request){
        $user_location = new \stdClass();
        $user_location->latitude = $request->latitude;
        $user_location->longitude = $request->longitude;

        $all_locations = $this->get_locations_array($request->criteria);

        $sorted_locations = array();
        foreach($all_locations as $i => $location){
            $distance = $this->get_distance($user_location, $location);
            $location->distance = $distance;
            if($distance <= 100){
                array_push($sorted_locations, $location);
            }
        }
        $sorted_locations = $this->insertion_sort($sorted_locations);
        return response()->json($sorted_locations);
    }
}
