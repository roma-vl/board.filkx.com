<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/swagger-ui.css')}}">
</head>
<body>
<div id="swagger-ui"></div>

<script src="{{ asset('js/swagger-ui-bundle.js')}}"></script>
<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            url: '/docs/{{$docName}}',
            dom_id: '#swagger-ui',
            deepLinking: true,
            filter: true,
            presets: [
                SwaggerUIBundle.presets.apis,
            ],
            displayRequestDuration: true,
            layout: 'BaseLayout',
        });
    };
</script>
</body>
</html>
