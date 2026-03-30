// Utility Functions
function getAddressCount() {
    let count = $("#addressList .inner_address").length;
    $("#addressCount").text(count);
    console.log("Address count:", count);
    return count;
}

function show_address_error_max() {
    Swal.fire({
        title: "Warning",
        text: "You have reached the maximum count of addresses (3)",
        icon: "warning",
        background: "var(--white-color)",
        confirmButtonColor: "#28a745",
        customClass: {
            popup: "swal-custom-shadow",
        },
    });
}

function getAddressData(addressElement) {
    let $el = $(addressElement);
    return {
        id: $el.attr("data-id") || "",
        street_address:
            $el.find(".card-title").text().split(":")[1]?.trim() || "",
        apartment_suite_unit:
            $el
                .find(".card-text")
                .html()
                .split("<br>")[0]
                ?.split(":")[1]
                ?.trim() || "",
        town_city:
            $el
                .find(".card-text")
                .html()
                .split("<br>")[1]
                ?.split(":")[1]
                ?.trim() || "",
        postcode:
            $el
                .find(".card-text")
                .html()
                .split("<br>")[2]
                ?.split(":")[1]
                ?.trim() || "",
        phone:
            $el
                .find(".card-text")
                .html()
                .split("<br>")[3]
                ?.split(":")[1]
                ?.trim() || "",
    };
}

// Show Address Form
function showAddressForm(addressData = null, addressElement = null) {
    if (getAddressCount() >= 3 && !addressData) {
        show_address_error_max();
        return;
    }

    Swal.fire({
        title: `<span class="custom-swal-title">${
            addressData ? "Edit Address" : "Add New Address"
        }</span>`,
        html: `
          <input type="hidden" id="address_id" value="${
              addressData ? addressData.id : ""
          }" />
          <input type="text" class="swal2-input" id="street_address" placeholder="Street Address" value="${
              addressData ? addressData.street_address : ""
          }" required />

          <input type="text" class="swal2-input" id="town_city" placeholder="Town / City" value="${
              addressData ? addressData.town_city : ""
          }" required />

          <input type="text" inputmode="numeric" pattern="01\\d{9}" maxlength="11" class="swal2-input" id="phone" placeholder="Phone Number (11 digits - Egypt)" value="${
              addressData ? addressData.phone : ""
          }" required />
      `,
        showCancelButton: true,
        confirmButtonText: addressData ? "Update" : "Save",
        confirmButtonColor: "#28a745",
        cancelButtonText: "Cancel",
        preConfirm: () => {
            const street = document
                .getElementById("street_address")
                .value.trim();
            // const suite = document
            //     .getElementById("apartment_suite_unit")
            //     .value.trim();
            const city = document.getElementById("town_city").value.trim();
            // const postcode = document.getElementById("postcode").value.trim();
            const phone = document.getElementById("phone").value.trim();

            const englishOnly = /^[A-Za-z0-9\s\-.,/#]+$/;

            if (!street) {
                Swal.showValidationMessage("Street address cannot be empty.");
                return false;
            }
            if (!englishOnly.test(street)) {
                Swal.showValidationMessage(
                    "Street address must be in English only."
                );
                return false;
            }

            if (!city) {
                Swal.showValidationMessage("Town / City cannot be empty.");
                return false;
            }
            if (!englishOnly.test(city)) {
                Swal.showValidationMessage(
                    "Town / City must be in English only."
                );
                return false;
            }

            // if (!/^\d{5}$/.test(postcode)) {
            //     Swal.showValidationMessage(
            //         "Postcode must be exactly 5 digits."
            //     );
            //     return false;
            // }

            if (!/^01\d{9}$/.test(phone)) {
                Swal.showValidationMessage(
                    "Phone must be a valid 11-digit Egyptian number starting with 01."
                );
                return false;
            }

            return {
                id: document.getElementById("address_id").value.trim(),
                street_address: street,
                apartment_suite_unit: suite,
                town_city: city,
                postcode: postcode,
                phone: phone,
            };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.id) {
                updateAddress(result.value);
            } else {
                addAddress(result.value);
            }
        }
    });
}

// Event Handlers
$(document).on("click", "#addAddressBtn", function () {
    let count = getAddressCount();
    if (count < 3) {
        showAddressForm();
    } else {
        show_address_error_max();
    }
});

$("#addressList").on("click", ".edit-address-btn", function () {
    let addressElement = $(this).closest(".card");
    let addressData = getAddressData(addressElement);
    console.log("Editing Address Data:", addressData);
    showAddressForm(addressData, addressElement);
});

$("#addressList").on("click", ".delete-address-btn", function () {
    let addressElement = $(this).closest(".card");
    deleteAddress(addressElement);
});

