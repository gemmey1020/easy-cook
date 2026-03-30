@push('extra-css')
    <style>

    </style>
@endpush


<div class="max-w-5xl mx-auto">
    <!-- Cards -->
    <div class="flex justify-center m-3 " id="card-container">
        <!-- <div class="scene "  data-type="customize" onclick="handleCustomize(this)">
                <div class="cube">
                    <span class="side top">Customize</span>
                    <span class="side front">Customize</span>
                </div>
            </div> -->

        <div class="scene" data-type="stander" id="standard-card">
            <div class="cube">
            <span class="side top">Lifestyle Plan</span>
            <span class="side front">Lifestyle Plan</span>
            </div>
        </div>
    </div>
    <!-- Tabs for Calorie Categories -->
    <div class="flex flex-wrap justify-center  border-gray-300 mb-6 mt-2" id="plans">
        <button id="level-1400"
        class="tab-btn py-2 px-6 text-lg font-bold actives"
        data-tab="1400">
        Weight Loss <br> 1300 - 1600kcal
        </button>
        <button id="level-1700"
        class="tab-btn py-2 px-6 text-lg font-bold"
        data-tab="1700"

        >
        Fitness <br> 1600 - 1900kcal
        </button>
        <button id="level-2000"
        class="tab-btn py-2 px-6 text-lg font-bold"
        data-tab="2000"
        >
        Weight Gain <br> 1900 - 2200kcal
        </button>
    </div>
    <div class="tab-content" id="main-meal-tab">
        <div class="tab-content">
        <!-- 1400kcal Tab -->
        <div id="tab-1400" class="tab-pane">
            <h2 class="text-xl font-bold text-center mb-4">Choose Your Subscription</h2>
            <div id="stander-plan-length-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 max-w-3xl mx-auto">
            @if($threeWeekUiEnabled ?? false)
                <button
                type="button"
                class="plan-length-card border rounded-2xl px-5 py-4 text-left transition-all hover:bg-green-600 hover:text-white"
                data-weeks="3">
                <span class="block text-lg font-bold">3 Weeks</span>
                <span class="block text-sm opacity-80">18 Days or 15 Days</span>
                </button>
            @endif
                <button
                type="button"
                class="plan-length-card border rounded-2xl px-5 py-4 text-left bg-green-600 text-white transition-all"
                data-weeks="4">
                <span class="block text-lg font-bold">Monthly</span>
                <span class="block text-sm opacity-80">24 Days or 20 Days</span>
                </button>
            </div>
            <h2 class="text-xl font-bold text-center mb-4">Choose Delivery Days</h2>
            <div id="stander-duration-container" class="flex flex-wrap justify-center mb-4">
            <button class="duration-btn py-1 px-4 mx-2 border rounded-full bg-green-600 text-white position-relative "
                data-duration="6" id="1st_6day">
                <div class="duration-icon  number-test-duration-6 position-absolute text-white bg-green-600 px-1 border rounded-full"
                style="top:-22px;left: 0;"> ✔</div>
                <span class="duration-label-text" data-days="6">24 Days</span>
            </button>
            <button class="duration-btn py-1 px-4 mx-2 border rounded-full hover:bg-green-600 position-relative"
                data-duration="5">
                <div
                class=" duration-icon  number-test-duration-5  hidden position-absolute text-white bg-green-600 px-1 border rounded-full"
                style="top:-22px;left: 0;"> ✔ </div>
                <span class="duration-label-text" data-days="5">20 Days</span>
            </button>
            </div>
            <h2 class="text-xl font-bold text-center mb-4">View a Week</h2>
            <div class="flex flex-wrap justify-center mb-4 sm:gap-5 gap-4" id="stander-weeks-container">
            <button class=" relative tap-week py-1 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600"
                week-duration="1">
                1st week
            </button>
            <button class=" relative tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="2">

                2nd week
            </button>
            <button class=" relative tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="3">

                3rd week
            </button>
            <button class="relative tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="4">

                4th week
            </button>
            </div>
            <div class="days-content text-center">
            <h3 class="text-lg font-bold text-center mb-4">View a Day</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center ">
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600  "
                data-day="saturday" id="1st_day">
                Saturday
                </button>
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-day="sunday">
                Sunday
                </button>
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-day="monday">
                Monday
                </button>
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-day="tuesday">
                Tuesday
                </button>
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-day="wednesday">
                Wednesday
                </button>
                <button class="day-option relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-day="thursday">
                Thursday
                </button>
            </div>
            </div>
            <div class="meals-content mt-6">
            <h3 class="text-lg font-bold text-center mb-4">View a Meal</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center">
                <button class="meal-btn relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-meal="breakfast"
                id="1st_meal">
                Breakfast
                </button>
                <button class="meal-btn relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600  " data-meal="lunch">
                Lunch
                </button>
                <button class="meal-btn relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-meal="dinner">
                Dinner
                </button>
                <button class="meal-btn relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-meal="snack1">
                Snack 1
                </button>
                <button class="meal-btn relative tap-week py-2 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600" data-meal="snack2">
                Snack 2
                </button>
            </div>
            <div id="stander-meal-accordion"
                class="meal-section flex flex-row-reverse gap-4 meal-options-accordion items-center justify-center">
            </div>
            </div>
        </div>
        </div>
        <div id="standerConfirmOverlay" class="overlaystander"></div>
        <div id="standerConfirmModal" class="menuWrapper">
        <div class="corner1"></div>
        <div class="corner2"></div>
        <div class="corner3"></div>
        <div class="corner4"></div>
        <div class="menu">
            <div class="middleDiv">
            <span style=" padding: 1em; background-color: #fff;box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
                        text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5); text-align: center;  font-size: x-large;color: #34D06E;width: 75%;
                "></span>
            </div>

            <div class="bottomDiv">
            <div class="buttonsdays">
                <label class="yes" for="yesCheck">
                <input class="yesCheck" type="radio" />
                <span>Yes</span>
                <div class="cornerBtn11"></div>
                <div class="cornerBtn12"></div>
                <div class="cornerBtn13"></div>
                <div class="cornerBtn14"></div>
                </label>
            </div>
            </div>
        </div>
        </div>
        <div class="main">
        <input hidden="" class="yesCheck" id="yesCheck" name="toggle" type="radio" />
        <input hidden="" class="noCheck" id="noCheck" name="toggle" type="radio" />
        <input hidden="" class="open" name="toggle" type="radio" id="openMenu" />
        <div class="menuOpenBtns">
            <div class="selectionBtns">
            <label class="open" for="openMenu">
                <div class="clickSVG meal-btn flex justify-center align-items-center border rounded bg-green-600 text-white position-relative">
                <h3 class="text-white text-center">Order Now</h3>
                </div>
                <div class="cornerCBtn1"></div>
                <div class="cornerCBtn2"></div>
                <div class="cornerCBtn3"></div>
                <div class="cornerCBtn4"></div>
            </label>
            </div>
        </div>
        <div class="overlaystander"></div>
            <div class="menuWrapper">
                <div class="corner1"></div>
                <div class="corner2"></div>
                <div class="corner3"></div>
                <div class="corner4"></div>
                <div class="menu">
                    <div class="middleDiv">
                        <span style=" padding: 1em; background-color: #fff;  box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
                            text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5);  font-size: x-large;  color: #34D06E;" id="prices">
                        </span>
                    </div>
                    <div class="bottomDiv">
                        <div class="buttons">
                        <label id="standerConfirmYes" class="yes" for="yesCheck">
                            <input class="yesCheck" type="radio" />
                            <span>Next</span>
                            <div class="cornerBtn11"></div>
                            <div class="cornerBtn12"></div>
                            <div class="cornerBtn13"></div>
                            <div class="cornerBtn14"></div>
                        </label>
                        <label class="no" for="noCheck">
                            <input class="noCheck" type="radio" />
                            <span>Back</span>
                            <div class="cornerBtn21"></div>
                            <div class="cornerBtn22"></div>
                            <div class="cornerBtn23"></div>
                            <div class="cornerBtn24"></div>
                        </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- Blok Customize (نسخة من الـ Stander) -->

    <!--
    <div class="tab-content hidden" id="main-meal-tab-customize">
        <div class="tab-content">
            <div id="tab-1400" class="tab-pane ">
                <h2 class="text-xl font-bold text-center mb-4">Select Duration</h2>
                <div class="flex flex-wrap justify-center mb-4">
                <button class="duration-btn py-1 px-4 mx-2 border rounded-full bg-green-600 text-white position-relative"
                    data-duration="6" id="1st_6day">
                    <div class=" number-test-duration-6   position-absolute text-white bg-green-600 px-1 border rounded-full"
                    style="top:-22px;left: 0;"> 0/6</div> 24 Days
                </button>
                <button class="duration-btn py-1 px-4 mx-2 border rounded-full hover:bg-green-600 position-relative"
                    data-duration="5">
                    <div
                    class=" number-test-duration-5  hidden position-absolute text-white bg-green-600 px-1 border rounded-full"
                    style="top:-22px;left: 0;"> 0/5 </div> 20 Days
                    </button>
                </div>
                <h2 class="text-xl font-bold text-center mb-4">Select a Weeks</h2>
                <div class="flex flex-wrap justify-center mb-4 sm:gap-5 gap-4">
                    <button class="relative tap-week py-1 px-4 mx-2 border rounded-full bg-green-600 text-white hover:bg-green-600"
                        week-duration="1">
                        <div class=" week-test   position-absolute text-white bg-green-600 px-1 border rounded-full hidden  "
                            style="top:-22px;left: 0;">✔</div>
                        1 weeks
                    </button>c
                    <button class="relative tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="2">
                        <div class=" week-test   position-absolute text-white bg-green-600 px-1 border rounded-full hidden  "
                            style="top:-22px;left: 0;">✔</div>
                        2 weeks
                    </button>
                    <button class="relative tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="3">
                        <div class=" week-test   position-absolute text-white bg-green-600 px-1 border rounded-full hidden "
                            style="top:-22px;left: 0;">✔</div>
                        3 weeks
                    </button>
                    <button class=" relative  tap-week py-1 px-4 mx-2 border rounded-full hover:bg-green-600" week-duration="4">
                        <div class=" week-test   position-absolute text-white bg-green-600 px-1 border rounded-full hidden "
                            style="top:-22px;left: 0;">✔</div>
                        4 weeks
                    </button>
                </div>
                <div class="days-content text-center mb-4">
                    <h3 class="text-lg font-bold mb-4">Select a Day (Customize)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 justify-center">
                        <button class="day-option-customize py-2 border rounded bg-green-600 text-white position-relative  "
                        data-day="saturday" >
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full  "
                            style="top:-22px;left: 0;">0/5</div> Saturday
                        </button>
                        <button class="day-option-customize py-2 border rounded position-relative" data-day="sunday">
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                            style="top:-22px;left: 0;">0/5</div> Sunday
                        </button>
                        <button class="day-option-customize py-2 border rounded position-relative" data-day="monday">
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                            style="top:-22px;left: 0;">0/5</div> Monday
                        </button>
                        <button class="day-option-customize py-2 border rounded position-relative" data-day="tuesday">
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                            style="top:-22px;left: 0;">0/5</div> Tuesday
                        </button>
                        <button class="day-option-customize py-2 border rounded position-relative" data-day="wednesday">
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                            style="top:-22px;left: 0;">0/5</div> Wednesday
                        </button>
                        <button class="day-option-customize py-2 border rounded position-relative" data-day="thursday">
                        <div class=" number-test-day   position-absolute text-white bg-green-600 px-1 border rounded-full "
                            style="top:-22px;left: 0;">0/5</div> Thursday
                        </button>
                    </div>
                </div>
                <div class="meals-content text-center mb-4">
                    <h3 class="text-lg font-bold mb-4">Select a Meal (Customize)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="breakfast">  <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
                                style="top:-22px;left: 0;"> ✔</div>
                            Breakfast
                        </button>
                        <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="lunch"> <div class=" number-test-Meal  position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
                                style="top:-22px;left: 0;"> ✔</div>
                            Lunch
                        </button>
                        <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="dinner"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
                                style="top:-22px;left: 0;"> ✔</div>
                            Dinner
                        </button>
                        <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="snack1"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
                                style="top:-22px;left: 0;"> ✔</div>
                            Snack 1
                        </button>
                        <button class="meal-btn-customize position-relative py-2 border rounded" data-meal="snack2"> <div class=" number-test-Meal   position-absolute text-white bg-green-600 px-1 border rounded-full hidden"
                                style="top:-22px;left: 0;"> ✔</div>
                            Snack 2
                        </button>
                    </div>
                </div>
                <div id="meal-options-accordion-customize" class="meal-sectioncast grid grid-cols-1 sm:grid-cols-3 md:grid-cols-2 gap-4 justify-center"></div>
            </div>
        </div>
        <div id="customizeConfirmOverlay" class="overlaystander"></div>
            <div id="customizeConfirmMenuWrapper" class="menuWrapper">
                <div class="corner1"></div>
                <div class="corner2"></div>
                <div class="corner3"></div>
                <div class="corner4"></div>
                <div class="menu">
                    <div class="middleDiv">
                        <span style="padding: 1em;
                            background-color: #fff;
                            box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
                            text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5);
                            text-align: center;
                            font-size: x-large;
                            color: #34D06E;
                            "></span>
                    </div>
                    <div class="bottomDiv">
                    <div class="buttons" style="justify-content: center;">
                        <label class="yes"><span>Yes</span>
                            <div class="cornerBtn11"></div>
                            <div class="cornerBtn12"></div>
                            <div class="cornerBtn13"></div>
                            <div class="cornerBtn14"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <input hidden="" class="yesCheck" id="yesCheckcast" name="toggleCustomize" type="radio" />
            <input hidden="" class="noCheck" id="noCheckcast" name="toggleCustomize" type="radio" />
            <input hidden="" class="open" name="toggleCustomize" type="radio" id="openMenuCustomize" />
            <div class="menuOpenBtns">
                <div class="selectionBtns">
                <label class="open" for="openMenuCustomize">
                <div onclick="handleCustomizeSaveClick()" class="clickSVG meal-btn py-2 border rounded bg-green-600 text-white position-relative"> <h1 class="text-white text-center">save</h1></div>
                    <div class="cornerCBtn1"></div>
                    <div class="cornerCBtn2"></div>
                    <div class="cornerCBtn3"></div>
                    <div class="cornerCBtn4"></div>
                </label>
                </div>
            </div>
            <div class="overlaystander"></div>
            <div class="menuWrapper">
                <div class="corner1"></div>
                <div class="corner2"></div>
                <div class="corner3"></div>
                <div class="corner4"></div>
                <div class="menu">
                    <div class="middleDiv">
                        <span id="textmasdge" style=" padding: 1em; background-color: #fff;  box-shadow: 0 4px 8px rgba(0, 255, 0, 0.3);
                            text-shadow: 0 7px 10px rgba(0, 255, 0, 0.5);  font-size: x-large;  color: #34D06E; text-align: center;"
                                >Are you sure you want to  choose this option?</span>
                    </div>
                    <div class="bottomDiv">
                        <div class="buttons">
                        <label id="castConfirmYes" class="yes " for="yesCheckcast">
                            <input class="yesCheck" type="radio" />
                            <span>Yes</span>
                            <div class="cornerBtn11"></div>
                            <div class="cornerBtn12"></div>
                            <div class="cornerBtn13"></div>
                            <div class="cornerBtn14"></div>
                        </label>
                        <label id="castConfirmNo" class="no" for="noCheckcast">
                            <input class="noCheck" type="radio" />
                            <span>No</span>
                            <div class="cornerBtn21"></div>
                            <div class="cornerBtn22"></div>
                            <div class="cornerBtn23"></div>
                            <div class="cornerBtn24"></div>
                        </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
  <!-- ============================= -->

