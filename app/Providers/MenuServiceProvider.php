<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   
        // horizontalMenuData
        $adminMenuJson = file_get_contents(base_path('resources/data/menus/admin.json'));
        $adminMenuData = json_decode($adminMenuJson);
        
        // verticalMenuData
        $vendorMenuJson = file_get_contents(base_path('resources/data/menus/vendor.json'));
        $vendorMenuData = json_decode($vendorMenuJson);
        
        // verticalMenuBoxiconsData
        $customerMenuJson = file_get_contents(base_path('resources/data/menus/customer.json'));
        $customerMenuData = json_decode($customerMenuJson);
        
        // share all menuData to all the views
        \View::share('menuData',[$adminMenuData, $vendorMenuData, $customerMenuData]);
    }
}
