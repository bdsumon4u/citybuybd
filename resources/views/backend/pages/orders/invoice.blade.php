<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        @media print {
            body {
                zoom: 75%;
            }
        }

        body {
            font-family: sans-serif;
            font-size: 14px;
            margin: 0;
        }

        .main-body {
            min-height: 380px;
            height: 380px;
        }

        .header {
            min-height: 160px;
            border: 1px solid #b3b0b0;
            margin-bottom: 15px;
        }

        .header img {
            max-width: 200px;
        }

        .left-header {
            min-height: 160px;
            width: 30%;
            float: left;
            border-right: 1px solid #b3b0b0;
        }

        .left-header-inner {
            padding: 15px;
            height: 130px;
        }

        .middle-header-inner {
            padding: 15px;
        }

        .right-header-inner {
            padding: 15px;
            height: 130px;
        }

        .middle-header {
            min-height: 160px;
            border-right: 1px solid #b3b0b0;
            width: 35%;
            float: left;
        }

        .right-header {
            min-height: 160px;
            width: 34%;
            float: left;
            text-align: left;
        }

        .right-header h2 {
            margin: 0;
            font-size: 40px;
        }

        .info {
            height: 130px;
        }

        .customer_info {
            font-size: 14px;
        }

        .customer_info p {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .owner_info {
            border: 1px solid #b3b0b0;
            width: 300px;
            height: 112px;
            float: right;
            font-size: 14px;
            padding: 5px 10px;
        }

        .owner_info p {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .owner_info img {
            width: 200px;
        }

        .left_div {
            float: left;
            width: 28%;
        }

        .right_div {
            float: left;
            width: 72%;
        }

        .left_div2 {
            float: left;
            width: 86px;
        }

        .right_div2 {
            float: left;
        }

        .product_table {
        }

        .product_table table {
            border: 1px solid #b3b0b0;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .product_table table thead {
        }

        .product_table table thead tr {
            border: 1px solid #b3b0b0;
            height: 35px;
        }

        .product_table table thead tr th {
            border: 1px solid #b3b0b0;
        }

        .product_table table tbody {
            vertical-align: top;
        }

        .product_table table tbody tr {
        }

        .product_table table tbody tr td {
            border: 1px solid #b3b0b0;
            padding-top: 10px;
            padding-bottom: 10px;
        }

    </style>
</head>
<body>
<div class="main-body">
    <div class="header">
        <div class="left-header">
            <div class="left-header-inner">.
                <img src="{{ asset('backend/img/'.$settings->logo)  }}" alt="">
                <p style="margin: 0;margin-bottom: 10px;margin-top: 10px">{{$settings->address}} <br>
                    <strong>Mobile: </strong>{{$settings->phone}}</p>
            </div>
        </div>

        <div class="middle-header">
            <div class="middle-header-inner">
                <h3 style="margin: 0;margin-bottom: 10px">Customer </h3>
                <div class="customer_info">
                    <div class="right_div">
                        <p>
                            <span>{{$orders->name}}</span>
                        </p>

                        <p>
                            <span>{{$orders->phone}}</span>
                        </p>

                        <p>
                            <span>{{$orders->address}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-header">
            <div class="right-header-inner">
                <h3 style="margin: 0;margin-bottom: 10px">Invoice #{{$orders->id}}</h3>
                <div class="customer_info">
                    <div class="left_div2">
                        <p>
                            <strong>Order Date</strong>
                        </p>

                        @if($orders->courier)
                            <p>
                                <strong>Courier</strong>
                            </p>
                        @endif
                        @if($orders->courier)
                            <p>
                                <strong>Courier Inv.</strong>
                            </p>
                        @endif
                    </div>

                    <div class="right_div2">
                        <p>
                            <strong>:</strong> &nbsp;<span>{{date('d M, Y',strtotime($orders->created_at))}}</span>
                        </p>

                        @if($orders->couriers)
                            <p>
                                <strong>:</strong> &nbsp;<span>{{$orders->couriers->name??"N/A"}}</span>
                            </p>
                        @endif
                        @if($orders->couriers)
                            <p>
                                <strong>:</strong> &nbsp;<span>{{$orders->couriers->id??"N/A"}}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="product_table">
        <table class="table">
            <thead>
            <tr>
                <th>SL #</th>
                <th style="text-align: left;padding-left: 10px">Product(s)</th>
                <th>Qty</th>
                <th style="text-align: right;padding-right: 10px">Price</th>
            </tr>
            </thead>
            <tbody>

            @foreach($carts as $item)
                <tr style="vertical-align: top">
                    <td style="text-align: center;width: 5%">{{$loop->iteration}}</td>
                    <td style="padding-left: 10px;width: 60%">
                        <span>{{$item->product->name??"N/A"}}</span><br>
                        @if($item->color)

                                <span style="font-size: 10px" class="text-primary">{{$item->color}}</span><br>

                        @endif
                    </td>
                    <td style="text-align: center;width: 10%">{{$item->quantity}}</td>
                    <td style="text-align: right;width: 25%;padding-right: 10px">{{$settings->currency}} {{($item->product->offer_price ?? $item->product->regular_price) * $item->quantity}}</td>

                </tr>
            @endforeach

            <tr style="border-top: 1px solid black;">
                <td colspan="3"
                    style="padding-left: 10px;text-align: right;padding-right: 10px;padding-bottom: 0;">
                    <strong>Sub
                        Total</strong></td>
                <td style="text-align: right;padding-right: 10px;padding-bottom: 0;">{{$settings->currency}} {{$orders->sub_total}}</td>
            </tr>

            <tr style="border-top: 1px solid black;">
                <td colspan="3"
                    style="padding-left: 10px;text-align: right;padding-right: 10px;padding-bottom: 0;">
                    <strong>Delivery Cost
                        (+)</strong></td>
                <td style="text-align: right;padding-right: 10px;padding-bottom: 0;">{{$settings->currency_sign}} {{$orders->shipping_cost}}</td>
            </tr>

            {{--<tr style="border-top: 1px solid black;">
                <td colspan="3"
                    style="padding-left: 10px;text-align: right;padding-right: 10px;padding-bottom: 0;">
                    <strong>Discount
                        (-)</strong></td>
                <td style="text-align: right;padding-right: 10px;padding-bottom: 0;">{{$settings->currency_sign}} {{$orders->discount}}</td>
            </tr>--}}

            <tr style="border-top: 1px solid black;">
                <td colspan="3"
                    style="padding-left: 10px;text-align: right;padding-right: 10px;padding-bottom: 0;">
                    <strong>Total</strong>
                </td>
                <td style="text-align: right;padding-right: 10px;padding-bottom: 0;">{{$settings->currency_sign}} {{$orders->total}}</td>
            </tr>
            </tbody>
        </table>

        @if($orders->order_note)
            <p>Note: {{$orders->order_note}}</p>
        @endif
    </div>
</div>
<script>
    window.onload = function () {
        window.print();
        window.close();
    }
</script>
</body>
</html>
