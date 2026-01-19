<table class="table mg-b-0 table-bordered table-striped">
    <thead>
        <tr>
            <th scope="col">
                <input type="checkbox" class="chkCheckAll">
            </th>
            <th scope="col">#Sl</th>
            <th scope="col">Invoice ID</th>
            <th scope="col">Customer Info </th>
            <th scope="col">Products </th>
            <th scope="col">Total </th>
            <th scope="col">Type </th>
            <th scope="col">Courier </th>
            <th scope="col">Courier Status</th>
            <th scope="col">Date </th>
            <th scope="col">Status </th>
            <th scope="col">Note </th>
            <th scope="col">Asigned </th>
            <th scope="col">Action </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $order)
            <?php
            $check_duplicate = App\Models\Order::where('phone', $order->phone)->take(2)->count();
            ?>
            <tr>
                <th scope="row">
                    <input type="checkbox" class="sub_chk" data-id="{{ $order->id }}">
                </th>
                <td>{{ $key + 1 }}</td>
                <td>{{ $order->id }} <br>
                    @if ($order->coming == '1')
                        <span class="text-white tx-10 font-weight-bold bg-success pd-4">Landing</span>
                    @endif
                </td>
                <td class='{{ $check_duplicate > 1 ? 'bg-danger-light' : '' }}'>
                    <p class="mb-0">{{ $order->name ?? 'N/A' }}</p>
                    <p class="mb-0">
                        <a href="tel:{{ $order->phone ?? 'N/A' }}"><strong>{{ $order->phone ?? 'N/A' }}</strong></a>
                    </p>
                    <p class="mb-0">{{ $order->address ?? 'N/A' }}</p>
                </td>
                <td>

                    @foreach ($order->many_cart as $cart)
                        <a href="{{ route('details', $cart->product->slug) }}" target="_blank">
                            <p> <span
                                    class="text-white tx-10 font-weight-bold bg-crystal-clear pd-4">{{ $cart->quantity }}</span>
                                {{ $cart->product->name }}</p>
                        </a>
                        @foreach (@$cart->AtrItem as $atr_item)
                            <strong> {{ @$atr_item->productAttr->name }}: </strong>
                            {{ @$atr_item->name }},
                        @endforeach
                    @endforeach

                </td>
                <td>৳ {{ $order->total }}</td>
                <td>
                    @php
                        $orderType = $order->order_type ?? \App\Models\Order::TYPE_ONLINE;

                        // Define vibrant hex color palette for order types
                        $hexColors = [
                            '#007bff', // Blue
                            '#28a745', // Green
                            '#dc3545', // Red
                            '#ffc107', // Yellow
                            '#17a2b8', // Cyan
                            '#6f42c1', // Purple
                            '#fd7e14', // Orange
                            '#e83e8c', // Pink
                            '#20c997', // Teal
                            '#6c757d', // Gray
                            '#343a40', // Dark
                            '#f012be', // Magenta
                            '#3d9970', // Olive
                            '#ff851b', // Burnt Orange
                            '#0074d9', // Navy
                            '#2ecc40', // Lime
                            '#b10dc9', // Violet
                            '#ff4136', // Bright Red
                            '#ffdc00', // Gold
                            '#39cccc', // Aqua
                        ];

                        // Map specific order types to preferred colors
                        $colorMap = [
                            \App\Models\Order::TYPE_ONLINE => '#007bff', // Blue
                            \App\Models\Order::TYPE_INCOMPLETE => '#ffc107', // Yellow
                            \App\Models\Order::TYPE_MANUAL => '#17a2b8', // Cyan
                        ];

                        // Use mapped color if exists, otherwise generate from hash
                        if (isset($colorMap[$orderType])) {
                            $bgColor = $colorMap[$orderType];
                        } else {
                            // Generate consistent color based on order type hash
                            $hash = crc32($orderType);
                            $bgColor = $hexColors[abs($hash) % count($hexColors)];
                        }
                    @endphp
                    <span class="badge" style="background-color: {{ $bgColor }}; color: #fff;">
                        {{ ucfirst($orderType) }}
                    </span>
                </td>
                <td> {!! @$order->my_courier !!} </td>
                <td> {{ $order->courier_status }} </td>
                <td>
                    {{ date('d M, Y', strtotime($order->created_at)) }}<br>
                    {{ date('h:i:s A', strtotime($order->created_at)) }}
                </td>
                <td>
                    <div class="btn-group">
                        @if ($order->status == 1)
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Processing</button>
                        @elseif($order->status == 2)
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Courier Entry</button>
                        @elseif($order->status == 17)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Printed Invoice</button>
                        @elseif($order->status == 16)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Total Courier</button>
                        @elseif($order->status == 3)
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"> On Hold</button>
                        @elseif($order->status == 4)
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Cancel</button>
                        @elseif($order->status == 5)
                            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"> Delivery</button>
                        @elseif($order->status == 6)
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">pending payment</button>
                        @elseif($order->status == 7)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">on delivery</button>
                        @elseif($order->status == 7)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">on delivery</button>
                        @elseif($order->status == 7)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">on delivery</button>
                        @elseif($order->status == 8)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">No Response 1</button>
                        @elseif($order->status == 9)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">No Response 2</button>
                        @elseif($order->status == 11)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Courier Hold</button>
                        @elseif($order->status == 12)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Return</button>
                        @elseif($order->status == 18)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Pending Return</button>
                        @elseif($order->status == 13)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Partial Delivery</button>
                        @elseif($order->status == 14)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Paid Return</button>
                        @elseif($order->status == 15)
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Stock Out</button>
                        @endif
                        <div class="dropdown-menu">
                            @if ($order->status != 1)
                                <button class="dropdown-item" type="button"
                                    onclick="statusChange(1,{{ $order->id }})">Processing</button>
                            @endif
                            @if ($order->status != 2)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(2,{{ $order->id }})" href="#">Courier
                                    Entry</button>
                            @endif
                            @if ($order->status != 17)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(17,{{ $order->id }})" href="#">Printed
                                    Invoice</button>
                            @endif
                            @if ($order->status != 16)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(16,{{ $order->id }})" href="#">Total
                                    Courier</button>
                            @endif
                            @if ($order->status != 6)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(6,{{ $order->id }})" href="#">Pending
                                    Payment</button>
                            @endif
                            @if ($order->status != 7)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(7,{{ $order->id }})" href="#">On Delivery</button>
                            @endif
                            @if ($order->status != 8)
                                <button type="button" class="dropdown-item" href="#"
                                    onclick="statusChange(8,{{ $order->id }})">No Response 1</button>
                            @endif
                            @if ($order->status != 9)
                                <button type="button" class="dropdown-item" href="#"
                                    onclick="statusChange(9,{{ $order->id }})">No Response 2</button>
                            @endif

                            @if ($order->status != 3)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(3,{{ $order->id }})" href="#">On Hold</button>
                            @endif
                            @if ($order->status != 11)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(11,{{ $order->id }})" href="#">Courier
                                    Hold</button>
                            @endif
                            @if ($order->status != 4)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(4,{{ $order->id }})" href="#">Cancel</button>
                            @endif
                            @if ($order->status != 15)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(15,{{ $order->id }})" href="#">Stock Out</button>
                            @endif
                            @if ($order->status != 14)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(14,{{ $order->id }})" href="#">Paid
                                    Return</button>
                            @endif
                            @if ($order->status != 18)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(18,{{ $order->id }})" href="#">Pending
                                    Return</button>
                            @endif
                            @if ($order->status != 12)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(12,{{ $order->id }})" href="#">Return</button>
                            @endif
                            @if ($order->status != 13)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(13,{{ $order->id }})" href="#">Partial
                                    Delivery</button>
                            @endif
                            @if ($order->status != 5)
                                <button type="button" class="dropdown-item"
                                    onclick="statusChange(5,{{ $order->id }})" href="#">Delivery</button>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="mt-1 list-group">
                        <div class="list-group-item align-items-center justify-content-start">
                            <div class="">
                                <p class="mg-b-0 tx-inverse tx-medium">{{ $order->order_note ?? 'N/A' }}</p>
                            </div>
                            <div class="">
                                <a href="" data-toggle="modal" data-target="#noted{{ $order->id }}"
                                    class="btn btn-outline-primary btn-icon">
                                    <div class="tx-10"><i class="fa-solid fa-pen-to-square tx-14"></i></div>
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- assign modaal start -->
                    <div class="modal fade" id="noted{{ $order->id }}" aria-labelledby="exampleModalLabel2"
                        aria-hidden="true" style="overflow: hidden">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel2">Note </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <!-- <form action="{{ route('order.noted_edit', $order->id) }}" method="POST" class="assign_f_button">
                                            @csrf -->
                                            <div class="col-md-12">
                                                <label>Pre-saved Order Note</label>
                                                <div class="mb-2"
                                                    style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                                    @foreach (App\Models\OrderNote::active()->ordered()->get() as $note)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="pre_saved_note_{{ $order->id }}"
                                                                id="pre_saved_note_{{ $order->id }}_{{ $note->id }}"
                                                                value="{{ $note->note }}"
                                                                @if ($order->order_note == $note->note) checked @endif
                                                                onchange="applyPreSavedNoteToModal({{ $order->id }}, {{ json_encode($note->note) }})">
                                                            <label class="form-check-label"
                                                                for="pre_saved_note_{{ $order->id }}_{{ $note->id }}">
                                                                {{ $note->note }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <textarea name="order_noted" id="order_noted_{{ $order->id }}" class="form-control" rows="3">{{ $order->order_note ?? 'N/A' }} </textarea>
                                                <input onclick="notedEdit({{ $order->id }})" type="button"
                                                    value="Save" name="delete"
                                                    class="mt-2 btn btn-success btn-block noted_e_button">
                                            </div>

                                            <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- assign modaal end -->

                </td>
                <td>
                    <div class="mt-1 list-group">
                        <div class="list-group-item align-items-center justify-content-start">
                            <div class="">
                                <p class="mg-b-0 tx-inverse tx-medium">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>

                        </div>
                    </div>

                </td>
                <td class="action-button">
                    <div class="list-group">
                        <div class="list-group-item d-grid">
                            <a href="{{ route('manager.order.edit', $order->id) }}" class="btn btn-icon">
                                <i class="fa-solid fa-pen-to-square tx-17"></i>
                            </a>

                        </div>
                    </div>

                </td>
            </tr>
        @endforeach
    </tbody>


    @if ($orders->count() == 0)
        <div class="alert alert-danger">sorry! No Orders Found.</div>
    @endif
</table>

<div class="row">
    <div class="col-lg-12">
        <div style="overflow-x: auto;">
        </div>
        <div class="ht-80 bd d-flex align-items-center justify-content-center">
            <ul class="pagination pagination-basic pagination-danger mg-b-0">
                <li>{{ $orders->withQueryString()->links() }}</li>
            </ul>
        </div>
    </div>
</div>


<script>
    $(function() {
        $(".chkCheckAll").click(function() {
            $(".sub_chk").prop('checked', $(this).prop('checked'));
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        })
    })
</script>
