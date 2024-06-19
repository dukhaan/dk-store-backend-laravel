<?php

namespace App\Http\Controllers;

use App\Models\ProductGallery;
use App\Http\Requests\StoreProductGalleryRequest;
use App\Http\Requests\UpdateProductGalleryRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\View\View;

class SessionController extends Controller {
    public function show(Request $request, string $id): View
    {
        $value = $request->session()->get('key');

        if ($value) {
        
            return view('user.profile', ['user' => $value]);
            
        }
 
        $user = $this->users->find($id);
 
        return view('user.profile', ['user' => $user]);
    }
}
