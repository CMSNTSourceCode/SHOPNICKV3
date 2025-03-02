<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="SHOPNICKv3 By CMSNT.CO">
  <meta name="keywords" content="SHOPNICKv3 By CMSNT.CO">
  <meta name="author" content="cmsnt.co">
  <link rel="icon" href="/_admin/images/favicon/favicon.png" type="image/x-icon">
  <link rel="shortcut icon" href="/_admin/images/favicon/favicon.png" type="image/x-icon">
  <title>@yield('title', 'SHOPNICKV3 - CMSNT.CO') - CMSNT.CO</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600&amp;display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/font-awesome.css">
  <!-- ico-font-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/icofont.css">
  <!-- Themify icon-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/themify.css">
  <!-- Flag icon-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/flag-icon.css">
  <!-- Feather icon-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/feather-icon.css">
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/prism.css">
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/datatables.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.20/sweetalert2.min.css">
  <!-- Bootstrap css-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/vendors/bootstrap.css">
  <!-- App css-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/style.css">
  <link id="color" rel="stylesheet" href="/_admin/css/color-1.css" media="screen">
  <!-- Responsive css-->
  <link rel="stylesheet" type="text/css" href="/_admin/css/responsive.css">

  <!-- CoreJS -->
  <script>
    window.webData = @json([
        'csrfToken' => csrf_token(),
    ]);
    window.userData = @json(auth()->user());
  </script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- extra style -->
  <style>
    * {
      font-family: 'Chakra Petch', sans-serif;
    }
  </style>
  @yield('css')
  @yield('styles')

</head>
