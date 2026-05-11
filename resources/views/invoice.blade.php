<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <style>

        body{
            font-family: Arial, sans-serif;
        }

        table{
            width:100%;
            border-collapse: collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid black;
        }

        th, td{
            padding:8px;
            text-align:left;
        }

        h2{
            text-align:center;
        }

    </style>
</head>

<body>

    <h2>INVOICE BENGKEL</h2>

    <p>
        <strong>No Invoice:</strong>
        #{{ $order->id }}
    </p>

    <p>
        <strong>Tanggal:</strong>
        {{ $order->tanggal }}
    </p>

    <p>
        <strong>Pelanggan:</strong>
        {{ $order->customer->nama }}
    </p>

    <table>

        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

            @foreach($order->details as $detail)

            <tr>

                <td>

                    @if($detail->service)
                        {{ $detail->service->nama_service }}
                    @endif

                    @if($detail->product)
                        {{ $detail->product->nama_produk }}
                    @endif

                </td>

                <td>
                    {{ $detail->qty }}
                </td>

                <td>
                    Rp {{ number_format($detail->subtotal,0,',','.') }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <h3>
        Total :
        Rp {{ number_format($order->total,0,',','.') }}
    </h3>

</body>
</html>