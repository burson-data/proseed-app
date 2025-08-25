<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $transaction->transaction_id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo {
            width: 150px;
            margin-bottom: 10px;
        }
        .company-address {
            font-size: 10px;
            color: #555;
            line-height: 1.4;
        }
        .receipt-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .date-section {
            text-align: right;
            font-size: 11px;
        }
        .to-section {
            margin-top: 20px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .content-table th, .content-table td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }
        .content-table th {
            background-color: #f2f2f2;
            width: 150px;
        }
        .signature-section {
            margin-top: 80px;
            width: 300px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            height: 60px;
        }
        .signature-label {
            font-size: 10px;
        }
    </style>
</head>
<body>

    <table class="main-table">
        <tr>
            {{-- Kolom Kiri: Logo dan Alamat --}}
            <td style="width: 50%; vertical-align: top;">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
                @endif
                
                <p class="company-address">
                    RDTXPlace Lt.35 Jl. Prof. DR. Satrio<br>
                    No. Kav.3, RT008/RW4, Kuningan, Karet Kuningan,<br>
                    Kecamatan Setiabudi, Kota Jakarta Selatan,<br>
                    DKI Jakarta 12930 M +62 21 39720572
                </p>
            </td>

            {{-- Kolom Kanan: Tanggal --}}
            <td style="width: 50%; vertical-align: top;" class="date-section">
                <strong>Date of Issue:</strong><br>
                {{ \Carbon\Carbon::parse($transaction->borrow_date)->format('F d, Y') }}
            </td>
        </tr>
    </table>

    <div class="receipt-title">RECEIPT</div>

    <div class="to-section">
        <strong>To:</strong><br>
        {{ $transaction->partner?->partner_name ?? 'N/A' }} - {{ $transaction->partner?->phone_number ?? 'N/A' }}<br>
        {!! nl2br(e($transaction->partner?->address ?? 'N/A')) !!}
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th colspan="2">Product Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Product Name</th>
                <td>{{ $transaction->product?->product_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ $transaction->product?->key_attribute_label ?? 'Key Attribute' }}</th>
                <td>{{ $transaction->product?->key_attribute_value ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <strong>Accepted By:</strong>
        <div class="signature-line">
            {{-- Area untuk tanda tangan --}}
        </div>
        <table style="width: 100%; font-size: 11px;">
            <tr>
                <td class="signature-label">Name: {{ $transaction->partner?->pic_name ?? '...........................' }}</td>
                <td class="signature-label">Date: ...........................</td>
            </tr>
        </table>
    </div>

</body>
</html>
