<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Label Print Invoice</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @media print {
            body {
                zoom: 75%;
            }

            .pagebreak {
                clear: both;
                page-break-after: always;
            }

            body {
                font-family: sans-serif;
                font-size: 14px;
                margin: 0;
            }

        }

        body {
            font-family: sans-serif;
            font-size: 14px;
            margin: 0;
        }

        .product_table {
            overflow: hidden;
            /*min-height: 134px;*/
            /*max-height: 165px;*/
        }

        .product_table table {
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .product_table table thead {}

        .product_table table thead tr {
            border-bottom: 1px solid black;
            /*height: 35px;*/
        }

        .product_table table thead tr th {
            border-right: 1px solid black;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .product_table table tbody {
            vertical-align: top;
        }

        .product_table table tbody tr {}

        .product_table table tbody tr td {
            border-right: 1px solid black;
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    @php
    $k = 0;
    $l = 1;
    @endphp
    <div class="row">

        <?php
        $p = 1;
        $all = count($orders);
        ?>
        @foreach($orders as $key => $item)
        <p style="margin-left: 20px;margin-bottom: 0;">{{$p++}} / {{$all}}</p>
        <div class="block"
            style="overflow:hidden;margin-left: 20px; margin-bottom: 20px;margin-top: 20px;border: 1px solid #000;border-radius: 5px;width: 94.5%">
            <div style="min-height: 65px;display: flex;justify-content: space-between">
                <div style=" margin-left: 5px;margin-top: 10px;">
                    <p style="margin: 0; margin-bottom: 10px;"><strong>Name: </strong>{{$item->name}}</p>
                    <p style="margin: 0; margin-bottom: 0;"><strong>Phone: </strong>{{$item->phone}}</p>
                    <p style="margin: 0; margin-top: 5px;"><strong>Address: </strong>{{$item->address}}</p>
                </div>
                <div>
                    <img style="height: 40px; margin-left: 5px; margin-top: 5px;" src="{{asset('backend/img/'.$settings->logo)}}" alt="">
                </div>
                <div style="margin-right: 5px;margin-top: 5px;display: flex;align-items: flex-start;gap: 10px;">
                    <div>
                        <svg class="barcode-{{$item->id}}" style="height: 40px;"></svg>
                    </div>
                    <div>
                        <span><strong>Invoice #</strong> <span style="font-size: 20px;font-weight: bold">{{$item->id}}</span></span> <br>
                        <span><strong>Date :</strong> {{date('d M, Y',strtotime($item->created_at))}}</span>
                    </div>
                </div>
            </div>
            <div class="product_table">
                <table style="border: 1px solid #000;border-left:none;border-right:none;border-bottom:none;width: 100%;">
                    <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Item(s)</th>
                            <th>Qty</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Model</th>
                            <th style="border-right: none;text-align: right;padding-right: 10px">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach(App\Models\Cart::where('order_id', $item->id)->get() as $cart)

                        <tr style="vertical-align: top">
                            <td style="text-align: center;width: 5%">{{$i++}}</td>
                            <td style="padding-left: 10px;width: 60%">
                                <div style="display:flex;align-items: center">
                                    <div>
                                        <img style="width: 25px;margin-right: 2px;" src="{{asset('backend/img/products/'.$cart->product->image)}}" alt="">
                                    </div>
                                    <div>
                                        <span>{{\Illuminate\Support\Str::limit($cart->product->name ?? "N/A",30)}}</span><br>

                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;width: 10%">
                                <span>{{$cart->quantity}}</span>
                            </td>
                            <td style="text-align: center">{{ $cart->size ?: 'N/A' }}</td>
                            <td style="text-align: center">{{ $cart->color ?: 'N/A' }}</td>
                            <td style="text-align: center">{{ $cart->model ?: 'N/A' }}</td>
                            <td style="text-align: right;width: 25%;padding-right: 10px;border-right: none">
                                <span>@if($cart->product)
                                    ৳ {{$cart->price }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tr style="border-top: 1px solid black;">
                        <td colspan="6" style="text-align: right; padding-right: 10px; border-right: none;">
                            <strong>Sub Total</strong>
                        </td>
                        <td style="text-align: right; padding-right: 10px; border-right: none;">
                            ৳ {{$item->sub_total}}
                        </td>
                    </tr>

                    <tr style="border-top: 1px solid black;">
                        <td colspan="6" style="text-align: right; padding-right: 10px; border-right: none;">
                            <strong>Delivery Charge</strong>
                        </td>
                        <td style="text-align: right; padding-right: 10px; border-right: none;">
                            ৳ {{$item->shipping_cost}}
                        </td>
                    </tr>

                    <tr style="border-top: 1px solid black;">
                        <td colspan="6" style="text-align: right; padding-right: 10px; border-right: none;">
                            <strong>Discount</strong>
                        </td>
                        <td style="text-align: right; padding-right: 10px; border-right: none;">
                            ৳ {{$item->discount}}
                        </td>
                    </tr>

                    <tr style="border-top: 1px solid black;">
                        <td colspan="6" style="text-align: right; padding-right: 10px; border-right: none;">
                            <strong>Total</strong>
                        </td>
                        <td style="text-align: right; padding-right: 10px; border-right: none;">
                            ৳ {{$item->total}}
                        </td>
                    </tr>

                </table>
            </div>
        </div>

        <?php $k++; ?>
        @if($k == $l)
        <div class="pagebreak"></div>
        <?php $l = $l + 1; ?>
        @endif
        @endforeach
    </div>
    <script>
        window.onload = function() {
            @foreach($orders as $item)
            JsBarcode(".barcode-{{$item->id}}", "{{$item->id}}", {
                format: "CODE128",
                width: 2,
                height: 40,
                displayValue: false,
                fontSize: 14,
                margin: 5
            });
            @endforeach
            window.print();
        }
    </script>
</body>

</html>
