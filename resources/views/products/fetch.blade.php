@extends('layouts.app')

@section('title', 'Produk (Fetch API)')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="alert theme-danger" role="alert" id="errors" hidden>
                <ul class="m-0 p-0 px-4"></ul>
            </div>

            <form id="productForm">
                <div class="form-field mb-2">
                    <label for="nameInput" class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" id="nameInput" placeholder="Nama Produk">
                </div>

                <div class="form-field mb-2">
                    <label for="priceInput" class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" id="priceInput" placeholder="Harga">
                </div>

                <div class="form-field mb-2">
                    <label for="stockInput" class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" id="stockInput" placeholder="Stok">
                </div>

                <input type="submit" value="Kirim" class="btn-solid theme-primary mt-2">
            </form>
        </div>
    </div>

    <table class="table table-bordered my-3">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody id="rows">
            <tr>
                <td colspan="4" class="text-center">Memuat...</td>
            </tr>
        </tbody>
    </table>
@endsection

@section('script')
    <script>
        const rows = document.getElementById('rows')
        const form = document.getElementById('productForm')
        const errBox = document.getElementById('errors')
        const rupiah = new Intl.NumberFormat('id-ID')
        const headers = {
            Accept: 'application/json',
            'Content-Type': 'application/json'
        }

        function msgRow(text) {
            const tr = rows.insertRow()
            const td = tr.insertCell()
            td.colSpan = 4
            td.className = 'text-center'
            td.textContent = text
        }

        function showErrors(list) {
            const ul = errBox.querySelector('ul')
            ul.replaceChildren(...list.map(m => Object.assign(document.createElement('li'), {
                textContent: m
            })))
            errBox.hidden = list.length === 0
        }

        async function load() {
            rows.replaceChildren()

            try {
                const res = await fetch('/api/products', {
                    headers
                })
                if (!res.ok) throw new Error(res.status)

                const products = await res.json()
                if (products.length === 0) return msgRow('Tidak ada produk untuk ditampilkan.')

                // textContent keeps user-provided names from being parsed as html
                products.forEach((p, i) => {
                    const tr = rows.insertRow()
                    for (const v of [i + 1, p.name, rupiah.format(p.price), p.stock]) tr.insertCell()
                        .textContent = v
                })
            } catch (e) {
                msgRow('Gagal memuat produk.')
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            try {
                const res = await fetch('/api/products', {
                    method: 'POST',
                    headers,
                    body: JSON.stringify(Object.fromEntries(new FormData(form))),
                })

                if (res.status === 422) return showErrors(Object.values((await res.json()).errors).flat())
                if (!res.ok) throw new Error(res.status)

                showErrors([])
                form.reset()
                load()
            } catch (e) {
                showErrors(['Gagal menyimpan produk.'])
            }
        })

        load()
    </script>
@endsection
