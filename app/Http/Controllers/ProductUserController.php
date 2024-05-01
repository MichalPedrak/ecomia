<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Models\ProductUser;
use Illuminate\Http\Response;

class ProductUserController extends Controller
{
   

    public function add(Request $request, $id): Response
    {
        $userId = Auth::id();

        ProductUser::create([
            'user_id' => $userId,
            'product_id' => $id,
        ]);

        return new Response('Success', 200);
    }
    public function destroy(Request $request, $id): Response
    {
        $userId = Auth::id();

        ProductUser::where('user_id', $userId)
           ->where('product_id', $id)
           ->delete();
           return new Response('Success', 200);

    }
}
