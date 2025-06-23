<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        p {
            line-height: 1.6;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
        .details {
            background-color: #f4f6f8;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, {{ $user->name ?? $user->email }}!</h2>
    <p>You have just logged into your account.</p>

    <div class="details">
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>User ID:</strong> {{ $user->id }}</p>
        <p><strong>Login Time:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        <p><strong>IP Address:</strong> {{ request()->ip() }}</p>
    </div>

    <p>If this wasn't you, we strongly recommend you secure your account immediately by changing your password and enabling additional security features.</p>

    <div class="footer">
        &copy; {{ now()->year }} YourApp. All rights reserved.
    </div>
</div>
</body>
</html>
