<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Home;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    private $homeTours;

    public function __construct(){
        $this->homeTours = new Home();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Trang chủ';
        $tours = $this->homeTours->getHomeTours();

        /* dd($tours); */
        return view('clients.home', compact('title','tours'));
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
