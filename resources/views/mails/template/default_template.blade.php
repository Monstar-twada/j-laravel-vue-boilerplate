<html>
    <head></head>
    <body>
        @if(View::hasSection('header'))
            @yield('header')
            <br>
        @endif

        @yield('content')
        <br>

        @if(View::hasSection('footer'))
            @yield('footer')
            <br>
        @endif
    </body>
</html>

