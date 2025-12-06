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

@auth
<script>
    (function () {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            return;
        }

        const vapidPublicKey = "{{ config('webpush.vapid.public_key') }}";
        if (!vapidPublicKey) {
            return;
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        const AUDIO_READY_KEY = 'citybuybd_audio_ready';
        let audioContext;
        let audioUnlocked = false;
        let pendingBeep = false;
        let audioPromptElement;

        function getAudioContext() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }

            return audioContext;
        }

        function unlockAudio() {
            return new Promise(function (resolve, reject) {
                const context = getAudioContext();

                if (context.state === 'suspended') {
                    const resumePromise = context.resume();

                    // Timeout if resume takes too long (browser blocking)
                    const timeoutPromise = new Promise((_, rejectTimeout) => {
                        setTimeout(() => rejectTimeout(new Error('Audio resume timed out')), 500);
                    });

                    Promise.race([resumePromise, timeoutPromise])
                        .then(function () {
                            if (context.state === 'running') {
                        audioUnlocked = true;
                        resolve();
                } else {
                                reject(new Error('AudioContext still suspended'));
                            }
                        })
                        .catch(reject);
                } else if (context.state === 'running') {
                    audioUnlocked = true;
                    resolve();
                } else {
                    reject(new Error('AudioContext state: ' + context.state));
                }
            });
        }

        function hideAudioPrompt() {
            if (audioPromptElement) {
                audioPromptElement.style.display = 'none';
            }
        }

        function showAudioPrompt() {
            if (audioPromptElement) {
                audioPromptElement.style.display = 'flex';
                return;
            }

            audioPromptElement = document.createElement('div');
            audioPromptElement.style.position = 'fixed';
            audioPromptElement.style.top = '50%';
            audioPromptElement.style.left = '50%';
            audioPromptElement.style.transform = 'translate(-50%, -50%)';
            audioPromptElement.style.zIndex = '99999';
            audioPromptElement.style.background = '#fff';
            audioPromptElement.style.border = '2px solid #0d6efd';
            audioPromptElement.style.padding = '24px';
            audioPromptElement.style.borderRadius = '8px';
            audioPromptElement.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
            audioPromptElement.style.display = 'flex';
            audioPromptElement.style.flexDirection = 'column';
            audioPromptElement.style.gap = '16px';
            audioPromptElement.style.minWidth = '320px';
            audioPromptElement.style.maxWidth = '400px';

            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.background = 'rgba(0,0,0,0.5)';
            overlay.style.zIndex = '99998';
            document.body.appendChild(overlay);

            const title = document.createElement('h3');
            title.textContent = 'Enable Audio Notifications';
            title.style.margin = '0';
            title.style.fontSize = '18px';
            title.style.fontWeight = '600';
            title.style.color = '#333';

            const message = document.createElement('p');
            message.textContent = 'Allow audio notifications to hear alerts for new orders. Click "Allow" to enable sound alerts.';
            message.style.margin = '0';
            message.style.fontSize = '14px';
            message.style.color = '#666';
            message.style.lineHeight = '1.5';

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '12px';
            buttonContainer.style.justifyContent = 'flex-end';
            buttonContainer.style.marginTop = '8px';

            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Not Now';
            cancelButton.style.background = '#f0f0f0';
            cancelButton.style.color = '#333';
            cancelButton.style.border = '1px solid #ddd';
            cancelButton.style.borderRadius = '4px';
            cancelButton.style.padding = '10px 20px';
            cancelButton.style.cursor = 'pointer';
            cancelButton.style.fontSize = '14px';

            const allowButton = document.createElement('button');
            allowButton.textContent = 'Allow';
            allowButton.style.background = '#0d6efd';
            allowButton.style.color = '#fff';
            allowButton.style.border = 'none';
            allowButton.style.borderRadius = '4px';
            allowButton.style.padding = '10px 20px';
            allowButton.style.cursor = 'pointer';
            allowButton.style.fontSize = '14px';
            allowButton.style.fontWeight = '600';

            function closePrompt() {
                if (audioPromptElement) {
                    audioPromptElement.remove();
                }
                if (overlay) {
                    overlay.remove();
                }
                audioPromptElement = null;
            }

            cancelButton.addEventListener('click', function () {
                closePrompt();
            });

            overlay.addEventListener('click', function () {
                closePrompt();
            });

            allowButton.addEventListener('click', function () {
                unlockAudio().then(function () {
                    closePrompt();
                    localStorage.setItem(AUDIO_READY_KEY, '1');

                    if (pendingBeep) {
                        pendingBeep = false;
                        playBeep();
                    } else {
                        playBeep();
                    }
                }).catch(function (error) {
                    console.error('Audio unlock failed:', error);
                    alert('Your browser blocked audio playback. Please allow audio permissions in your browser settings.');
                });
            });

            audioPromptElement.appendChild(title);
            audioPromptElement.appendChild(message);
            buttonContainer.appendChild(cancelButton);
            buttonContainer.appendChild(allowButton);
            audioPromptElement.appendChild(buttonContainer);
            document.body.appendChild(audioPromptElement);
        }

        function ensureAudioReady() {
            if (audioUnlocked) {
                return;
            }

            const context = getAudioContext();
            if (context.state === 'running') {
                audioUnlocked = true;
                return;
            }

            const previouslyEnabled = localStorage.getItem(AUDIO_READY_KEY) === '1';
            const shouldPrompt = pendingBeep || !previouslyEnabled;

            const tryResume = function () {
                return unlockAudio().then(function() {
                    console.log('Audio unlocked automatically');
                }).catch(function(err) {
                    console.warn('Audio auto-unlock failed (waiting for interaction):', err.message);
                    if (shouldPrompt) {
                        showAudioPrompt();
                    } else {
                        // Previously enabled, but browser blocked autoplay.
                        // Wait for any user interaction to unlock silently.
                        const silentUnlock = function() {
                            unlockAudio().then(() => {
                                console.log('Audio unlocked via interaction');
                                document.removeEventListener('click', silentUnlock);
                                document.removeEventListener('touchstart', silentUnlock);
                                document.removeEventListener('keydown', silentUnlock);
                            }).catch(function(e) {
                                console.log('Silent unlock failed:', e.message);
                            });
                        };
                        document.addEventListener('click', silentUnlock);
                        document.addEventListener('touchstart', silentUnlock);
                        document.addEventListener('keydown', silentUnlock);
                    }
                });
            };

            if (previouslyEnabled) {
                tryResume();
            }

            if (shouldPrompt) {
                const delay = previouslyEnabled ? 0 : 1000;
                setTimeout(function () {
                    if (!audioUnlocked) {
                showAudioPrompt();
                    }
                }, delay);
            }
        }

        function playBeep() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }

            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            const now = audioContext.currentTime;
            const duration = 0.15;
            const pause = 0.05;

            function playTone(frequency, startTime, volume) {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.type = 'sine';
                oscillator.frequency.value = frequency;

                gainNode.gain.setValueAtTime(0, startTime);
                gainNode.gain.linearRampToValueAtTime(volume, startTime + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.001, startTime + duration);

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

                oscillator.start(startTime);
                oscillator.stop(startTime + duration);
            }

            playTone(523.25, now, 0.3);
            playTone(659.25, now + duration + pause, 0.3);
            playTone(783.99, now + (duration + pause) * 2, 0.3);
        }

        function handleSwMessage(event) {
            console.log("Message received from SW:", event.data);
            if (event.data && event.data.type === 'order-notification') {
                console.log("Order notification received, playing beep");
                if (audioUnlocked) {
                    playBeep();
                } else {
                    pendingBeep = true;
                    ensureAudioReady();
                }
            }
        }

        function setupMessageListener() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.addEventListener('message', handleSwMessage);
            }
        }

        function ensurePermission() {
            if (Notification.permission === 'granted') {
                return Promise.resolve('granted');
            }

            if (Notification.permission === 'denied') {
                console.log('Please allow notifications for CitybuyBD in your browser settings to receive updates.');
                return Promise.reject('denied');
            }

            return Notification.requestPermission().then(function (result) {
                if (result !== 'granted') {
                    alert('Notifications are required for real-time updates. Please allow them.');
                    return Promise.reject(result);
                }

                return result;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOMContentLoaded');
            ensureAudioReady();
            setupMessageListener();

            navigator.serviceWorker.register('/push-sw.js')
                .then(function (registration) {
                    setupMessageListener();
                    return ensurePermission().then(function () {
                        return registration.pushManager.getSubscription()
                            .then(function (subscription) {
                                if (subscription) {
                                    return;
                                }

                                var convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

                                return registration.pushManager.subscribe({
                                    userVisibleOnly: true,
                                    applicationServerKey: convertedVapidKey
                                }).then(function (newSubscription) {
                                    return fetch("{{ route('push.subscribe') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        },
                                        body: JSON.stringify(newSubscription)
                                    });
                                });
                            });
                    });
                })
                .catch(function (error) {
                    console.error('Push setup failed', error);
                });
        });
    })();
</script>
@endauth
