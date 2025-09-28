<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Church;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Générer le sitemap XML
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Page d'accueil
        $sitemap .= '<url>';
        $sitemap .= '<loc>https://eglix.lafia.tech/</loc>';
        $sitemap .= '<lastmod>' . Carbon::now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';
        
        // Pages d'authentification
        $authPages = [
            'login' => 'monthly',
            'register' => 'monthly',
            'forgot-password' => 'yearly'
        ];
        
        foreach ($authPages as $page => $freq) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>https://eglix.lafia.tech/' . $page . '</loc>';
            $sitemap .= '<lastmod>' . Carbon::now()->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>' . $freq . '</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
        
        // Pages publiques d'inscription des membres
        $churches = Church::where('is_active', true)->get();
        foreach ($churches as $church) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>https://eglix.lafia.tech/members/create/' . $church->id . '</loc>';
            $sitemap .= '<lastmod>' . $church->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}
