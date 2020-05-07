<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;

class LocationController extends Controller
{
    public function searchLocations(Request $request){
        $location = Location::query();
        if($request->has('loc') && !empty($request->input('loc')))
            $location->Where('area', 'like', '%' . $request->input('loc') . '%');
        $result = $location->take(15)->get();
        return $result->toJSON();
    }
}
