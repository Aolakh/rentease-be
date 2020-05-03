<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use Illuminate\Support\Facades\Validator;

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

            $validator = Validator::make($request->json()->all(),[
                'title' => 'required|string|max:255|min:5',
                'rent' => 'required|integer',
                'size' => 'required|integer',
                'location' => 'required|string|max:255|min:7',
                'contact_information.owner_name' => 'required|string|max:255',
                'contact_information.owner_phone' => 'required|string|max:255',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $property = new Property();
            $property->title = $request->get('title');
            $property->rent = (int) $request->get('rent');
            $property->size = $request->get('size');
            $property->location = $request->get('location');
            $property->occupancy_status = 'available';
            $property->contact_information = [
                "id" => $user['_id'],
                "name" => $request->input('contact_information.owner_name'),
                "phone" => $request->input('contact_information.owner_phone'),
            ];
            $property->save();
            return response()->json($property);
        }
        return response()->json(['user_not_found'], 400);
    }

    public function search(Request $request){
        $property = Property::query();


        if($request->has('loc') && !empty($request->input('loc')))
            $property->Where('location', 'like', '%' . $request->input('loc') . '%');

        if($request->has('minp') && !empty($request->input('minp')))
            $property->where('rent','>=', (int) $request->input('minp'));

        if($request->has('maxp') && !empty($request->input('maxp')))
            $property->where('rent','<=', (int) $request->input('maxp'));

        if($request->has('oc_status') && !empty($request->input('oc_status')))
            $property->where('occupancy_status', $request->input('oc_status'));

        if($request->has('size') && !empty($request->input('size')))
            $property->where('size', (int) $request->input('size'));

        $result = $property->get();
        return $result->toJSON();
    }
    
}
