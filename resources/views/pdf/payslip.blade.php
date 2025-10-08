<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payslip</title>
    <style>
        {!! file_get_contents(public_path('css/adminlte.css')) !!}
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <table width="100%">
                <tr>
                    <td align="left" valign="top">
                        <h2 class="mb-0">{{ getCompany()->name }}</h2>
                        <p class="mb-0">{{ 'Email: ' . getCompany()->email }}</p>
                    </td>
                    <td align="right" valign="top">
                        <h1 class="text-right">PAYSLIP</h1>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row mt-4">
            <table width="100%">
                <tr>
                    <td width="20%" valign="top">
                        Employee Name
                    </td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">{{ $salary->employee->name }}</td>
                    <td width="20%" valign="top">Payroll Period</td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">{{ $salary->payroll->month_string }}</td>
                </tr>
                <tr>
                    <td width="20%" valign="top">
                        Job Position
                    </td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">{{ $salary->employee->designation->name }}</td>
                    <td width="20%" valign="top">Rate</td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">{{ 'IDR ' . number_format($salary->gross_salary, 0, ',', '.'); }}</td>
                </tr>
                <tr>
                    <td width="20%" valign="top">
                        PTKP
                    </td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">
                        @php
                            $ptkp = '';
                            if($salary->employee->marital_status === 'married') {
                                $ptkp .= 'K/';
                            } else {
                                $ptkp .= 'TK/';
                            }

                            if($salary->employee->dependents_count >= 3) {
                                $ptkp .= '3';
                            } else {
                                $ptkp .= $salary->employee->dependents_count;
                            }
                        @endphp
                        {{ $ptkp }}
                    </td>
                    <td width="20%" valign="top">Rate Type</td>
                    <td width="5%" align="center" valign="top">:</td>
                    <td width="25%" valign="top">{{ $salary->employee->getActiveContract()->rate_type }}</td>
                </tr>
            </table>
        </div>
        <div class="row mt-4">
            <table class="table table-striped table-bordered table-hover" width="100%">
                <tr class="text-center" style="background-color: #d5d5d5;">
                    <td width="50%" class="p-1">Description</td>
                    <td width="25%" class="p-1">Earnings</td>
                    <td width="25%" class="p-1">Deductions</td>
                </tr>
                <tr>
                    <td class="p-1 border-left text-center">Base Salary</td>
                    <td class="p-1 text-right" style="text-align: right;">{{ number_format($salary->gross_salary, 0, ',', '.') }}</td>
                    <td class="p-1 border-right"></td>
                </tr>
                <tr>
                    <td class="p-1 border-left text-center">BPJS Kesehatan</td>
                    <td class="p-1"></td>
                    <td class="p-1 border-right" style="text-align: right;">{{ number_format($salary->breakdown->calculateBPJSKesehatan(), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="p-1 border-left text-center">BPJS JHT</td>
                    <td class="p-1"></td>
                    <td class="p-1 border-right" style="text-align: right;">{{ number_format($salary->breakdown->calculateBPJSJHT(), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="p-1 border-left text-center">BPJS JHT</td>
                    <td class="p-1"></td>
                    <td class="p-1 border-right" style="text-align: right;">{{ number_format($salary->breakdown->calculateBPJSJP(), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="p-1 border-left text-center">PPH 21</td>
                    <td class="p-1"></td>
                    <td class="p-1 border-left border-right" style="text-align: right;">{{ number_format($salary->breakdown->calculatePPH21(), 0, ',', '.') }}</td>
                </tr>
                <tr class="fw-bold" style="background-color: #d5d5d5;">
                    <td class="p-1 text-center">Total</td>
                    <td class="p-1" style="text-align: right;">{{ number_format($salary->gross_salary, 0, ',', '.') }}</td>
                    <td class="p-1" style="text-align: right;">{{ number_format($salary->breakdown->calculateBPJSKesehatan() + $salary->breakdown->calculateBPJSJHT() + $salary->breakdown->calculateBPJSJP() + $salary->breakdown->calculatePPH21(), 0, ',', '.') }}</td>
                </tr>
                <tr class="fw-bold" style="background-color: #d5d5d5;">
                    <td class="p-1 text-center">Net Pay</td>
                    <td colspan="2" class="p-1" style="text-align: right;">{{ number_format($salary->breakdown->calculateNetPay(), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div class="row mt-">

        </div>
    </div>
</body>

</html>
