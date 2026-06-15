<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ClearCacheController extends Controller
{
    public function clearcache()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->back()->with('success', 'Cache Cleared Successfully');
    }

}