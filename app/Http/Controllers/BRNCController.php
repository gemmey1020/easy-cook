<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BRNCController extends Controller
{
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required',
            'message' => 'required|string',
        ]);

        // ✅ Get visitor info from session
        $visitor = session('visitor_info', []);

        // ✅ Prepare the payload
        $payload = [
            'name' => $validated['name'] ?? 'conflict name',
            'mobile' => $validated['number'],
            'email' => $validated['email'] ?? '--',
            'services' => $validated['message'],
            'ip' => $request->ip(),
            // 'location'   => $visitor['city'] ?? '',
            'country' => $visitor['countryName'] ?? '',
            'account' => 'easy',
            'platform' => 'Google',
            'user_agent' => $request->userAgent(),

            // BRNC Data Mapping
            'ip1' => $request->ip(),
            'ip2' => $_SERVER['REMOTE_ADDR'] ?? '',
            'lat' => $visitor['latitude'] ?? '',
            'lng' => $visitor['longitude'] ?? '',
            'country' => $visitor['country_name'] ?? 'Egypt',
            'city' => $visitor['cityName'] ?? '',
            'region' => $visitor['regionName'] ?? '',
        ];
        // dd($payload ,$visitor);
        // ✅ Send the POST request to external PHP endpoint
        try {
            $response = Http::timeout(10)->post('https://system.yousrygroup.com/api_test/php/contact.php', $payload);

            if ($response->successful()) {
                return back()->with('success', 'تم إرسال رسالتك بنجاح');
            } else {
                return back()->with('error', 'حصل خطأ أثناء الإرسال، حاول مرة تانية.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'فشل الاتصال بالسيرفر: '.$e->getMessage());
        }
    }

    public function trackClick(Request $request)
    {
        $visitor = session('visitor_info', []);

        $type = $request->input('type'); // "whatsapp" or "call"
        $insertValue = $type === 'call' ? 1 : 0;

        $payload = [
            'insert' => $insertValue,
            'click_type' => $type,
            'account' => 'easy',
            'platform' => 'Google',
            'platform_ip' => $request->ip(),
            'ip_lat' => $visitor['latitude'] ?? '',
            'ip_lng' => $visitor['longitude'] ?? '',
            'ip_country' => $visitor['country_name'] ?? 'Egypt',
            'ip_city' => $visitor['cityName'] ?? '',
            'ip_region' => $visitor['regionName'] ?? '',
        ];

        //     try {
        //         $response = Http::post('https://system.yousrygroup.com/api_test/php/server.php', $payload);

        //  if ($response->successful()) {
        //                 return response()->json(['success' => true, 'msg' => 'Click tracked']);
        //             }

        //             return response()->json(['success' => false, 'msg' => 'Server error'], 500);
        //         } catch (\Exception $e) {
        //             return response()->json([
        //                 'success' => false,
        //                 'msg' => 'Connection failed',
        //                 'error' => $e->getMessage(),
        //             ], 500);
        //         }

        $response = Http::timeout(5)->post(
            'https://system.yousrygroup.com/api_test/php/server.php',
            $payload
        );

        if ($response->successful()) {
            return response()->json(['success' => true, 'msg' => 'Click tracked']);
        }

        // 👇 أضف دا للطباعة
        return response()->json([
            'success' => false,
            'msg' => 'Server error',
            'status' => $response->status(),
            'body' => $response->body(),  // مهم جداً: محتوى رد السيرفر
        ], 500);

    }

    public function getData(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:btn_track,contact_us',
        ]);
        $response = Http::post('https://system.yousrygroup.com/api_test/php/get-data.php', [
            'account_name' => 'easy',
            'type' => $request->type, // or 'btn_track' 'contact_us'
        ]);

        $data = $response->json()['data'] ?? [];
// return $data;
        $view = 'dash.brnc.'.$request->type;

        return view($view, compact('data'));
    }
}
