<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;

class PropertyController extends Controller
{
    public function index(){
        return Property::all();
    }

    public function store(Request $request){
        $user = JWTAuth::parseToken()->authenticate()->toArray();
        if(!empty($user)){
            $property = new Property();
            $property->title = $request->get('title');
            $property->rent = (int) $request->get('rent');
            $property->size = $request->get('size');
            $property->location = $request->get('location');
            $property->occupancy_status = $request->get('occupancy_status');
            $property->contact_information = [
                'owner_name' => $user['name'],
                'owner_phone' => $user['phone'],
            ];
            $property->save();
            return response()->json($property);
        }
        return response()->json(['user_not_found'], 400);
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
