<div class="sidemenu-wrapper d-block">
    <div class="sidemenu-content">
        <button class="closeButton sideMenuCls">
            <i style="color: var(--theme-color);" class="far fa-times"></i>
        </button>
        <div class="widget woocommerce widget_shopping_cart">
            <h3 class="widget_title">Saved Meals</h3>
            <div class="widget_shopping_cart_content">
                <div id="saved-meals-container"></div>
            </div>
        </div>
        <p class="woocommerce-mini-cart__total total">
        </p>
        <p id="mini-cart-button" class="woocommerce-mini-cart__buttons buttons text-center">
            <a class="th-btn wc-forward" href="{{ route('checkout.show') }}">Subscribe</a>
        </p>
    </div>
</div>



@push('extra-js')

<script>
    window.userId = {!! auth()->check() ? auth()->id() : 'null' !!};

    function getSelectionStorage() {
        return window.EasyCookSelectionStorage || null;
    }

    function getStoredWeekKeys(planData) {
        return getSelectionStorage()?.extractWeekKeys?.(planData)
            || Object.keys(planData || {}).filter((key) => /^week\d+$/i.test(key));
    }

    function getStoredWeeksCount(planData) {
        return getSelectionStorage()?.resolveStoredWeeksCount?.(planData) || 0;
    }

    function getStoredDaysPerWeek(planData) {
        return getSelectionStorage()?.resolveStoredDaysPerWeek?.(planData) || 0;
    }

    function collectSubscriptions() {
        const subscriptions = getSelectionStorage()?.collectSubscriptions?.(window.userId);
        return Array.isArray(subscriptions) ? subscriptions : [];
    }

    const payNowButton = document.getElementById('pay-now');

    payNowButton?.addEventListener('click', async function (e) {
        // console.log("Pay Now button clicked");
        e.preventDefault();

        if (window.userId === null) {
            Swal.fire({
                title: "You are not logged in!",
                text: "Please log in to complete your payment.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Login",
                background: "var(--white-color)",
                color: "var(--title-color)"
            }).then((res) => {
                if (res.isConfirmed) {
                    window.location.href = "{{ route('login_web') }}";
                }
            });
              setTimeout(() => {
                window.location.href = "{{ route('login_web') }}";
            }, 3000);
            return;
        }

        const subscriptions = collectSubscriptions();

        if (subscriptions.length === 0) {
            Swal.fire("Error", "No plans selected.", "warning");
            return;
        }

        try {
            const response = await sendCheckoutRequest({ subscriptions });

            if (response?.status === 'success' && response.subscriptions) {
                const subscriptionIds = response.subscriptions;

                try {
                    const payResponse = await fetch("{{ route('payment.checkout') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ subscription_ids: subscriptionIds })
                    });

                    if (payResponse.status === 401) {
                        Swal.fire({
                            title: "You are not logged in!",
                            text: "Please log in to complete your payment.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Login",
                            background: "var(--white-color)",
                            color: "var(--title-color)"
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.href = "{{ route('login_web') }}";
                            }
                        });
                        return;
                    }

                    const result = await payResponse.json();

                    if (result.status === 'success') {
                        window.location.href = result.payment_url; // ✅ or inject iframe
                    } else {
                        Swal.fire("Error", result.message || "Payment failed.", "error");
                    }

                } catch (err) {
                    // console.error("Paymob Checkout Error:", err);
                    Swal.fire("Error", "Server error while creating payment intention.", "error");
                }

            } else {
                Swal.fire("Error", "Could not process subscription.", "error");
            }

        } catch (error) {
            // console.error("Checkout failed:", error);
            Swal.fire("Error", "Failed to create subscription. Please try again.", "error");
        }
    });

    function extractMeals(planData) {
        return getSelectionStorage()?.extractMeals?.(planData) || [];
    }

    async function sendCheckoutRequest(payload) {
        const response = await fetch("{{ route('checkout') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            console.error("Invalid JSON:", text);
            return null;
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
        renderAllPackages();
        observeAllStorageChanges();
        toggleMiniCartButton(); // ← Check initially
        observeStorageForMiniCartToggle();
    });

    function toggleMiniCartButton() {
        const btnWrapper = document.getElementById("mini-cart-button");
        const hasStander = Object.keys(getSelectionStorage()?.readStandardStore?.() || {}).length > 0;
        const hasCustomize = Object.keys(getSelectionStorage()?.readCustomizeStore?.() || {}).length > 0;

        btnWrapper.style.display = (hasStander || hasCustomize) ? "block" : "none";
    }




    function observeStorageForMiniCartToggle() {
        // عند التغيير من تبويب تاني
        window.addEventListener("storage", (e) => {
            if (e.key === "customizeMealsSave" || e.key === "standerdPackages") {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                toggleMiniCartButton();
            }
        });

        // عند التغيير داخل نفس الصفحة
        const origSet = localStorage.setItem;
        localStorage.setItem = function(k, v) {
            origSet.apply(this, arguments);
            if (k === "customizeMealsSave" || k === "standerdPackages") {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                toggleMiniCartButton();
            }
        };
    }



    function observeAllStorageChanges() {
        window.addEventListener("storage", e => {
            if (e.key === "standerdPackages" || e.key === "customizeMealsSave") {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                renderAllPackages();
            }
        });
        const origSet = localStorage.setItem;
        localStorage.setItem = function(k, v) {
            origSet.apply(this, arguments);
            if (k === "standerdPackages" || k === "customizeMealsSave") {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                renderAllPackages();
            }
        };
    }

    async function fetchJSON(path) {
        try {
            const res = await fetch(path);
            if (!res.ok) throw new Error(res.statusText);
            return await res.json();
        } catch (err) {
            console.error(`fetch ${path} failed:`, err);
            return {};
        }
    }

    async function renderAllPackages() {
        const custStore = getSelectionStorage()?.readCustomizeStore?.() || {};
        const standStore = getSelectionStorage()?.readStandardStore?.() || {};
        const dataMeals = await fetchJSON("/customized.json");
        const dataStander = await fetchJSON("/standred_weeks.json");

        const container = document.getElementById("saved-meals-container");
        // const priceBox    = document.getElementById("total-price");
        container.innerHTML = "";
        let totalPrice = 0;


        Object.entries(custStore).forEach(([planId, pkg]) => {
            let price = 0;
            getStoredWeekKeys(pkg).forEach((weekKey) => {
                const weekData = pkg[weekKey] || {};
                Object.entries(weekData).forEach(([day, mealsObj]) => {
                    Object.entries(mealsObj).forEach(([mealType, mealId]) => {
                        const arr = dataMeals[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                        const m = arr.find(x => x.id === mealId);
                        if (m && m.price) price += parseFloat(m.price);
                    });
                });
            });
            totalPrice += price;

            // 2. انشاء الحاوية الرئيسية
            const pkgDiv = document.createElement("div");
            pkgDiv.className = "package-container mb-4";

            // 3. الهيدر مع التوغل
            const hdr = document.createElement("h4");
            hdr.style.cursor = "pointer";
            hdr.className = "fs-3 fw-medium";
            hdr.innerHTML = `<span class="toggle-arrow mr-2 fs-3">▶</span> Customize ${planId} — ${price.toFixed(2)} EGP`;
            pkgDiv.appendChild(hdr);

            const weeksCount = getStoredWeeksCount(pkg);
            const weekKeys = getStoredWeekKeys(pkg);
            const firstWeekKey = weekKeys[0];
            const firstWeekData = pkg[firstWeekKey] || {};
            const daysCount = Object.keys(firstWeekData).length;
            const info = document.createElement("p");
            info.innerHTML = `<strong>Weeks:</strong> ${weeksCount}, <strong>Days:</strong> ${getStoredDaysPerWeek(pkg) || daysCount}`;
            pkgDiv.appendChild(info);

            // 5. قائمة الأسابيع القابلة للطي
            const weekList = document.createElement("ul");
            weekList.className = "day-list pl-4";
            weekList.style.display = "none";
            pkgDiv.appendChild(weekList);

            hdr.addEventListener("click", () => {
                const open = weekList.style.display === "block";
                weekList.style.display = open ? "none" : "block";
                hdr.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
            });

            getStoredWeekKeys(pkg).forEach((weekKey) => {
                const weekData = pkg[weekKey] || {};
                const liWeek = document.createElement("li");
                const weekHeader = document.createElement("div");
                weekHeader.style.cursor = "pointer";
                weekHeader.innerHTML = `<span class="toggle-arrow mr-2">▶</span><strong>${weekKey.charAt(0).toUpperCase()+weekKey.slice(1)}</strong>`;
                liWeek.appendChild(weekHeader);

                const weekUl = document.createElement("ul");
                weekUl.className = "ml-4";
                weekUl.style.display = "none";
                liWeek.appendChild(weekUl);

                weekHeader.addEventListener("click", () => {
                    const allWeekUls = pkgDiv.querySelectorAll("ul.ml-4");
                    allWeekUls.forEach(ul => {
                        if (ul !== weekUl) {
                            ul.style.display = "none";
                            const hdr = ul.previousElementSibling;
                            hdr.querySelector(".toggle-arrow").textContent = "▶";
                        }
                    });
                    const open = weekUl.style.display === "block";
                    weekUl.style.display = open ? "none" : "block";
                    weekHeader.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
                });

                Object.entries(weekData).forEach(([day, mealsObj]) => {
                    const liDay = document.createElement("li");
                    const dayHdr = document.createElement("div");
                    dayHdr.style.cursor = "pointer";
                    dayHdr.innerHTML = `<span class="toggle-arrow ms-4">▶</span><strong>${day.charAt(0).toUpperCase()+day.slice(1)}</strong>`;
                    liDay.appendChild(dayHdr);

                    const mealList = document.createElement("ul");
                    mealList.className = "meal-list pl-6";
                    mealList.style.display = "none";
                    liDay.appendChild(mealList);

                    dayHdr.addEventListener("click", () => {
                        const allMealLists = weekUl.querySelectorAll("ul.meal-list");
                        allMealLists.forEach(list => {
                            if (list !== mealList) {
                                list.style.display = "none";
                                const hdr = list.previousElementSibling;
                                hdr.querySelector(".toggle-arrow").textContent = "▶";
                            }
                        });
                        const open = mealList.style.display === "block";
                        mealList.style.display = open ? "none" : "block";
                        dayHdr.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
                    });

                    // 8. قائمة الوجبات
                    Object.entries(mealsObj).forEach(([mealType, mealId]) => {
                        const arr = dataMeals[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                        const m = arr.find(x => x.id === mealId);
                        if (!m) return;
                        const liM = document.createElement("li");
                        liM.className = "flex items-center mb-1 ms-5 ";
                        liM.innerHTML = `
                         <span><strong>${mealType.charAt(0).toUpperCase()+mealType.slice(1)}:</strong> ${m.title}</span>`;
                        mealList.appendChild(liM);
                    });

                    weekUl.appendChild(liDay);
                });

                weekList.appendChild(liWeek);
            });

            container.appendChild(pkgDiv);
        });

        Object.entries(standStore).forEach(([planId, pkg]) => {
            const weeks = getStoredWeeksCount(pkg);
            const days = getStoredDaysPerWeek(pkg) || 6;
            const price = Number(pkg.price_without_vat ?? pkg.Price ?? 0);

            const pkgDiv = document.createElement("div");
            pkgDiv.className = "package-container mb-4";

            const hdr = document.createElement("h4");
            hdr.style.cursor = "pointer";
            hdr.className = "fs-3 fw-medium";
            hdr.innerHTML = `<span class="toggle-arrow mr-2 ">▶</span> ${pkg.packageName || 'Unnamed Package'}`;
            pkgDiv.appendChild(hdr);

            const info = document.createElement("p");
            info.innerHTML = `<strong>Weeks:</strong> ${weeks}, <strong>Days:</strong> ${days}`;
            pkgDiv.appendChild(info);

            const dayList = document.createElement("ul");
            dayList.className = "day-list pl-4";
            dayList.style.display = "none";
            pkgDiv.appendChild(dayList);

            hdr.addEventListener("click", () => {
                const open = dayList.style.display === "block";
                dayList.style.display = open ? "none" : "block";
                hdr.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
            });

            getStoredWeekKeys(pkg).forEach((weekKey) => {
                const weekData = pkg[weekKey] || {};
                const liWeek = document.createElement("li");
                const weekList = document.createElement("ul");
                weekList.style.display = "none";
                weekList.className = "ml-4";

                const weekHeader = document.createElement("div");
                weekHeader.style.cursor = "pointer";
                weekHeader.innerHTML = `<span class="toggle-arrow mr-2">▶</span><strong>${weekKey.charAt(0).toUpperCase() + weekKey.slice(1)}</strong>`;
                liWeek.appendChild(weekHeader);
                liWeek.appendChild(weekList);

                weekHeader.addEventListener("click", () => {
                    const allWeekLists = pkgDiv.querySelectorAll("ul.ml-4");
                    allWeekLists.forEach(wl => {
                        if (wl !== weekList) {
                            wl.style.display = "none";
                            const hdr = wl.previousElementSibling;
                            hdr.querySelector(".toggle-arrow").textContent = "▶";
                        }
                    });
                    const open = weekList.style.display === "block";
                    weekList.style.display = open ? "none" : "block";
                    weekHeader.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
                });




                // 👇 داخل الأسبوع، كرر الأيام والوجبات
                Object.entries(weekData).forEach(([day, dayObj]) => {
                    const liDay = document.createElement("li");
                    const dayHdr = document.createElement("div");
                    dayHdr.style.cursor = "pointer";
                    dayHdr.innerHTML = `<span class="toggle-arrow ms-4">▶</span><strong>${day.charAt(0).toUpperCase() + day.slice(1)}</strong>`;
                    liDay.appendChild(dayHdr);

                    const mealList = document.createElement("ul");
                    mealList.className = "meal-list pl-6";
                    mealList.style.display = "none";
                    liDay.appendChild(mealList);

                    dayHdr.addEventListener("click", () => {
                        const allMealLists = weekList.querySelectorAll("ul.meal-list");
                        allMealLists.forEach(ml => {
                            if (ml !== mealList) {
                                ml.style.display = "none";
                                const hdr = ml.previousElementSibling;
                                hdr.querySelector(".toggle-arrow").textContent = "▶";
                            }
                        });
                        const open = mealList.style.display === "block";
                        mealList.style.display = open ? "none" : "block";
                        dayHdr.querySelector(".toggle-arrow").textContent = open ? "▶" : "▼";
                    });

                    Object.entries(dayObj).forEach(([mealType, mealId]) => {
                        const arr = dataStander[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                        const meal = arr.find(m => m.id === mealId);
                        if (!meal) return;

                        const liM = document.createElement("li");
                        liM.className = "flex items-center mb-1 ms-5";
                        liM.innerHTML = `
        <span><strong>${mealType.charAt(0).toUpperCase() + mealType.slice(1)}:</strong> ${meal.title}</span>`;
                        mealList.appendChild(liM);
                    });

                    weekList.appendChild(liDay);
                });

                dayList.appendChild(liWeek);
            });

            container.appendChild(pkgDiv);
        });


    }
</script>
@endpush


@push('extra-css')
<style>
    /* .toggle-arrow {
  font-size: 16px;
  font-weight: bold;
}

.package-container {
  margin-bottom: 10px;
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.day-list,
.meal-list {
  margin-left: 20px;
  list-style-type: none;
  padding: 0;
} */
</style>
@endpush
