<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('pages.about',
            [
                'page' => 'About Us Page'
            ]
        );
    }

    public function contact()
    {
        return view('pages.contact',
            [
                'page' => 'Contact Us Page'
            ]
        );
    }

    public function tnc()
    {
        return view('pages.tnc',
            [
                'page' => 'Terms & Condition Page'
            ]
        );
    }

    public function faq()
    {
        return view('pages.faq',
            [
                'page' => 'FAQ Page'
            ]
        );
    }

    public function privacy()
    {
        return view('pages.privacy',
            [
                'page' => 'Data Privacy Page'
            ]
        );
    }
}
