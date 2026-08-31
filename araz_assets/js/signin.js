$(document).on('submit','form#review_form', function(e) {
    e.preventDefault(); 
  
    var url=$(this).attr('action');
    var method=$(this).attr('method');
    var formData = $(this).serialize();
    $.ajax({
        type: method,
        url: url,
        data: formData,
        success: function(res) {
            if(res.success==true){
                toastr.success(res.msg);
              
              	setTimeout(function () {
                  window.location.reload();
                }, 1500);
                
            }else if(res.success==false){
                toastr.error(res.msg);
            }
            
        },
        error:function (response){
          	if(response.status==401){
              	toastr.error('Please Login First For Product Review');
            }else if(response.status==422){
              	$.each(response.responseJSON.errors,function(field_name,error){
                  toastr.error(error);
              });
            }
          	
            
        }
    });
});


$(document).on('click', '.cart-close', function(e) {
    e.preventDefault(); // লিংকের ডিফল্ট অ্যাকশন বন্ধ করা

    // ১. কার্ট ড্রপডাউন বন্ধ করা
    $(document).find('#cart-dropdown').removeClass('open');
    $(document).find('.closeMask').remove();

    // ২. বডি থেকে স্ক্রল লক সরানো
    $('body').removeClass('open sidebar-open overflow-hidden'); // ক্লাসগুলো রিমুভ করা
    
    // 'auto' এর বদলে খালি স্ট্রিং ('') ব্যবহার করুন
    $('body').css('overflow', '');      // ইনলাইন স্টাইল রিমুভ করবে
    $('body').css('overflow-y', '');    // Y-অক্ষ বরাবর ইনলাইন স্টাইল রিমুভ করবে
    $('body').css('padding-right', ''); // প্যাডিং রিমুভ করবে
});

$(document).ready(function(){
    $(".signin-tab").click(function(){
        $(".signin-body").fadeIn('fast');
        $(".signup-body").fadeOut('fast');
        $(".signin-tab").addClass("tab-active");
        $(".signup-tab").removeClass("tab-active");
        $(".Tracking").fadeIn("fast");
        $(".benefit").fadeOut("fast");

    }); 
    $(".signup-tab").click(function(){
        $(".signin-tab").removeClass("tab-active");
        $(".signup-tab").addClass("tab-active");
        $(".signin-body").fadeOut('fast');
        $(".signup-body").fadeIn('fast');        
        $(".Tracking").fadeOut("fast");
        $(".benefit").fadeIn("fast");
    }); 
});

$(document).on('submit','form#ajax_form', function(e) {
    e.preventDefault(); 
    $('span.textdanger').text('');
    var url=$(this).attr('action');
    var method=$(this).attr('method');
    var formData = $(this).serialize();
    $.ajax({
        type: method,
        url: url,
        data: formData,
        success: function(res) {
            if(res.success==true){
                toastr.success(res.msg);
                if(res.url){
                    document.location.href = res.url;
                }else{
                    window.location.reload();
                }
                
            }else if(res.success==false){
                toastr.error(res.msg);
            }
            
        },
        error:function (response){
            $.each(response.responseJSON.errors,function(field_name,error){
                $(document).find('[name='+field_name+']').after('<span class="textdanger" style="color:red">' +error+ '</span>');
            })
        }
    });
});