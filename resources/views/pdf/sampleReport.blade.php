<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: sans-serif;
            }

            body {
                padding: 5px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                border: 1px solid black;
                text-align: center;
            }

            .title-header td {
                border: none;
            }

            .header, .total, .title {
                font-weight: bold;
            }

            img {
                width: 100px;
                height: auto;
            }

            .data {
                margin-top: 10px;
            }

            .header td {
                padding: 15px 0;
            }

            .reportData td:first-child, .reportData td:last-child {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <table class="title-header">
            <tbody>
                <tr>
                    <td>
                        <img src="../public/images/logo.png" alt="DOH LOGO">
                    </td>
                    <td colspan="12">
                        <h1>Department of Health</h1>
                        <h3>Center for Health Development - Northern Mindanao</h3>
                        <p>J. Seriña St., Carmen, Cagayan de Oro City, 9000</p>
                        <p>Contact No.: (088) 858 7123</p>
                    </td>
                    <td>
                    <img src="../public/images/DOHCHDNM.png" alt="DOH CHDNM LOGO">
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="text-align: center; margin-top: 15px;">
            <tbody>
                <tr>
                    <h3>OBLIGATION PER FUND SOURCE FOR MOOE</h3>
                    <h4>CY 2024</h4>
                </tr>
            </tbody>
        </table>

        <table class="data">
            </tbody>
                <tr class="header">
                    <td>Program</td>
                    @for ($i = 1; $i <= $months; $i++)
                    <td>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</td>
                    @endfor
                    <td>Grand Total</td>
                </tr>

                <tr class="reportData">
                    @foreach ($reportDataToDisplay as $key => $data)
                    @if ($key == 0)
                    <td>{{ $data }}</td>
                    @else
                    <td>{{ number_format(floatval($data), 2, '.', ',') }}</td>
                    @endif
                    @endforeach
                </tr>
            </tbody>
        </table>
    </body>
</html>