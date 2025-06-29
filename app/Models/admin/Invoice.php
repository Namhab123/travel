<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{

    public function getInvoiceByBooking($bookingId)
{
    return DB::table($this->table)
        ->where('bookingId', $bookingId)
        ->first();
}
    protected $table = 'tbl_invoice'; 
    public $timestamps = false;

    
}


