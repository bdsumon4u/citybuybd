 <div class="br-header d-flex justify-content-between">
     <div class="br-header-left">
         <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i
                     class="icon ion-navicon-round"></i></a></div>
         <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i
                     class="icon ion-navicon-round"></i></a></div>

     </div><!-- br-header-left -->
     <div class="br-header-right">
         <nav class="nav">
             <!-- Attendance Toggle -->
             <div class="nav-item" style="margin-right: 15px; display: flex; align-items: center; padding: 8px 0;">
                 <label class="switch" style="margin: 0; cursor: pointer;" title="Toggle Attendance">
                     <input type="checkbox" id="attendanceToggle">
                     <span class="att-slider round"></span>
                 </label>
                 <span id="attendanceLabel"
                     style="margin-left: 8px; font-size: 12px; color: #28a745; white-space: nowrap;">Attendance</span>
             </div>

             <div class="nav-item" style="margin-right: 20px; display: flex; align-items: center; padding: 8px 0;">
                 <label class="switch" style="margin: 0; cursor: pointer;" title="Toggle In-App Notifications">
                     <input type="checkbox" id="inAppNotificationToggle">
                     <span class="slider round"></span>
                 </label>
                 <span
                     style="margin-left: 8px; font-size: 12px; color: #5969ff; white-space: nowrap;">Notifications</span>
             </div>
         </nav>
     </div><!-- br-header-right -->
 </div><!-- br-header -->

 <style>
     .br-header-right .nav-item {
         display: flex !important;
         align-items: center !important;
     }

     .switch {
         position: relative;
         display: inline-block;
         width: 45px;
         height: 22px;
         flex-shrink: 0;
     }

     .switch input {
         opacity: 0;
         width: 0;
         height: 0;
     }

     .slider {
         position: absolute;
         cursor: pointer;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background-color: #ccc;
         transition: .4s;
     }

     .slider:before {
         position: absolute;
         content: "";
         height: 16px;
         width: 16px;
         left: 3px;
         bottom: 3px;
         background-color: white;
         transition: .4s;
     }

     input:checked+.slider {
         background-color: #5969ff;
     }

     input:checked+.slider:before {
         transform: translateX(23px);
     }

     .slider.round {
         border-radius: 22px;
     }

     .slider.round:before {
         border-radius: 50%;
     }

     /* Attendance toggle styles */
     .att-slider {
         position: absolute;
         cursor: pointer;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background-color: #ccc;
         transition: .4s;
     }

     .att-slider:before {
         position: absolute;
         content: "";
         height: 16px;
         width: 16px;
         left: 3px;
         bottom: 3px;
         background-color: white;
         transition: .4s;
     }

     input:checked+.att-slider {
         background-color: #28a745;
     }

     input:checked+.att-slider:before {
         transform: translateX(23px);
     }

     .att-slider.round {
         border-radius: 22px;
     }

     .att-slider.round:before {
         border-radius: 50%;
     }
 </style>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const toggle = document.getElementById('attendanceToggle');
         const label = document.getElementById('attendanceLabel');

         // Check current status on page load
         fetch("{{ route('employee.attendance.status') }}")
             .then(r => r.json())
             .then(data => {
                 if (data.is_checked_in) {
                     toggle.checked = true;
                     label.textContent = 'Checked In (' + data.check_in + ')';
                     label.style.color = '#28a745';
                 } else if (data.is_checked_out) {
                     toggle.checked = false;
                     toggle.disabled = true;
                     label.textContent = 'Done (' + data.check_in + ' - ' + data.check_out + ')';
                     label.style.color = '#6c757d';
                 } else {
                     toggle.checked = false;
                     label.textContent = 'Not Checked In';
                     label.style.color = '#dc3545';
                 }
             });

         toggle.addEventListener('change', function() {
             fetch("{{ route('employee.attendance.toggle') }}", {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },
                     body: JSON.stringify({})
                 })
                 .then(r => r.json())
                 .then(data => {
                     if (data.status === 'checked_in') {
                         toggle.checked = true;
                         label.textContent = 'Checked In (' + data.check_in + ')';
                         label.style.color = '#28a745';
                     } else if (data.status === 'checked_out') {
                         toggle.checked = false;
                         toggle.disabled = true;
                         label.textContent = 'Done (' + data.check_out + ')';
                         label.style.color = '#6c757d';
                     }
                     alert(data.message);
                 })
                 .catch(err => {
                     toggle.checked = !toggle.checked;
                     alert('Error toggling attendance.');
                 });
         });
     });
 </script>
