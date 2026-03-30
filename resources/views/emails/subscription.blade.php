<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Invoice</title>
</head>
<body>
    <p>Dear {{ $address->user->first_name }},</p>
    <p>Thank you for your subscription. Please find your invoice attached.</p>
    <p>If you have any questions, feel free to contact us.</p>
    <p>Best regards,</p>
    <p>Your Company Name</p>
</body>
</html>
