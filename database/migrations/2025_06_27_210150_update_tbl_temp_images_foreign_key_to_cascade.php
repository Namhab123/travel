<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTblTempImagesForeignKeyToCascade extends Migration
{
    public function up()
    {
        Schema::table('tbl_temp_images', function (Blueprint $table) {
            // Sử dụng tên ràng buộc chính xác (thay 'fk_imagesTemp_tours' bằng tên thực tế từ bước 1)
            $table->dropForeign('fk_imagesTemp_tours');
            $table->foreign('tourId')
                  ->references('tourId')
                  ->on('tbl_tours')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tbl_temp_images', function (Blueprint $table) {
            $table->dropForeign('fk_imagesTemp_tours');
            $table->foreign('tourId')
                  ->references('tourId')
                  ->on('tbl_tours');
        });
    }
}