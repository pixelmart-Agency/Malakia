<!DOCTYPE html lang={{ app()->getLocale() }}>
<html>
@include('web.layouts.head')

<body>
    @include('web.layouts.sidebar')
    <div class="main home">
        <div class="main-content">
            @include('web.layouts.header')
            @yield('content')
        </div>
    </div>
    @include('web.layouts.footer')
    @yield('js')
    </div>
</body>

</html>
