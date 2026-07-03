<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category\MainCategory;
use App\Models\Category\SubCategory;
use App\Models\Category\SubSubCategory;
use App\Models\Color\Color_Model;
use App\Models\Currency\Currency_Model;

class CategoryServiceProviders extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('categories', function () {
            return [
                'category' => MainCategory::all(),
                'subcategory' => SubCategory::all(),
                'subsubcategory' => SubSubCategory::all(),
                'currency' => Currency_Model::all(),
            ];
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
