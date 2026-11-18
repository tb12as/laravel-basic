@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <a class="btn-solid theme-primary my-3" href="{{ route('products.create') }}">Tambah Produk</a>
    <a class="btn-subtle theme-primary my-3" href="{{ route('products.fetch') }}">Versi Fetch API</a>
    <table class="table table-bordered" border="1" cellpadding="6">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <a class="btn-subtle theme-success btn-sm"
                            href="{{ route('products.show', ['product' => $product]) }}">Detail</a>

                        <a class="btn-subtle theme-warning btn-sm"
                            href="{{ route('products.edit', ['product' => $product]) }}">Edit</a>

                        <button class="btn-subtle theme-danger btn-sm"
                            onclick="handleDelete({{ $product->id }}, '{{ $product->name }}')">Hapus</button>

                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                            id="delete-{{ $product->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada produk untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('script')
    <script>
        function handleDelete(id, name) {
            if (!confirm(`${name} akan dihapus, aksi ini tidak dapat dibatalkan!`)) {
                return
            }
            const form = document.getElementById('delete-' + id);
            if (form) form.submit()
        }
    </script>
@endsection
