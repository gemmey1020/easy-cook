    <section class="space" id="bookingSec1" data-bg-src="assets/img/bg/booking_bg_1.jpg" data-overlay="title" data-opacity="8">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">
                    <img class="icon" src="assets/img/icon/title_icon.svg" alt="icon">
                    Reservation
                </span>
                <h2 class="sec-title text-white">Book A Table <span class="font-style text-theme">Now!</span></h2>
                <p class="sec-text text-white ms-auto me-auto">Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.</p>
            </div>
            <form action="mail.php" method="POST" class="booking-form">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <input type="text" class="form-control date-pick" id="date-pick" name="date" placeholder="Select Date">
                        <i class="fal fa-calendar-days"></i>
                    </div>
                    <div class="form-group col-lg-4">
                        <input type="text" class="form-control time-pick" id="time-pick" name="time" placeholder="Select-time">
                        <i class="fal fa-clock"></i>
                    </div>
                    <div class="form-group col-lg-4">
                        <input type="number" class="form-control" name="guest" id="guest" placeholder="Total Guests">
                        <i class="fal fa-user-group"></i>
                    </div>
                    <div class="form-group col-12">
                        <textarea name="message" id="message" cols="30" rows="3" class="form-control" placeholder="Write Message"></textarea>
                        <i class="fal fa-comment"></i>
                    </div>
                    <div class="form-btn col-12 text-center">
                        <button class="th-btn style3">SUBMIT REQUEST</button>
                    </div>
                </div>
                <p class="form-messages mb-0 mt-3"></p>
            </form>
        </div>
    </section>