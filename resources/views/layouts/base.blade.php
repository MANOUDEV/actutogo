<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title') | Togo actualité</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Webestica.com">
        <meta name="description" content="Bootstrap based News, Magazine and Blog Theme">

        <!-- Favicon -->
        <link rel="shortcut icon" href="/assets/images/logo.png">

        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Plugins CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css"> 
        <link rel="stylesheet" type="text/css" href="/assets/vendor/tiny-slider/tiny-slider.css">

        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
         <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5594963864359932"
                crossorigin="anonymous"></script>
        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body >

        <div id="app">
            @yield('content')
        </div>
           
        <!-- Back to top -->
        <div class="back-top"><i class="bi bi-arrow-up-short"></i></div>
        
        <!-- Vendors -->
        <script defer src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

        <!-- =======================JS libraries, plugins and custom scripts -->

        <!-- Bootstrap JS -->
        <script defer src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Vendors -->
        <script defer src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
        <script defer src="/assets/vendor/sticky-js/sticky.min.js"></script>

        <!-- Template Functions -->
        <script defer src="/assets/js/functions.js"></script>

    </body>
</html> 
