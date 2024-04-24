<?php

namespace App\Http\Controllers;

use App\Models\DataCollector;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DataCollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function collect(Request $request): Response
    {


        $product = new DataCollector();
        $product->collect();

        return Inertia::render('Product/Index', [
//            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
//            'status' => session('status'),
        ]);
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
    public function show(DataCollector $dataCollector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataCollector $dataCollector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataCollector $dataCollector)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataCollector $dataCollector)
    {
        //
    }
}
