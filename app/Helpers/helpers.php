<?php

use App\Models\Company;
use Illuminate\Support\Facades\Request;

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $output = 'active')
    {
        $routes = (array) $routes;

        foreach ($routes as $route) {
            if (Request::routeIs($route)) {
                return $output;
            }
        }

        return '';
    }
}

if (!function_exists('isMenuOpen')) {
    function isMenuOpen($routes, $output = 'menu-open')
    {
        $routes = (array) $routes;

        foreach ($routes as $route) {
            if (Request::routeIs($route)) {
                return $output;
            }
        }

        return '';
    }
}

if(!function_exists('getCompany')) {
    function getCompany()
    {
        return Company::findOrFail(session('company_id'));
    }
}
