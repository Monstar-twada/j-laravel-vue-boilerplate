<!DOCTYPE html>
<html>
    <head>
      <script>
        var contentWidth = 768;
        var ua = navigator.userAgent;
        if((ua.indexOf('Android') > 0 && ua.indexOf('Mobile') == -1) || ua.indexOf('iPad') > 0 || ua.indexOf('Kindle') > 0 || ua.indexOf('Silk') > 0){
          document.write('<meta name="viewport" content="width=' + contentWidth + '">');
        }else{
          document.write('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>');
        }
      </script>
        <title>CBRE-Tenant</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" charset="UTF-8">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link rel="icon" href="{{{ asset('favicon/favicon.ico') }}}" type="image/gif" sizes="32x32">
        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>
    </head>
    <body>
        <div id="app" v-cloak></div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
