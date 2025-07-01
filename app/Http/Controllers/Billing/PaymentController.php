<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Banners\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function result(Request $request)
    {
        $password2 = 'dsf234234da';

        $out_sum = $request['OutSum'];
        $inv_id = $request['InvId'];
        $shp_item = $request['Shp_item'];
        $crc = $request['SignatureValue'];

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_sum:$inv_id:$password2:Shp_item=$shp_item"));

        if ($my_crc !== $crc) {
            return 'bad sign';
        }

        $banner = Banner::findOrFail($inv_id);
        $banner->pay(Carbon::now());

        return 'OK'.$inv_id;
    }

    public function success(Request $request) {}

    public function fail(Request $request) {}
}
