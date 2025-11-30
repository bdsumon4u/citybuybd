<div class="footer" style="padding-top: 50px;">
    <div class="main-footer">
        <div class="container">
            <div class="custom-row">
                <!-- Existing Footer Columns -->
                <div class="custom-col-2">
                    <div class="footer-about">
                        <div class="footer-logo">
                            @if($settings)
                            <img src="{{ asset('backend/img/'.$settings->logo)  }}" alt="Logo">
                            @endif
                        </div>

                        <ul>
                            <li>
                                <div class="icon">
                                    <span><i class="flaticon-map"></i></span>
                                </div>
                                <div class="txt">
                                    <span>{{ $settings->address }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span><i class="flaticon-email"></i></span>
                                </div>
                                <div class="txt">
                                    <a href="mailto:info@example.com">{{ $settings->email }}</a>
                                    <a href="mailto:test@example.com">{{ $settings->email_two }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span><i class="flaticon-telephone"></i></span>
                                </div>
                                <div class="txt">
                                    <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                                    <a href="tel:{{ $settings->whatsapp_number }}">{{ $settings->whatsapp_number }}</a>
                                    <a href="tel:{{ $settings->imo_number }}">{{ $settings->imo_number }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="custom-col footer-extra">
                    <div class="link-wrap">
                        <div class="footer-link">
                            <h3 class="footer-title">Information</h3>
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Blogs</a></li>
                                <li><a href="#">Terms Of Use</a></li>
                                <li><a href="#">Privacy Policies</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="custom-col-2">
                    <div class="footer-subscribe">
                        <h3 class="footer-title">Facebook Page</h3>
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F{{ $settings->twitter_link}}%2F&amp;tabs&amp;width=340&amp;height=214&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=true&amp;appId" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    </div>
                </div>
            </div>

            <!-- ✅ Contact Icons Row -->
            <!-- Floating Contact FAB -->
            <div class="floating-contact">
            <!-- Main Toggle Button -->
            <button class="fab-btn" id="contactToggle">
                <img src="/public/multi-chat.svg" alt="Contact" id="fabIcon">
            </button>

           <div class="contact-icons" id="contactIcons">
                @php
                    // Helper function to prepend +88 for numeric numbers
                    function addCountryCode($number) {
                        if (!$number) return null;
                        $number = preg_replace('/\D/', '', $number); // remove non-digit characters
                        return '+88' . $number; // prepend +88, keep leading 0
                    }

                    $dialUp = addCountryCode($settings->dial_up);
                    $whatsapp = addCountryCode($settings->whatsapp_number);
                    $imo = addCountryCode($settings->imo_number);

                    // Messenger: check if numeric
                    $messengerRaw = $settings->messenger_username;
                    if ($messengerRaw) {
                        if (ctype_digit(preg_replace('/\D/', '', $messengerRaw))) {
                            // numeric → prepend +88
                            $messenger = addCountryCode($messengerRaw);
                        } else {
                            // contains letters → username, keep as-is
                            $messenger = $messengerRaw;
                        }
                    } else {
                        $messenger = null;
                    }
                @endphp

                <!-- Dial-up Call -->
                @if($dialUp)
                {{-- <a href="tel:{{ $dialUp }}" class="contact-icon call" title="Call">
                    <i class="fas fa-phone"></i>
                </a> --}}
                @endif

                <!-- WhatsApp -->
                @if($whatsapp)
                <a href="https://wa.me/{{ ltrim($whatsapp, '+') }}" target="_blank" class="contact-icon whatsapp" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @endif

                <!-- Messenger -->
                @if($settings->messenger_username)
                <a href="{{ $settings->messenger_username }}"
                target="_blank" class="contact-icon messenger" title="Messenger">
                    <i class="fab fa-facebook-messenger"></i>
                </a>
                @endif

                <!-- IMO -->
                <!-- @if($imo)
                    <a href="imo://user?id=$imo"
                    target="_blank"
                    class="contact-icon imo"
                    title="IMO">
                        <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/imo-icon.png" alt="IMO">
                    </a>
                @endif -->
            </div>

            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>Maintained By <a href="https://hotash.tech" target="_blank">Hotash Tech</a> </p>
                </div>
                <div class="col-md-6">
                    <div class="part-img d-flex justify-content-md-end justify-content-center">
                        <img src="{{asset('frontend/images/payment-gateway.png')}}" alt="Image" style="width: 350px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <!-- <script>
    const toggleBtn = document.getElementById('contactToggle');
    const contactIcons = document.getElementById('contactIcons');
    const fabIcon = document.getElementById('fabIcon');

    toggleBtn.addEventListener('click', function() {
    contactIcons.classList.toggle('show');

    if (contactIcons.classList.contains('show')) {
        // Change to cross (close icon)
        fabIcon.style.display = "none";
        let crossIcon = document.createElement("i");
        crossIcon.className = "fas fa-times";
        crossIcon.id = "closeIcon";
        crossIcon.style.color = "#fff";
        crossIcon.style.fontSize = "26px";
        toggleBtn.appendChild(crossIcon);
    } else {
        // Back to contact icon
        fabIcon.style.display = "block";
        let crossIcon = document.getElementById("closeIcon");
        if (crossIcon) crossIcon.remove();
    }
    });
    </script> -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('contactToggle');
    const contactIcons = document.getElementById('contactIcons');
    const fabIcon = document.getElementById('fabIcon');

    const closeIconId = "closeIcon";

    function addCloseIcon() {
        if (!document.getElementById(closeIconId)) {
            const crossIcon = document.createElement("i");
            crossIcon.className = "fas fa-times";
            crossIcon.id = closeIconId;
            crossIcon.style.color = "#fff";
            crossIcon.style.fontSize = "26px";
            toggleBtn.appendChild(crossIcon);
        }
    }

    function removeCloseIcon() {
        const crossIcon = document.getElementById(closeIconId);
        if (crossIcon) crossIcon.remove();
    }

    function openContacts() {
        contactIcons.classList.add('show');
        fabIcon.style.display = "none";
        addCloseIcon();
    }

    function closeContacts() {
        contactIcons.classList.remove('show');
        fabIcon.style.display = "block";
        removeCloseIcon();
    }

    // Ensure closed by default
    closeContacts();

    toggleBtn.addEventListener('click', function() {
        if (contactIcons.classList.contains('show')) {
            closeContacts();
        } else {
            openContacts();
        }
    });
});

</script>


<style>
/* Container bottom-right */
.floating-contact {
position: fixed;
bottom: 20px;
right: 20px;
z-index: 9999;
}

/* Main button */
.fab-btn {
background: #e84b2a;
border: none;
border-radius: 50%;
width: 60px;
height: 60px;
cursor: pointer;
display: flex;
align-items: center;
justify-content: center;
box-shadow: 0 4px 8px rgba(0,0,0,0.25);
transition: transform 0.3s, background 0.3s;
position: relative;
}

.fab-btn:hover {
transform: scale(1.1);
}

.fab-btn img,
.fab-btn i {
width: 28px;
height: 28px;
filter: brightness(0) invert(1);
}

/* Hidden icons stack */
.contact-icons {
display: flex;
flex-direction: column;
align-items: center;
position: absolute;
bottom: 70px;
right: 5px;
opacity: 0;
pointer-events: none;
transition: all 0.3s ease;
gap: 12px;
}

.contact-icons.show {
opacity: 1;
pointer-events: auto;
transform: translateY(0);
}

/* Contact icon buttons */
.contact-icon {
width: 46px;
height: 46px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
color: #fff;
font-size: 26px;
box-shadow: 0 3px 6px rgba(0,0,0,0.25);
transition: transform 0.2s;
}

.contact-icon:hover {
transform: scale(1.1);
}

/* Service colors */
.contact-icon.call { background: #2ba0d6; }
.contact-icon.whatsapp { background: #25d366; }
.contact-icon.messenger { background: #0084ff; }
.contact-icon.imo { background: #00bcd4; }

/* IMO icon override */
.contact-icon.imo img {
width: 28px;
height: 28px;
filter: brightness(0) invert(1);
}
</style>
