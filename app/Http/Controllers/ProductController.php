<?php

namespace App\Http\Controllers;

use App\Models\DataCollector;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use \Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $userId = Auth::id();

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
            // 'products' => DB::table('products')
            //     ->leftJoin(DB::raw('(SELECT dc1.* FROM data_collectors dc1 JOIN (SELECT product_ean, MAX(created_at) AS max_created_at FROM data_collectors  GROUP BY product_ean) dc2 ON dc1.product_ean = dc2.product_ean AND dc1.created_at = dc2.max_created_at) as dc1'), 'products.ean', '=', 'dc1.product_ean')
            //     ->select('products.*', 'dc1.quantity')
            //     ->get(),
            'products' => DB::table('products')
                ->leftJoin(DB::raw('(SELECT dc1.* FROM data_collectors dc1 JOIN (SELECT product_ean, MAX(created_at) AS max_created_at FROM data_collectors GROUP BY product_ean) dc2 ON dc1.product_ean = dc2.product_ean AND dc1.created_at = dc2.max_created_at) as dc1'), 'products.ean', '=', 'dc1.product_ean')
                // ->leftJoin('product_user', 'products.id', '=', 'product_user.product_id')
                ->leftJoin(DB::raw("(SELECT * FROM product_user WHERE user_id = 1) as product_user"), 'products.id', '=', 'product_user.product_id')
                ->select('products.*', 'dc1.quantity', 'dc1.price', 'product_user.user_id')
                ->orderby('products.id', 'DESC')
                ->get(),    

       
        ]);
    }
    public function category(Request $request, $category , $subcategory = null, $subsubcategory = null): Response
    {

       

        $userId = Auth::id();
        $whereIn = [];


        
        if($subsubcategory){

            $getSubSubCategory = ProductCategory::where('slug', $subsubcategory)->first();
            $whereIn = [$getSubSubCategory->id];

        } else if ($subcategory){
         
            $getSubCategories = ProductCategory::where('slug', $subcategory)->first();
            $getSubSubCategories = ProductCategory::where('parent_category', $getSubCategories->id)->get();
            $whereIn = [$getSubCategories->id];
    
            foreach($getSubSubCategories as $s){
                array_push($whereIn , $s->id);
            }

        } else {
            $getCategory = ProductCategory::where('slug', $category)->first();
            $getSubCategories = ProductCategory::where('parent_category', $getCategory->id)->get();
    
            $whereIn = [$getCategory->id];
    
            foreach($getSubCategories as $s){
                array_push($whereIn , $s->id);
    
                $getSubSubCategories = ProductCategory::where('parent_category', $s->id)->get();
    
                foreach($getSubSubCategories as $ss){
                    array_push($whereIn , $ss->id);
                     var_dump($ss->id);
                }
    
            }
        }



        return Inertia::render('Product/Index', [

            'products' => DB::table('products')
                ->whereIn('category', $whereIn)
                ->leftJoin(DB::raw('(SELECT dc1.* FROM data_collectors dc1 JOIN (SELECT product_ean, MAX(created_at) AS max_created_at FROM data_collectors GROUP BY product_ean) dc2 ON dc1.product_ean = dc2.product_ean AND dc1.created_at = dc2.max_created_at) as dc1'), 'products.ean', '=', 'dc1.product_ean')
                // ->leftJoin('product_user', 'products.id', '=', 'product_user.product_id')
                ->leftJoin(DB::raw("(SELECT * FROM product_user WHERE user_id = 1) as product_user"), 'products.id', '=', 'product_user.product_id')
                ->select('products.*', 'dc1.quantity', 'dc1.price', 'product_user.user_id')
                ->orderby('products.id', 'DESC')
                ->get(),    

       
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

    public function watched(Request $request): Response
    {    $userId = Auth::id();


        return Inertia::render('Product/Watched', [

                    'products' => DB::table('products')
                    ->where('user_id', '?')
                    ->leftJoin(DB::raw('(SELECT dc1.* FROM data_collectors dc1 JOIN (SELECT product_ean, MAX(created_at) AS max_created_at FROM data_collectors GROUP BY product_ean) dc2 ON dc1.product_ean = dc2.product_ean AND dc1.created_at = dc2.max_created_at) as dc1'), 'products.ean', '=', 'dc1.product_ean')
                    ->leftJoin('product_user', 'products.id', '=', 'product_user.product_id')
                    // ->leftJoin(DB::raw("(SELECT * FROM product_user WHERE user_id = ?) as product_user"), 'products.id', '=', 'product_user.product_id')
                    ->select('products.*', 'dc1.quantity', 'dc1.price', 'product_user.user_id')
                    ->setBindings([$userId])
                    ->get(),   
                       
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
