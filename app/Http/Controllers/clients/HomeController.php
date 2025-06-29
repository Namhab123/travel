<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Home;
use Illuminate\Http\Request;
use App\Models\clients\Tours;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $homeTours;
    private $tours;

    public function __construct()
    {
        parent::__construct();
        $this->homeTours = new Home();
        $this->tours = new Tours();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $title = 'Trang chủ';
    $tours = $this->homeTours->getHomeTours(6); // Giới hạn 9 tour

    // Mặc định lấy tour phổ biến trước
    $toursPopular = $this->tours->toursPopular(6); // Giới hạn 9 tour phổ biến

    $userId = $this->getUserId();
    if ($userId) {
        try {
            $apiUrl = 'http://127.0.0.1:5555/api/user-recommendations';
            $response = Http::get($apiUrl, [
                'user_id' => $userId
            ]);

            if ($response->successful()) {
                $tourIds = $response->json('recommended_tours');
                $recommendTours = $this->tours->toursRecommendation($tourIds, 6);
                // Nếu có tour recommend thì ưu tiên gán vào toursPopular
                if (!$recommendTours->isEmpty()) {
                    $toursPopular = $recommendTours;
                }
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi API liên quan: ' . $e->getMessage());
        }
    }

    return view('clients.home', compact('title', 'tours', 'toursPopular'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}