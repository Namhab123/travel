<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    private $tours;

    public function __construct()
    {
        $this->tours = new Tours();
    }
    public function index(Request $request)
    {
        $title = 'Tìm kiếm';

        $destinationMap = [
            'hn' => 'Hà Nội',
            'hcm' => 'TP. Hồ Chí Minh',
            'dn' => 'Đà Nẵng',
            'ht' => 'Hà Tĩnh',
            'cd' => 'Côn Đảo',
            'hl' => 'Hạ Long',
            'nb' => 'Ninh Bình',
            'pq' => 'Phú Quốc',
            'dl' => 'Đà Lạt',
            'qt' => 'Quảng Trị',
            'kh' => 'Khánh Hòa',
            'ct' => 'Cần Thơ',
            'vt' => 'Vũng Tàu',
            'qn' => 'Quảng Ninh',
            'la' => 'Lào Cai',
            'bd' => 'Bình Định',
        ];

        $destination = $request->input('destination');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        //dd($startDate, $endDate);
        // Chuyển đổi định dạng ngày tháng
        $formattedStartDate = $startDate ? Carbon::createFromFormat('Y-m-d', $startDate)->format('Y-m-d') : null;
        $formattedEndDate = $endDate ? Carbon::createFromFormat('Y-m-d', $endDate)->format('Y-m-d') : null;
        // Chuyển đổi giá trị sang tên chi tiết nếu có trong mảng
        $destinationName = $destinationMap[$destination] ?? null;

         try {
            $apiUrl = 'http://127.0.0.1:5555/api/search-tours';
            $response = Http::get($apiUrl, [
                'startDate' => $formattedStartDate,
                'endDate' => $formattedEndDate,
            ]);

            if ($response->successful()) {
                $resultTours = $response->json('related_tours');
            } else {
                $resultTours = [];
            }
        } catch (\Exception $e) {
            $resultTours = [];
            Log::error('Lỗi khi gọi API search theo ngày: ' . $e->getMessage());
        }

        if ($resultTours) {
            $tours = $this->tours->toursSearch($resultTours);
        } else {
            $dataSearch = [
                'destination' => $destinationName,
                'startDate' => $formattedStartDate,
                'endDate' => $formattedEndDate,
            ];
            $tours = $this->tours->searchTours($dataSearch);
        }
        // dd($tours);

        return view('clients.search', compact('title', 'tours'));
    }

    public function searchTours(Request $request)
    {
        $title = 'Tìm kiếm';

        $keyword = $request->input('keyword');

        // Gọi API Python đã xử lý để lấy danh sách tour tìm kiếm
        try {
            $apiUrl = 'http://127.0.0.1:5555/api/search-tours';
            $response = Http::get($apiUrl, [
                'keyword' => $keyword
            ]);

            if ($response->successful()) {
                $resultTours = $response->json('related_tours');
            } else {
                $resultTours = [];
            }
        } catch (\Exception $e) {
            // Xử lý lỗi khi gọi API
            $resultTours = [];
            Log::error('Lỗi khi gọi API liên quan: ' . $e->getMessage());
        }

        // dd($resultTours);
        if ($resultTours) {
            $tours = $this->tours->toursSearch($resultTours);

        } else {
            $dataSearch = [
                'keyword' => $keyword
            ];
            $tours = $this->tours->searchTours($dataSearch);
        }

        // dd($tours);

        return view('clients.search', compact('title', 'tours'));
    }
}
