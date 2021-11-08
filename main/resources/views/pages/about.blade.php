@extends('layout.base_tpl')
    @section('contents')
    <!-- About us content -->
    @include('pages.partials.headers')

    <section class="section-padding inner-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="mt-0 mb-3">About Us</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 pl-5 pr-5">
                    <p class="text-justify">Alfamart is a joint venture between SM and Alfamart Indonesia. Alfamart is one of Indonesia’s leading mini-market chain operators with more than 15,000 branches across the country. With its entry to the Philippines.  Alfamart aims to raise the retail standards by offering its customers a wider assortment of basic necessities at great value in convenient setting.</p>
                    <p class="text-justify">Alfamart reaches out to different types of customers and provides everything from their smallest to biggest needs as it offers basic groceries, select SM Bonus products, fresh items, snacks such as Turon, and personal care products at affordable prices. It also offers bills payment service and a prepaid loading station for added convenience to shoppers. Moreover, it helps uplift communities by providing employment for locals and an opportunity for small property owners to earn by leasing their space, assuring them of stable income. By serving and uplifting the local communities, Alfamart lives up to its tagline “Always here for You.”</p>
                </div>
            </div>
        </div>
    </section>



    @include('pages.partials.footers')

    <!-- End about us content -->
    @endsection
