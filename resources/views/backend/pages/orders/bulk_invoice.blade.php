<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
        .pagebreak {
                clear: both;
                page-break-after: always;
            }

        .product_table {
            overflow: hidden;
            padding: 5px;
            /*min-height: 134px;*/
            /*max-height: 165px;*/
        }

        .product_table table {
            border: 1px solid #ababab;
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .product_table table thead {
        }

        .product_table table thead tr {
            border-bottom: 1px solid #ababab;
            /*height: 35px;*/
        }

        .product_table table thead tr th {
            border: 1px solid #ababab;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .product_table table tbody {
            vertical-align: top;
        }

        .product_table table tbody tr {
        }

        .product_table table tbody tr td {
            border: 1px solid #ababab;
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
@php
    $k = 0;
    $l = 6;
@endphp
<div class="row">
    @foreach($orders as $key => $item)
        <div class="block"
             style="min-height: 295px; overflow:hidden; margin-left:10px;margin-right:10px; margin-bottom: 10px;margin-top: 10px;border: 1px solid #000000;border-radius: 3px;width: 46%;float:left;">
            <div class="row d-flex" style="margin-left: 5px;min-height: 70px;max-height:80px;overflow: hidden;display: flex;
    justify-content: space-between;padding: 5px;">
                <div class="col-md-4" style="width: 35%;text-align: left;">
                    <p style="margin: 0; margin-bottom: 5px;font-size: 11px">{{$item->name}}</p>
                    <p style="margin: 0; margin-bottom: 5px;font-size: 11px">{{$item->phone}}</p>
                    <p style="margin: 0; margin-bottom: 5px;font-size: 11px">{{$item->address}}</p>
                </div>
                <div class="col-md-3" style="width: 30%;text-align: left;">
                    <div style="text-align: center;">
                        <img style="height: 20px; margin-top: 5px;" src="{{ asset('backend/img/'.$settings->logo)  }}" alt="">
                    </div>
                </div>
                <div class="col-md-5" style="width: 35%;text-align: right;">
                    <span style="font-size: 12px"><strong></strong>Invoice: <span style="font-weight: 900;font-size: 16px;">{{$item->id}} </span>  </span> <br>
                    <span style="font-size: 12px"><strong></strong> {{date('d M, Y',strtotime($item->created_at))}}</span>
                </div>

            </div>
            <div class="product_table">
                <table style="border: 1px solid #ababab;width: 100%;">
                    <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Img</th>
                        <th>Item(s)</th>
                        <th>Qty</th>
                        <th style="border-right: none">Price</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach(App\Models\Cart::where('order_id', $item->id)->get()  as $cart)
                        <tr style="vertical-align: top">
                    <td style="text-align: center;width: 5%">{{$loop->iteration}}</td>
                    <td style="text-align: center;width: 5%">
                       
                        <img src=" {{ asset('backend/img/products/'.$cart->product->image) }}" width="30">
                        
                        </td>
                    <td style="padding-left: 10px;width: 60%">
                        <span style="font-size: 10px">{{ $cart->product->name??"N/A"}}</span>
                        <br>
                       
                        <span style="font-size: 9px">@foreach (@$cart->AtrItem as $atr_item)
                            
                            <strong> {{ @$atr_item->productAttr->name }}: </strong>
                            {{ @$atr_item->name}},
                        @endforeach
                        </span>
                       
                    </td>
                    <td style="text-align: center;width: 10%">{{$cart->quantity}}</td>
                    <td style="text-align: right;width: 25%;padding-right: 10px">
                        @if($cart->product)
                            ৳ {{$cart->price }}
                        @endif
                    </td>

                </tr>
                    @endforeach
                    </tbody>


                </table>

                <div class="row" style="display: flex;justify-content: space-between;">
                    <div class="col-md-4">
                        <!--<div style="margin: 20px 0px 5px 35px">-->
                        <!--    <img style="height: 70px; " src="{{ asset('backend/img/qr_code.png')  }}" alt="">-->
                        <!--</div>-->
                    </div>
                    <div class="col-md-8" style="margin-right: 10px;margin-top: 5px;text-align: end;">
                        <p style="padding-bottom: 5px;margin-block-start: 0em;margin-block-end: 0em;"><strong>Sub Total:</strong> {{$settings->currency}} {{$item->sub_total}} </p>
                        <p style="padding-bottom: 5px;margin-block-start: 0em;margin-block-end: 0em;"><strong>Discount (-):</strong> {{$settings->currency}} {{$item->discount}}</p>
                        <p style="padding-bottom: 5px;margin-block-start: 0em;margin-block-end: 0em;"><strong>Delivery Cost (+):</strong> {{$settings->currency}} {{$item->shipping_cost}}</p>
                        <p style="padding-bottom: 5px;margin-block-start: 0em;margin-block-end: 0em;"><strong>Total:</strong> {{$settings->currency}} {{$item->total}}</p>
                    </div>
                </div>



            </div>
        </div>

        <?php  $k++; ?>
        @if($k == $l)
       
            <div class="pagebreak"></div>
         
            <?php $l += 6; ?>
        @endif
    @endforeach
</div>
<script>
    window.onload = function () {
        window.print();

    }
</script>
</body>
</html>
