@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
    <div class="row">
        <div class="col-6">
            <table border="0">
                <tr>
                    <td width="90px"><b>Nama</b></td>
                    <td width="10px">:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td width="90px"><b>Stok</b></td>
                    <td width="10px">:</td>
                    <td>{{ $product->stock }}</td>
                </tr>
                <tr>
                    <td width="90px"><b>Harga</b></td>
                    <td width="10px">:</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn-solid theme-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
@endsection
