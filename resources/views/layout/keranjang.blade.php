
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
