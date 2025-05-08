<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tours extends Model
{
    use HasFactory;
    protected $table = 'tbl_tours';

    //Lấy tất cả tours
    public function getAllTours()
    {
        
        $allTours = DB::table($this->table)->get();
        foreach ($allTours as $tour) {
            //lấy danh sách hình ảnh thuộc về tours
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageURL');
        }
        return $allTours;
    }

    //Lấy chi tiết tour
    public function getTourDetail($id)
    {
        $getTourDetail = DB::table($this->table)
            ->where('tourID', $id)
            ->first();

        if ($getTourDetail) {
            //lấy danh sách hình ảnh thuộc về tours
            $getTourDetail->images = DB::table('tbl_images')
                ->where('tourId', $getTourDetail->tourId)
                ->limit(5)
                ->pluck('imageURL');

            //lấy danh sách timeline thuộc về tours
            $getTourDetail->timeline = DB::table('tbl_timeline')
                ->where('tourId', $getTourDetail->tourId)
                ->get();
        }
        return $getTourDetail;
    }

    //Lấy khu ực đến Bắc - Trung - Nam
    public function getDomain()
    {
        return DB::table($this->table)
            ->select('domain', DB::raw('COUNT(*) as count'))
            ->whereIn('domain', ['b', 't', 'n'])
            ->groupBy('domain')
            ->get();
    }

    //filter tours
    public function filterTours($filters = [], $sorting = null, $perPage = null)
    {
        $getTours = DB::table($this->table);

        // Aps dụng bộ lọc nếu có
        if (!empty($filters)) {
            $getTours = $getTours->where($filters);
        }

        if (!empty($sorting)&& isset($sorting[0][0])&& isset($sorting[0][1])) {
            $getTours = $getTours->orderBy($sorting[0][0], $sorting[0][1]);
        }

        //THực hiện truy vấn để ghi log
         $tours = $getTours->get(); 

        //In ra câu lệnh SQL    
        $queryLog = DB::getQueryLog();

        foreach ($tours as $tour) {
            //lấy danh sách hình ảnh thuộc về tours
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageURL');
        };
        /* dd($queryLog);  */

        
        return $tours;
    }
}
