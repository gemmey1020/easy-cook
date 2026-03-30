<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment...</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <p>Redirecting to payment page...</p>
    <script>
        window.location.href = "{{ $iframeUrl }}";
    </script>

</body>

</html>
