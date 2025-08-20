<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Request Resolved</title>
    <style>body{font-family:Arial, sans-serif;}</style>
    </head>
<body>
<p>Hello {{ $supportRequest->name }},</p>
<p>Your request has been resolved. Below is our response:</p>
<blockquote style="border-left:4px solid #ccc;padding-left:8px;">{{ $supportRequest->comment }}</blockquote>
<p>Original message:</p>
<blockquote style="border-left:4px solid #ccc;padding-left:8px;">{{ $supportRequest->message }}</blockquote>
<p>Regards,<br>Support Team</p>
</body>
</html>


