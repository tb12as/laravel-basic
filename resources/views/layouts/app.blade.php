<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikasi Produk')</title>
    {{-- Bootstrap 6 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-1a/pXj49ZQ1aHEmrJ+gMw1otqoVsYwlEnlD8mIfY2TV03r20Y0CN7uqx1tQogjPL" crossorigin="anonymous">
    </script>
</head>

<body data-bs-theme="light">
    <div class="container mt-4">
        <h1>@yield('title', 'Aplikasi Produk')</h1>

        @if (session('success'))
            <div class="alert theme-success" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </div>
</body>

@yield('script')

</html>