// CRUD Functions
function addAddress(data) {
    let count = $("#addressList .inner_address").length;
    $("#addressCount").text(count);

    if (count >= 3) {
        show_address_error_max();
        return 0;
    }

    console.log("Adding Address:", data);

    $.ajax({
        url: "{{ route('addresses.store') }}",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            street_address: data.street_address,
            apartment_suite_unit: data.apartment_suite_unit,
            town_city: data.town_city,
            postcode: data.postcode,
            phone: data.phone,
        },
        success: function (response) {
            console.log("Add Address Response:", response);
            const addressList = $("#addressList");
            const newAddress = $("<div>")
                .addClass("card my-5")
                .attr("data-id", response.id);
            newAddress.html(`
          <div class="card-body border border-secondary">
              <h5 class="card-title">Street Address: ${data.street_address}</h5>
              <p class="card-text">
                  Apartment/Suite/Unit: ${
                      data.apartment_suite_unit || "N/A"
                  } <br>
                  Town/City: ${data.town_city} <br>
                  Postcode: ${data.postcode} <br>
                  Phone: ${data.phone}
              </p>
          </div>
          <div class="card-footer d-flex justify-content-end border border-secondary">
              <button class="btn btn-sm btn-secondary me-2 edit-address-btn" data-id="${
                  response.id
              }">Edit</button>
              <button class="btn btn-sm btn-danger me-2 delete-address-btn" data-id="${
                  response.id
              }">Delete</button>
              <button class="btn btn-sm btn-success me-2" onclick="makeAddressPrimary(${
                  response.id
              }),setSelectedAddress(this.closest('.card'))">Set as Primary</button>
          </div>
        `);
            addressList.append(newAddress);

            Swal.fire({
                title: "Success",
                text: "Address added successfully!",
                icon: "success",
                background: "var(--white-color)",
                confirmButtonColor: "#28a745",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
            window.location.reload();
        },
        error: function (xhr) {
            console.error("Add Address Error:", xhr.responseJSON);
            Swal.fire({
                title: "Error",
                text: xhr.responseJSON?.message || "An error occurred",
                icon: "error",
                background: "var(--white-color)",
                confirmButtonColor: "#d33",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
        },
    });
}

function updateAddress(data) {
    console.log("Updating Address:", data);

    $.ajax({
        url: `/addresses/${data.id}`,
        type: "PUT",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            street_address: data.street_address,
            apartment_suite_unit: data.apartment_suite_unit,
            town_city: data.town_city,
            postcode: data.postcode,
            phone: data.phone,
        },
        success: function (response) {
            console.log("Update Successful:", response);
            let addressElement = $(`#addressList .card[data-id="${data.id}"]`);
            addressElement
                .find(".card-title")
                .text(`Street Address: ${data.street_address}`);
            addressElement.find(".card-text").html(`
          Apartment/Suite/Unit: ${data.apartment_suite_unit || "N/A"} <br>
          Town/City: ${data.town_city} <br>
          Postcode: ${data.postcode} <br>
          Phone: ${data.phone}
        `);

            Swal.fire({
                title: "Success",
                text: "Address updated successfully!",
                icon: "success",
                background: "var(--white-color)",
                confirmButtonColor: "#28a745",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
        },
        error: function (xhr) {
            console.error("Update Error:", xhr.responseJSON);
            Swal.fire({
                title: "Error",
                text: xhr.responseJSON?.message || "An error occurred",
                icon: "error",
                background: "var(--white-color)",
                confirmButtonColor: "#d33",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
        },
    });
}

function deleteAddress(addressElement) {
    let $el = $(addressElement);
    const addressId = $el.attr("data-id");

    Swal.fire({
        title: "Are you sure?",
        text: "This address will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/addresses/${addressId}`,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    console.log("Delete Successful:", response);
                    $el.remove();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Address deleted successfully.",
                        icon: "success",
                        background: "var(--white-color)",
                        confirmButtonColor: "#28a745",
                        customClass: {
                            popup: "swal-custom-shadow",
                        },
                    });
                },
                error: function (xhr) {
                    console.error("Delete Error:", xhr.responseJSON);
                    Swal.fire({
                        title: "Error",
                        text: xhr.responseJSON?.message || "An error occurred",
                        icon: "error",
                        background: "var(--white-color)",
                        confirmButtonColor: "#d33",
                        customClass: {
                            popup: "swal-custom-shadow",
                        },
                    });
                },
            });
        }
    });
}

function makeAddressPrimary(addressId) {
    let url =
        `{{ route('addresses.makePrimary', ['id' => '__id__']) }}`.replace(
            "__id__",
            addressId
        );

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $.get("{{ route('addresses.index') }}", function (data) {
                $("#addressList").html(data.html);
            });

            Swal.fire({
                title: "Success",
                text: response.message,
                icon: "success",
                background: "var(--white-color)",
                confirmButtonColor: "#28a745",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
        },
        error: function (xhr) {
            Swal.fire({
                title: "Error",
                text:
                    xhr.responseJSON?.message ||
                    "Failed to set primary address",
                icon: "error",
                background: "var(--white-color)",
                confirmButtonColor: "#d33",
                customClass: {
                    popup: "swal-custom-shadow",
                },
            });
        },
    });
}
