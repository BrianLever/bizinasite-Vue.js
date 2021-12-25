@extends('layouts.app')

@section('title', $seo['meta_title']?? 'Site Advertisement Spots - ' . $spot->name)

@section('meta')
    @include("components.front.seo", $seo)
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendors/lightgallery/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/calendar/fullcalendar.css')}}">
@endsection
@section('content')
    <x-front.site-ads-hero></x-front.site-ads-hero>

    <form action="{{route('siteAds.addtocart', $spot->id)}}" id="submitForm">
        @csrf
        <div class="container mt-3" >
            <div class="items_result">
                <div class="my-5">
                    <h1 class="text-center font-size24">{{$spot->name}}</h1>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="lightgallery">
                                <a href="{{$spot->getFirstMediaUrl("image")}}"
                                   class="progressive replace h-cursor box-shadow-dark"
                                >
                                    <img src="{{$spot->getFirstMediaUrl("image", 'thumb')}}" alt="" class="preview w-100">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            {{$spot->description}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Spot Name</label>
                                <input type="text" class="form-control border-radius-none bg-transparent" id="name" value="{{$spot->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="page_name">Page Name</label>
                                <input type="text" class="form-control border-radius-none bg-transparent" id="page_name" value="{{$spot->page->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">Ad Type</label>
                                <input type="text" class="form-control border-radius-none bg-transparent" id="area" value="{{$spot->parentType->name}} ({{$spot->parentType->width}}x{{$spot->parentType->height}}px)" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Price Option</label>
                            <div class="m-radio-list">
                                @foreach($spot->prices as $price)
                                    <label class="m-radio m-radio--state-primary">
                                        <input type="radio" name="price" value="{{$price->id}}" @if($spot->standardPrice->id==$price->id) checked @endif data-price="{{$price}}">
                                        <p class="slashed_price_text d-inline-block mb-0 ml-0">{{$price->slashed_price? '$'.$price->slashed_price: ''}}</p> ${{$price->price}} / {{$price->getUnit()}}
                                        <span></span>
                                    </label> <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{route('siteAds.index')}}" class="btn border-radius-none btn-outline-primary back_btn">Back</a>
                        <button type="submit" class="btn border-radius-none btn-outline-success smtBtn add_to_cart_btn">Add to cart</button>
                        <a href="{{route('cart.index')}}" class="btn border-radius-none btn-outline-success d-none add_to_cart_btn">
                            Go to cart <i class="fas fa-arrow-right margin-5px-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar_area mt-3 p-lg-5">
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <div id="calendar" class="overflow-auto"></div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-primary border-radius-none" role="alert">
                        Select Period
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table ajaxTable datatable">
                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Price ($)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic_date_field">
                            </tbody>
                        </table>
                        <div class="form-group text-right">
                            <div class="font-size20">Total Price : $<span class="total_price">0.00</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{asset('assets/vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/calendar/moment.js') }}"></script>
    <script src="{{ asset('assets/vendors/calendar/fullcalendar.js') }}"></script>
    <script>
        var g_price = `<?php echo json_encode($spot->standardPrice); ?>`,
            siteads_spot = "{{route('siteAds.spot', $spot->slug)}}",
            slug = "{{$spot->slug}}"
    </script>
    <script src="{{asset('assets/js/front/siteAds/spot.js')}}"></script>
    <script src="{{asset('assets/js/dev1/calendar-hover.js')}}"></script>
@endsection
