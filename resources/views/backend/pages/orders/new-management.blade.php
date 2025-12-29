@extends('backend.layout.template')
@section('body-content')

@include('backend.includes.statistics')
@include('backend.includes.ajax-filter')

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="px-3 py-2 row align-items-center">
                <div class="text-center col-md-6 text-md-left">
                    <span class="mt-1 tx-20 d-block d-md-inline">All Processing Orders</span>
                </div>
                <div class="mt-2 text-center col-md-6 text-md-right mt-md-0">
                    <div class="custom-control custom-switch d-inline-block">
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            id="toggle_total_with_delivery"
                            @if(!empty($withDeliveryCharge)) checked @endif
                        >
                        <label class="custom-control-label" for="toggle_total_with_delivery">
                            Total amount with delivery charge
                        </label>
                    </div>
                    <input
                        type="hidden"
                        id="with_delivery_charge"
                        value="{{ !empty($withDeliveryCharge) ? 1 : 0 }}"
                    >
                </div>
            </div>
            <div class="assign">

            <div class="loader"></div>

            </div>


        </div>
    </div>

<div class="modal qc_logModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

function Processing(status){
  $("#status_ajax").val(status);
  statistics();
  getData(1, 0);
}

function filterData(){

    console.log("dsadsa");
  statistics();
getData(1, 0);

}

function typeFun(){
  getData(1, 0);
}

function direction(){
getData(1, 0);
}

function exchange(){
getData(1, 0);
}

$(document).ready(function() {

// Ensure hidden value matches initial checkbox (from server preference)
$('#with_delivery_charge').val(
  $('#toggle_total_with_delivery').is(':checked') ? 1 : 0
);

getData(1, 0);
statistics();

$('#toggle_total_with_delivery').on('change', function() {
  $('#with_delivery_charge').val(this.checked ? 1 : 0);
  statistics();
  getData(1, 0);
});
});
$(document).on('click', '.pagination a',function(event){
$('li').removeClass('active');
$(this).parent('li').addClass('active');
event.preventDefault();
var myurl = $(this).attr('href');
var page=$(this).attr('href').split('page=')[1];
// Get data
getData(page, 0);
});
$('.enquiry-filter').on('click', function(){
getData(1, 1);
});
function getData(page, event)
{
 // statistics();
     $(".assign").empty().append('<div class="loader"></div>');
var params = {
status : $("#status_ajax").val(),
search_input : $("#search_input").val(),
fromDate : $("#fromDate").val(),
toDate : $("#toDate").val(),
courier : $("#courier").val(),
fixeddate : $("#fixeddate").val(),
order_type : $("#order_type").val(),

order_assign : $("#order_assign").val(),
product_id:$("#product_id").val(),

paginate : $("#paginate").val(),
with_delivery_charge: $("#with_delivery_charge").val(),
};
var paramStrings = [];
for (var key in params) {
paramStrings.push(key + '=' + encodeURIComponent(params[key]));
}

$('.btn-submit').prop('disabled', true);

$.ajax({
url: "{{ url('admin/order-management/new-manage-action?page=') }}"+page +"&"+paramStrings.join('&'),
type: "get",
datatype: "html",
})
.done(function(data){

$(".assign").empty().append(data);
$('.btn-submit').prop('disabled', false);
})
.fail(function(jqXHR, ajaxOptions, thrownError){
getData(page, 0);
$('.btn-submit').prop('disabled', false);
});
}



