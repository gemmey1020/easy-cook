<!-- Booking Area -->
<!--==============================-->
    <section class="space-bottom">
        <div class="container">
            <div class="reservation-area">
                <h4 class="sec-title text-center mb-30">Send Us Message</h4>
                <form action="mail.php" method="POST" class="booking-form3">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                            <input type="text" class="form-control rounded-2" id="name" name="name" placeholder="Your Name">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <input type="tel" class="form-control rounded-2" id="number" name="number" placeholder="Phone Number">
                            <i class="far fa-phone"></i>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control rounded-2" id="subject" name="subject" placeholder="Subject">
                            <i class="far fa-tag"></i>
                        </div>
                        <div class="form-group col-12">
                            <textarea name="message" id="message" cols="30" rows="3" class="form-control rounded-2" placeholder="Write Message"></textarea>
                            <i class="far fa-comment"></i>
                        </div>
                    </div>
                    <div class="form-btn text-center">
                        <button class="th-btn rounded-2">Send Message<i class="fa-solid fa-arrow-right ms-2"></i></button>
                    </div>
                    <p class="form-messages mb-0 mt-3"></p>
                </form>
            </div>
        </div>
    </section>
