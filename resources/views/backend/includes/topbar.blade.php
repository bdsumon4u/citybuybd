 <div class="br-header d-flex justify-content-between">
      <div class="br-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>

      </div><!-- br-header-left -->
      <div class="br-header-right">
        @php
          $balance = cache()->remember('sms-balance', now()->addMinutes(10), function () use ($settings) {
            return \Illuminate\Support\Facades\Http::withoutVerifying()
              ->withHeaders([
                  'Content-Type' => 'application/json',
              ])
              ->get('http://sms.hotash.tech/api/v2/Balance', [
                'ApiKey' => $settings->sms_api_key,
                'ClientId' => $settings->sms_api_secret,
              ])->json('Data.0.Credits', 0);
          });
        @endphp
        <div class="mr-4">SMS: {{$balance}}</div>
        <nav class="nav">
          <div class="nav-item" style="margin-right: 20px; display: flex; align-items: center; padding: 8px 0;">
            <label class="switch" style="margin: 0; cursor: pointer;" title="Toggle In-App Notifications">
              <input type="checkbox" id="inAppNotificationToggle">
              <span class="slider round"></span>
            </label>
            <span style="margin-left: 8px; font-size: 12px; color: #5969ff; white-space: nowrap;">Notifications</span>
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

input:checked + .slider {
  background-color: #5969ff;
}

input:checked + .slider:before {
  transform: translateX(23px);
}

.slider.round {
  border-radius: 22px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
