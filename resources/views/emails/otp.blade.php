<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: system-ui, sans-serif; background: #1a1a2e; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #16213e; border-radius: 16px; padding: 40px; text-align: center;">
        <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 24px;">ALWEFAQ</h1>
        <p style="color: #94a3b8; margin: 0 0 32px 0;">Verification Code</p>

        <div style="background: #0f3460; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
            <p style="color: #94a3b8; margin: 0 0 8px 0; font-size: 14px;">Your verification code is:</p>
            <p style="color: #ffffff; font-size: 36px; font-weight: bold; letter-spacing: 8px; margin: 0;">
                {{ $code }}
            </p>
        </div>

        <p style="color: #64748b; font-size: 13px; margin: 0;">
            This code expires in 10 minutes.<br>
            If you did not request this code, ignore this email.
        </p>
    </div>
</body>
</html>
