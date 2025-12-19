<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $data['subject'] }}</title>
</head>
<body style="background-color:#f8f9fa; margin:0; padding:0; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
    <tr>
        <td align="center">

            <!-- Card -->
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:6px; box-shadow:0 0 10px rgba(0,0,0,0.05);">
                
                <!-- Header -->
                <tr>
                    <td style="padding:20px 30px; background:#0d6efd; color:#ffffff; border-radius:6px 6px 0 0;">
                        <h2 style="margin:0; font-size:20px;">
                            {{ config('app.name') }}
                        </h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px;">
                        <h3 style="margin-top:0; color:#212529;">
                            You’ve been invited
                        </h3>

                        <p style="color:#6c757d; font-size:15px; line-height:1.6;">
                            {{ $data['discription'] }}
                        </p>

                        <p style="color:#6c757d; font-size:14px;">
                            {{ $data['tagline'] }}
                        </p>

                        <!-- Button -->
                        <div style="text-align:center; margin:30px 0;">
                            <a href="{{ $data['route'] }}"
                               style="background:#0d6efd; color:#ffffff; padding:12px 25px;
                                      text-decoration:none; border-radius:4px; font-size:15px; display:inline-block;">
                                Accept Invitation
                            </a>
                        </div>

                        <p style="font-size:13px; color:#adb5bd;">
                            This invitation will expire in 7 days.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:15px 30px; background:#f1f3f5; text-align:center; font-size:12px; color:#6c757d;">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
