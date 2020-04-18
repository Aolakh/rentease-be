<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
class PropertyController extends Controller
{
    public function index(){
        return Property::all();
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

    public function search(Request $request){
        $property = Property::query();
        if($request->has('loc'))
            $property->Where('location', 'like', '%' . $request->input('loc') . '%');
        if($request->has('minp'))
            $property->where('rent','>=', (int) $request->input('minp'));
        if($request->has('maxp'))
            $property->where('rent','<=', (int) $request->input('maxp'));
        if($request->has('oc_status'))
            $property->where('occupancy_status', $request->input('oc_status'));
        $result = $property->get();
        return $result->toJSON();
    }
    
}
