<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>

    <!-- Bootstrap 5.3.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MyApp</a>
    </div>
</nav>

<!-- Page Content -->
<main class="">

    @yield('content')

</main>

<!--- Toast starts--->
@if(session()->has('success'))
    <div id="toast-container" class="toast-top-center">
        <div class="toast toast-success" aria-live="polite" style="">

            <div class="toast-message"> {{ session('success') }}</div>
        </div>
    </div>
@endif


@if(session()->has('failed'))
    <div id="toast-container" class="toast-top-center">
        <div class="toast toast-warning" aria-live="polite" style="">

            <div class="toast-message"> {{ session('failed') }}</div>
        </div>
    </div>
@endif

<script>

    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'You have logged in successfully.',
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap 5.3.6 JS Bundle with Popper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
