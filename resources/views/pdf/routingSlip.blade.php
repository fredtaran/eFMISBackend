<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Routing Slip</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }

            body {
                padding: 5px;
                font-size: 16px;
            }
            
            .header {
                text-align: center;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                border: 1px solid black;
                text-align: center;
                font-size: 0.5rem;
            }

            .main {
                margin-top: 25px;
            }

            .header h1 {
                color: #0039a6;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>DOH CHD - NORTHERN MINDANAO</h1>
            <h4>eFinance Management Information System</h4>
        </div>

        <div class="main">
            <table>
                <tbody>
                    <tr>
                        <td>Reference Number</td>
                        <td>{{ $transactionDetails->reference_no }}</td>
                        <td rowspan="3" valign="middle" width="90px" style="padding-left: 25px; text-align: left;">
                            {!! DNS2D::getBarcodeHTML($transactionDetails->reference_no, 'QRCODE', 3, 3) !!}
                            {{ $transactionDetails->reference_no }}
                        </td>
                    </tr>

                    <tr>
                        <td>Activity/Particulars</td>
                        <td>{{ $transactionDetails->activity_title }}</td>
                    </tr>

                    <tr>
                        <td>Date</td>
                        <td>{{ $transactionDetails->created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            
        </div>
    </body>
</html>