</div>
@push('extra-js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    window.plansPricingCatalog = @json($plansPricingCatalog ?? []);
    window.threeWeekUiEnabled = @json($threeWeekUiEnabled ?? false);
    window.subscriptionVatRate = @json($vatRate ?? 0.14);

    // ====== Stander-only Script ======
    let selectedLevel = '1400';
    let currentStanderWeekData = null;
    let selectedStanderDurationDays = 6;
    let selectedStanderDurationWeeks = 4;
    let selectedStanderWeekCount = 1;
    let selectedLanguage = 'en';
    let selectedStanderDay = 'saturday';
    let selectedStanderMeal = 'breakfast';

    function resolvePlansTier(level, weeksCount, daysPerWeek) {
        const planCatalog = window.plansPricingCatalog?.[String(level)]
            || window.plansPricingCatalog?.[Number(level)];
        const weeksCatalog = planCatalog?.[String(weeksCount)]
            || planCatalog?.[Number(weeksCount)];

        return weeksCatalog?.[String(daysPerWeek)]
            || weeksCatalog?.[Number(daysPerWeek)]
            || null;
    }

    function resolveSubscriptionSubtotal(level, daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
        const tier = resolvePlansTier(level, weeksCount, daysPerWeek);

        if (!tier) {
            throw new Error(`Unsupported subscription pricing combination: ${level} calories / ${weeksCount} weeks / ${daysPerWeek} days.`);
        }

        return Number(tier.without_vat || 0);
    }

    function getDurationLabel(daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
        if (weeksCount === 3) {
            return daysPerWeek === 6 ? '18 Days' : '15 Days';
        }

        return daysPerWeek === 6 ? '24 Days' : '20 Days';
    }

    function getDurationConfirmation(daysPerWeek = selectedStanderDurationDays, weeksCount = selectedStanderDurationWeeks) {
        const durationLabel = getDurationLabel(daysPerWeek, weeksCount);

        return daysPerWeek === 6
            ? ` <span>${durationLabel}</span>   <br/> Friday will be off  `
            : `<span>${durationLabel}</span>   <br/> <span>Friday and Saturday</span> <br/> will be off  `;
    }

    function syncCurrentWeekData() {
        currentStanderWeekData = window._allStanderData?.[selectedLevel]?.[`week${selectedStanderWeekCount}`] || null;
    }

    function refreshPlanLengthCards() {
        document.querySelectorAll('#stander-plan-length-container .plan-length-card').forEach((btn) => {
            const isActive = Number(btn.dataset.weeks) === selectedStanderDurationWeeks;

            btn.classList.toggle('bg-green-600', isActive);
            btn.classList.toggle('text-white', isActive);
        });
    }

    function refreshDurationLabels() {
        document.querySelectorAll('#stander-duration-container .duration-label-text').forEach((label) => {
            const days = Number(label.dataset.days || 0);
            label.textContent = getDurationLabel(days, selectedStanderDurationWeeks);
        });
    }

    function refreshWeekButtons() {
        const weekBtns = document.querySelectorAll('#stander-weeks-container .tap-week');

        if (selectedStanderWeekCount > selectedStanderDurationWeeks) {
            selectedStanderWeekCount = 1;
        }

        weekBtns.forEach((btn) => {
            const weekNumber = Number(btn.getAttribute('week-duration') || 0);
            const isVisible = weekNumber <= selectedStanderDurationWeeks;
            const isActive = isVisible && weekNumber === selectedStanderWeekCount;

            btn.classList.toggle('hidden', !isVisible);
            btn.classList.toggle('bg-green-600', isActive);
            btn.classList.toggle('text-white', isActive);
        });

        syncCurrentWeekData();
    }

    function applyPlanLength(weeksCount) {
        if (!window.threeWeekUiEnabled && weeksCount === 3) {
            return;
        }

        selectedStanderDurationWeeks = weeksCount;
        selectedStanderMeal = 'breakfast';
        selectedStanderDay = selectedStanderDurationDays === 5 ? 'sunday' : 'saturday';

        refreshPlanLengthCards();
        refreshDurationLabels();
        refreshWeekButtons();
        toggleSaturday();
        setInitialActivesStander();
        renderMealsStander();
        updateprice();
    }

    function bindPlanLengthControls() {
        document.querySelectorAll('#stander-plan-length-container .plan-length-card').forEach((btn) => {
            btn.onclick = () => {
                const weeksCount = Number(btn.dataset.weeks || 4);

                if (weeksCount === selectedStanderDurationWeeks) {
                    return;
                }

                applyPlanLength(weeksCount);
            };
        });
    }

    function toggleSaturday() {
        const satBtn = document.querySelector('#tab-1400 .day-option[data-day="saturday"]');
        if (!satBtn) return;
        if (selectedStanderDurationDays === 5) satBtn.classList.add('hidden');
        else satBtn.classList.remove('hidden');
    }

    function bindStanderWeekControls() {
        const weekBtns = document.querySelectorAll('#stander-weeks-container .tap-week');
        weekBtns.forEach(btn => {
            btn.onclick = () => {
            const nextWeekCount = Number(btn.getAttribute('week-duration') || 0);

            if (nextWeekCount > selectedStanderDurationWeeks) {
                return;
            }

            selectedStanderWeekCount = nextWeekCount;
            refreshWeekButtons();

            selectedStanderMeal = 'breakfast';
            selectedStanderDay = (selectedStanderDurationDays === 5 ? 'sunday' : 'saturday');

            toggleSaturday();
            setInitialActivesStander();
            renderMealsStander();
            };
        });
    }

    function bindStanderDurationControls() {
        const container = document.getElementById('stander-duration-container');
        const btns = container.querySelectorAll('.duration-btn');
        const overlay = document.getElementById('standerConfirmOverlay');
        const modal = document.getElementById('standerConfirmModal');
        const msgSpan = modal.querySelector('.middleDiv span');
        overlay.style.display = 'none';
        modal.style.display = 'none';

        modal._currentBtn = null;

        if (!modal.dataset.bound) {
            modal.addEventListener('click', e => {
            if (e.target.closest('.yes')) {
                overlay.style.display = modal.style.display = 'none';
                if (modal._currentBtn) applyDuration(modal._currentBtn);
                return;
            }
            if (e.target.closest('.no')) {
                overlay.style.display = modal.style.display = 'none';
                return;
            }
            });

            modal.dataset.bound = 'true';
        }

        btns.forEach(btn => {
            btn.onclick = () => {
                if (btn.classList.contains('bg-green-600')) return;
                const days = Number(btn.dataset.duration || 0);
                msgSpan.innerHTML = getDurationConfirmation(days, selectedStanderDurationWeeks);

                modal._currentBtn = btn;
                overlay.style.display = modal.style.display = 'block';
            };
        });
    }

    function applyDuration(btn) {
        const btns = document.querySelectorAll('#stander-duration-container .duration-btn');
        btns.forEach(b => {
        b.classList.remove('bg-green-600', 'text-white');
        const ic = b.querySelector('.duration-icon');
        if (ic) ic.classList.add('hidden');
        });

        btn.classList.add('bg-green-600', 'text-white');
        const icon = btn.querySelector('.duration-icon');
        if (icon) icon.classList.remove('hidden');

        selectedStanderDurationDays = parseInt(btn.dataset.duration, 10);

        selectedStanderMeal = 'breakfast';
        selectedStanderDay = selectedStanderDurationDays === 5 ? 'sunday' : 'saturday';
        refreshDurationLabels();
        updateprice();
        toggleSaturday();
        setInitialActivesStander();
        renderMealsStander();
    }

    function bindStanderControls() {
        document.querySelectorAll('#tab-1400 .day-option')
        .forEach(btn => {
            btn.onclick = () => {
            document.querySelectorAll('#tab-1400 .day-option')
                .forEach(b => b.classList.remove('bg-green-600', 'text-white'));
            btn.classList.add('bg-green-600', 'text-white');
            selectedStanderDay = btn.dataset.day;
            renderMealsStander();
            };
        });

        document.querySelectorAll('#tab-1400 .meal-btn')
        .forEach(btn => {
            btn.onclick = () => {
            document.querySelectorAll('#tab-1400 .meal-btn')
                .forEach(b => {
                b.classList.remove('bg-green-600', 'text-white');
                b.querySelector('.meal-icon')?.classList.add('hidden');
                });
            btn.classList.add('bg-green-600', 'text-white');
            btn.querySelector('.meal-icon')?.classList.remove('hidden');

            selectedStanderMeal = btn.dataset.meal;
            renderMealsStander();
            };
        });
    }

    function setInitialActivesStander() {
        document.querySelectorAll('#tab-1400 .day-option').forEach(btn => {
        const isActive = btn.dataset.day === selectedStanderDay;
        btn.classList.toggle('bg-green-600', isActive);
        btn.classList.toggle('text-white', isActive);
        });
        document.querySelectorAll('#tab-1400 .meal-btn').forEach(btn => {
        const isActive = btn.dataset.meal === selectedStanderMeal;
        btn.classList.toggle('bg-green-600', isActive);
        btn.classList.toggle('text-white', isActive);
        btn.querySelector('.meal-icon')?.classList.toggle('hidden', !isActive);
        });
    }

    function renderMealsStander() {
        const container = document.getElementById('stander-meal-accordion');
        container.innerHTML = '';

        const dayBlock = (currentStanderWeekData || {})[selectedStanderDay] || {};
        const mealBlock = (dayBlock[selectedStanderMeal] || {});
        const arr = mealBlock[selectedLanguage] || [];

        if (!arr.length) {
        container.innerHTML = '<p class="text-center text-gray-500">No meals</p>';
        return;
        }
        arr.forEach(item => {
        const card = document.createElement('div');
        card.className = 'meal-option mb-2 rounded p-4 meal-active selected-meal reduses ' ;
        card.innerHTML = `
            <div class="meal-desc content w-100">
                    <h4 class="font-bold text-lg mb-2 text-center">${item.title}</h4>

                    <div class="row row-cols-2 text-center" >
                        <p class="col">  <i class="fas fa-drumstick"></i> Protein : ${item.nutrition.Pro} </p>
                        <p class="col"> <i class="fas fa-bread-slice "></i> Carp : ${item.nutrition.Carb}</p>
                        <p class="col">   <i class="fas fa-avocado "></i> Fats : ${item.nutrition.Fats}</p>
                        <p class="col"> <i class="fas fa-fire "></i> Kcal : ${item.nutrition.kcal}</p>
                    </div>
            </div>
            `;
      const img = document.createElement('img');
        img.src = item.image;
        img.alt = item.title;
        img.className = 'img-glow-meal rounded-lg w-full h-full object-cover';

        const imgWrapper = document.createElement('div');
        imgWrapper.className = 'img-wrapper-meal w-full h-[220px] mb-4 overflow-hidden flex items-center justify-center rounded-lg';

        imgWrapper.appendChild(img);
        container.prepend(imgWrapper);

            // ——— only open & populate dialog, never mutating the page container ———
            // card.addEventListener('click', () => {
            //     return true
            //     // 1) make sure the dialog exists
            //     let dlg = document.querySelector('dialog.meal-dialogStander');
            //     if (!dlg) {
            //     dlg = document.createElement('dialog');
            //     dlg.className = 'meal-dialogStander rounded-lg shadow-lg p-4 border min-h-80';
            //     dlg.innerHTML = `
            //     <div class="dialog-container">
            //         <button class="close-dialog">×</button>
            //         <form class="form-horizontal" id="ModalForm">
            //         <div class="modal-body">
            //             <input type="number" hidden value="1" id="myBox" />
            //             <div id="dialogMealsSection"></div>
            //         </div>
            //         <div class="submit-menu-btn-div">
            //             <button type="button" class="menu-submit" id="okBtn">OK</button>
            //         </div>
            //         </form>
            //     </div>
            //     `;
            //     document.body.appendChild(dlg);
            //     }

            //     // 2) clear out just the dialog’s list
            //     const dialogList = dlg.querySelector('#dialogMealsSection');
            //     dialogList.innerHTML = '';

            //     // 3) fetch what’s already selected so we can pre-check it
            //     const existing = JSON.parse(localStorage.getItem('customizeMeals'))?.[selectedLevel]?.[selectedStanderDay] || {};

            //     // 4) fill it
            //     arr.forEach(m => {
            //     const isChecked = Object.values(existing).includes(m.id) ? 'checked' : '';
            //     dialogList.insertAdjacentHTML('beforeend', `
            //     <div class="meals" data-meal-id="${m.id}" style="margin-bottom:1em;">
            //         <div class="circle">-</div>
            //         <div class="meal-data">
            //         <img class="dialog-image rounded-lg" src="${m.image}" alt="${m.title}" width="150" height="150"/>
            //         <div class="meal-info">
            //             <h4>${m.title}</h4>
            //             <div class="meal-macros">
            //                                 <div class="macros">
            //                                     <div class="cal">
            //                                     <input class="num Kcal" value="60" readonly style="border: none; text-align: center" />
            //                                     <div class="title">kcal</div>
            //                                     </div>
            //                                     <div class="cal">
            //                                     <input class="num fat" value="1.1" readonly style="border: none; text-align: center" />
            //                                     <div class="title">fat</div>
            //                                     </div>
            //                                     <div class="cal">
            //                                     <input class="num protein" value="4.1" readonly style="border: none; text-align: center" />
            //                                     <div class="title">protein</div>
            //                                     </div>
            //                                     <div class="cal">
            //                                     <input class="num carb" value="7.9" readonly style="border: none; text-align: center" />
            //                                     <div class="title">carb</div>
            //                                     </div>
            //                                 </div>
            //                                 </div>
            //             <p>${m.description}</p>

            //         </div>
            //         </div>
            //     </div>
            //     `);
            //     });



            //     // 6) wire up close
            //     dlg.querySelector('.close-dialog').onclick = () => dlg.close();
            //     dlg.querySelector('#okBtn').onclick = () => dlg.close();

            //     // 7) finally show it
            //     dlg.showModal();
            // });

            container.appendChild(card);
        });
    }

    function handleStander() {
        document.getElementById('main-meal-tab').classList.remove('hidden');
        fetch('/standred_weeks.json')
        .then(r => r.ok ? r.json() : Promise.reject(r.statusText))
        .then(data => {
            window._allStanderData = data;
            syncCurrentWeekData();
            selectedStanderMeal = 'breakfast';
            bindPlanLengthControls();
            toggleSaturday();
            bindStanderWeekControls();
            bindStanderDurationControls();
            bindStanderControls();
            refreshPlanLengthCards();
            refreshDurationLabels();
            refreshWeekButtons();
            setInitialActivesStander();
            renderMealsStander();
            updateprice();
        })
        .catch(console.error);
    }

    document.querySelectorAll('#plans .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
        document.querySelectorAll('#plans .tab-btn')
            .forEach(b => b.classList.remove('actives'));
        btn.classList.add('actives');
        selectedLevel = btn.dataset.tab;
        handleStander();
        updateprice();
        });
    });

    window.addEventListener('DOMContentLoaded', () => {
        refreshPlanLengthCards();
        refreshDurationLabels();
        refreshWeekButtons();
        document.querySelector('#tab-1400')?.click();
        handleStander();
    });

    document.getElementById('standerConfirmYes')
    .addEventListener('click', () => {
        const allData = window._allStanderData || {};
        const days = selectedStanderDurationDays;
        const weeksCount = selectedStanderDurationWeeks;
        const daysList = days === 6
        ? ['saturday','sunday','monday','tuesday','wednesday','thursday']
        : ['sunday','monday','tuesday','wednesday','thursday'];
        let packageName = allData[selectedLevel].packaging_Name;
        const newWeeks = {};
        for (let i = 1; i <= weeksCount; i++) {
        const key = `week${i}`;
        const plan = allData[selectedLevel]?.[key] || {};
        const filtered = {};
        daysList.forEach(day => {
            filtered[day] = {};
            Object.entries(plan[day] || {}).forEach(([mealType, mealArr]) => {
            const meal = (mealArr.en || [])[0];
            if (meal) filtered[day][mealType] = meal.id;
            });
        });
        newWeeks[key] = filtered;
        }

        const priceWithoutVat = resolveSubscriptionSubtotal(selectedLevel, days, weeksCount);
        const newPlan = {
        days,
        weeks_count: weeksCount,
        packageName,
        price_without_vat: priceWithoutVat,
        price_with_vat: Number((priceWithoutVat * (1 + window.subscriptionVatRate)).toFixed(2)),
        ...newWeeks
        };
        const freshStore = {
        [selectedLevel]: newPlan
        };
        localStorage.setItem('standerdPackages', JSON.stringify(freshStore));
        window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
        renderAllPackages();
        updateCartCount();
        toggleMiniCartButton();

        document.querySelector('.overlaystander')?.classList.remove('active');
        document.querySelector('.menuWrapper')?.classList.remove('active');
        window.location.href = '{{ route("checkout.show") }}';
    });

    function updateprice() {
        const totalprice = document.getElementById('prices');
        const price = resolveSubscriptionSubtotal(selectedLevel, selectedStanderDurationDays, selectedStanderDurationWeeks);
        totalprice.innerText = `Total : ${price.toLocaleString()} EGP`;
    }

    // ====== end Stander-only Script ======
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------
    // ==== Global Variables ====

    </script>
