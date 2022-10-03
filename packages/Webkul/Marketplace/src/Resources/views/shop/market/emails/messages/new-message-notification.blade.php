<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap');

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
                background-color: #eeeeee; padding: 16px 32px;
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
            .two-cols {
                width: 100%;
            }
            .two-cols-one {
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
        <div style="margin-bottom: 32px; text-align: center;">
            <a href="https://www.2agunshow.com/" style="display: inline-block;">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>
        <div style=" text-align: center;">
            <p style="font-weight: 700; font-size: 24px; margin-bottom: 16px; color: #111111;">Hey {{$email_data['receiver_name']}} you have a new message from {{$email_data['sender_name']}}</p>
        </div>
        <div
            style="margin-top: 40px;">
            <p style="color: #111111; text-align: center;">"<span>{{$email_data['message']}}</span>"</p>
        </div>
        <div style="text-align: center; margin-top: 45px;">
            <a href="{{ route('marketplace.account.messages.index') }}?m=<?=$email_data['message_id']?>"
               style="display: inline-block; padding: 10px 20px; border: 2px solid #FFC107; background-color: #FFC107; border-radius: 4px; color: #111111; font-weight: 700; text-decoration: none; margin: 5px;">Reply</a>
        </div>
    </div>
</div>

</body>

</html>