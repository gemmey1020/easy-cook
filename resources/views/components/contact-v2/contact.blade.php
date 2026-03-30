    <div class="" data-pos-for="#contactFormSec" data-sec-pos="bottom-half">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4">
            <div
              class="contact-info"
              data-bg-src="assets/img/bg/contact_info_bg.png"
            >
              <div class="contact-info_icon">
                <i class="fal fa-phone"></i>
              </div>
              <h4 class="contact-info_title box-title">Phone</h4>
              <span class="contact-info_text">
                <a href="tel:+65485965789">(+65) - 48596 - 5789</a>
                <a href="tel:+65485965789">+65-48596-5789</a>
              </span>
            </div>
          </div>
          <div class="col-lg-4">
            <div
              class="contact-info"
              data-bg-src="assets/img/bg/contact_info_bg.png"
            >
              <div class="contact-info_icon">
                <i class="fal fa-envelope"></i>
              </div>
              <h4 class="contact-info_title box-title">Email</h4>
              <span class="contact-info_text">
                <a href="mailto:info@pizzan.com">info@pizzan.com</a>
                <a href="mailto:info.example@gmail.com"
                  >info.example@gmail.com</a
                >
              </span>
            </div>
          </div>
          <div class="col-lg-4">
            <div
              class="contact-info"
              data-bg-src="assets/img/bg/contact_info_bg.png"
            >
              <div class="contact-info_icon">
                <i class="fal fa-location-dot"></i>
              </div>
              <h4 class="contact-info_title box-title">Location</h4>
              <span class="contact-info_text"
                >1403 Washington Ave, New Orlea <br />
                ns, LA 70130, United States</span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="space bg-smoke2" id="contactFormSec">
      <div class="container">
        <form action="mail.php" method="POST" class="contact-form ajax-contact">
          <h3 class="form-title">Get In Touch</h3>
          <div class="row">
            <div class="form-group col-md-6">
              <input
                type="text"
                class="form-control"
                name="name"
                id="name"
                placeholder="Your Name"
              />
              <i class="fal fa-user"></i>
            </div>
            <div class="form-group col-md-6">
              <input
                type="email"
                class="form-control"
                name="email"
                id="email"
                placeholder="Email Address"
              />
              <i class="fal fa-envelope"></i>
            </div>
            <div class="form-group col-md-6">
              <input
                type="tel"
                class="form-control"
                name="number"
                id="number"
                placeholder="Phone Number"
              />
              <i class="fal fa-phone"></i>
            </div>
            <div class="form-group col-md-6">
              <select name="subject" id="subject" class="form-select">
                <option value="" disabled selected hidden>
                  Select Service
                </option>
                <option value="Reservation For Birthday">
                  Reservation For Birthday
                </option>
                <option value="Book A Table">Book A Table</option>
                <option value="Food Pre-order">Food Pre-order</option>
                <option value="Request a Quote">Request a Quote</option>
              </select>
              <i class="fal fa-chevron-down"></i>
            </div>
            <div class="form-group col-12">
              <textarea
                name="message"
                id="message"
                cols="30"
                rows="3"
                class="form-control"
                placeholder="Your Message"
              ></textarea>
              <i class="fal fa-comment"></i>
            </div>
            <div class="form-btn text-center col-12">
              <button class="th-btn">Submit Message</button>
            </div>
          </div>
          <p class="form-messages mb-0 mt-3"></p>
        </form>
      </div>
    </section>