@endpush
{{-- </section> --}}
<!--
  let currentCustomizeData  = null;
  let selectedCustomizeDay  = 'saturday';
  let selectedCustomizeMeal = 'breakfast';
  let weekDuration          = 1;
  let dayDuration           = 6;
  // ==== Clear “active” on cards ====
  // ==== Main entrypoint for Customize ====
  async function handleCustomize(elem) {
    clearCardActive(elem);
    elem.classList.add('active');
    // show Customize tab, hide Stander tab
    document.getElementById('main-meal-tab').classList.add('hidden');
    document.getElementById('main-meal-tab-customize').classList.remove('hidden');
    try {
      const res = await fetch('/customized.json');
      if (!res.ok) throw new Error(res.statusText);
      const all = await res.json();
      console.log('Fetched Customize Data:', all[selectedLevel][`week${weekDuration}`]);
      // pick current level
      currentCustomizeData = all[selectedLevel];
      // reset defaults
      selectedCustomizeDay  = 'saturday';
      selectedCustomizeMeal = 'breakfast';
      setInitialActivesCustomize();
      bindCustomizeWeekControls()
      const firstWeekBtn = document.querySelector('#main-meal-tab-customize .tap-week');
    if (firstWeekBtn) {
      setTimeout(() => {
        firstWeekBtn.click();
      }, 0);
    }
    } catch(err) {
      console.error('Error in handleCustomize:', err);
    }
}
// ___________________________________________________________

