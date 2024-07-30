<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\AddressModelTrait;
use App\Models\Quitter;
use App\Models\Report;

class Barangay extends Model
{
    use HasFactory, AddressModelTrait;

    protected static $sync_urls = ['https://psgc.gitlab.io/api/barangays.json'];
    protected static $sync_uniques = ['code'];
    protected static $sync_fields = [
        "name", "oldName", "cityCode", "provinceCode",
        "regionCode", "islandGroupCode", "psgc10DigitCode",
    ];

    protected $fillable = [
        "code",
        "name",
        "oldName",
        "cityCode",
        "provinceCode",
        "regionCode",
        "islandGroupCode",
        "psgc10DigitCode",

        "district",
        "telephone_number",
        "contact_number",
        "lng",
        "lat",
        "dru",
    ];

    protected static function sync_fix(){
        $province_fix = [
            // City of Cotabato
            '129804000' => [
                'provinceCode' => '124700000', // Province of Cotabato
            ],
            // City of Isabela
            '099701000' => [
                // Geographically located in Basilan but Administratively part of Region IX(Zamboanga peninsula)
                // Lets just put it in Basilan
                'provinceCode' => '150700000',
            ],
        ];

        $additional_info = [
            "141102001" => [
                "district" => "12",
                "telephone_number" => null,
                "contact_number" => "0930 363 1559",
                "lng" => "120.62457600",
                "lat" => "16.37738900",
                "dru" => "2"
            ],
            "141102002" => [
                "district" => "15",
                "telephone_number" => "(074) 422 1993",
                "contact_number" => "0948 710 2809",
                "lng" => "120.56532700",
                "lat" => "16.40439200",
                "dru" => "1"
            ],
            "141102003" => [
                "district" => "12",
                "telephone_number" => null,
                "contact_number" => "0995 821 2306",
                "lng" => "120.63041200",
                "lat" => "16.37914400",
                "dru" => "2"
            ],
            "141102004" => [
                "district" => "14",
                "telephone_number" => null,
                "contact_number" => "0908 202 0207",
                "lng" => "120.58243300",
                "lat" => "16.39636900",
                "dru" => "3"
            ],
            "141102005" => [
                "district" => "14",
                "telephone_number" => "(074) 424 6899",
                "contact_number" => "0938 625 3919",
                "lng" => "120.59072400",
                "lat" => "16.39172500",
                "dru" => "3"
            ],
            "141102006" => [
                "district" => "11",
                "telephone_number" => null,
                "contact_number" => "0927 465 6263",
                "lng" => "120.62406400",
                "lat" => "16.39586900",
                "dru" => "16"
            ],
            "141102007" => [
                "district" => "4",
                "telephone_number" => null,
                "contact_number" => "0966 624 8157",
                "lng" => "120.59193800",
                "lat" => "16.39948500",
                "dru" => "6"
            ],
            "141102008" => [
                "district" => "9",
                "telephone_number" => null,
                "contact_number" => "0908 345 5244",
                "lng" => "120.59966100",
                "lat" => "16.43125200",
                "dru" => "4"
            ],
            "141102010" => [
                "district" => "9",
                "telephone_number" => "(074) 661 7045",
                "contact_number" => "0927 401 7870",
                "lng" => "120.60814500",
                "lat" => "16.42725300",
                "dru" => "4"
            ],
            "141102011" => [
                "district" => "19",
                "telephone_number" => "(074) 665 2126",
                "contact_number" => "0918 352 1693",
                "lng" => "120.60884600",
                "lat" => "16.42535300",
                "dru" => "4"
            ],
            "141102012" => [
                "district" => "19",
                "telephone_number" => null,
                "contact_number" => "0947 745 1375",
                "lng" => "120.60289200",
                "lat" => "16.42034300",
                "dru" => "4"
            ],
            "141102013" => [
                "district" => "2",
                "telephone_number" => "(074) 422 7356",
                "contact_number" => "0949 648 5365",
                "lng" => "120.60920800",
                "lat" => "16.41248700",
                "dru" => "7"
            ],
            "141102016" => [
                "district" => "6",
                "telephone_number" => "(074) 442 9799",
                "contact_number" => "0921 582 5176",
                "lng" => "120.59194400",
                "lat" => "16.41654800",
                "dru" => "5"
            ],
            "141102017" => [
                "district" => "13",
                "telephone_number" => "(074) 665 2258",
                "contact_number" => "0909 932 2367",
                "lng" => "120.60670100",
                "lat" => "16.38187800",
                "dru" => "10"
            ],
            "141102018" => [
                "district" => "13",
                "telephone_number" => null,
                "contact_number" => "0998 230 6034",
                "lng" => "120.60030500",
                "lat" => "16.39812500",
                "dru" => "7"
            ],
            "141102019" => [
                "district" => "6",
                "telephone_number" => null,
                "contact_number" => "0935 837 9969",
                "lng" => "120.58819800",
                "lat" => "16.41479600",
                "dru" => "5"
            ],
            "141102022" => [
                "district" => "5",
                "telephone_number" => "(074) 445 5879",
                "contact_number" => "0907 448 0049",
                "lng" => "120.58726900",
                "lat" => "16.41107300",
                "dru" => "6"
            ],
            "141102023" => [
                "district" => "5",
                "telephone_number" => "(074) 244 8233",
                "contact_number" => "0966 722 0595",
                "lng" => "120.58962100",
                "lat" => "16.41086800",
                "dru" => "6"
            ],
            "141102025" => [
                "district" => "11",
                "telephone_number" => "(074) 422 3848",
                "contact_number" => "0908 775 5477",
                "lng" => "120.61926200",
                "lat" => "16.40952700",
                "dru" => "13"
            ],
            "141102026" => [
                "district" => "7",
                "telephone_number" => null,
                "contact_number" => "0919 785 2377 / 0910 387 4616",
                "lng" => "120.58748900",
                "lat" => "16.42052500",
                "dru" => "5"
            ],
            "141102028" => [
                "district" => "3",
                "telephone_number" => null,
                "contact_number" => "0921 520 2709",
                "lng" => "120.60759300",
                "lat" => "16.39460300",
                "dru" => "16"
            ],
            "141102029" => [
                "district" => "2",
                "telephone_number" => "(074) 446 5371",
                "contact_number" => "0915 269 7079",
                "lng" => "120.60414600",
                "lat" => "16.40518300",
                "dru" => "7"
            ],
            "141102030" => [
                "district" => "8",
                "telephone_number" => "(074) 300 5517",
                "contact_number" => "0998 576 1520",
                "lng" => "120.58906400",
                "lat" => "16.42488600",
                "dru" => "15"
            ],
            "141102034" => [
                "district" => "8",
                "telephone_number" => "(074) 661 0330",
                "contact_number" => "0928 148 0414",
                "lng" => "120.59338700",
                "lat" => "16.43104800",
                "dru" => "15"
            ],
            "141102035" => [
                "district" => "2",
                "telephone_number" => "(074) 422 8228",
                "contact_number" => "0995 363 0106",
                "lng" => "120.60234600",
                "lat" => "16.40764400",
                "dru" => "7"
            ],
            "141102036" => [
                "district" => "6",
                "telephone_number" => null,
                "contact_number" => "0938 328 3019",
                "lng" => "120.58381500",
                "lat" => "16.41583300",
                "dru" => "14"
            ],
            "141102037" => [
                "district" => "12",
                "telephone_number" => "(074) 619 0608",
                "contact_number" => "0943 706 7634",
                "lng" => "120.61855500",
                "lat" => "16.36818900",
                "dru" => "2"
            ],
            "141102038" => [
                "district" => "10",
                "telephone_number" => "(074) 304 1471",
                "contact_number" => "0997 395 7079",
                "lng" => "120.60151400",
                "lat" => "16.41278300",
                "dru" => "7"
            ],
            "141102039" => [
                "district" => "10",
                "telephone_number" => "(074) 661 4588",
                "contact_number" => "0949 472 7006",
                "lng" => "120.59930700",
                "lat" => "16.41361200",
                "dru" => "11"
            ],
            "141102040" => [
                "district" => "1",
                "telephone_number" => "(074) 443 5630",
                "contact_number" => "0947 277 4129",
                "lng" => "120.62328100",
                "lat" => "16.41810800",
                "dru" => "9"
            ],
            "141102041" => [
                "district" => "2",
                "telephone_number" => "(074) 661 3656",
                "contact_number" => "0949 953 6441",
                "lng" => "120.60668300",
                "lat" => "16.40137500",
                "dru" => "7"
            ],
            "141102042" => [
                "district" => "7",
                "telephone_number" => "(074) 442 0082",
                "contact_number" => "0950 060 0551",
                "lng" => "120.58590600",
                "lat" => "16.42211900",
                "dru" => "12"
            ],
            "141102043" => [
                "district" => "7",
                "telephone_number" => null,
                "contact_number" => "0950 299 7841",
                "lng" => "120.58400600",
                "lat" => "16.42108900",
                "dru" => "5"
            ],
            "141102045" => [
                "district" => "3",
                "telephone_number" => "(074) 422 4463",
                "contact_number" => "0908 957 9242",
                "lng" => "120.60439400",
                "lat" => "16.39743100",
                "dru" => "16"
            ],
            "141102046" => [
                "district" => "20",
                "telephone_number" => "(074) 665 3539 ",
                "contact_number" => "0956 300 1362",
                "lng" => "120.60432400",
                "lat" => "16.41772500",
                "dru" => "11"
            ],
            "141102047" => [
                "district" => "20",
                "telephone_number" => "(074) 422 7887",
                "contact_number" => "0908 457 8201",
                "lng" => "120.60079800",
                "lat" => "16.41562600",
                "dru" => "11"
            ],
            "141102049" => [
                "district" => "20",
                "telephone_number" => "(074) 619 2089",
                "contact_number" => "0995 855 9710",
                "lng" => "120.60722400",
                "lat" => "16.41770800",
                "dru" => "11"
            ],
            "141102050" => [
                "district" => "16",
                "telephone_number" => "(074) 445 7247",
                "contact_number" => "0930 100 0000",
                "lng" => "120.55684800",
                "lat" => "16.42031300",
                "dru" => "8"
            ],
            "141102051" => [
                "district" => "6",
                "telephone_number" => null,
                "contact_number" => "0918 812 4766 / 0908 149 4545",
                "lng" => "120.58942400",
                "lat" => "16.41466200",
                "dru" => "6"
            ],
            "141102052" => [
                "district" => "12",
                "telephone_number" => "(074) 422 5059",
                "contact_number" => "0967 405 5703",
                "lng" => "120.63150500",
                "lat" => "16.36683100",
                "dru" => "2"
            ],
            "141102053" => [
                "district" => "18",
                "telephone_number" => null,
                "contact_number" => "0938 033 2565",
                "lng" => "120.59626700",
                "lat" => "16.41680500",
                "dru" => "11"
            ],
            "141102054" => [
                "district" => "12",
                "telephone_number" => null,
                "contact_number" => "0948 787 9575",
                "lng" => "120.61352500",
                "lat" => "16.37671600",
                "dru" => "10"
            ],
            "141102055" => [
                "district" => "19",
                "telephone_number" => "(074) 248 1328",
                "contact_number" => null,
                "lng" => "120.60435100",
                "lat" => "16.42590900",
                "dru" => "4"
            ],
            "141102056" => [
                "district" => "15",
                "telephone_number" => null,
                "contact_number" => null,
                "lng" => "120.58294900",
                "lat" => "16.41044400",
                "dru" => "6"
            ],
            "141102058" => [
                "district" => "3",
                "telephone_number" => null,
                "contact_number" => "0907 276 9490",
                "lng" => "120.60759200",
                "lat" => "16.39276200",
                "dru" => "16"
            ],
            "141102059" => [
                "district" => "15",
                "telephone_number" => null,
                "contact_number" => null,
                "lng" => "120.58510900",
                "lat" => "16.41076100",
                "dru" => "6"
            ],
            "141102061" => [
                "district" => "8",
                "telephone_number" => "(074) 244 9156",
                "contact_number" => "0909 450 6000",
                "lng" => "120.59103100",
                "lat" => "16.42808500",
                "dru" => "15"
            ],
            "141102062" => [
                "district" => "5",
                "telephone_number" => null,
                "contact_number" => "0921 773 3613",
                "lng" => "120.58905300",
                "lat" => "16.40961100",
                "dru" => "6"
            ],
            "141102063" => [
                "district" => "11",
                "telephone_number" => "(074) 442 8681",
                "contact_number" => "0948 531 1733",
                "lng" => "120.62061500",
                "lat" => "16.41436900",
                "dru" => "9"
            ],
            "141102064" => [
                "district" => "11",
                "telephone_number" => null,
                "contact_number" => "0946 250 3862",
                "lng" => "120.62963100",
                "lat" => "16.40467500",
                "dru" => "9"
            ],
            "141102065" => [
                "district" => "7",
                "telephone_number" => "(074) 248 1296",
                "contact_number" => "0948 415 1597",
                "lng" => "120.59349600",
                "lat" => "16.41989700",
                "dru" => "11"
            ],
            "141102066" => [
                "district" => "17",
                "telephone_number" => "(074) 424 2177",
                "contact_number" => "0929 266 6171",
                "lng" => "120.59373700",
                "lat" => "16.42382600",
                "dru" => "11"
            ],
            "141102067" => [
                "district" => "19",
                "telephone_number" => null,
                "contact_number" => "0909 854 3102",
                "lng" => "120.60435200",
                "lat" => "16.42439100",
                "dru" => "4"
            ],
            "141102068" => [
                "district" => "2",
                "telephone_number" => "(074) 424 0567",
                "contact_number" => "0916 593 9195",
                "lng" => "120.60308800",
                "lat" => "16.40526300",
                "dru" => "7"
            ],
            "141102069" => [
                "district" => "8",
                "telephone_number" => "(074) 661 0243",
                "contact_number" => "0907 422 3547",
                "lng" => "120.59131400",
                "lat" => "16.42896300",
                "dru" => "15"
            ],
            "141102071" => [
                "district" => "13",
                "telephone_number" => "(074) 424 1009",
                "contact_number" => "0946 938 5735",
                "lng" => "120.59778100",
                "lat" => "16.40215300",
                "dru" => "7"
            ],
            "141102072" => [
                "district" => "1",
                "telephone_number" => "(074) 619 1847",
                "contact_number" => "0929 691 1121",
                "lng" => "120.62699900",
                "lat" => "16.41986200",
                "dru" => "13"
            ],
            "141102074" => [
                "district" => "19",
                "telephone_number" => "(074) 422 2528",
                "contact_number" => null,
                "lng" => "120.60309800",
                "lat" => "16.42233600",
                "dru" => "4"
            ],
            "141102075" => [
                "district" => "19",
                "telephone_number" => null,
                "contact_number" => "0925 900 0000",
                "lng" => "120.60309400",
                "lat" => "16.42234300",
                "dru" => "4"
            ],
            "141102076" => [
                "district" => "17",
                "telephone_number" => "(074) 445 3716",
                "contact_number" => "0949 366 1327",
                "lng" => "120.59674200",
                "lat" => "16.42098400",
                "dru" => "11"
            ],
            "141102078" => [
                "district" => "9",
                "telephone_number" => null,
                "contact_number" => "0910 566 4180",
                "lng" => "120.60469900",
                "lat" => "16.42485500",
                "dru" => "4"
            ],
            "141102079" => [
                "district" => "17",
                "telephone_number" => "(074) 665 3567",
                "contact_number" => "0921 585 1513",
                "lng" => "120.59877300",
                "lat" => "16.42961700",
                "dru" => "11"
            ],
            "141102081" => [
                "district" => "11",
                "telephone_number" => "(074) 424 1293",
                "contact_number" => "0919 483 1670",
                "lng" => "120.62397300",
                "lat" => "16.41058500",
                "dru" => "9"
            ],
            "141102082" => [
                "district" => "1",
                "telephone_number" => "(074) 619 1917",
                "contact_number" => "0918 413 2588",
                "lng" => "120.61514500",
                "lat" => "16.41668600",
                "dru" => "13"
            ],
            "141102086" => [
                "district" => "8",
                "telephone_number" => "(074) 661 0269",
                "contact_number" => "0950 799 5141",
                "lng" => "120.58535300",
                "lat" => "16.42681300",
                "dru" => "12"
            ],
            "141102087" => [
                "district" => "7",
                "telephone_number" => "(074) 445 7486",
                "contact_number" => null,
                "lng" => "120.58244900",
                "lat" => "16.42556300",
                "dru" => "12"
            ],
            "141102088" => [
                "district" => "16",
                "telephone_number" => "(074) 445 5552",
                "contact_number" => "0910 529 3779",
                "lng" => "120.57744600",
                "lat" => "16.42150300",
                "dru" => "12"
            ],
            "141102089" => [
                "district" => "13",
                "telephone_number" => "(074) 424 4238",
                "contact_number" => null,
                "lng" => "120.59924200",
                "lat" => "16.39609400",
                "dru" => "7"
            ],
            "141102090" => [
                "district" => "1",
                "telephone_number" => null,
                "contact_number" => "0930 577 8237 / 0948 801 6180",
                "lng" => "120.62716200",
                "lat" => "16.41682200",
                "dru" => "9"
            ],
            "141102091" => [
                "district" => "6",
                "telephone_number" => "(074) 442 1596",
                "contact_number" => "0939 919 2429",
                "lng" => "120.58655400",
                "lat" => "16.41249500",
                "dru" => "6"
            ],
            "141102092" => [
                "district" => "5",
                "telephone_number" => "(074) 619 5926",
                "contact_number" => "0921 986 6265",
                "lng" => "120.58654900",
                "lat" => "16.40829800",
                "dru" => "6"
            ],
            "141102094" => [
                "district" => "10",
                "telephone_number" => "(074) 443 9637",
                "contact_number" => "0920 494 5537",
                "lng" => "120.60057100",
                "lat" => "16.41122200",
                "dru" => "7"
            ],
            "141102096" => [
                "district" => "9",
                "telephone_number" => null,
                "contact_number" => "0923 736 9722",
                "lng" => "120.60201200",
                "lat" => "16.42809300",
                "dru" => "4"
            ],
            "141102097" => [
                "district" => "15",
                "telephone_number" => "(074) 446 3338",
                "contact_number" => "0928 370 7767",
                "lng" => "120.57668400",
                "lat" => "16.40570900",
                "dru" => "1"
            ],
            "141102098" => [
                "district" => "15",
                "telephone_number" => "(074) 442 4345",
                "contact_number" => "0918 340 5400",
                "lng" => "120.58042900",
                "lat" => "16.41180200",
                "dru" => "1"
            ],
            "141102099" => [
                "district" => "13",
                "telephone_number" => null,
                "contact_number" => "0908 369 2909",
                "lng" => "120.59717600",
                "lat" => "16.39488800",
                "dru" => "7"
            ],
            "141102100" => [
                "district" => "3",
                "telephone_number" => "(074) 442 6547",
                "contact_number" => "0927 920 0730",
                "lng" => "120.60366600",
                "lat" => "16.39989700",
                "dru" => "7"
            ],
            "141102101" => [
                "district" => "4",
                "telephone_number" => " (074) 447 2131 ",
                "contact_number" => "0921 463 8878",
                "lng" => "120.58719900",
                "lat" => "16.40167700",
                "dru" => "3"
            ],
            "141102102" => [
                "district" => "14",
                "telephone_number" => null,
                "contact_number" => "0919 560 1000 / 0918 592 2120",
                "lng" => "120.57288800",
                "lat" => "16.36874500",
                "dru" => "3"
            ],
            "141102103" => [
                "district" => "14",
                "telephone_number" => "(074) 444 4789",
                "contact_number" => "0999 705 3846",
                "lng" => "120.56853600",
                "lat" => "16.37720300",
                "dru" => "3"
            ],
            "141102105" => [
                "district" => "3",
                "telephone_number" => " (074) 444 8900",
                "contact_number" => "0919 456 5014",
                "lng" => "120.60851300",
                "lat" => "16.39647200",
                "dru" => "16"
            ],
            "141102106" => [
                "district" => "10",
                "telephone_number" => "(074) 446 4436",
                "contact_number" => "0936 911 3858",
                "lng" => "120.59873700",
                "lat" => "16.41006500",
                "dru" => "11"
            ],
            "141102107" => [
                "district" => "7",
                "telephone_number" => null,
                "contact_number" => "0927 444 1244",
                "lng" => "120.59445600",
                "lat" => "16.42043500",
                "dru" => "11"
            ],
            "141102109" => [
                "district" => "17",
                "telephone_number" => "(074) 661 1759",
                "contact_number" => "0928 893 2273",
                "lng" => "120.59711200",
                "lat" => "16.42749700",
                "dru" => "11"
            ],
            "141102110" => [
                "district" => "1",
                "telephone_number" => "(074) 304 2259",
                "contact_number" => "0916 200 2012",
                "lng" => "120.61110600",
                "lat" => "16.41707800",
                "dru" => "13"
            ],
            "141102111" => [
                "district" => "18",
                "telephone_number" => "(074) 619 0534",
                "contact_number" => "0929 642 2006",
                "lng" => "120.59695500",
                "lat" => "16.42017700",
                "dru" => "11"
            ],
            "141102112" => [
                "district" => "17",
                "telephone_number" => null,
                "contact_number" => "0915 323 0685",
                "lng" => "120.59913300",
                "lat" => "16.42461100",
                "dru" => "11"
            ],
            "141102114" => [
                "district" => "5",
                "telephone_number" => null,
                "contact_number" => "0920 344 9368 / 0907 903 8351",
                "lng" => "120.58818100",
                "lat" => "16.40756200",
                "dru" => "6"
            ],
            "141102115" => [
                "district" => "16",
                "telephone_number" => "(074) 665 4134",
                "contact_number" => "0999 446 5579",
                "lng" => "120.57868200",
                "lat" => "16.41436800",
                "dru" => "14"
            ],
            "141102116" => [
                "district" => "8",
                "telephone_number" => "(074) 661 2803",
                "contact_number" => "0998 985 7588",
                "lng" => "120.59086600",
                "lat" => "16.43021200",
                "dru" => "15"
            ],
            "141102117" => [
                "district" => "6",
                "telephone_number" => "(074) 619 1802",
                "contact_number" => "0918 308 1176",
                "lng" => "120.58582300",
                "lat" => "16.41787700",
                "dru" => "5"
            ],
            "141102119" => [
                "district" => "4",
                "telephone_number" => " (074) 442 3426",
                "contact_number" => "0912 375 7248",
                "lng" => "120.59395500",
                "lat" => "16.40669400",
                "dru" => "6"
            ],
            "141102120" => [
                "district" => "4",
                "telephone_number" => " (074) 442 6271",
                "contact_number" => "0946 046 0126",
                "lng" => "120.58618600",
                "lat" => "16.39908800",
                "dru" => "6"
            ],
            "141102122" => [
                "district" => "15",
                "telephone_number" => null,
                "contact_number" => null,
                "lng" => "120.58366600",
                "lat" => "16.41230400",
                "dru" => "6"
            ],
            "141102124" => [
                "district" => "5",
                "telephone_number" => "(074) 445 8611",
                "contact_number" => "0948 440 5134",
                "lng" => "120.59050200",
                "lat" => "16.40496100",
                "dru" => "6"
            ],
            "141102125" => [
                "district" => "18",
                "telephone_number" => "(074) 665 5720",
                "contact_number" => "0912 311 2960",
                "lng" => "120.59733600",
                "lat" => "16.41799900",
                "dru" => "11"
            ],
            "141102126" => [
                "district" => "9",
                "telephone_number" => "(074) 442 0494 ",
                "contact_number" => "0933 876 0265",
                "lng" => "120.60776700",
                "lat" => "16.42841700",
                "dru" => "4"
            ],
            "141102128" => [
                "district" => "9",
                "telephone_number" => null,
                "contact_number" => "0918 655 9634",
                "lng" => "120.60669100",
                "lat" => "16.42571300",
                "dru" => "4"
            ],
            "141102129" => [
                "district" => "18",
                "telephone_number" => "(074) 661 7531",
                "contact_number" => "0917 790 1618",
                "lng" => "120.59338700",
                "lat" => "16.41369300",
                "dru" => "11"
            ],
            "141102130" => [
                "district" => "18",
                "lng" => "120.594711",
                "lat" => "16.4148425",
            ],
            "141102131" => [
                "district" => "4",
                "telephone_number" => " (074) 665 2809",
                "contact_number" => " (074) 665 2809",
                "lng" => "120.59769600",
                "lat" => "16.39987200",
                "dru" => "7"
            ],
            "141102132" => [
                "district" => "9",
                "telephone_number" => "(074) 309 5048",
                "contact_number" => "0927 438 4128",
                "lng" => "120.60615500",
                "lat" => "16.42736500",
                "dru" => "4"
            ],
            "141102133" => [
                "district" => "8",
                "telephone_number" => null,
                "contact_number" => "0920 230 2076",
                "lng" => "120.59338100",
                "lat" => "16.42685900",
                "dru" => "15"
            ],
            "141102134" => [
                "district" => "5",
                "telephone_number" => "(074) 244 4394",
                "contact_number" => "0917 808 3297",
                "lng" => "120.58942400",
                "lat" => "16.41269600",
                "dru" => "6"
            ],
            "141102135" => [
                "district" => "15",
                "telephone_number" => null,
                "contact_number" => null,
                "lng" => "120.58290300",
                "lat" => "16.40890900",
                "dru" => "6"
            ],
            "141102136" => [
                "district" => "17",
                "telephone_number" => "(074) 300 1408",
                "contact_number" => "0928 900 0000",
                "lng" => "120.59589600",
                "lat" => "16.42481400",
                "dru" => "11"
            ],
            "141102137" => [
                "district" => "14",
                "telephone_number" => "(074) 444 8451",
                "contact_number" => null,
                "lng" => "120.56852700",
                "lat" => "16.37721700",
                "dru" => "3"
            ],
            "141102138" => [
                "district" => "4",
                "telephone_number" => null,
                "contact_number" => " (074) 665 2809",
                "lng" => "120.59193800",
                "lat" => "16.40210300",
                "dru" => "6"
            ],
            "141102139" => [
                "district" => "8",
                "telephone_number" => "(074) 442 2527",
                "contact_number" => "0917 507 2733",
                "lng" => "120.59076300",
                "lat" => "16.40218500",
                "dru" => "11"
            ],
            "141102140" => [
                "district" => "10",
                "telephone_number" => "(074) 619 6470",
                "contact_number" => "0912 755 1900",
                "lng" => "120.59679900",
                "lat" => "16.41200500",
                "dru" => "11"
            ],
            "141102141" => [
                "district" => "20",
                "telephone_number" => null,
                "contact_number" => "0928 278 8099 / 0917 320 9316",
                "lng" => "120.60043100",
                "lat" => "16.41900200",
                "dru" => "11"
            ],
            "141102142" => [
                "district" => "18",
                "telephone_number" => "(074) 619 1769",
                "contact_number" => "0922 362 1669",
                "lng" => "120.59679900",
                "lat" => "16.41472200",
                "dru" => "11"
            ],
            "141102143" => [
                "district" => "18",
                "telephone_number" => null,
                "contact_number" => "0908 149 4545",
                "lng" => "120.59355200",
                "lat" => "16.41514400",
                "dru" => "11"
            ],
            "141102144" => [
                "district" => "3",
                "telephone_number" => null,
                "contact_number" => "0977 324 0118",
                "lng" => "120.60380300",
                "lat" => "16.39390500",
                "dru" => "16"
            ],
            "141102145" => [
                "district" => "12",
                "telephone_number" => null,
                "contact_number" => "0999 706 1880",
                "lng" => "120.61179600",
                "lat" => "16.37842600",
                "dru" => "10"
            ],
            "141102147" => [
                "district" => "10",
                "telephone_number" => "(074) 619 6470",
                "contact_number" => "0912 755 1900",
                "lng" => "120.59607100",
                "lat" => "16.41440400",
                "dru" => "11"
            ],
            "141102148" => [
                "district" => "20",
                "telephone_number" => "(074) 446 3947 / (074) 661 4766",
                "contact_number" => "0908 715 2648",
                "lng" => "120.60798100",
                "lat" => "16.41503900",
                "dru" => "13"
            ],
            "141102149" => [
                "district" => "1",
                "telephone_number" => null,
                "contact_number" => null,
                "lng" => "120.59199400",
                "lat" => "16.41996800",
                "dru" => "5"
            ],
            "141102150" => [
                "district" => "16",
                "telephone_number" => "(074) 445 5405",
                "contact_number" => "0929 183 3311",
                "lng" => "120.57677100",
                "lat" => "16.41621100",
                "dru" => "14"
            ],
            "141102151" => [
                "district" => "5",
                "telephone_number" => "(074) 422 8849",
                "contact_number" => "0945 823 7091",
                "lng" => "120.58587900",
                "lat" => "16.40863800",
                "dru" => "6"
            ],
            "141102152" => [
                "district" => "4",
                "telephone_number" => null,
                "contact_number" => "0915 852 0693",
                "lng" => "120.59452400",
                "lat" => "16.39986700",
                "dru" => "7"
            ],
            "141102153" => [
                "district" => "16",
                "telephone_number" => "(074) 619 2835",
                "contact_number" => "0933 823 5633",
                "lng" => "120.57439500",
                "lat" => "16.41633600",
                "dru" => "14"
            ],
            "141102154" => [
                "district" => "16",
                "telephone_number" => null,
                "contact_number" => "0966 753 0675",
                "lng" => "120.57467400",
                "lat" => "16.41670600",
                "dru" => "14"
            ],
            "141102155" => [
                "district" => "18",
                "telephone_number" => null,
                "contact_number" => "0947 818 7648",
                "lng" => "120.59210700",
                "lat" => "16.41174900",
                "dru" => "11"
            ],
            "141102156" => [
                "district" => "14",
                "telephone_number" => "(074) 442 1730",
                "contact_number" => null,
                "lng" => "120.58905100",
                "lat" => "16.39091700",
                "dru" => "3"
            ],
            "141102157" => [
                "district" => "11",
                "telephone_number" => "(074) 443 4818",
                "contact_number" => "0921 606 2198",
                "lng" => "120.61083900",
                "lat" => "16.40914100",
                "dru" => "13"
            ],
            "141102159" => [
                "district" => "18",
                "telephone_number" => null,
                "contact_number" => "0939 309 1029",
                "lng" => "120.59535900",
                "lat" => "16.41716200",
                "dru" => "11"
            ],
            "141102160" => [
                "district" => "6",
                "telephone_number" => "(074) 620 5165",
                "contact_number" => "0915 322 0110",
                "lng" => "120.59374200",
                "lat" => "16.41661900",
                "dru" => "5"
            ],
            "141102161" => [
                "district" => "7",
                "telephone_number" => "(074) 309 5890",
                "contact_number" => "0909 746 9921",
                "lng" => "120.59280200",
                "lat" => "16.41754300",
                "dru" => "5"
            ]
        ];

        foreach($province_fix as $key => $province){
            self::where('cityCode', $key)->update($province);
        }

        foreach($additional_info as $key => $value){
            self::where('code', $key)->update($value);
        }
    }
    
    public function city(){
        return $this->belongsTo(City::class, 'cityCode', 'code');
    }
    
    public function province(){
        return $this->belongsTo(Province::class, 'provinceCode', 'code');
    }

    public function region(){
        return $this->belongsTo(Region::class, 'regionCode', 'code');
    }
    
    public function islandGroup(){
        return $this->belongsTo(IslandGroup::class, 'islandGroupCode', 'code');
    }

    //relationship with Quitter
    public function quitters(){
        return $this->belongsToMany(Quitter::class);
    }

    //relationship with Report
    public function reports(){
        return $this->belongsToMany(Report::class);
    }
}