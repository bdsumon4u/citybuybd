$(document).ready(function(){

    // ==========================================
    // ১. Cart Form Submit (Add to Cart)
    // ==========================================
    $(document).on('submit', 'form#cart_form', function(e) {
        e.preventDefault();

        // বাটন খুঁজে বের করা এবং আগের ডিজাইন/টেক্সট সেভ করে রাখা
        let submitBtn = $(this).find('#order_btn1, button[type="submit"]');
        let originalHtml = submitBtn.html();

        // বাটন ডিজেবল করে স্পিনার দেখানো
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        let size = $('#size_value').val() || '';
        let url = $(this).attr('action');
        let method = $(this).attr('method');
        let data = $(this).serialize() + '&size=' + size;

        $.ajax({
            url: url,
            method: method,
            data: data,
            cache: false,
            success: function (res) {
                if (res.success) {
                    
                    // Update cart view instantly
                    if (res.view) {
                        $('div#cart_section').html(res.view);
                    }
                    if (res.item) {
                        $('span.cart-count').text(res.item);
                    }

                    // ★★★ Popup Checkout Logic ★★★
                    // window.checkoutType আমরা app.blade.php থেকে পাঠাবো
                    if (window.checkoutType === 'popup' && $('#quickCheckoutModal').length > 0) {
                        // পপআপ মোড অন থাকলে Modal ওপেন হবে
                        $('#quickCheckoutModal').modal('show');
                    } else {
                        // ডিফল্ট মোড (আগের মত SweetAlert এবং Redirect)
                        Swal.fire({
                            icon: 'success',
                            title: 'পরবর্তী ধাপ',
                            text: res.msg || 'নাম /ঠিকানা দিয়ে অর্ডার কনফার্ম করুন',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        if (res.url) {
                            setTimeout(function(){
                                window.location.href = res.url;
                            }, 1000);
                        }
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ত্রুটি!',
                        text: res.msg || 'পণ্য যোগ হয়নি!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function (response) {
                let errorMsg = 'ইনপুট সমস্যা';
                if(response.responseJSON && response.responseJSON.errors) {
                    errorMsg = Object.values(response.responseJSON.errors)[0][0];
                }
                Swal.fire({
                    icon: 'error',
                    title: 'ত্রুটি',
                    text: errorMsg,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            complete: function() {
                // রিকোয়েস্ট শেষ হলে বাটন আবার আগের অবস্থায় ফিরিয়ে আনা
                submitBtn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // ==========================================
    // ২. Popup Checkout Form Submit
    // ==========================================
    $(document).on('submit', '#popup_checkout_form', function(e) {
        e.preventDefault();
        let popupForm = $(this);
        let popupBtn = popupForm.find('#popup_confirm_btn');
        let originalPopupBtnText = popupBtn.html();

        popupBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> প্রসেস হচ্ছে...');

        $.ajax({
            url: popupForm.attr('action'),
            method: 'POST',
            data: popupForm.serialize(),
            success: function (data) {
                if (data.success) {
                    toastr.success('অর্ডার সফল হয়েছে!');
                    if(data.url) {
                        window.location.href = data.url;
                    }
                } else {
                    toastr.error(data.message || data.msg || 'অর্ডার সম্পন্ন করা যায়নি।');
                    popupBtn.prop('disabled', false).html(originalPopupBtnText);
                }
            },
            error: function (err) {
                let errorMsg = 'সার্ভার এরর! আবার চেষ্টা করুন।';
                if(err.responseJSON && err.responseJSON.errors) {
                    errorMsg = Object.values(err.responseJSON.errors)[0][0];
                }
                toastr.error(errorMsg);
                popupBtn.prop('disabled', false).html(originalPopupBtnText);
            }
        });
    });

    // ==========================================
    // ৩. Cart Remove Form Submit (Unchanged)
    // ==========================================
    $(document).on('submit','form#cart_remove_form', function(e) {
        e.preventDefault(); 
        var ele=$(this);
        swal({
          title: "Are you sure?",
          text: "You want To Delete!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#006400",
          confirmButtonText: "Yes, do it!",
          cancelButtonText: "No, cancel plz!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {

            let url=ele.attr('action');
            let method=ele.attr('method');
            let data= ele.serialize();

            $.ajax({
                type: method,
                url: url,
                data: data,
                success: function(res) {
                    if(res.success==true){
                        toastr.success(res.msg);
                        $(document).find('span.cart-count').text(res.item);
                        $('div#cart-dropdown').html(res.html);
                        $(document).find('div.orderDetails').html(res.html2);
                        $(document).find('div.cart_other_details').html(res.html3);
                        
                       if(res.item <= 0) {
                          document.location.href = res.url;
                       }
                    }else if(res.success==false){
                        toastr.error(res.msg);
                    }
                },
                error:function (response){ }
            });
            swal.close();
          } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
          }
        });
    });

    // ==========================================
    // 4. Cart Dropdown Btn (Unchanged)
    // ==========================================
    $(document).on('click','a.cart-dropdown-btn', function(){
        let url=$(this).attr('href');
        $.ajax({
            url: url,
            method: "GET",
            data: {},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('div#cart-dropdown').html(res.html);
                }else{
                    toastr.error(res.msg);
                }
            }
        });
    });

});