function bindCustomizeWeekControls() {
    document
    .querySelectorAll('#main-meal-tab-customize .tap-week')
    .forEach(btn => {
      btn.onclick = () => {
        document
          .querySelectorAll('#main-meal-tab-customize .tap-week')
          .forEach(b => b.classList.remove('bg-green-600','text-white'));
        btn.classList.add('bg-green-600','text-white');
        weekDuration = +btn.getAttribute('week-duration');
        console.log('Selected week duration:', weekDuration);
        setInitialActivesCustomize();
            bindCustomizeDurationControls();
            bindCustomizeControls();
            renderMealsCustomize();
            updateSelectedMealCard();
        updateCustomizeDayCounts();
        updateCustomizeDurationCount()
        refreshWeekCheckmarks()
      };
    });
}

// ___________________________________________________________

    function refreshWeekCheckmarks() {
    const store        = JSON.parse(localStorage.getItem('customizeMeals')) || {};
    const level        = selectedLevel;
    const requiredDays = dayDuration;
    const daysOrder = ['saturday','sunday','monday','tuesday','wednesday','thursday'];
    const startIndex = daysOrder.length - requiredDays;
    const allowedDays = daysOrder.slice(startIndex);
    document
        .querySelectorAll('#main-meal-tab-customize .tap-week')
        .forEach(btn => {
        const weekNum  = +btn.getAttribute('week-duration');
        const weekKey  = `week${weekNum}`;
        const weekData = (store[level] && store[level][weekKey]) || {};
        const validDaysCount = allowedDays
            .filter(day => {
            const meals = weekData[day] || {};
            return Object.keys(meals).length >= 5;
            })
            .length;
        const checkEl = btn.querySelector('.week-test');
        if (validDaysCount === requiredDays) {
            checkEl.classList.remove('hidden');
        } else {
            checkEl.classList.add('hidden');
        }
        });
    }
