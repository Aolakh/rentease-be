<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
class PropertyController extends Controller
{
    public function index(){
        $properties = Property::all()->toJSON();
        print_r($properties);
    }

    public function store(Request $request){
        $property = new Property();
        $property->title = $request->get('title');
        $property->rent = (int) $request->get('rent');
        $property->size = $request->get('size');
        $property->location = $request->get('location');
        $property->occupancy_status = $request->get('occupancy_status');
        $property->contact_information = [
            'owner_name' => $request->input('contact_information.owner_name'),
            'owner_phone' => $request->input('contact_information.owner_phone'),
        ];
        
        $property->save();
        return response()->json($property);

    }

    public function findProperty(Request $request){
        $property = new Property();
        $property = Property::where($request->get('by'), $request->get('cond'))->get();
        return response()->json($property);
    }
    
}
