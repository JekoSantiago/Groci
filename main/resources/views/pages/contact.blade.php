@extends('layout.base_tpl')
    @section('contents')
    <!-- About us content -->
    @include('pages.partials.headers')


    <section class="section-padding bg-white inner-header">
        <div class="container">
            <div class="row">
                <div class="pl-5 col-lg-12">
                    <img class="rounded img-fluid" src="{{ asset('img/Customer-Care-web-banner.png') }}" alt="Card image cap">
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-lg-12">
                    <h5>For customer concerns, please email us at <b>customercare@alfamart.com.ph</b> or submit it through our <a href="https://docs.google.com/forms/d/e/1FAIpQLSdn9tal8rUoQZ07yulkoxPn5oFEcYKQ0hGYzfay_mTSx-U_4A/viewform" target="_blank" class="text-info">customer care form</a>.</h5>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding">

    </section>

    @include('pages.partials.footers')

    <!-- End about us content -->
    @endsection
