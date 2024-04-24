<?php

namespace App\Http\Controllers;

use App\Models\DataCollector;
use App\Models\Product;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {


        return Inertia::render('Product/Index', [
//            'products' => Product::all()->take(10000),
//            'products' => Product::all(),
//            'products' =>  Product::leftJoin('data_collectors', 'products.ean', '=', 'data_collectors.product_ean')
//                ->select('products.*', 'data_collectors.*')
//                ->orderBy('data_collectors.id')
//                ->limit(10)
//                ->get(),


//            'products' => Product::with('DataCollector')->limit(1000)->get(),
            // 'products' => Product::with('DataCollector')->latest()->limit(1000)->get(),
            'products' => DB::table('products')
                ->leftJoin(DB::raw('(SELECT dc1.* FROM data_collectors dc1 JOIN (SELECT product_ean, MAX(created_at) AS max_created_at FROM data_collectors  GROUP BY product_ean) dc2 ON dc1.product_ean = dc2.product_ean AND dc1.created_at = dc2.max_created_at) as dc1'), 'products.ean', '=', 'dc1.product_ean')
                ->select('products.*', 'dc1.quantity')
                ->get(),
            'data' => DataCollector::where('id', ">", 1)->limit(1)->latest('created_at')->get(),
        ]);
    }

    public function import(Request $request): Response
    {



        $product = new Product();
        $product->import();

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
    public function show($ean)
    {
    

        return Inertia::render('Product/Single', [
            'product' => Product::where('ean', $ean)->first(),
            'data' => DataCollector::where('product_ean', $ean)->orderby('created_at')->get(),
                    ]);
    }

      /**
     * Display the specified resource.
     */
    public function period($ean, $period)
    {   
        $period = $period == -1 ? $period = 365 : $period;

        return [
            'data' => DataCollector::where('product_ean', $ean)
            ->whereDate('created_at', '>=', now()->subDays($period))
            ->orderby('created_at')
            ->get(),
            ];
       
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
