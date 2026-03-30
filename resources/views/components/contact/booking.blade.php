<section class="space-bottom contact-form-sec">
    <div class="container">
        <div class="reservation-area">
            <h4 class="sec-title text-center mb-30">Send Us Message</h4>
            <form action="{{ route('contact-us') }}" method="POST" class="booking-form3">
                @csrf
                <div class="row">
                    <div class="form-group col-lg-4 col-md-6">
                        <input type="text" class="form-control rounded-2 @error('name') is-invalid @enderror"
                            id="name" name="name" placeholder="Your Name" value="{{ old('name') }}" required />
                        <i class="far fa-user"></i>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4 col-md-6">
                        <input type="tel" class="form-control rounded-2 @error('number') is-invalid @enderror"
                            id="number" name="number" placeholder="Phone Number" value="{{ old('number') }}" required />
                        <i class="far fa-phone"></i>
                        @error('number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-lg-4">
                        <input type="text" class="form-control rounded-2 @error('subject') is-invalid @enderror"
                            id="subject" name="subject" placeholder="Subject" value="{{ old('subject') }}" required />
                        <i class="far fa-tag"></i>
                        @error('subject')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <textarea name="message" id="message" cols="30" rows="3"
                            class="form-control rounded-2 @error('message') is-invalid @enderror"
                            placeholder="Write Message" required>{{ old('message') }}</textarea>
                        <i class="far fa-comment"></i>
                        @error('message')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-btn text-center">
                    <input class="th-btn " style="border-radius: 10px;" type="submit" value="Send Message">
                        {{-- <i class="fa-solid fa-arrow-right ms-2"></i> --}}
                    </button>
                </div>
                <p class="form-messages mb-0 mt-3"></p>
            </form>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('number');

        if (nameInput) {
            nameInput.addEventListener('input', function () {
                // Remove everything except English letters and spaces
                this.value = this.value.replace(/[^A-Za-z\s]/g, '');
            });
        }

        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                // Remove everything except digits
                this.value = this.value.replace(/\D/g, '');
            });
        }
    });
</script>
