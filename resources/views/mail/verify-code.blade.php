<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #fff;
        }

        .container {
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            padding: 0 0px;
            padding-bottom: 10px;
            border-radius: 5px;
            line-height: 1.8;
        }

        .header {
            border-bottom: 1px solid #eee;
        }

        .header a {
            font-size: 1.4em;
            color: #000;
            text-decoration: none;
            font-weight: 600;
        }

        .content {
            min-width: 700px;
            overflow: auto;
            line-height: 2;
        }

        .otp {
            background: linear-gradient(to right, #00bc69 0, #00bc88 50%, #00bca8 100%);
            margin: 0 auto;
            width: max-content;
            padding: 0 10px;
            color: #fff;
            border-radius: 4px;
        }

        .footer {
            color: #aaa;
            font-size: 0.8em;
            line-height: 1;
            font-weight: 300;
        }

        .email-info {
            color: #666666;
            font-weight: 400;
            font-size: 13px;
            line-height: 18px;
            padding-bottom: 6px;
        }

        .email-info a {
            text-decoration: none;
            color: #00bc69;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>
            We have received a login request for your <strong>highlightsforge.space</strong> account. For
            security purposes, please verify your identity by providing the
            following One-Time Password (OTP).
            <br />
            <br />
            <span>Your verification code is: <strong>{{ $code }}</strong></span>
        </p>
        <p style="font-size: 0.9em">
            <i>One-Time Password (OTP) is valid for 5 minutes.</i>
            <br />
            <br />
            If you did not initiate this login request, please disregard this
            message. Please ensure the confidentiality of your OTP and do not share
            it with anyone.<br />
            <strong>Do not forward or give this code to anyone.</strong>
            <br />
            <br />
            Best regards,
            <br />
            <strong>Highlights Forge</strong>
        </p>
    </div>
</body>

</html>
