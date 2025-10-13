@extends('layouts.app')
@section('title', $title)
@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ session('company_id') ? getCompany()->name . ' ' . $title : $title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ session('company_id') ? getCompany()->name . ' ' . $title : $title }}</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            @if($is_company_selected)
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <!--begin::Small Box Widget 1-->
                                    <div class="small-box text-bg-primary">
                                    <div class="inner">
                                        <h3>{{ $summary['total_employees'] }}</h3>
                                        <p>Employees</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-people-fill small-box-icon" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                    </div>
                                    <!--end::Small Box Widget 1-->
                                </div>
                                <div class="col-lg-3 col-6">
                                    <!--begin::Small Box Widget 1-->
                                    <div class="small-box text-bg-success">
                                    <div class="inner">
                                        <h3>{{ $summary['total_departments'] }}</h3>
                                        <p>Departments</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-diagram-3-fill small-box-icon" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zm-6 8A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm6 0A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm6 0a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5z"/>
                                    </svg>
                                    </div>
                                    <!--end::Small Box Widget 1-->
                                </div>
                                <div class="col-lg-3 col-6">
                                    <!--begin::Small Box Widget 1-->
                                    <div class="small-box text-bg-warning">
                                    <div class="inner">
                                        <h3 class="text-white">{{ $summary['total_active_contracts'] }}</h3>
                                        <p class="text-white">Active Contracts</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-file-text-fill small-box-icon" viewBox="0 0 16 16">
                                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1m-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1m0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1"/>
                                    </svg>
                                    </div>
                                    <!--end::Small Box Widget 1-->
                                </div>
                                <div class="col-lg-3 col-6">
                                    <!--begin::Small Box Widget 1-->
                                    <div class="small-box text-bg-danger">
                                    <div class="inner">
                                        <h3>{{ $summary['total_pending_payrolls'] }}</h3>
                                        <p>Payroll Pending</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cash small-box-icon" viewBox="0 0 16 16">
                                        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                                        <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                                    </svg>
                                    </div>
                                    <!--end::Small Box Widget 1-->
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <h4>Quick Links</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <a class="btn btn-primary d-block mb-2" href="{{ route('payrolls.index') }}">Generate Payroll</a>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <a class="btn btn-primary d-block mb-2" href="{{ route('employees.create') }}">Add Employee</a>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <a class="btn btn-primary d-block mb-2" href="{{ route('payments.create') }}">Add Payment</a>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <a class="btn btn-primary d-block mb-2" href="{{ route('contracts.create') }}">Create Contract</a>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-6 col-12">
                                    <h4 class="mb-4">Employees By Department</h4>
                                    <div id="pie-chart" class="mb-4">

                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <h4 class="mb-4">Employee Growth Over Time</h4>
                                    <div id="employee-growth-chart" class="mb-4">

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 col-12">
                                    <h4 class="mb-4">Payroll Summary By Month</h4>
                                    <div id="payroll-summary-chart" class="mb-4"></div>
                                </div>
                            </div>
                            @else
                            <h5 class="mb-0">Welcome, {{ auth()->user()->name }}!</h5>
                            <p class="mb-0 mt-3">You haven’t selected a company yet.</p>
                            <p class="mb-0">Choose one from the sidebar to see reports, employees, and payroll information.</p>
                            <p class="mb-0">Or create a new company if you don’t have one yet.</p>
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

@push('scripts')
<script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous">
</script>
<script>
    const totalEmployees = @json($employee_by_department['count']);
    const departments = @json($employee_by_department['departments']);
    const pie_chart_options = {
        series: totalEmployees,
        chart: {
            type: 'donut',
        },
        labels: departments,
        dataLabels: {
            enabled: false,
        },
    };

    const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
    pie_chart.render();


    // Employees Growth
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sept",
        "Oct",
        "Nov",
        "Dec"
    ];

    const newHires = @json(array_values($employee_growth['new_hires']));
    const activeEmployees = @json(array_values($employee_growth['active_employees']));

    const employeeGrowthOptions = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: false },
        },
        stroke: { curve: 'smooth', width: 3 },
        series: [
            { name: 'New Hires', data: newHires },
            { name: 'Total Active', data: activeEmployees }
        ],
        xaxis: {
            categories: months,
            title: { text: 'Month' }
        },
        yaxis: {
            title: { text: 'Employees' },
            min: 0
        },
        colors: ['#34d399', '#60a5fa'], // hijau & biru
        markers: { size: 4 },
        legend: { position: 'top' },
        tooltip: {
            shared: true,
            intersect: false
        }
    };

    const employeeGrowthChart = new ApexCharts(
        document.querySelector('#employee-growth-chart'),
        employeeGrowthOptions,
    );
    employeeGrowthChart.render();

    // Payroll Summary
    const payrollData = @json(array_values($payroll_summary));

     const options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false },
        },
        series: [{
            name: 'Total Payroll',
            data: payrollData
        }],
        xaxis: {
            categories: months,
            title: { text: 'Month' },
        },
        yaxis: {
            title: { text: 'Total Salary (Rp)' },
            labels: {
                formatter: (val) => 'Rp ' + val.toLocaleString()
            }
        },
        colors: ['#3b82f6'], // biru (Tailwind blue-500)
        dataLabels: { enabled: false },
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: false,
                columnWidth: '45%',
            }
        },
        tooltip: {
            y: {
                formatter: (val) => 'Rp ' + val.toLocaleString()
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
    };

    const chart = new ApexCharts(document.querySelector("#payroll-summary-chart"), options);
    chart.render();
</script>
@endpush
