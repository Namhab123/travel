<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Home extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    public function getHomeTours($limit = null)
    {
        // Lấy thông tin tours
        $tours = DB::table($this->table)->where('availability', 1)->orderBy('tourId', 'desc');
        if ($limit) {
            $tours = $tours->take($limit);
        }
        $tours = $tours->get();

        foreach ($tours as $tour) {
            // Lấy danh sách hình ảnh thuộc về tours
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageURL');
        }

        return $tours;
    }
}