// ___________________________________________________________

    function bindCustomizeDurationControls() {
    const btns       = document.querySelectorAll('#main-meal-tab-customize .duration-btn');
    const overlay    = document.getElementById('customizeConfirmOverlay');
    const modal      = document.getElementById('customizeConfirmMenuWrapper');
    const msgSpan    = modal.querySelector('.middleDiv span');
    const saturdayBtn= document.querySelector(
        '#main-meal-tab-customize .day-option-customize[data-day="saturday"]'
    );
    let currentBtn   = null;

    overlay.classList.remove('active');
    modal.classList.remove('active');

    modal.addEventListener('click', e => {
        if (e.target.closest('.yes')) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        applyCustomizeDuration(currentBtn);
        }
        if (e.target.closest('.no')) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        }
    });

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
        if (btn.classList.contains('bg-green-600')) return;
        const days = btn.dataset.duration;
        msgSpan.innerHTML = days === '6'
        ? 'Are you sure you want to choose <br/> <span  >24 Days</span>   <br/> Friday will be off only  ?'
            : 'Are you sure you want to choose <br/> <span  >20 Days</span>   <br/> <span >Friday and Saturday</span> <br/> will be off only ?';
        currentBtn = btn;
        overlay.classList.add('active');
        modal.classList.add('active');
        });
    });

    function applyCustomizeDuration(btn) {
        btns.forEach(b => {
        b.classList.remove('bg-green-600','text-white');
        b.querySelector('.number-test-duration-6')?.classList.add('hidden');
        b.querySelector('.number-test-duration-5')?.classList.add('hidden');
        });

        btn.classList.add('bg-green-600','text-white');
        const days = btn.dataset.duration;
        btn.querySelector(days === '6'
        ? '.number-test-duration-6'
        : '.number-test-duration-5'
        )?.classList.remove('hidden');

        dayDuration = parseInt(days,10);
        selectedCustomizeMeal = 'breakfast';

        if (dayDuration === 5) saturdayBtn.classList.add('hidden');
        else                    saturdayBtn.classList.remove('hidden');

        const dayToActivate = dayDuration === 5 ? 'sunday' : 'saturday';
        selectedCustomizeDay = dayToActivate;
        const dayBtn = document.querySelector(
        `#main-meal-tab-customize .day-option-customize[data-day="${dayToActivate}"]`
        );

    bindCustomizeWeekControls();
    refreshWeekCheckmarks()
        if (dayBtn) dayBtn.click();
    }
    }

