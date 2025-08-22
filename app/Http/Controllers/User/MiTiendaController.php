<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\BuildsSettings;

/**
 * MiTiendaController
 * 
 * Controller specifically for handling Mi Tienda sections integration.
 * Follows the same pattern as CardController but dedicated to Mi Tienda functionality.
 * Each method serves a Blade view that embeds the corresponding HTML file in an iframe.
 */
class MiTiendaController extends Controller
{
    use BuildsSettings;

    /**
     * Show the main Mi Tienda section
     */
    public function index()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.index', compact('settings'));
    }

    /**
     * Show the Dashboard section
     */
    public function dashboard()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.dashboard', compact('settings'));
    }

    /**
     * Show the Income (Ingresos) section
     */
    public function ingresos()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.ingresos', compact('settings'));
    }

    /**
     * Show the Design (Diseño) section
     */
    public function diseno()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.diseno', compact('settings'));
    }

    /**
     * Show the Customers section
     */
    public function customers()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.customers', compact('settings'));
    }

    /**
     * Show the Statistics section
     */
    public function statistics()
    {
        $settings = $this->buildSettings();
        return view('user.pages.mi-tienda.statistics', compact('settings'));
    }
}