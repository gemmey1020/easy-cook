    <section
      class="booking-sec1 space"
      id="bookingSec1"
      data-bg-src="assets/img/update_2/bg/booking_bg_2.png"
    >
      <div class="container">
        <div class="row">
          <div class="col-xl-7">
            <div class="title-area text-center text-xl-start mb-30">
              <span class="sub-title">Booking Table</span>
              <h2 class="sec-title text-white">Make a Reservation</h2>
            </div>
            <form action="mail.php" method="POST" class="booking-form3">
              <div class="row">
                <div class="form-group col-md-6">
                  <input
                    type="text"
                    class="form-control rounded-2 date-pick"
                    id="date-pick"
                    name="date"
                    placeholder="Select Date"
                  />
                  <i class="far fa-calendar-days"></i>
                </div>
                <div class="form-group col-md-6">
                  <input
                    type="text"
                    class="form-control rounded-2 time-pick"
                    id="time-pick"
                    name="time"
                    placeholder="Select-time"
                  />
                  <i class="far fa-clock"></i>
                </div>
                <div class="form-group col-12">
                  <input
                    type="number"
                    class="form-control rounded-2"
                    name="guest"
                    id="guest"
                    placeholder="Total Guests"
                  />
                  <i class="far fa-user-group"></i>
                </div>
                <div class="form-group col-12">
                  <textarea
                    name="message"
                    id="message"
                    cols="30"
                    rows="3"
                    class="form-control rounded-2"
                    placeholder="Write Message"
                  ></textarea>
                  <i class="far fa-comment"></i>
                </div>
                <div class="form-btn col-12 text-center text-xl-start">
                  <button class="th-btn style3 rounded-2">
                    Make a Reservation<i
                      class="fa-solid fa-arrow-right ms-2"
                    ></i>
                  </button>
                </div>
              </div>
              <p class="form-messages mb-0 mt-3"></p>
            </form>
          </div>
        </div>
      </div>
    </section>