<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Tours;

class ToursController extends Controller
{
    private $tours;

    public function __construct()
    {
        $this->tours = new Tours();
    }

    public function index()
    {
        $title = 'Tours';
        $tours = $this->tours->getAllTours();
        $domain = $this->tours->getDomain();
        $domainCount = [
            'mien_bac' => optional($domain->firstWhere('domain', 'b'))->count,
            'mien_trung' => optional($domain->firstWhere('domain', 't'))->count,
            'mien_nam' => optional($domain->firstWhere('domain', 'n'))->count,
        ];
        return view('clients.tours', compact('title', 'tours', 'domainCount'));
    }

    //XỬ lý filteTours
    public function  filterTours(Request $req)
    {

        $conditions = [];
        $sorting = [];

        if ($req->filled('minPrice')&& $req->filled('maxPrice')) {
                $minPrice = $req->minPrice;
                $maxPrice = $req->maxPrice;
                $conditions[] = ['priceAdult', '>=', $minPrice];
                $conditions[] = ['priceAdult', '<=', $maxPrice];
            }
        

        // Handle domain filter
        if ($req->filled('domain')) {
            $domain = $req->domain;
            $conditions[] = ['domain', '=', $domain];
        }

        // Handle star rating filter
        /* if ($req->filled('star')) {
            $star = (int) $req->star;
            $conditions[] = ['averageRating', '>=', $star];
        } */

        // Handle duration filter
        if ($req->filled('time')) {
            $duration = $req->time;
            $time = [
                '3n2d' => '3 ngày 2 đêm',
                '4n3d' => '4 ngày 3 đêm',
                '5n4d' => '5 ngày 4 đêm',
            ];
            $conditions[] = ['time', '=', $time[$duration]];
        }

        // Handle orderby filter
        if ($req->filled('sorting')) {
            $sortingOption = trim($req->sorting);

            if($sortingOption == 'new' ){
                $sorting[] = ['tourId','DESC'];
            } else if ($sortingOption == 'old'){
                $sorting[] = ['tourId','ASC'];
            }else if ($sortingOption == 'hight-to-low'){
                $sorting[] = ['priceAdult', 'DESC'];
            }else if($sortingOption == 'low-to-high'){
                $sorting[] = ['priceAdult', 'ASC'];
            };
        }
    
        //dd($sorting);
        $filterTours = $this->tours->filterTours($conditions, $sorting);
        return view('clients.partials.filter-tours', compact('filterTours')); //partials.tours là view riêng chỉ chứa dánn sách tour
    }
}
