<script src="{{ asset('backend/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('backend/lib/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <script src="{{ asset('backend/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
     <script src="{{ asset('backend/js/bootstrap-tagsinput.js')}}"></script>
     <script src="{{ asset('backend/js/bootstrap-tagsinput.min.js')}}"></script>
    
    <script src="{{ asset('backend/lib/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('backend/lib/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('backend/lib/peity/jquery.peity.min.js')}}"></script>
    <script src="{{ asset('backend/lib/rickshaw/vendor/d3.min.js')}}"></script>
    <script src="{{ asset('backend/lib/rickshaw/vendor/d3.layout.min.js')}}"></script>
    <script src="{{ asset('backend/lib/rickshaw/rickshaw.min.js')}}"></script>
    <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{ asset('backend/lib/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ asset('backend/lib/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{ asset('backend/lib/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('backend/lib/echarts/echarts.min.js')}}"></script>
    <script src="{{ asset('backend/lib/select2/js/select2.full.min.js')}}"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyAq8o5-8Y5pudbJMJtDFzb8aHiWJufa5fg"></script>
    <script src="{{ asset('backend/lib/gmaps/gmaps.min.js')}}"></script>

    <script src="{{ asset('backend/js/bracket.js')}}"></script>
    <script src="{{ asset('backend/js/map.shiftworker.js')}}"></script>
    <script src="{{ asset('backend/js/ResizeSensor.js')}}"></script>
    <script src="{{ asset('backend/js/dashboard.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript">
      @if(Session::has('message'))
      var type = "{{ Session::get('alert-type', 'info') }}"

      switch(type){
        case 'info':
        toastr.info("{{Session::get('message')}}");

        break;

        case 'warning':
        toastr.warning("{{Session::get('message')}}");


        break;

        case 'success':
        toastr.success("{{Session::get('message')}}");


        break;

        case 'error':
        toastr.error("{{Session::get('message')}}");


        break;
      }

      @endif
      
    </script>
    <script>
        toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    </script>
    <script>
      $(function(){
        'use strict'

        // FOR DEMO ONLY
        // menu collapsed by default during first page load or refresh with screen
        // having a size between 992px and 1299px. This is intended on this page only
        // for better viewing of widgets demo.
        $(window).resize(function(){
          minimizeMenu();
        });

        minimizeMenu();

        function minimizeMenu() {
          if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1299px)').matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
          } else if(window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
          }
        }
      });
    </script>

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
<script>
  $(function(){
    $(".chkCheckAll").click(function(){
      $(".sub_chk").prop('checked',$(this).prop('checked'));
      $(".checkBoxClass").prop('checked',$(this).prop('checked'));
    })
  })
</script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<script>
  $(function() {
    $('.emp_time').hide(); 
    $('.role').change(function(){
        if($('.role').val() == '3') {
            $('.emp_time').show(); 
        } else {
            $('.emp_time').hide(); 
        } 
    });
});

   $(function() {
    if($('.role_two').val() == '3') {
            $('.emp_time_two').show(); 
        } else {
            $('.emp_time_two').hide(); 
        } 
    $('.role_two').change(function(){
        if($('.role_two').val() == '3') {
            $('.emp_time_two').show(); 
        } else {
            $('.emp_time_two').hide(); 
        } 
    });
});

  
</script>
<script type="text/javascript">
    // add row
   

    // remove row
   
    $("#SubmitForm").on('submit',function(e){
        e.preventDefault();
        let title = $('#title').val();
        let status = $('#status').val();
        $.ajax({
            url: "/employee/category/store",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                title:title,
                status:status,
            },
            success:function(response){
                document.location.href = '/employee/category/manage';

            }
        })

    })





    // 
      $.ajax({
      url: "/submit-form",
      type:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
        name:name,
        email:email,
        mobile:mobile,
        message:message,
      },
      success:function(response){
        $('#successMsg').show();
        console.log(response);
      },
      error: function(response) {
        $('#nameErrorMsg').text(response.responseJSON.errors.name);
        $('#emailErrorMsg').text(response.responseJSON.errors.email);
        $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
        $('#messageErrorMsg').text(response.responseJSON.errors.message);
      },
      });


</script>

<script type="text/javascript">
     
            $('#bulk_delete').on('click', function (e) {
                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    if (confirm('Are Your Sure To Delete?') == true) {
                        $('#all_id').val(allVals);
                        $('#bulk_delete_form').submit();
                    }
                }
            });
            $('#bulk_print').on('click', function (e) {
                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {

                    $('#all_id_print').val(allVals);
                    $('#bulk_print_form').submit();

                }
            });
             $('#status').on('change', function (e) {
                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    $('#all_status').val(allVals);
                    $('#all_status_form').submit();
                }
            });
    
</script>



