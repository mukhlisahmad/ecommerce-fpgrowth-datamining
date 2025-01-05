
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Brotherhood cafe.">
    <meta name="author" content="Brotherhood">
    <link rel="shortcut icon" href="{{ ('dist') }}/assets/imgs/bh.png" type="image/x-icon">
    <title>{{$title}}</title>

    <!-- font icons -->

    <link rel="stylesheet" href="{{ asset('dist') }}/assets/vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('dist') }}/assets/vendors/animate/animate.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap + FoodHut main styles -->
	<link rel="stylesheet" href="{{ asset('dist') }}/assets/css/foodhut.css">
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="40" id="home">

    <!-- Navbar -->
    <nav class="custom-navbar navbar navbar-expand-lg navbar-dark fixed-top" data-spy="affix" data-offset-top="10">
        <button class="m-2 mb-4 navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#terlaris">Produk Terlaris</a>
                </li>

            </ul>
            <a class="m-auto navbar-brand" href="#">
                <img src="{{ asset('dist') }}/assets/imgs/bh.png" class="brand-img" alt="">
                <span class="brand-txt">Brotherhood</span>
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.login') }}">
                        <i class="fa fa-shopping-bag"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.login') }}" class="btn btn-primary ml-xl-4">Login</a>
                    <a href="{{ route('customer.register') }}" class="btn btn-primary ml-xl-4">register</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- header -->
    <header id="home" class="header">
        <div class="text-center text-white overlay">
            <h1 class="my-3 display-4 font-weight-bold">Brotherhood</h1>
            <h2 class="mb-5 display-8">"Ojok Sampek Jam Kerjomu</h2>
            <h2 class="mb-5 display-8">Ganggu Jam Ngopimu"</h2>
            <a class="btn btn-lg btn-primary" href="#menu">Menu</a>
        </div>
    </header>
    {{-- <div id="rekomendasi" class="text-center bg-dark text-light has-height-md middle-items wow fadeIn">
        <h2 class="section-title">Rekomendasi Untukmu</h2>
    </div>
    @if(empty($fpgrowthData['transformed_data']) || empty($fpgrowthData['transformed_data'][1]))
    <p class="text-center text-light">Belum ada rekomendasi produk untukmu saat ini.</p>
    @else
        <div class="gallery row">
            @foreach($fpgrowthData['transformed_data'][1] as $categoryId) <!-- Use transformed_data[1] -->
                @php
                    $products = \App\Models\Product::where('id', $categoryId)->get();
                @endphp

                @foreach($products as $product)
                    <div class="col-sm-6 col-lg-3 gallery-item wow fadeIn">
                        @if(file_exists(public_path('storage/' . $product->foto)))
                            <img src="{{ asset('storage/' . $product->foto) }}" class="gallery-img" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('storage/default-image.jpg') }}" class="gallery-img" alt="{{ $product->name }}">
                        @endif

                        <a href="#" class="gallery-overlay"
                            data-id="{{ $product->id }}"
                            data-category="{{ $product->category_id }}"
                            data-name="{{ $product->name }}"
                            data-description="{{ $product->description }}"
                            data-price="{{ number_format($product->price, 0, ',', '.') }}"
                            data-image="{{ asset('storage/' . $product->foto) }}">
                            <i class="gallery-icon ti-plus"></i>
                        </a>

                        <div class="gallery-info">
                            <h5>{{ $product->name }}</h5>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif --}}


    <!-- menu Section  -->
    <div id="menu" class="py-5 text-center container-fluid bg-dark text-light wow fadeIn">
        <h2 class="py-5 section-title">MENU</h2>
        <div class="row justify-content-center">
            <div class="mb-5 col-sm-7 col-md-4">
                <ul class="mb-3 nav nav-pills nav-justified" id="pills-tab" role="tablist">
                    @foreach ($categories as $key => $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == 0 ? 'active' : '' }}"
                               id="tab-{{ $category->id }}"
                               data-toggle="pill"
                               href="#category-{{ $category->id }}"
                               role="tab"
                               aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">
            @foreach ($categories as $key => $category)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                     id="category-{{ $category->id }}"
                     role="tabpanel">
                    <div class="row">
                        @foreach ($category->products as $product)
                            <div class="col-md-4">
                                <div class="my-3 bg-transparent border card my-md-0">
                                    <img src="{{ asset('storage/' . $product->foto) }}"
                                         class="rounded-0 card-img-top mg-responsive"
                                         alt="{{ $product->name }}">
                                    <div class="card-body">
                                        <h1 class="mb-4 text-center">
                                            <a href="{{ route('customer.login') }}" class="badge badge-primary">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </a>
                                        </h1>
                                        <h4 class="pt20 pb20">{{ $product->name }}</h4>
                                        <p class="text-white">{{ $product->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <!-- CONTACT Section  -->
    <div id="contact" class="container-fluid bg-dark text-light border-top wow fadeIn">
        <div class="row">
            <div class="px-0 col-md-6">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63352.53662248066!2d112.58410744999999!3d-7.0639625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77fd5da6655d1f%3A0xebbf23ae9d4ba627!2sLos%20Brotherhood!5e0!3m2!1sid!2sid!4v1733578458194!5m2!1sid!2sid"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="px-5 col-md-6 has-height-lg middle-items">
                <h3>Contact</h3>
                <p></p>
                <div class="text-muted">
                    <p><span class="pr-3 ti-location-pin"></span> 12345 Fake ST NoWhere, AB Country</p>
                    <p><span class="pr-3 ti-support"></span> (123) 456-7890</p>
                    <p><span class="pr-3 ti-email"></span>info@website.com</p>
                </div>
            </div>
        </div>
    </div>

    <!-- page footer  -->
    <div class="text-center container-fluid bg-dark text-light has-height-md middle-items border-top wow fadeIn">
        <div class="row">
            <div class="col-sm-4">
                <h3>EMAIL US</h3>
                <P class="text-muted"></P>
            </div>
            <div class="col-sm-4">
                <h3>CALL US</h3>
                <P class="text-muted"></P>
            </div>
            <div class="col-sm-4">
                <h3>FIND US</h3>
                <P class="text-muted"></P>
            </div>
        </div>
    </div>
    <div class="text-center bg-dark text-light border-top wow fadeIn">
        <p class="py-3 mb-0 text-muted small">&copy; Copyright <script>.write(new Date().getFullYear())</script> Brotherhood <i class="ti-heart text-danger"></i></p>
    </div>
    <!-- end of page footer -->

	<!-- core  -->
    <script src="{{ asset('dist') }}/assets/vendors/jquery/jquery-3.4.1.js"></script>
    <script src="{{ asset('dist') }}/assets/vendors/bootstrap/bootstrap.bundle.js"></script>

    <!-- bootstrap affix -->
    <script src="{{ asset('dist') }}/assets/vendors/bootstrap/bootstrap.affix.js"></script>

    <!-- wow.js -->
    <script src="{{ asset('dist') }}/assets/vendors/wow/wow.js"></script>

    <!-- google maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtme10pzgKSPeJVJrG1O3tjR6lk98o4w8&callback=initMap"></script>

    <!-- FoodHut js -->
    <script src="{{ asset('dist') }}/assets/js/foodhut.js"></script>

</body>
</html>
