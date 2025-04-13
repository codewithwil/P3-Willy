@extends('admin.template.template')
@section('title', 'Invoice Satuan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
<style>
    .invoice-image {
        max-width: 100px;
        max-height: 100px;
        margin-bottom: 10px;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .invoice-image {
            max-width: 80px;
            max-height: 80px;
        }

        .invoice-title {
            text-align: right;
            flex-grow: 1;
        }

        .invoice-title h1 {
            font-size: 32px;
            margin: 0;
        }

        .invoice-title h4 {
            font-size: 16px;
            margin: 0;
        }

        .company-info {
            display: flex;
            align-items: center;
        }

        .company-details {
            margin-left: 10px;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .no-print {
            display: none;
        }
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .company-info {
        display: flex;
        align-items: center;
    }

    .company-details {
        margin-left: 10px;
        line-height: 0.5;
    }

    .invoice-title {
        text-align: right;
        flex-grow: 1;
    }

    .invoice-title h1 {
        font-size: 48px;
        font-weight: bold;
        margin: 0;
    }

    .invoice-title h4 {
        font-size: 18px;
        margin: 0;
    }

</style>
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Invoice Satuan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Satuan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <!-- Invoice Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="printableArea">
                            <div class="invoice-header">
                                <div class="company-info">
                                    <img src="{{ asset($company->image) }}" style="height: 100px; width:100px" alt="Logo" class="invoice-image shadow img-fluid rounded-circle">
                                    <div class="company-details">
                                        <p class="company-name" style="font-weight: bold;">{{ $company->name }}</p>
                                        <p>Email: {{ $company->email }}</p>
                                        <p>Phone: {{ $company->phone }}</p>
                                        <p>Address: {{ $company->address }}</p>
                                    </div>
                                </div>
                            
                                <div class="invoice-title">
                                    <h1 class="text-uppercase">Invoice </h1>
                                    <p>Date: {{ now()->format('Y-m-d') }}</p>
                                </div>
                            </div>

                            <table id="dataTableUnit" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Satuan</th>
                                        <th>Singkatan Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unit as $un)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $un->name }}</td>
                                        <td>{{ $un->abbreviation }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary no-print" onclick="printInvoice()">Print Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#dataTableUnit');
        
        function printInvoice() {
            var content = document.getElementById('printableArea').innerHTML;
            var newWindow = window.open('', '', 'width=800, height=600');

            newWindow.document.write('<html><head><title>Invoice</title>');
            newWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">');
            newWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 10px; }</style>');
            newWindow.document.write('<style>.invoice-header { display: flex; justify-content: space-between; align-items: center; }</style>');
            newWindow.document.write('<style>.company-info { display: flex; align-items: center; }</style>');
            newWindow.document.write('<style>.company-details { margin-left: 10px; line-height: 1.2; }</style>');
            newWindow.document.write('<style>.invoice-title { text-align: right; flex-grow: 1; }</style>');
            newWindow.document.write('<style>.invoice-title h1 { font-size: 32px; margin: 0; }</style>');
            newWindow.document.write('<style>.invoice-title h4 { font-size: 16px; margin: 0; }</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(content);
            newWindow.document.write('</body></html>');
            newWindow.document.close();

            newWindow.onload = function () {
                newWindow.print();
                newWindow.close();
            };
        }
    </script>
@endpush
@endsection
