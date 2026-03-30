    <section
      class="space bg-title position-relative"
      data-bg-src="assets/img/bg/booking_bg_2.png"
    >
      <div class="container th-container">
        <div class="row justify-content-between align-items-center">
          <div class="col-xl-auto">
            <div class="available-table">
              <h3 class="available-table_title">Opening Time</h3>
              <p class="available-table_info">
                <span>Monday - Wednesday</span><span>09:00 - 06:00</span>
              </p>
              <p class="available-table_info">
                <span>Thursday - Friday</span><span>10:00 - 05:00</span>
              </p>
              <p class="available-table_info">
                <span>Saturday</span><span>09:00 - 04:00</span>
              </p>
              <p class="available-table_info">
                <span>Sunday</span><span>07:00 - 09:00</span>
              </p>
              <h3 class="available-table_title style2">Call Now</h3>
              <a href="tel:+15365479874" class="available-table_call"
                >+153-6547-9874</a
              >
            </div>
          </div>
          <div class="col-xl-auto">
            <h2 class="sec-title text-center text-white">
              Book A Table <span class="font-style text-theme">Now!</span>
            </h2>
            <form action="mail.php" method="POST" class="booking-form style2">
              <div class="row">
                <div class="form-group col-lg-4">
                  <input
                    type="text"
                    class="form-control date-pick"
                    id="date-pick"
                    name="date"
                    placeholder="Select Date"
                  />
                  <i class="fal fa-calendar-days"></i>
                </div>
                <div class="form-group col-lg-4">
                  <input
                    type="text"
                    class="form-control time-pick"
                    id="time-pick"
                    name="time"
                    placeholder="Select-time"
                  />
                  <i class="fal fa-clock"></i>
                </div>
                <div class="form-group col-lg-4">
                  <input
                    type="number"
                    class="form-control"
                    name="guest"
                    id="guest"
                    placeholder="Total Guests"
                  />
                  <i class="fal fa-user-group"></i>
                </div>
                <div class="form-group col-12">
                  <textarea
                    name="message"
                    id="message"
                    cols="30"
                    rows="3"
                    class="form-control"
                    placeholder="Write Message"
                  ></textarea>
                  <i class="fal fa-comment"></i>
                </div>
                <div class="form-btn col-12 text-center">
                  <button class="th-btn style3">SUBMIT REQUEST</button>
                </div>
              </div>
              <p class="form-messages mb-0 mt-3"></p>
            </form>
          </div>
        </div>
        <div
          class="available-table-bg"
          data-bg-src="assets/img/bg/table_bg_1.jpg"
        ></div>
      </div>
    </section>