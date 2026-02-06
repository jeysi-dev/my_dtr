<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>DTR - {{ $record->employee_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .container {
            width: 90%;
            max-width: 90%;
            margin: 0 auto;
            padding: 5px 5%;
        }

        .two-column {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .column {
            display: table-cell;
            width: 49%;
            padding: 5px 10px;
            vertical-align: top;
        }

        .column-divider {
            display: table-cell;
            width: 2%;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 2px;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .month-row {
            text-align: center;
            margin-bottom: 3px;
            font-size: 10px;
        }

        .month-value {
            border-bottom: 1px solid #000;
            padding: 0 10px;
            font-weight: bold;
            display: inline-block;
        }

        .fy-indicator {
            float: right;
            font-size: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        .info-table td {
            padding: 1px 2px;
            vertical-align: middle;
            font-size: 10px;
        }

        .info-table .label {
            font-weight: bold;
            width: 70px;
        }

        .info-table .value {
            border-bottom: 1px solid #000;
        }

        .dtr-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 9px;
        }

        .dtr-table th,
        .dtr-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .dtr-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }

        .dtr-table .day-col {
            width: 30px;
            text-align: left;
            padding-left: 2px;
            font-size: 9px;
        }

        .dtr-table .time-col {
            width: 40px;
            font-size: 9px;
        }

        .dtr-table .hours-col {
            width: 28px;
            font-size: 9px;
        }

        .dtr-table .holiday-col {
            text-align: left;
            padding-left: 2px;
        }

        .dtr-table .remark-col {
            width: 35px;
            font-size: 9px;
        }

        .holiday-text {
            font-style: italic;
            color: #333;
            font-size: 9px;
        }

        .flexiplace-row {
            background-color: #f9f9f9;
        }

        .summary-row td {
            border: 1px solid #000;
            padding: 1px 2px;
            font-size: 9px;
        }

        .summary-label {
            font-weight: normal;
            text-align: left;
            padding-left: 3px !important;
        }

        .summary-value {
            text-align: center;
        }

        .summary-title {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
            font-size: 9px;
            border: 1px solid #000;
            padding: 2px;
        }

        .certification {
            font-size: 9px;
            margin-top: 2px;
            margin-bottom: 2px;
            text-align: justify;
            line-height: 1.2;
        }

        .signature-section {
            text-align: center;
            margin-bottom: 2px;
            margin-top: 25px;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 1px;
            font-size: 10px;
        }

        .signature-position {
            font-size: 9px;
        }

        .director-section {
            text-align: center;
            margin-top: 55px;
            padding-top: 1px;
        }

        .director-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10px;
        }

        .director-title {
            font-size: 9px;
        }

        .remarks-section {
            margin-top: 2px;
            font-size: 9px;
        }

        .remarks-label {
            font-weight: bold;
        }

        .weekend-row {
            background-color: #fafafa;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="two-column">
            <!-- First Copy -->
            <div class="column">
                @include('dtr.pdf-single-copy', ['record' => $record, 'summary' => $summary])
            </div>

            <div class="column-divider"></div>

            <!-- Second Copy -->
            <div class="column">
                @include('dtr.pdf-single-copy', ['record' => $record, 'summary' => $summary])
            </div>
        </div>
    </div>
</body>

</html>
