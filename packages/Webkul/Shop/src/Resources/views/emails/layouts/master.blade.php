<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        p {
            margin-top: 0;
            margin-bottom: 8px;
        }
        .gray-bg {
            background-color: #eeeeee; padding: 48px 64px;
        }
        .white-bg {
            background-color: white; padding: 48px;
        }
        .two-cols {
            width: 45%;
        }
        .two-cols-one {
            padding-right: 30px;
        }
        .three-cols {
            width: 30%;
        }
        .three-cols-one {
            padding-right: 3%;
        }
        .mail-table {
            font-size: 16px;
        }
        .order-summary {
            width: 40%;
        }
        .table-cell-name {
            width: 60%;
        }
        @media (max-width: 812px) and (min-width: 640px) {
            .gray-bg {
                background-color: #eeeeee; padding: 32px;
            }
            .white-bg {
                background-color: white; padding: 32px;
            }
            .mail-table {
                font-size: 13px;
            }
            .order-summary {
                width: 70%;
            }
            .table-cell-name {
                width: 45%;
            }
        }
        @media (max-width: 640px) {
            .mail-table {
                font-size: 13px;
            }
            .two-cols, .three-cols {
                width: 100%;
            }
            .two-cols-one, .three-cols-one {
                padding-right: 0;
                margin-bottom: 30px;
            }
            .gray-bg {
                background-color: transparent; padding: 0px 0px;
            }
            .white-bg {
                background-color: white; padding: 32px 16px;
            }
            .table-cell-name {
                width: 45%;
            }
            .order-summary {
                width: 100%;
            }
        }
    </style>
</head>

<body style="font-family: 'Open Sans', sans-serif; font-size: 16px; color: #111111; font-weight: 400;">
    <div class="gray-bg">
        <div class="white-bg" style="max-width: 950px; margin: 0 auto;">
            <div>
                {{ $header ?? '' }}
            </div>
            <div>
                {{ $slot }}

                {{ $subcopy ?? '' }}
            </div>
        </div>
    </div>
</body>

</html>