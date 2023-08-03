<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class DetailKelasController extends Controller
{
    public function index(){
        try {
            $response = Http::get('http://ecocim-backend-theone.beit.co.id/api/ManualConfig/TestBEIT');
        
            if ($response->successful()) {
                // API call was successful (status code 2xx)
                $responseData = $response->json();
                $array_kelas = [];
                $mati_bulan_ini = [];

                $menikah_tahun_depan = [];
                
                for($i = 0; $i < count($responseData["listNama"]); $i++) {
                    $nama = $responseData["listNama"][$i];
                    $nilai = $responseData["listNilai"][$i];
                    $mati_tahun_ini = false;
                    $bulan_mati = $nilai % 10;

                    if(isPrime($nilai)){
                        $mati_tahun_ini = true;
                    }

                    $containsSAndO = true;
                    if (strpos($nama, 'C') === false || strpos($nama, 'O') === false) {
                        $containsSAndO = false;
                    }
                    
                    if ($containsSAndO) {
                        $kelas = "Khusus";
                        $array_kelas[$kelas][$nama] = $nilai;

                        if(fmod($nilai, 7) === 0){
                            $menikah_tahun_depan[$name] = [$kelas, $nilai];
                        }
                    } else {
                        $kelas = "kelas " . fmod(floor($responseData["listNilai"][$i] / 10), 10);
                        $array_kelas[$kelas][$nama] = $nilai;

                    }

                    if(($mati_tahun_ini && $bulan_mati == date('n') )){
                        $mati_bulan_ini[$nama] = [$nilai, $kelas];
                    }

                }

                return response()->json([
                    "Detail Kelas" => $array_kelas, 
                    "Menikah Tahun Depan" => $menikah_tahun_depan,
                    "Mati Bulan Ini" => $mati_bulan_ini
                ], 200);

            } else {
                // API call was not successful (status code not 2xx)
                echo 'API call failed with status code: ' . $response->status();
            }
        } catch (Exception $e) {
            // An exception occurred during the API call
            echo 'API call threw an exception: ' . $e->getMessage();
        }
        
       
        
    }
    
}

function isPrime($number) {

    if ($number <= 1) {
        return false;
    }

    for ($i = 2; $i * $i <= $number; $i++) {
        if ($number % $i == 0) {
            return false; 
        }
    }

    return true; 
}