
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Brotherhood cafe.">
    <meta name="author" content="Brotherhood">
    <link rel="shortcut icon" href="{{ asset('dist') }}/assets/imgs/bh.png" type="image/x-icon">
    <title>{{$title}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('dist') }}/assets/vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('dist') }}/assets/vendors/animate/animate.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <a class="nav-link" href="{{ route('customer.cart') }}">
                        <i class="fa fa-shopping-bag"></i>
                        @if($cartCount > 0)
                        <span class="badge badge-light">{{ $cartCount }}</span>
                    @endif
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-link" href="">
                        {{ $customer ? $customer->name : 'Guest' }}
                    </span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.logout') }}" class="btn btn-primary ml-xl-4">Logout</a>
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



    <!-- Menu Section -->
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
                                            <a href="#" class="badge badge-primary"
                                            data-toggle="modal"
                                            data-target="#productDetailModal"
                                            data-id="{{ $product->id }}"
                                            data-category="{{ $product->category_id }}"
                                            data-name="{{ $product->name }}"
                                            data-description="{{ $product->description }}"
                                            data-price="{{ number_format($product->price, 0, ',', '.') }}"
                                            data-image="{{ asset('storage/' . $product->foto) }}">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </a>
                                        </h1>
                                        <h4 class="pt-2 pb-2">{{ $product->name }}</h4>
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

    <!-- Modal Product Detail -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailModalLabel">Detail Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalProductImageDetail" src="" class="mb-3 img-fluid" alt="Product Image">
                    <h4 class="text-dark" id="modalProductNameDetail"></h4>
                    <p class="text-dark" id="modalProductDescriptionDetail"></p>
                    <h5 class="text-dark" id="modalProductPriceDetail"></h5>
                    <input type="hidden" id="modalProductIdDetail" value="">
                    <input type="hidden" id="modalCategoryIdDetail" value="">
                    <button id="addToCartButtonDetail" class="btn btn-primary btn-block">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>
    </div>



    <!-- CONTACT Section  -->
    <div id="contact" class="container-fluid bg-dark text-light border-top wow fadeIn">
        <div class="row">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63352.53662248066!2d112.58410744999999!3d-7.0639625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77fd5da6655d1f%3A0xebbf23ae9d4ba627!2sLos%20Brotherhood!5e0!3m2!1sid!2sid!4v1733578458194!5m2!1sid!2sid"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
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
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function resetModal(modal) {
                modal.find('input[type="hidden"]').val('');
                modal.find('.modal-body img').attr('src', '');
                modal.find('.modal-body h4').text('');
                modal.find('.modal-body p').text('');
                modal.find('.modal-body h5').text('');
            }

            $('#productDetailModal, #productRecommendationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var modal = $(this);

                // Data modal value tombol
                var productId = button.data('id');
                var categoryId = button.data('category');
                var productName = button.data('name');
                var productDescription = button.data('description');
                var productPrice = button.data('price');
                var productImage = button.data('image');
                resetModal(modal);
                modal.find('#modalProductIdDetail, #modalProductIdRecommendation').val(productId);
                modal.find('#modalCategoryIdDetail, #modalCategoryIdRecommendation').val(categoryId);
                modal.find('.modal-body img').attr('src', productImage);
                modal.find('.modal-body h4').text(productName);
                modal.find('.modal-body p').text(productDescription);
                modal.find('.modal-body h5').text('Rp ' + productPrice);
            });

            $('#addToCartButtonDetail, #addToCartButtonRecommendation').click(function () {
                var buttonId = $(this).attr('id');
                var productId = buttonId === 'addToCartButtonDetail'
                    ? $('#modalProductIdDetail').val()
                    : $('#modalProductIdRecommendation').val();
                var categoryId = buttonId === 'addToCartButtonDetail'
                    ? $('#modalCategoryIdDetail').val()
                    : $('#modalCategoryIdRecommendation').val();
                var customerId = {{ Auth::guard('customer')->user()->id }};

                $.ajax({
                    url: '/customer/dashboard/add-to-cart',
                    method: 'POST',
                    data: {
                        customer_id: customerId,
                        category_id: categoryId,
                        produk_id: productId
                    },
                    success: function (response) {
                        alert(response.success || 'Produk berhasil ditambahkan ke keranjang!');
                        $('.modal').modal('hide');
                    },
                    error: function (xhr) {
                        var errorMsg = xhr.responseJSON?.error || 'Terjadi kesalahan saat menambahkan produk ke keranjang.';
                        alert(errorMsg);
                    }
                });
            });
        });
        </script>


    <!-- end of page footer -->
    <script src="{{ asset('dist') }}/assets/vendors/jquery/jquery-3.4.1.js"></script>
    <script src="{{ asset('dist') }}/assets/vendors/bootstrap/bootstrap.bundle.js"></script>

    <!-- bootstrap affix -->
    <script src="{{ asset('dist') }}/assets/vendors/bootstrap/bootstrap.affix.js"></script>

    <!-- wow.js -->
    <script src="{{ asset('dist') }}/assets/vendors/wow/wow.js"></script>

    <!-- FoodHut js -->
    <script src="{{ asset('dist') }}/assets/js/foodhut.js"></script>

</body>
</html>