function statistics(){
    var params = {
        fixeddate : $("#fixeddate").val(),

      fromDate : $("#fromDate").val(),
      toDate : $("#toDate").val(),
      courier : $("#courier").val(),
      order_assign : $("#order_assign").val(),
      product_id:$("#product_id").val(),
      order_type : $("#order_type").val(),
      with_delivery_charge: $("#with_delivery_charge").val(),
      };
      var paramStrings = [];
        for (var key in params) {
        paramStrings.push(key + '=' + encodeURIComponent(params[key]));
      }

    //  var url: "{{ url('http://localhost/ecommerce/total-order-list?') }}"+paramStrings.join('&');

$.ajax({
url: "{{ url('total-order-list?') }}"+paramStrings.join('&'),
type: "get",
datatype: "html",
})
.done(function(data){

        $('#processing').text(data.processing);
        $('#pending').text(data.pending_Delivery);
        $('#printed_invoice').text(data.printed_invoice);
        $('#ondelivery').text(data.on_Delivery);
        $('#pending_p').text(data.pending_Payment);
        $('#hold').text(data.on_Hold);
        $('#courier_hold').text(data.courier_hold);
        $('#noresponse1').text(data.no_response1);
        $('#noresponse2').text(data.no_response2);
        $('#cancel').text(data.cancel);
        $('#return').text(data.return);
        $('#pending_return').text(data.pending_return);
        $('#completed').text(data.completed);
        $('#partial_delivery').text(data.partial_delivery);
        $('#paid_return').text(data.paid_return);
        $('#stock_out').text(data.stock_out);
        $('#total_delivery').text(data.total_delivery);
        $('#total_count').text(data.total);

        $('.total_count_percent').text(((data.total / data.total) * 100).toFixed(3) + " %");
        $('.processing_percent').text(((data.processing / data.total) * 100).toFixed(3) + " %");
        $('.pending_percent').text(((data.pending_Delivery / data.total) * 100).toFixed(3) + " %");
        $('.printed_invoice_percent').text(((data.printed_invoice / data.total) * 100).toFixed(3) + " %");
        $('.ondelivery_percent').text(((data.on_Delivery / data.total) * 100).toFixed(3) + " %");
        $('.pending_p_percent').text(((data.pending_Payment / data.total) * 100).toFixed(3) + " %");
        $('.hold_percent').text(((data.on_Hold / data.total) * 100).toFixed(3) + " %");
        $('.courier_hold_percent').text(((data.courier_hold / data.total) * 100).toFixed(3) + " %");
        $('.noresponse1_percent').text(((data.no_response1 / data.total) * 100).toFixed(3) + " %");
        $('.noresponse2_percent').text(((data.no_response2 / data.total) * 100).toFixed(3) + " %");
        $('.cancel_percent').text(((data.cancel / data.total) * 100).toFixed(3) + " %");
        $('.return_percent').text(((data.return / data.total) * 100).toFixed(3) + " %");
        $('.pending_return_percent').text(((data.pending_return / data.total) * 100).toFixed(3) + " %");
        $('.completed_percent').text(((data.completed / data.total) * 100).toFixed(3) + " %");
        $('.partial_delivery_percent').text(((data.partial_delivery / data.total) * 100).toFixed(3) + " %");
        $('.paid_return_percent').text(((data.paid_return / data.total) * 100).toFixed(3) + " %");
        $('.stock_out_percent').text(((data.stock_out / data.total) * 100).toFixed(3) + " %");
        $('.total_delivery_percent').text(((data.total_delivery / data.total) * 100).toFixed(3) + " %");

  });
}

  function statusChange(status,orderId){

    var activePageNumber = $(".page-item.active .page-link").text();
    $.ajax({
      url: "{{ url('admin/order-management/order/') }}/"+status+'/'+orderId,
      type: "get",
      })
      .done(function(data){

      statistics();
        getData(activePageNumber,1)
      })


  }

  function AssignEdit(id){
    var activePageNumber = $(".page-item.active .page-link").text();
    var order_assign = $('#order_assign_'+id).val();
        var token = '{{ csrf_token() }}';
        $.ajax({
            type: "POST",
            url: "{{ url('admin/order-management/assign_edit/') }}/"+id,
            data: {
              order_assign: order_assign,
                _token: token
            },
            success: function(response) {
                window.location.reload(true);
                $('#assign' + id).modal('hide');

                getData(activePageNumber,1)
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
  }

  function notedEdit(id){
    var activePageNumber = $(".page-item.active .page-link").text();
    var note = $('#order_noted_'+id).val();
        var token = '{{ csrf_token() }}';
        $.ajax({
            type: "POST",
            url: "{{ url('admin/order-management/noted_edit/') }}/"+id,
            data: {
                order_noted: note,
                _token: token
            },
            success: function(response) {
               window.location.reload(true);
                $('#noted' + id).modal('hide');

                getData(activePageNumber,1)
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
  }

  function applyPreSavedNoteToModal(orderId, noteValue){
    if (noteValue) {
      $('#order_noted_' + orderId).val(noteValue);
    }
  }




        $(document).on('click', '.qc_btn_modal',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');

           $.ajax({
                url: "{{ url('qc_report/') }}/"+id,
                type: "get",
                datatype: "html",
            }).done(function(data){
                $('.qc_logModal .modal-body').empty().append(data.details);
                $(this).closest('td').find('.qc_result').empty().append('<span class="text-primary">T:'+(data.delivered+data.returned)+'</span> <br><span class="text-success">D:'+data.delivered+'</span> <br><span class="text-danger">R:'+data.returned+'</span>')
                $('.qc_logModal').modal('toggle')


            });



    });

</script>


@endsection
