<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Storefront')</title>
  <link rel="icon" type="image/x-icon" href="https://images.icon-icons.com/844/PNG/512/AWS_icon-icons.com_67084.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://images.icon-icons.com/844/PNG/512/AWS_icon-icons.com_67084.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://images.icon-icons.com/844/PNG/512/AWS_icon-icons.com_67084.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://images.icon-icons.com/844/PNG/512/AWS_icon-icons.com_67084.png">
  @include('frontend.partials.styles')
</head>
<body class="@yield('body-class')">
  @include('frontend.partials.header')

  <main class="mx-auto grid max-w-7xl gap-3 px-4 py-6 lg:grid-cols-[260px_minmax(0,1fr)]" role="main">
    @yield('content')
  </main>

  @include('frontend.partials.footer')
  @include('frontend.partials.scripts')
</body>
</html>
