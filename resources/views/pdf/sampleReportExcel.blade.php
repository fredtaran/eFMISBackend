<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report</title>
    </head>
    <body>
        <table class="title-header">
            <tbody>
                <tr>
                    <td></td>

                    <td colspan="{{ $months }}" align="center" height="120px" valign="middle" style="font-size: 18px;">
                        <h1>Department of Health</h1>
                        <h3>Center for Health Development - Northern Mindanao</h3>
                        <p>J. Seriña St., Carmen, Cagayan de Oro City, 9000</p>
                        <p>Contact No.: (088) 858 7123</p>
                    </td>

                    <td></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td></td>
                    <td colspan="{{ $months }}" align="center">
                        <h3>OBLIGATION PER FUND SOURCE FOR MOOE</h3>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="{{ $months }}" align="center">
                        <h4>CY 2024</h4>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table class="data">
            <tbody>
                <tr class="header">
                    <td align="center" width="120px">Program</td>
                    @for ($i = 1; $i <= $months; $i++)
                    <td align="center" width="120px">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</td>
                    @endfor
                    <td align="center" width="120px">Grand Total</td>
                </tr>

                <tr class="reportData">
                    @foreach ($reportDataToDisplay as $key => $data)
                    @if ($key == 0)
                    <td align="center" width="120px">{{ $data }}</td>
                    @else
                    <td align="center" width="120px">{{ number_format(floatval($data), 2, '.', ',') }}</td>
                    @endif
                    @endforeach
                </tr>
            </tbody>
        </table>
    </body>
</html>