// ___________________________________________________________

  // ==== Bind day & meal buttons ====
    function bindCustomizeControls() {
    document.querySelectorAll('#main-meal-tab-customize .day-option-customize')
        .forEach(btn => {
        btn.onclick = () => {
            document.querySelectorAll('#main-meal-tab-customize .day-option-customize')
            .forEach(b => {
                b.classList.remove('bg-green-600','text-white');
            });
            btn.classList.add('bg-green-600','text-white');

            selectedCustomizeDay = btn.dataset.day;

            selectedCustomizeMeal = 'breakfast';

            setInitialActivesCustomize();    // لخلفية الزر والصح للوجبة
            updateCustomizeDayCounts();       // لأرقام الأيام
            renderMealsCustomize();           // إعادة رسم الوجبات
            updateCustomizeDurationCount()
            document.querySelector(
            '#main-meal-tab-customize .meal-btn-customize[data-meal="breakfast"]'
            )?.click();
        };
        });
        document.querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
            .forEach(btn => {
            btn.onclick = () => {
                selectedCustomizeMeal = btn.dataset.meal;

                setInitialActivesCustomize();
            bindCustomizeWeekControls();
            bindCustomizeDurationControls();
            updateCustomizeDurationCount()
            renderMealsCustomize();
            updateSelectedMealCard();
        updateCustomizeDayCounts();
            };
        });
    }
// ___________________________________________________________

    function setInitialActivesCustomize() {
    const level = selectedLevel;
    const weekKey = `week${weekDuration}`;
    const day = selectedCustomizeDay;

    let store = JSON.parse(localStorage.getItem('customizeMeals')) || {};

    if (!store[level]) store[level] = {};
    if (!store[level][weekKey]) store[level][weekKey] = {};
    if (!store[level][weekKey][day]) store[level][weekKey][day] = {};



    const saved = store[level][weekKey][day];

    document.querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
        .forEach(btn => {
        const isActive = btn.dataset.meal === selectedCustomizeMeal;
        btn.classList.toggle('bg-green-600', isActive);
        btn.classList.toggle('text-white', isActive);
        });

    document.querySelectorAll('#main-meal-tab-customize .meal-btn-customize')
        .forEach(btn => {
        const icon = btn.querySelector('.number-test-Meal');
        if (icon) {
            if (saved && saved[btn.dataset.meal]) icon.classList.remove('hidden');
            else icon.classList.add('hidden');
        }
        });
    }

