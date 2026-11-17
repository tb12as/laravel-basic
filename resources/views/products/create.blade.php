@extends('layouts.app')

@section('title', 'Buat Produk')

@section('content')
    <div class="row">
        <div class="col-6">
            @if ($errors->any())
                <div class="alert theme-danger" role="alert">
                    <ul class="m-0 p-0 px-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-field mb-2">
                    <label for="nameInput" class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" id="nameInput" placeholder="Nama Produk"
                        value="{{ old('name') }}">
                </div>

                <div class="form-field mb-2">
                    <label for="priceInput" class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" id="priceInput" placeholder="Harga"
                        value="{{ old('price') }}">
                </div>

                <div class="form-field mb-2">
                    <label for="stockInput" class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" id="stockInput" placeholder="Stok"
                        value="{{ old('stock') }}">
                </div>

                <input type="submit" value="Kirim" class="btn-solid theme-primary mt-2">
            </form>
        </div>
    </div>
@endsection
