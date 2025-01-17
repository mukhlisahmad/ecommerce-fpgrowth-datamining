
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
                    <a class="nav-link" href="{{ route('customer.view') }}">Home</a>
                </li>

            </ul>
            <a class="m-auto navbar-brand" href="#">

                <span class="brand-txt">Brotherhood</span>
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fa fa-shopping-bag"></i>
                        @if($cartCount > 0)
                        <span class="badge badge-light">{{ $cartCount }}</span>
                    @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.orders') }}">
                        Orderan
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-link">
                        {{ $customer ? $customer->name : 'Guest' }}
                    </span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.logout') }}" class="btn btn-primary ml-xl-4">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="mt-4 text-center bg-dark text-light has-height-md middle-items wow fadeIn">
        <div class="row">
            <div class="col-sm-12 col-lg-12 gallary-item wow fadeIn">
                <h1>Keranjang Belanja</h1>

                @if($keranjangItems->isEmpty())
                <p>Keranjang Anda kosong.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            {{-- <th>category</th> --}}
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keranjangItems as $item)
                            <tr>
                                <td>
                                    @if($item->product && $item->product->foto)
                                        <img src="{{ asset('storage/' . $item->product->foto) }}"
                                             class="rounded-0 card-img-top mg-responsive"
                                             alt="{{ $item->product->name }}"
                                             style="width: 50px; height: auto;">
                                    @else
                                        <img src="{{ asset('images/default.png') }}"
                                             class="rounded-0 card-img-top mg-responsive"
                                             alt="No Image Available"
                                             style="width: 50px; height: auto;">
                                    @endif
                                </td>
                                <td>{{ $item->product->name }}</td>
                                {{-- <td>{{ $item->product->category_id }}</td> --}}
                                <td>{{ $item->quantity }} pcs</td>
                                <td>{{ number_format($item->total_price, 2) }}</td>
                                <td>
                                    <form action="{{ route('customer.cart.delete', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('customer.checkout') }}" class="btn btn-primary">Lanjutkan Checkout</a>
            @endif

            </div>
        </div>
    </div>
<!-- page footer -->
<div class="text-center container-fluid bg-dark text-light has-height-md middle-items border-top wow fadeIn">
    <div class="row">
        <div class="mb-3 col-sm-12 col-lg-12 item-center">
            <h2 class="text-center">
                Menu Lain yang sering di pesan
            </h2>
        </div>
        @if(empty($fpgrowthData['association_rules']) || count($fpgrowthData['association_rules']) == 0)
            <p class="text-center text-light">Belum ada rekomendasi produk untukmu saat ini.</p>
        @else
            <div class="gallery row">
                @php
                    $productIds = [];
                    foreach ($fpgrowthData['association_rules'] as $rule) {
                        $consequents = explode(",", $rule['consequents']);
                        $productIds = array_merge($productIds, $consequents);
                    }
                    $productIds = array_unique($productIds);
                @endphp

                @foreach($productIds as $productId)
                    @php
                        $product = \App\Models\Product::find($productId);
                    @endphp
                    @if($product)
                        <div class="col-sm-6 col-md-4 col-lg-3 gallery-item">
                            <div class="gallery-card">
                                <img src="{{ asset('storage/' . $product->foto) }}" class="rounded gallery-img" alt="{{ $product->name }}">
                                <a class="gallery-overlay"
                                  data-toggle="modal"
                                  data-target="#productDetailModal"
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
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>


<!-- Modal untuk Detail Produk -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="productDetailModalLabel">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" class="img-fluid" alt="">
                <h4 id></h4>
                <input type="hidden" id="modalProductIdDetail">
                <input type="hidden" id="modalCategoryIdDetail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="addToCartButtonDetail" class="btn btn-primary">Tambah ke Keranjang</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="text-center bg-dark text-light border-top wow fadeIn">
    <p class="py-3 mb-0 text-muted small">&copy; Copyright <script>.write(new Date().getFullYear())</script> Brotherhood <i class="ti-heart text-danger"></i></p>
</div>

<style>
    /* Gallery styling */
.gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.gallery-item {
    flex: 1 1 22%; /* Ukuran dasar item gallery */
    max-width: 22%; /* Maksimum lebar item gallery */
    box-sizing: border-box;
    margin-bottom: 20px; /* Ruang di bawah setiap gambar */
    transition: transform 0.3s ease-in-out;
}

.gallery-item:hover {
    transform: scale(1.05); /* Zoom efek saat hover */
}

.gallery-card {
    position: relative;
    overflow: hidden;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.gallery-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease-in-out;
}

.gallery-card:hover .gallery-img {
    transform: scale(1.05); /* Zoom efek gambar saat hover */
}

.gallery-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    background-color: rgba(0, 0, 0, 0.6);
    padding: 10px;
    border-radius: 50%;
    display: none;
}

.gallery-item:hover .gallery-overlay {
    display: block; /* Menampilkan overlay saat hover */
}

.gallery-info {
    padding: 15px;
    text-align: center;
}

.gallery-info h5 {
    font-size: 1rem;
    font-weight: bold;
    color: #333;
}

</style>
<script>
    $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Reset modal function
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
