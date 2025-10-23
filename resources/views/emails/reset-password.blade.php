<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
    <style>
        body {
            background: #f8fafc;
            font-family: Arial, sans-serif;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            max-width: 600px;
            margin: 50px auto;
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            color: #aaa;
            font-size: 12px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello, {{ $user->name ?? 'User' }} 👋</h2>
        <p>You requested to reset your password. Click the button below to create a new one.</p>
        <a href="{{ $resetUrl }}" class="btn" style="color: #fff !important;">Reset Password</a>
        <p>If you didn’t request this, you can safely ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
