<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Storefront')</title>
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
