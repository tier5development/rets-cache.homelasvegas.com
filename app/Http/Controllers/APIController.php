<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DB;
use App\PropertyDetail;
use App\PropertyImage;
use App\PropertyAdditional;
use App\PropertyExternalFeature;
use App\PropertyFeature;
use App\PropertyFinancialDetail;
use App\PropertyInteriorFeature;
use App\PropertyLocation;
use App\PropertyLatLong;
use App\Citylist;
use App\Jobs\InserSearchList;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd('hiiiii');
        //dd($request->all());
        //$term=
        $term=$request['search_input'];
        
        $myArray ="";
        $postal_code="";
        $community="";

        $termcity=$request['city'];
        //dd($termcity);
        $termlisting_id=$request['listing_id'];
        //dd($termcity.''.$termlisting_id);
        $termlisting_address=$request['address'];
        $termlisting_postal_code=$request['postal_code'];
        if($termlisting_postal_code!=""){
          $postal_code=$termlisting_postal_code;
        }
        $termlisting_search_community=$request['search-community'];
        if ($termlisting_search_community!=""){
            $community=$termlisting_search_community;
        }
        //dd($termlisting_address);
        if ($termlisting_address != ""){
        $myArray = explode(',', $termlisting_address);
        //dd($myArray);
        $termstreet_no=$myArray[0];        
        $termstreet_name=$myArray[1];
        //dd($termstreet_name);
        }
        else{
          $termstreet_no="";  
          $termstreet_name="";
        }
        //dd($term);
        //dd($myArray[0]);
        //dd($termcity.'$'.$termlisting_address);
        $PropertyLocation = PropertyDetail:: where('City', '=',$term)->orWhere('MLSNumber','=',$term)->orWhere('StreetNumber','like','%'.$term.'%')->orWhere('StreetName','like','%'.$term.'%')->orWhere('PostalCode','=',$term)->orWhereHas('propertylocation', function ($query) use ($term) {$query->where('CommunityName', '=',$term);})
    ->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
        //$PropertyLocation = PropertyDetail:: where('City', '=', $termcity)->orWhere('MLSNumber','=',$termlisting_id)->orWhere('StreetNumber','=',$termstreet_no)->orWhere('StreetName','=',$termstreet_name)->orWhereHas('propertylocation', function ($query) use ($community) {$query->where('CommunityName', '=',$community);})
    //->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->toSql(); 
        //dd($PropertyLocation);
        return response()->json($PropertyLocation);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advance_search(Request $request)
    {
        //dd('test');
          $property_type=$request['property_type'];
          //dd($property_type);
          if($property_type=='RES'){
            $property_type='Residential';
          }
          elseif($property_type=='RNT'){
            $property_type='Residential Rental';
          }
          elseif($property_type=='BLD'){
            $property_type='Builder';
          }
          elseif($property_type=='LND'){
            $property_type='Vacant/Subdivided Land';
          }
          elseif($property_type=='MUL'){
            $property_type='Multiple Dwelling';
          }
          elseif($property_type=='VER'){
            $property_type='High Rise';
          }
          //dd($property_type);
          $property_sub_type=$request['property_sub_type'];
          $city=$request['city'];
          $min_price=$request['min_price'];
          $max_price=$request['max_price'];
          $square_feet=$request['square_feet'];
          $acres=$request['acres'];
          $max_days_listed=$request['max_days_listed'];
          $sort_by=$request['sort_by'];
          $status=$request['status'];
          $bedrooms=$request['bedrooms'];
          $bathrooms=$request['bathrooms'];
          if(isset($request['result_per_page']) && $request['result_per_page'] != ""){
            $limit=$request['result_per_page'];
          }else{
            $limit=25;
          }
          
         


            if(isset($offset)){
          $start_page=($offset-1)*$limit;
        }else{
          $offset=0;
        }
$PropertyLocation = PropertyDetail::whereHas('propertyfeature', function ($newquery) use ($property_type) {$newquery->where('PropertyType', '=',$property_type);})->where('City','=',$city)->where('ListPrice','>=',$min_price)->where('ListPrice','<=',$max_price)
    ->with(['propertyfeature','propertyadditional','propertyexternalfeature','propertyimage','propertyfinancialdetail','propertyinteriorfeature','propertyinteriorfeature','propertylatlong','propertylocation'])->get();
    $wordCount = $PropertyLocation->count();
    //dd($wordCount);
        return response()->json($PropertyLocation);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
