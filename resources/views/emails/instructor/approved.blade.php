<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Instructor Approved</title>
</head>
<body style="font-family: Arial; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:8px;">
        <h2 style="color:#333; margin-bottom:10px;">Congratulations!</h2>
        <p>Your instructor application has been approved.</p>
        <p>You can now create and manage courses on EduAcademy.</p>

        <a href="{{ url('/instructor/dashboard') }}"
           style="display:inline-block; background:#4c6ef5; color:white; padding:12px 20px;
                  border-radius:6px; text-decoration:none; margin-top:20px;">
            Go to Instructor Dashboard
        </a>

        <p style="margin-top:30px; color:#777;">Thank you for joining our instructor community!</p>
    </div>
</body>
</html>
