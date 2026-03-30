<div class="th-cart-wrapper space-top space-extra-bottom">
    <div class="container">
        <!--

 -->

        <div class="woocommerce-notices-wrapper">
            <!--

      <div class="woocommerce-message">Shipping costs updated.</div>
    -->

            <form action="#" class="woocommerce-cart-form">
                <table class="cart_table">
                    <thead>
                        <tr style="background-color: var(--theme-color) !important; " >
                            <th class="cart-col-image" >Image</th>
                            <th class="cart-col-productname">Product Name</th>
                            <th class="cart-col-price">Price</th>
{{--                            <th class="cart-col-remove">Remove</th>--}}
{{--                            <th class="cart-col-dropdown">Details</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        <!-- سيتم إضافة المنتجات هنا ديناميكيًا -->
                    </tbody>
                </table>
            </form>

            <div class="actions">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                    <div class="col-md-4 th-cart-coupon d-flex mb-5 flex-column gap-3">
                        <input type="text" id="coupon-code" class="form-control" placeholder="Coupon Code..." />
                        <button type="button" class="th-btn rounded-2" id="apply-coupon">
                            Apply Coupon
                        </button>
                    </div>
                    <div class="col-12 col-md-8 col-lg-7 col-xl-6">
{{--                        <h2 class="h4 summary-title">Cart Totals</h2>--}}
{{--                        <table class="cart_totals">--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td>Cart Subtotal</td>--}}
{{--                                <td data-title="Cart Subtotal">--}}
{{--                                    <span class="amount"><bdi>500 <span>EGP</span></bdi></span>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            <!-- Taxes Row -->--}}
{{--                            <tr class="taxes">--}}
{{--                                <td>Taxes (14%)</td>--}}
{{--                                <td data-title="Taxes">--}}
{{--                                    <span class="amount"><bdi>8888 <span>EGP</span></bdi></span>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            <tr class="shipping">--}}
{{--                                <td>Shipping and Handling</td>--}}
{{--                                <td data-title="Shipping and Handling">--}}
{{--                                    <ul class="woocommerce-shipping-methods list-unstyled">--}}
{{--                                        <li>--}}
{{--                                            <input type="radio" id="free_shipping" name="shipping_method" class="shipping_method" />--}}
{{--                                            <label for="free_shipping">Free shipping</label>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <input type="radio" id="flat_rate" name="shipping_method" class="shipping_method" checked="checked" />--}}
{{--                                            <label for="flat_rate">Flat rate</label>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}

{{--                            <tfoot>--}}
{{--                            <tr class="order-total">--}}
{{--                                <td>Order Total</td>--}}
{{--                                <td data-title="Total">--}}
{{--                                    <strong><span class="amount"><bdi>570 <span>EGP</span></bdi></span></strong>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tfoot>--}}

{{--                        </table>--}}
                        <div class="wc-proceed-to-checkout text-center mb-30">
                            <a href="#" id="checkout-btn" class="th-btn rounded-2">Proceed to checkout</a>
                        </div>
                    </div>
                    <!--

       <button type="button" class="th-btn rounded-2" id="update-cart">
          Update cart
        </button>
     -->

                </div>
            </div>
        </div>
    </div>
    @push('extra-js')
        <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
        <script>
            window.userId = {!! auth()->check() ? auth()->id() : 'null' !!};

            function getSelectionStorage() {
                return window.EasyCookSelectionStorage || null;
            }

            function getStoredWeekKeys(planData) {
                return getSelectionStorage()?.extractWeekKeys?.(planData)
                    || Object.keys(planData || {}).filter(key => /^week\d+$/i.test(key));
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

            // ---------------------------- new fetchMealData() ----------------------------------

            document.getElementById('checkout-btn').addEventListener('click', async function(e) {
                e.preventDefault();

                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                const subscriptions = collectSubscriptions();

                if (subscriptions.length === 0) {
                    Swal.fire("Error", "No plans selected.", "warning");
                    return;
                }

                try {
                    const response = await sendCheckoutRequest({
                        subscriptions
                    });
                    handleCheckoutResponse(response);
                } catch (error) {
                    console.error("Checkout failed", error);
                }

            });

            /**
             * Turn the nested-week object into a flat array, one row per meal.
             * Result:
             *   {
             *     meal_id : "1400_week1_sunday_breakfast_2_en",
             *     week    : "week1",
             *     day     : "sunday",
             *     meal_type : "breakfast"
             *   }
             */
            function extractMeals(planData) {
                return getSelectionStorage()?.extractMeals?.(planData) || [];
            }


            /**
             * Sends a checkout request to the server.
             * @param {Object} payload - The checkout data.
             * @returns {Promise<Object>} - The response from the server.
             */
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

                return response.json();
            }

            /**
             * Handles the checkout response.
             * @param {Object} data - The response data.
             */
            function handleCheckoutResponse(data) {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    if (data.message?.includes("Validation error") && data.errors?.["meals.0.meal_id"]) {
                        console.error("Invalid meal IDs: ", data.errors);
                        Swal.fire("Meal Error", "Some meals no longer exist. Please refresh your plan.", "error");
                    } else {
                        Swal.fire({
                            title: "You are not logged in!",
                            text: "You must log in first.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Login",
                            background: "var(--white-color)",
                            color: "var(--title-color)"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('login_web') }}";
                            }
                        });
                    }
                }
            }

            // ---------------------------- end new fetchMealData() ----------------------------------



            // --------------------------------------------------------

            document.addEventListener("DOMContentLoaded", () => {
                window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                loadCartTable();
                observeStorageChanges();
            });
            // --------------------------------------------------------

            function observeStorageChanges() {
                window.addEventListener("storage", e => {
                    if (e.key === "customizeMealsSave" || e.key === "standerdPackages") {
                        window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                        loadCartTable();
                    }
                });
                const origSet = localStorage.setItem;
                localStorage.setItem = function(k, v) {
                    origSet.apply(this, arguments);
                    if (k === "customizeMealsSave" || k === "standerdPackages") {
                        window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                        loadCartTable();
                    }
                };
            }
            // --------------------------------------------------------

            async function fetchMealData() {
                try {
                    const r = await fetch("/customized.json");
                    if (!r.ok) throw new Error(r.statusText);
                    return await r.json();
                } catch (err) {
                    console.error("fetchMealData failed", err);
                    return {};
                }
            }
            // --------------------------------------------------------

            async function fetchStanderdData() {
                try {
                    const r = await fetch("/standred_weeks.json");
                    if (!r.ok) throw new Error(r.statusText);
                    return await r.json();
                } catch (err) {
                    console.error("fetchStanderdData failed", err);
                    return {};
                }
            }
            // --------------------------------------------------------


            function renderStandardPlan(planId, pkgObj, standerData) {
                const weekKeys = getStoredWeekKeys(pkgObj);
                let total = Number(pkgObj.price_without_vat ?? pkgObj.Price ?? 0);

                if (!total && weekKeys.length) {
                    weekKeys.forEach((wk) => {
                        total += computeWeekPrice(planId, wk, pkgObj[wk], standerData);
                    });
                }

                appendStandardRow(planId, total);
                return total;
            }


            // --------------------------------------------------------

            function computeWeekPrice(planId, weekKey, weekData, standerData) {
                let price = 0;

                // weekData يُمثّل كائن الأيام داخل هذا الأسبوع:
                // { saturday: { breakfast: "...", lunch: "...", … }, sunday: { … }, … }
                Object.entries(weekData).forEach(([day, mealsObj]) => {
                    // mealsObj = { breakfast: mealId, lunch: mealId, … }
                    Object.entries(mealsObj).forEach(([mealType, mealId]) => {
                        // نجيب المصفوفة الخاصة بهذا mealType من الـ JSON
                        const arr = standerData[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                        // نبحث العنصر الذي يطابق mealId
                        const meal = arr.find(x => x.id === mealId);
                        if (meal && meal.price) {
                            // نجمع السعر (parseFloat لتحويل النص إلى رقم)
                            price += parseFloat(meal.price);
                        }
                    });
                });

                return price;
            }

            // --------------------------------------------------------
            /**

             */
            // --------------------------------------------------------

            function appendStandardRow(planId, price) {
                const tbody = document.querySelector(".cart_table tbody");

                // الصف الرئيسي
                const tr = document.createElement("tr");
                tr.className = "cart_item";
                tr.dataset.packageId = planId;
                tr.dataset.packageType = "stander";
                tr.innerHTML = `
    <td data-title="Product"><img width="91" height="91" src="assets/img/menu/menu_thumb_1_1.png"></td>
    <td data-title="Name">
      <span class="cart-productname">Standerd Plan: ${planId}</span>
    </td>
    <td data-title="Price"><span class="amountstanderd"><bdi>${price} EGP</bdi></span></td>
    <td data-title="Remove">
     <a href="#" class="remove" data-package-id="${planId}" data-package-type="stander" style="display:none;">
       <i class="fal fa-trash-alt"></i>
      </a>
    </td>
    <td data-title="Details">
      <button class="dropdown-btn" data-package-id="${planId}" data-package-type="stander" style="display:none;">▼</button>
    </td>
  `;
                tbody.appendChild(tr);

                // صف التفاصيل (hidden by default)
                const trDetails = document.createElement("tr");
                trDetails.innerHTML = `
    <td colspan="6">
      <div class="meal-details-container" id="details-stander-${planId}" style="display:none;"></div>
    </td>
  `;
                tbody.appendChild(trDetails);
            }

            // --------------------------------------------------------

            async function loadCartTable() {
                const storedMeals = getSelectionStorage()?.readCustomizeStore?.() || {};
                const storedStander = getSelectionStorage()?.readStandardStore?.() || {};
                const mealData = await fetchMealData();
                const standerData = await fetchStanderdData();

                const tbody = document.querySelector(".cart_table tbody");
                const totalBdi = document.querySelector(".order-total .amount bdi");
                const subBdi = document.querySelector(".cart_totals tbody tr:first-child .amount bdi");
                tbody.innerHTML = "";

                let subTotal = 0;

                function renderPkg(planId, weekKeyOrObj, pkgObjOrType, typeMaybe) {
                    let id, weekKey, pkgObj, type;

                    const isCustomize = type === "meals";

                    if (!isCustomize && typeof pkgObjOrType === "object" && Object.keys(pkgObjOrType).some(k => /^week\d+$/i
                            .test(k))) {
                        renderStanderPackageWithTabs(planId, pkgObjOrType);
                        return;
                    }


                    if (typeof weekKeyOrObj === "object") {
                        id = planId;
                        const baseObj = weekKeyOrObj;
                        type = pkgObjOrType;

                        Object.entries(baseObj).forEach(([wk, wkData]) => {
                            if (/^week\d+$/i.test(wk)) {
                                renderPkg(id, wk, wkData, type);
                            }
                        });
                        return;
                    } else {
                        id = `${planId}-${weekKeyOrObj}`;
                        weekKey = weekKeyOrObj;
                        pkgObj = pkgObjOrType;
                        type = typeMaybe;
                    }

                    const weeks = getStoredWeeksCount(pkgObj) || 1;
                    const days = getStoredDaysPerWeek(pkgObj) || (type === "meals" ? 5 : 6);
                    let price = 0;



                    Object.entries(pkgObj).forEach(([day, meals]) => {
                        if (["weeks", "days"].includes(day)) return;

                        Object.entries(meals).forEach(([mealType, mealId]) => {
                            let found = null;

                            if (type === "meals") {
                                const arr = mealData[planId]?.[weekKey]?.[day]?.[mealType]?.en || [];
                                found = arr.find(x => x.id === mealId);
                            } else {
                                const arr = standerData[planId]?.[weekKey]?.[day]?.[mealType]
                                    ?.en || [];
                                found = arr.find(x => x.id === mealId);
                            }

                            if (found && found.price) {
                                price += parseFloat(found.price);
                            }
                        });
                    });



                    totalPrice += price;
                    let priceStr = price.toFixed(2);


                }

                /**
                 * تضيف صف واحد لـ Meal Package
                 */
                function appendCustomizeRow(planId, price) {
                    const tbody = document.querySelector(".cart_table tbody");

                    const tr = document.createElement("tr");
                    tr.className = "cart_item";
                    tr.dataset.packageId = planId;
                    tr.dataset.packageType = "meals";
                    tr.innerHTML = `
    <td data-title="Product">
      <img width="91" height="91"
           src="assets/img/menu/menu_thumb_1_1.png"
           alt="Meal Package">
    </td>
    <td data-title="Name">
      <span class="cart-productname">
        Meal Package: ${planId}
      </span>
    </td>
    <td data-title="Price">
      <span class="amount"><bdi>${price} EGP</bdi></span>
    </td>
    <td data-title="Remove">
      <a href="#" class="remove"
         data-package-id="${planId}"
         data-package-type="meals">
        <i class="fal fa-trash-alt"></i>
      </a>
    </td>
    <td data-title="Details">
      <button class="dropdown-btn"
              data-package-id="${planId}"
              data-package-type="meals">▼</button>
      <div class="dropdown-content"
           id="dropdown-meals-${planId}"></div>
    </td>
  `;
                    tbody.appendChild(tr);

                    const trDetails = document.createElement("tr");
                    trDetails.innerHTML = `
    <td colspan="6">
      <div class="meal-details-container"
           id="details-meals-${planId}"
           style="display:none;"></div>
    </td>`;
                    tbody.appendChild(trDetails);
                }

                // … داخل loadCartTable() بعد تنظيف tbody:

                // 1. رندر الـ Customize كصف واحد لكل خطة
                Object.entries(storedMeals).forEach(([planId, pkg]) => {
                    let total = 0;
                    getStoredWeekKeys(pkg).forEach((weekKey) => {
                        const weekData = pkg[weekKey] || {};
                        Object.entries(weekData).forEach(([day, mealsObj]) => {
                            Object.entries(mealsObj).forEach(([mealType, mealId]) => {
                                const arr = mealData[planId]?.[weekKey]?.[day]?.[mealType]
                                    ?.en || [];
                                const m = arr.find(x => x.id === mealId);
                                if (m && m.price) {
                                    total += parseFloat(m.price);

                                }
                            });
                        });
                    });

                    appendCustomizeRow(planId, total.toFixed(2));
                    subTotal += total;
                });

                // 2. رندر Standard كما قبل
                Object.entries(storedStander).forEach(([planId, pkgObj]) => {
                    const price = renderStandardPlan(planId, pkgObj, standerData);
                    subTotal += price;
                    // renderStandardPlan(planId, pkgObj, standerData);
                });




                subBdi.textContent = `${subTotal.toFixed(2)} EGP`;

                const vat = +(subTotal * 0.14).toFixed(2);
                const total = +(subTotal + vat).toFixed(2);

                document.querySelector(".taxes .amount bdi").textContent = `${vat} EGP`;
                totalBdi.textContent = `${total} EGP`;

                document.querySelectorAll(".dropdown-btn").forEach(btn => {
                    btn.onclick = async e => {
                        e.preventDefault();
                        const pkgId = btn.dataset.packageId;
                        const type = btn.dataset.packageType;
                        const cont = document.getElementById(`details-${type}-${pkgId}`);
                        const open = cont.style.display === "block";

                        document.querySelectorAll(".meal-details-container").forEach(d => d.style.display =
                            "none");
                        document.querySelectorAll(".dropdown-btn").forEach(b => b.textContent = "▼");

                        if (!open) {
                            await populateDetails(type, pkgId);
                            cont.style.display = "block";
                            btn.textContent = "▾";
                        } else {
                            cont.style.display = "none";
                            btn.textContent = "▼";
                        }
                    };
                });

                document.querySelectorAll(".cart_table .remove").forEach(a => {
                    a.onclick = e => {
                        e.preventDefault();
                        const pkgId = a.dataset.packageId;
                        const type = a.dataset.packageType;
                        removeFromCart(pkgId, type);
                    };
                });
            }
            // --------------------------------------------------------

            async function populateDetails(type, pkgId) {
                const cont = document.getElementById(`details-${type}-${pkgId}`);
                cont.innerHTML = "";

                const [planId] = pkgId.split("-"); // ignore weekKey
                const store = type === "meals"
                    ? (getSelectionStorage()?.readCustomizeStore?.() || {})
                    : (getSelectionStorage()?.readStandardStore?.() || {});
                const pkg = store[planId];
                if (!pkg) return;

                const data = type === "meals" ? await fetchMealData() : await fetchStanderdData();
                const weeks = getStoredWeekKeys(pkg);

                // Create tabs
                const tabsWrapper = document.createElement("div");
                tabsWrapper.className = "week-tabs text-center mb-4";

                const tableContainer = document.createElement("div");
                tableContainer.className = "table-wrapper";

                weeks.forEach((weekKey, index) => {
                    const btn = document.createElement("button");
                    btn.type = "button";

                    btn.textContent = `Week ${weekKey.replace("week", "")}`;
                    btn.className = `week-tab px-3 py-1 border ${index === 0 ? "bg-black text-white" : ""}`;
                    btn.style.marginRight = "10px";
                    btn.addEventListener("click", () => {
                        document.querySelectorAll(".week-tab").forEach(b => b.classList.remove("bg-black",
                            "text-white"));
                        btn.classList.add("bg-black", "text-white");
                        renderWeekTable(pkg[weekKey], data, tableContainer, planId, weekKey, type);
                    });
                    tabsWrapper.appendChild(btn);
                });

                cont.appendChild(tabsWrapper);
                cont.appendChild(tableContainer);

                // Render first week by default
                if (weeks.length > 0) {
                    renderWeekTable(pkg[weeks[0]], data, tableContainer, planId, weeks[0], type);
                }
            }
            // --------------------------------------------------------

            function renderWeekTable(weekData, data, tableContainer, planId, weekKey, type) {
                tableContainer.innerHTML = "";

                // 1. الترتيب الثابت لأنواع الوجبات
                const fixedOrder = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];

                // 2. أنشئ الجدول والعناوين
                const table = document.createElement("table");
                table.classList.add("meal-table");

                const thead = document.createElement("thead");
                const headerRow = document.createElement("tr");
                headerRow.innerHTML = `<th>Day</th>`; // العمود الأول

                // إضافة الأعمدة بترتيب ثابت إذا كانت موجودة في البيانات
                fixedOrder.forEach(mt => {
                    // نتأكد إنّ أي يوم يحتوي هذا الـ mealType
                    const exists = Object.values(weekData).some(d => d.hasOwnProperty(mt));
                    if (exists) {
                        const th = document.createElement("th");
                        th.textContent = mt.charAt(0).toUpperCase() + mt.slice(1);
                        headerRow.appendChild(th);
                    }
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                // 3. أنشئ body
                const tbody = document.createElement("tbody");

                Object.keys(weekData).forEach(day => {
                    if (['weeks', 'days', 'numday5', 'numday6'].includes(day)) return;

                    const tr = document.createElement("tr");
                    const tdDay = document.createElement("td");
                    tdDay.textContent = day.charAt(0).toUpperCase() + day.slice(1);
                    tr.appendChild(tdDay);

                    // إضافة الخلايا بترتيب ثابت
                    fixedOrder.forEach(mt => {
                        const td = document.createElement("td");
                        const mealId = weekData[day]?.[mt];
                        if (mealId) {
                            // جلب المعلومة من الـ data
                            const arr = data[planId]?.[weekKey]?.[day]?.[mt]?.en || [];
                            const meal = arr.find(x => x.id === mealId);
                            if (meal) {
                                td.innerHTML = `
            <div style="display:flex; align-items:center;">
              <div class="meal-avatar">
                <img src="${meal.image}" alt="${meal.title}" />
              </div>
              <span>${meal.title}</span>
            </div>`;
                            } else {
                                td.textContent = "-";
                            }
                        } else {
                            td.textContent = "-";
                        }
                        tr.appendChild(td);
                    });

                    tbody.appendChild(tr);
                });

                table.appendChild(tbody);
                tableContainer.appendChild(table);
            }

            // --------------------------------------------------------

            function removeFromCart(pkgId, type) {
                const key = (type === "meals") ? "customizeMealsSave" : "standerdPackages";
                let store = JSON.parse(localStorage.getItem(key)) || {};
                const [planId] = String(pkgId || '').split("-");
                delete store[planId];

                localStorage.setItem(key, JSON.stringify(store));
                loadCartTable();
            }

            // --------------------------------------------------------
        </script>
    @endpush


    <!-- xxxxxxxxxxxxxxxxxxxxx -->


    @push('extra-css')
        <style>
            .meal-table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
                margin: 20px 0;
            }


            .meal-table th,
            .meal-table td {
                border: 2px solid #28a745 !important;
                /* لون أخضر للحدود */
                padding: 10px;
                font-size: 16px;
            }

            /* جعل اليوم والوجبة محددين عند التحديد */
            .day-row.active td,
            .meal-tab.active {
                background-color: #28a745;
                color: white;
                font-weight: bold;
            }

            /* إزالة الخلفية الخضراء */
            .meal-table th,
            .meal-table td {
                background-color: var(--white-color);
            }

            /* تنسيق الأفاتار (الصورة) */
            .meal-avatar {
                display: inline-block;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 8px;
                vertical-align: middle;
            }

            .meal-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* توسيط البيانات في الجدول */
            .meal-cell {
                text-align: center;
                display: none;
                /* إخفاء جميع الوجبات مبدئيًا */
            }

            /* جعل أول وجبة وأول يوم مفعّلين عند التحميل */
            .day-row:first-child {
                background-color: #28a745;
                color: white;
            }

            .meal-cell:first-child {
                display: table-cell;
            }

            /* إضافة تأثير عند التحويل بين الأيام */
            .day-row:hover,
            .meal-tab:hover {
                cursor: pointer;
                opacity: 0.8;
            }

            /* الوضع الفاتح - الجسم أبيض */
            body.light-mode {
                background-color: var(--white-color);
                /* الخلفية البيضاء */
                color: var(--black-color);
            }

            /* تعديل الخلايا في الجدول في الوضع الفاتح */
            body.light-mode .cart_table td,
            body.light-mode .cart_table th {
                background-color: var(--smoke-color2);
                color: var(--black-color);
            }

            /* تعديل الـ tabs باللون الأخضر */
            body .cart-table th,
            body .cart-table td {
                background-color: var(--theme-color);
                color: var(--white-color);
            }

            /* تلوين الأعمدة (Image, Product Name, Price, Remove, Details) */
            body .cart-col-remove,
            body .cart-col-price,
            body .cart-col-productname,
            body .cart-col-image,
            .cart-col-dropdown {
                background-color: var(--theme-color) !important;
                /* اللون الأخضر */
            }

            /* تحسين مظهر زر الدروب داون */
            body .dropdown-btn {
                background-color: var(--theme-color);
                color: var(--black-color);
                border: none;
                padding: 8px 16px;
                /* جعل الزر أصغر */
                border-radius: 5px;
                cursor: pointer;

                text-align: left;
                transition: background-color 0.3s ease;
                margin-top: 10px;
                /* لضمان بقاء الزر في مكانه */
            }


            body {
                font-family: Arial, sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
                cursor: pointer;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            #details {
                margin-top: 20px;
                padding: 10px;
                border: 1px solid #ddd;
                display: none;
            }


            body.dark-mode .dropdown-content {
                /* لون خلفية داكن */
                color: var(--white-color);
            }

            /* إظهار الـ dropdown عند الضغط على الزر */
            body .dropdown-btn.active+.dropdown-content {
                display: block;
            }

            /* تحسين الشكل بشكل عام */


            /* تحسين عرض الصورة داخل الخلايا */
            .cart-productimage img {
                border-radius: 8px;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* تحسين خلفية الـ "Details" */
            body.light-mode .cart-col-remove {
                background-color: var(--theme-color);
                /* اللون الأخضر للزر */
                color: var(--white-color);
            }

            /* تخصيص باقي العناصر */
            .meal-card {

                padding: 15px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
                border: 1px solid #444;
                transition: transform 0.2s ease;
            }

            .meal-card:hover {
                transform: scale(1.05);
            }

            .meal-image {
                border-radius: 8px;
                margin-bottom: 10px;
            }

            .meal-title {
                font-size: 18px;
                font-weight: bold;
                color: var(--white-color);
            }

            .meal-description {
                font-size: 14px;
                color: var(--light-color);
            }

            /* تقليل حجم الـ dropdown */


            /* تحسين عرض الـ Package */
            .cart-productimage img {
                border-radius: 8px;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* لتعديل الـ dropdown ليبقى أسفل الزر فقط */
            .dropdown-container {
                position: relative;
            }

            /* تخصيص الـ Details في الوضع الداكن */
            body.dark-mode .meal-card {
                background: #2d2d2d;
                /* تغيير الخلفية في الوضع الداكن */
            }

            td.day-saturday {
                background-color: #ffe5e5 !important;
            }

            td.day-sunday {
                background-color: #e6f7ff !important;
            }

            td.day-monday {
                background-color: #f0fff0 !important;
            }

            td.day-tuesday {
                background-color: #fffbe6 !important;
            }

            td.day-wednesday {
                background-color: #f9f0ff !important;
            }

            td.day-thursday {
                background-color: #e6fffb !important;
            }
        </style>
    @endpush
