<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Your OTP Code</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f6f8fa; margin: 0; padding: 0; }
    .container { max-width: 500px; margin: 30px auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .header { text-align: center; margin-bottom: 30px; }
    .header h2 { margin: 0; color: #6a4413; }
    .subtext { font-size: 14px; color: #6a4413; margin-bottom: 20px; }
    .otp-box { background-color: #fdf2e9; color: #6a4413; font-size: 32px; letter-spacing: 8px; font-weight: bold; text-align: center; padding: 15px; border-radius: 8px; margin: 20px 0; }
    .message { font-size: 16px; color: #555555; line-height: 1.5; }
    .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #aaaaaa; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='header'>
      <h2>OTP Verification</h2>
    </div>
    <div class='message'>
      <p>Hello,</p>
      <p>Use the following One-Time Password (OTP) to verify your identity. This code is valid for the next 5 minutes:</p>
    </div>
    <div class='otp-box'>
      $otp
    </div>
    <div class='message'>
      <p>If you did not request this code, please ignore this message or contact support.</p>
    </div>
    <div class='footer'>
      &copy; 2025 Austin's Cafe & Gastro Pub. All rights reserved.
    </div>
  </div>
</body>
</html>