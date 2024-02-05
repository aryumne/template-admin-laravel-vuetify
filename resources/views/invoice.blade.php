<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            font-size: 14px;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .content-center {
            text-align: center;
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-bold {
            font-weight: 700px;
        }

        table {
            width: 100%;
        }

        tr,
        th,
        td {
            padding-top: 0;
            padding-bottom: 8px
        }
    </style>
</head>

<body>
    <div class="content-center">
        <table>
            <tbody>
                <tr>
                    <td class="text-start">{{ $data['created_at']->format('j/n/y, g:i A') }}</td>
                </tr>
                <tr>
                    <td class="text-center">
                        <h2 style="margin:0; font-size: 24px;">APOTEK REZKY MEDIKA</h2>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        Jl. Gunung Salju, NO.14
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 50%; margin-top: 12px;">
            <tbody>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{ $data['created_at']->format('Y-m-d') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>No. Resep</td>
                    <td>:</td>
                    <td>{{ $data['prescription_number'] }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>No. Transaksi</td>
                    <td>:</td>
                    <td>{{ $data['transaction_number'] }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 12px; padding-left: 1rem; padding-right:1rem;">
            <thead>
                <tr>
                    <td colspan="4" style="overflow: hidden;">
                        =================================================================================================
                    </td>
                </tr>
                <tr>
                    <th class="text-start">OBAT</th>
                    <th class="text-end">HARGA</th>
                    <th class="text-end">JML</th>
                    <th class="text-end">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['sales'] as $dt)
                    <tr>
                        <td class="text-start">{{ $dt['name'] }}</td>
                        <td class="text-end">Rp. {{ str_replace(',00', '', number_format($dt['price'], 2, ',', '.')) }}
                        </td>
                        <td class="text-end">{{ $dt['quantity'] . ' ' . $dt['type'] }}</td>
                        <td class="text-end">Rp.
                            {{ str_replace(',00', '', number_format($dt['total_price'], 2, ',', '.')) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end">Rp.
                        {{ str_replace(',00', '', number_format($data['amount'], 2, ',', '.')) }}
                    </th>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Tunai</td>
                    <td class="text-end">Rp.
                        {{ str_replace(',00', '', number_format($data['cash_amount'], 2, ',', '.')) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="overflow: hidden;">
                        =================================================================================================
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Kembali</td>
                    <td class="text-end">Rp.
                        {{ str_replace(',00', '', number_format($data['return_amount'], 2, ',', '.')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
