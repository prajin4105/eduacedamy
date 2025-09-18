<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduAcademy - Learn from the Best</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
    
    <script>
        // Make user data available to Vue
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            user: @json(auth()->user()),
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }}
        };
    </script>
</body>
</html>
