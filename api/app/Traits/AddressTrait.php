<?php

namespace App\Traits;
use Illuminate\Http\Request;

use App\Models\Address\IslandGroup;
use App\Models\Address\Region;
use App\Models\Address\Province;
use App\Models\Address\City;
use App\Models\Address\Barangay;
use App\Models\Address\AddressType;

trait AddressTrait
{
    public function IslandGroups(){
        return IslandGroup::select('code', 'name')->orderBy('name', 'ASC')->get();
    }

    public function Regions($islandGroupCode = null){
        $regions = Region::select('code', 'name', 'regionName', 'islandGroupCode')
            ->when($islandGroupCode != null, function ($query) use ($islandGroupCode){
                return $query->where('islandGroupCode', $islandGroupCode);
            })->get();
        return response()->json($regions);
    }

    public function Provinces($regionCode = null){
        $provinces = Province::select('code', 'name', 'isDistrict', 'regionCode', 'islandGroupCode')
            ->where('regionCode', $regionCode)->orderBy('name', 'ASC')->get();
        return response()->json($provinces);
    }

    public function Cities($provinceCode){
        $cities = City::select(
            'code', 'name', 'oldName', 'type_id', 'isCapital',
            'provinceCode', 'regionCode', 'islandGroupCode'
        )->where('provinceCode', $provinceCode)->orderBy('name', 'ASC')->get();
        return response()->json($cities);
    }

    public function Barangays($cityCode){
        $barangays = Barangay::select(
            'code', 'name', 'oldName', 'cityCode', 'provinceCode', 'regionCode', 'islandGroupCode',
            'district', 'telephone_number', 'contact_number', 'lng', 'lat', 'dru'
        )->where('cityCode', $cityCode)->orderBy('name', 'ASC')->get();
        return response()->json($barangays);
    }

    public function getBarangayAddress($code){
        $barangay = Barangay::where('code', $code)->first();

        if(!$barangay){
            return response([
                'message' => "Invalid Barangay Code!"
            ], 422);
        }

        return [
            // 'islandGroup' => $this->IslandGroups(),
            'regions' => $this->Regions(),
            'provinces' => $this->Provinces($barangay->regionCode),
            'cities' => $this->Cities($barangay->provinceCode),
            'barangays' => $this->Barangays($barangay->cityCode),
        ];
    }

    public function getCityAddress($code){
        $city = City::where('code', $code)->first();

        if(!$city){
            return response([
                'message' => "Invalid City Code!"
            ], 422);
        }

        return [
            // 'islandGroup' => $this->IslandGroups(),
            'regions' => $this->Regions(),
            'provinces' => $this->Provinces($city->regionCode),
            'cities' => $this->Cities($city->provinceCode),
            'barangays' => $this->Barangays($city->code),
        ];
    }

    public function getAddressTypes(){
        $types = AddressType::select('id', 'name')->where('active', 1)->get();
        return $types;
    }
}