// ___________________________________________________________

    function renderMealsCustomize() {
        const container = document.getElementById('meal-options-accordion-customize');
        container.innerHTML = '';

        const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
        const savedId = (store[selectedLevel]?.[`week${weekDuration}`] || {})[selectedCustomizeDay] || {}
        [selectedCustomizeMeal];
        const arr = ((currentCustomizeData[`week${weekDuration}`]||{})[selectedCustomizeDay]||{})[selectedCustomizeMeal][selectedLanguage] || [];

        if (!arr.length) {
        container.innerHTML = '<p class="text-center text-gray-500">No meals</p>';
        return;
        }
        arr.forEach(item => {
            const card = document.createElement('div');
            card.className = 'meal-option caster caster mb-2 rounded p-4 meal-active';
            card.dataset.mealId = item.id;
            card.innerHTML = `
                <img src="./cart.gif" alt="${item.title}" class="meal-image w-full h-auto rounded mb-2" />
                <div class="meal-desc content">
                    <h4 class="font-bold text-lg mb-2">${item.title}</h4>
                    <p class="text-gray-600">${item.description}</p>
                    <p class="text-green-600 font-semibold">${item.price} EGP</p>
                </div>
            `;
            if (item.id === savedId) {
                card.classList.add('selected-meal');
            }
            card.onclick = () => {
            container.querySelectorAll('.caster')
            .forEach(c => c.classList.remove('selected-meal'));
            card.classList.add('selected-meal');
            renderAccordion(arr);
        };
        container.appendChild(card);
        });
    }
// ___________________________________________________________

    function renderAccordion(mealOptions) {
        let target = document.querySelector('.meal-sectioncast');
        // if (!target) return;
        // target.innerHTML = "";
        if (!mealOptions.length) {
        target.innerHTML = '<p class="text-center text-gray-500">No meals available</p>';
        return;
        }
        // const newT = target.cloneNode(true);
        // target.parentNode.replaceChild(newT, target);
        // target = newT;

        const userData = JSON.parse(localStorage.getItem('customizeMeals'))||{};
        const selectedMeals = userData[selectedLevel]?.[`week${weekDuration}`]?.[selectedCustomizeDay]||{};

        let dlg = document.querySelector('.meal-dialog');
        if (!dlg) {
        dlg = document.createElement('dialog');
        dlg.className = 'meal-dialog rounded-lg shadow-lg p-4 border min-h-80';
        dlg.innerHTML = `
        <div class="dialog-container">
                            <button class="close-dialog">×</button>
                            <form class="form-horizontal" id="ModalForm" method="post">
                                <div class="modal-body">
                                <input type="number" hidden value="1" id="myBox" />
                                <div id="mealsContainer"></div>
                                </div>
                                <div class="submit-menu-btn-div">
                                <button type="button" class="menu-submit hidden " data-dismiss="modal" id="submit">Save</button>
                                </div>
                            </form>
                            </div>
        `;
        document.body.appendChild(dlg);
        }

        const mc = dlg.querySelector('#mealsContainer');
        mc.innerHTML = '';
        mealOptions.forEach(m => {
        const isChecked = Object.values(selectedMeals).includes(m.id) ? 'checked' : '';
        mc.insertAdjacentHTML('beforeend', `
        <div class="meals" data-meal-id="${m.id}" style="margin-bottom: 1em;">
                <input type="number" class="BoxNo" hidden value="1" />
                <div class="circle">-</div>
                <div class="meal-data">
                <img class="dialog-image rounded-lg" src="${m.image}" alt="${m.title}" width="150" height="150" />
                <div class="meal-info">
                    <div class="meal-name-macros">
                    <p class="meal-name dialog-title">${m.title}</p>
                    <div class="meal-macros">
                        <div class="macros">
                        <div class="cal">
                            <input class="num Kcal" value="${m.nutrition.Pro}" readonly style="border: none; text-align: center" />
                            <div class="title">kcal</div>
                        </div>
                        <div class="cal">
                            <input class="num fat" value="${m.nutrition.Pro}" readonly style="border: none; text-align: center" />
                            <div class="title">fat</div>
                        </div>
                        <div class="cal">
                            <input class="num protein" value="${m.nutrition.Pro}" readonly style="border: none; text-align: center" />
                            <div class="title">protein</div>
                        </div>
                        <div class="cal">
                            <input class="num carb" value="${m.nutrition.Pro}" readonly style="border: none; text-align: center" />
                            <div class="title">carb</div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="about-meal">${m.description}</div>
                    <p class="text-green-600 font-semibold">${m.price} EGP</p>
                    <div class="meal-choice">
                    <div class="input-field">
                        <div class="checkbox-wrapper">
                        <input id="meal-${m.id}" class="inputss meal-radio" name="selectedMeal" type="radio" value="${m.id}" ${isChecked} />
                        <label class="terms-label" for="meal-${m.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 200 200" class="checkbox-svg">
                            <mask fill="white" id="path-1-inside-${m.id}">
                                <rect height="200" width="200"></rect>
                            </mask>
                            <rect mask="url(#path-1-inside-${m.id})" stroke-width="40" class="checkbox-box" height="200" width="200"></rect>
                            <path stroke-width="15" d="M52 111.018L76.9867 136L149 64" class="checkbox-tick"></path>
                            </svg>
                            <span class="label-text">Add meal</span>
                        </label>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        `);
        });
        mc.querySelectorAll('input[type=radio]').forEach(radio => {
        radio.onchange = () => {
            mc.querySelectorAll('.meals').forEach(div => {
            div.classList.remove('selected');
            div.querySelector('.circle').textContent = '-';
            });
            const cur = radio.closest('.meals');

            cur.classList.add('selected');
            cur.querySelector('.circle').textContent = '✔';
        storeMealInCustomizeLocalStorage(radio.value, weekDuration, dayDuration);
        updateCustomizeDayCounts()
            refreshWeekCheckmarks()
            dlg.querySelector('.menu-submit').classList.remove('hidden');
        };
        if (radio.checked) radio.dispatchEvent(new Event('change'));
        });
        dlg.querySelector('.close-dialog').onclick = () => dlg.close();
        dlg.querySelector('.menu-submit').onclick = (ele) =>{ dlg.close()
        ele.target.classList.add('hidden');};
        dlg.showModal();
    }
// ___________________________________________________________

  // ==== Store to localStorage ====
function storeMealInCustomizeLocalStorage(id, weekDuration, dayDuration) {
 const parts = id.split('_');
  const level = parts[0];           // 1400
  const weekKey = parts[1];         // week1
  const day = parts[2];             // sunday
  const meal = parts[3];            // breakfast
  const mealId = parts[4];          // 1
  const lang = parts[5];            // en

  let store = JSON.parse(localStorage.getItem('customizeMeals')) || {};

  if (!store[level]) store[level] = {};
  if (!store[level][weekKey]) store[level][weekKey] = {};
  if (!store[level][weekKey][day]) store[level][weekKey][day] = {};

  store[level][weekKey][day][meal] = id;


  localStorage.setItem("customizeMeals", JSON.stringify(store));
  setInitialActivesCustomize();
  updateSelectedMealCard();
  if (typeof updateCustomizeDayCounts === 'function') updateCustomizeDayCounts();
  if (typeof updateCustomizeDurationCount === 'function') updateCustomizeDurationCount();

}

