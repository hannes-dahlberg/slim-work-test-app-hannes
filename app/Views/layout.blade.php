<html>
    <head>
        @include('components.header')
    </head>
    <body>
        <div class="container">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
                @yield('content')
                @include('components.footer')
            </div>
        </div>
    </body>
</html>