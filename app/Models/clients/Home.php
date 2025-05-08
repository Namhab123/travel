<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Home extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    public function getHomeTours(){
        //lấy thông tin tours
        $tours = DB::table($this->table)
            ->get();

        foreach ($tours as $tour){
            //lấy danh sách hình ảnh thuộc về tours
            $tour->images = DB::table('tbl_images')
            ->where('tourId', $tour->tourId)
            ->pluck('imageURL');

            //lấy danh sách timeline thuộc về tours
/*             $tours->timeline = DB::table('tbl_timeline')
            ->where('tourdId', $tours->tourId)
            ->pluck('title');

    */     }
    

        return $tours;
    }
}