// ___________________________________________________________

  // ==== Hook up “Customize” card click ====
  document.querySelector('[data-type="customize"]')
    .addEventListener('click', function(){ handleCustomize(this); });

// ___________________________________________________________



function updateCustomizeDayCounts() {
  const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const levelData = store[selectedLevel]?.[`week${weekDuration}`] || {};

  document
    .querySelectorAll('#main-meal-tab-customize .day-option-customize')
    .forEach(btn => {
      const day = btn.dataset.day;
      const icon = btn.querySelector('.number-test-day');
      const count = Object.keys(levelData[day] || {}).length;
      if (!icon) return;

      if (count >= 5) {
        icon.textContent = '✔';
      } else {
        icon.textContent = `${count}/5`;
      }
      // تأكد أنه ظاهر دائمًا
      icon.classList.remove('hidden');
    });
}

// ___________________________________________________________



  function updateCustomizeDurationCount() {
    const store = JSON.parse(localStorage.getItem('customizeMeals')) || {};
    const plan  = store[selectedLevel]?.[`week${weekDuration}`] || {};
    // حدد قائمة الأيام الفعلية للخطة
    const daysList = dayDuration === 5
      ? ['sunday','monday','tuesday','wednesday','thursday']
      : ['saturday','sunday','monday','tuesday','wednesday','thursday'];
    // عدّ الأيام التي تحوي 5 وجبات
    let completed = daysList.filter(d =>
      plan[d] && Object.keys(plan[d]).length === 5
    ).length;

    // حدّد الأيقونة وابن نصّها
    const btn       = document.querySelector(
      `#main-meal-tab-customize .duration-btn[data-duration="${dayDuration}"]`
    );
    const icon      = btn?.querySelector(
      dayDuration === 6
        ? '.number-test-duration-6'
        : '.number-test-duration-5'
    );
    if (!icon) return;

    if (completed === daysList.length) {
      icon.textContent = '✔';
    } else {
      icon.textContent = `${completed}/${daysList.length}`;
    }
  }

// ___________________________________________________________



function updateSelectedMealCard() {
  // جلب الوجبة المسجّلة لليوم الحالي
  const store   = JSON.parse(localStorage.getItem('customizeMeals')) || {};
  const savedId = (store[selectedLevel]?.[`week${weekDuration}`] || {})[selectedCustomizeDay]?.[selectedCustomizeMeal];
  // حدّد كل البطاقات:
  document
    .querySelectorAll('#meal-options-accordion-customize .meal-option')
    .forEach(card => {
      // إذا الـ data-meal-id يساوي savedId → أضف الكلاس
      if (card.dataset.mealId === savedId) {
        card.classList.add('selected-meal');
      } else {
        card.classList.remove('selected-meal');
      }
    });
}

// ___________________________________________________________

function handleCustomizeSaveClick() {
  // 1. جلب بيانات الوجبات الحالية
  const storeOrig = JSON.parse(localStorage.getItem("customizeMeals")) || {};
  const level     = selectedLevel;    // مثلاً "1400"

  // 2. قائمة الأيام التي يجب تضمينها في اللقطة
  const dayList = dayDuration === 6
    ? ['saturday','sunday','monday','tuesday','wednesday','thursday']
    : ['sunday','monday','tuesday','wednesday','thursday'];

  // 3. تحقق من كل أسبوع لاستكمال الشروط (كما قبل)
  const allowedWeekKeys = Array.from({ length: weekDuration }, (_, index) => `week${index + 1}`);
  const incompleteWeeks = [];
  allowedWeekKeys.forEach((weekKey, index) => {
    const wk       = index + 1;
    const weekData = storeOrig[level]?.[weekKey] || {};

    const isComplete = dayList.every(day => {
      const meals = weekData[day] || {};
      return Object.keys(meals).length >= 5;
    });

    if (!isComplete) incompleteWeeks.push(wk);
  });

  // 4. جلب عناصر المودال
  const overlay = document.querySelector(".sidemenu-wrapper");
  const menuWrap= document.querySelector(".menuWrapper");
  const midTxt  = document.querySelector("#textmasdge");
  const yesBtn  = document.querySelector("#castConfirmYes");
  const noBtn   = document.querySelector("#castConfirmNo");

  // 5. نزيل أي EventListeners قديمة على الأزرار
  yesBtn.replaceWith(yesBtn.cloneNode(true));
  noBtn.replaceWith(noBtn.cloneNode(true));

  const yes = document.querySelector("#castConfirmYes");
  const no  = document.querySelector("#castConfirmNo");

  // 6. إذا كل الأسابيع مكتملة
  if (incompleteWeeks.length === 0) {
    midTxt.innerHTML = `Are you sure you want to save your customized plan?`;
    yes.style.display = "inline-block";
    no.style.display  = "inline-block";
    no.querySelector("span").textContent = "No";

    yes.addEventListener('click', () => {
      // ——— بناء لقطة الحفظ دون المساس بالـ customizeMeals الأصلية ———
      const levelData = storeOrig[level] || {};
      const snapshot  = {
        days: dayDuration,
        weeks_count: allowedWeekKeys.length || weekDuration,
        packageName: `Customized ${level} Calories`,
      };

      allowedWeekKeys.forEach(weekKey => {
        const weekMeals = levelData[weekKey] || {};
        // نفلتر الأيام حسب dayList
        const filtered = {};
        dayList.forEach(day => {
          if (weekMeals[day]) {
            filtered[day] = weekMeals[day];
          }
        });
        if (Object.keys(filtered).length) {
          snapshot[weekKey] = filtered;
        }
      });

      // ——— حفظ اللقطة في customizeMealsSave ككائن مستوى→أسابيع→أيام ———
      const saveStore = JSON.parse(localStorage.getItem("customizeMealsSave")) || {};
      saveStore[level] = snapshot;
      localStorage.setItem("customizeMealsSave", JSON.stringify(saveStore));
      window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
      renderAllPackages();
      toggleMiniCartButton();
      updateCartCount();
      // ——— إغلاق المودال ———
      overlay.classList.add("show");
      menuWrap.classList.remove("active");
    });

    no.addEventListener('click', () => {

      menuWrap.classList.remove("active");
    });

  // 7. خلاف ذلك → تحذير بعدد الأسابيع الناقصة + OK فقط
  } else {
    const cnt = incompleteWeeks.length;
    midTxt.innerHTML = `<span>⚠️</span><br/>You must complete all required meals for ${dayDuration} days and ${cnt} week${cnt>1?'s':''} incomplete.`;
    yes.style.display = "none";
    no.style.display  = "inline-b
