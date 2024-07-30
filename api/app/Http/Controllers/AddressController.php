<?php

namespace App\Http\Controllers;

use App\Models\Address\Barangay;
use Illuminate\Http\Request;

use App\Traits\AddressTrait;

class AddressController extends Controller
{
    use AddressTrait;

    public function listBarangay(){
        $barangay = Barangay::where('cityCode','141102000')->get();
        return response()->json([
            'barangay' => $barangay,
            'message' => 'any'
        ]);
    }
    // public function getBarangayAddress(){
    //     // $barangay = Barangay::all();
    //     $island_group = $this->IslandGroups();
    //     // $barangay = $this->Barangays();

    //     return response()->json([
    //         'barangay' => $island_group
    //     ]);
    // }
    
}
