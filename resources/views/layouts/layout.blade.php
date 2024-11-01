<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang Chủ')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-1 bg-light border">
                <div class="sticky-top d-flex flex-column vh-100 py-3 justify-content-between align-items-center">
                    <a href="/home" class="btn btn-primary" title="Home"><i class="bi bi-house-door-fill"></i></a>
                    <div class="d-flex flex-column">
                        <a href="/customers" class="mb-4 btn btn-primary" title="Customers"><i class="bi bi-file-earmark-person-fill"></i></a>
                        <a href="/products" class="mb-4 btn btn-primary" title="Products"><i class="bi bi-box-fill"></i></a>
                        <a href="/orders" class="mb-4 btn btn-primary" title="Orders"><i class="bi bi-cart-fill"></i></a>
                        <a href="/notifications" class="btn btn-primary" title="Notifications"><i class="bi bi-bell-fill"></i></a>
                    </div>
                    <form action="/logout" method="POST">
                        @csrf
                        <button title="Logout" href="/logout" type="submit" class="mt-3 btn btn-danger"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </div>

            <div class="col-11">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>