@extends('manager.layout.template')
@section('body-content')

@include('manager.includes.statistics')
@include('manager.includes.ajax-filter')

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
            <div class="row justify-content-center">
                <span class="tx-20 text-center mt-1" >All Processing Orders</span>
            </div>
            <div class="assign">

            <div class="loader"></div>

            </div>


        </div>
    </div>






    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

function Processing(status){
    $("#search_input").val('');
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

getData(1, 0);
statistics();
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
  statistics();
var params = {
status : $("#status_ajax").val(),
search_input : $("#search_input").val(),
order_type : $("#order_type").val(),
// fromDate : $("#fromDate").val(),
// toDate : $("#toDate").val(),
courier : $("#courier").val(),
// fixeddate : $("#fixeddate").val(),

// order_assign : $("#order_assign").val(),
// product_id:$("#product_id").val(),

paginate : $("#paginate").val(),
};
var paramStrings = [];
for (var key in params) {
paramStrings.push(key + '=' + encodeURIComponent(params[key]));
}

$('.btn-submit').prop('disabled', true);

$.ajax({
url: "{{ url('manager/order-management/new-manage-action?page=') }}"+page +"&"+paramStrings.join('&'),
type: "get",
datatype: "html",
})
.done(function(data){

$(".assign").empty().append(data);
$('.btn-submit').prop('disabled', false);
console.log(data);
})
.fail(function(jqXHR, ajaxOptions, thrownError){
getData(page, 0);
$('.btn-submit').prop('disabled', false);
});
}



function statistics(){
    var params = {
        // fixeddate : $("#fixeddate").val(),

    //   fromDate : $("#fromDate").val(),
    //   toDate : $("#toDate").val(),
      courier : $("#courier").val(),
    //   order_assign : $("#order_assign").val(),
    //   product_id:$("#product_id").val(),
      order_type : $("#order_type").val(),
      };
      var paramStrings = [];
        for (var key in params) {
        paramStrings.push(key + '=' + encodeURIComponent(params[key]));
      }

    //  var url: "{{ url('http://localhost/ecommerce/total-order-list?') }}"+paramStrings.join('&');

$.ajax({
url: "{{ url('manager/order-management/manager-total-order-list?') }}"+paramStrings.join('&'),
type: "get",
datatype: "html",
})
.done(function(data){
              $('#processing').text(data.processing);
              $('#pending').text(data.pending_Delivery);
              $('#ondelivery').text(data.on_Delivery);
              $('#pending_p').text(data.pending_Payment);
              $('#hold').text(data.on_Hold);
              $('#courier_hold').text(data.courier_hold);
              $('#noresponse1').text(data.no_response1);
              $('#noresponse2').text(data.no_response2);
              $('#cancel').text(data.cancel);
              $('#return').text(data.return);
              $('#completed').text(data.completed);
              $('#partial_delivery').text(data.partial_delivery);
              $('#paid_return').text(data.paid_return);
              $('#stock_out').text(data.stock_out);
              $('#total_count').text(data.total);
          });
  }

  function statusChange(status,orderId){

    var activePageNumber = $(".page-item.active .page-link").text();
    $.ajax({
      url: "{{ url('manager/order-management/order/') }}/"+status+'/'+orderId,
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
            url: "{{ url('manager/order-management/assign_edit/') }}/"+id,
            data: {
              order_assign: order_assign,
                _token: token
            },
            success: function(response) {
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
            url: "{{ url('manager/order-management/noted_edit/') }}/"+id,
            data: {
                order_noted: note,
                _token: token
            },
            success: function(response) {
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

</script>


@endsection
