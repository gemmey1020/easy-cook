


@push('extra-css')
<link href="{{ asset('assets/src/plugins/src/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/plugins/css/light/fullcalendar/custom-fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/src/plugins/css/dark/fullcalendar/custom-fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">



<!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">-->
<!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">-->
<!-- END PAGE LEVEL STYLES -->

<!-- Font Awesome CDN -->
<!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">-->
<link rel="stylesheet" href="{{ asset('assets/src/plugins/src/sweetalerts2/sweetalerts2.css') }}">

<link href="{{ asset('assets/src/plugins/css/dark/sweetalerts2/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />




<style>
:root {
    /* ✅ ألوان الوضع الفاتح */
    --fc-bg-color: var(--white-color);
    --fc-text-color: var(--title-color);
    --fc-border-color: var(--border-color);
    --fc-event-bg-color: var(--theme-color);
    --fc-event-text-color: var(--white-color);
}

/* ✅ وضع الدارك مود */
/* [data-theme="dark"] {
    --fc-bg-color: #1e1e1e;
    --fc-text-color: #ffffff;
    --fc-border-color: #444;
    --fc-event-bg-color: var(--theme-color2);
    --fc-event-text-color: #ffffff;
} */

/* ✅ تحسين مظهر قائمة الأحداث */
/* [data-theme="dark"] .fc {
    background-color: var(--fc-bg-color) !important;
    color: var(--fc-text-color) !important;
    border: 1px solid var(--fc-border-color);
    padding: 10px;
    border-radius: 10px;
} */

/* ✅ تحسين مظهر الأحداث */
/* [data-theme="dark"] .fc-event {
    background-color: var(--fc-event-bg-color) !important;
    color: var(--fc-event-text-color) !important;
    border-radius: 6px;
    padding: 5px;
    font-weight: bold;
    text-align: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
} */

/* ✅ تعديل شكل أزرار الـ Filter & Search
[data-theme="dark"] .form-control,
[data-theme="dark"] .form-select {
    background-color: #262626;
    color: #ffffff;
    border: 1px solid var(--fc-border-color);
}

[data-theme="dark"] .form-control::placeholder {
    color: #888;
}

/* ✅ تحسين لون أزرار التحكم */
/* [data-theme="dark"] .fc-button {
    background-color: var(--theme-color2);
    color: var(--black-color);
    border: none;
} */

/* [data-theme="dark"] .fc-button:hover {
    background-color: var(--yellow-color);
    color: var(--black-color);
} */
/* [data-theme="dark"] {
    --fc-bg-color: #1e1e1e;
    --fc-text-color: #ffffff;
    --fc-border-color: #444;
    --fc-event-bg-color: var(--theme-color2);
    --fc-event-text-color: #ffffff;
} */

/* ✅ تحسين مظهر قائمة الأحداث */
/* [data-theme="dark"] .fc {
    background-color: var(--fc-bg-color) !important;
    color: var(--fc-text-color) !important;
    border: 1px solid var(--fc-border-color);
    padding: 10px;
    border-radius: 10px;
} */

/* ✅ تحسين مظهر الأحداث */
/* [data-theme="dark"] .fc-event {
    background-color: var(--fc-event-bg-color) !important;
    color: var(--fc-event-text-color) !important;
    border-radius: 6px;
    padding: 5px;
    font-weight: bold;
    text-align: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
} */

/* ✅ تعديل شكل أزرار الـ Filter & Search */
/* [data-theme="dark"] .form-control,
[data-theme="dark"] .form-select {
    background-color: #262626;
    color: #ffffff;
    border: 1px solid var(--fc-border-color);
} */

/* [data-theme="dark"] .form-control::placeholder {
    color: #888;
} */

/* ✅ تحسين لون أزرار التحكم */
/* [data-theme="dark"] .fc-button {
    background-color: var(--theme-color2);
    color: var(--black-color);
    border: none;
} */

/* [data-theme="dark"] .fc-button:hover {
    background-color: var(--yellow-color);
    color: var(--black-color);
}  */

/* ✅ تعديل ستايل FullCalendar */
.fc {
    background-color: var(--fc-bg-color) !important;
    color: var(--fc-text-color) !important;
    border: 1px solid var(--fc-border-color);
    padding: 10px;
    border-radius: 10px;
}

/* ✅ تعديل شكل الأحداث (الاشتراكات) */
.fc-event {
    background-color: var(--fc-event-bg-color) !important;
    color: var(--fc-event-text-color) !important;
    border-radius: 6px;
    padding: 5px;
    font-weight: bold;
    text-align: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

/* ✅ تعديل الأزرار والـ header */
.fc-button {
    background-color: var(--theme-color);
    color: var(--white-color);
    border: none;
}

.fc-button:hover {
    background-color: var(--theme-color2);
}

/* ✅ تغيير لون حدود الأيام */
.fc-daygrid-day {
    border: 1px solid var(--fc-border-color);
}

table.dataTable {
    width: 100% !important;
}

.table-responsive {
    overflow-x: auto;
}

</style>
@endpush



@push('extra-js')

<script src="{{ asset('assets/src/plugins/src/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/uuid/uuid4.min.js') }}"></script>






<script src="{{ asset('assets/src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script>
<!--<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>-->

<script>
/*
document.addEventListener('DOMContentLoaded', function() {



    var calendarEl = document.getElementById('calendar');

    // calendarEl.addEventSource($eventsData);
    var eventsData = [] ;
    var newDate = new Date();

    // دالة لجلب الشهر الحالي بصيغة مزدوجة الرقم
    function getDynamicMonth() {
        let month = newDate.getMonth() + 1;
        return month < 10 ? `0${month}` : month;
    }

      // Modal Elements
    var getModalTitleEl = document.querySelector('#event-title');
    var getModalStartDateEl = document.querySelector('#event-start-date');
    var getModalEndDateEl = document.querySelector('#event-end-date');
    var getModalAddBtnEl = document.querySelector('.btn-add-event');
    var getModalUpdateBtnEl = document.querySelector('.btn-update-event');


    // إعداد الفئات اللونية للأحداث
    var calendarsEvents = {
        Work: 'primary',
        Personal: 'success',
        Important: 'danger',
        Travel: 'warning',
    }
    var checkWidowWidth = function() {
        if (window.innerWidth <= 1199) {
            return true;
        } else {
            return false;
        }
    }

    var calendarHeaderToolbar = {
        left: 'prev next addEventButton',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    }
    var calendarSelect = function(info) {
        getModalAddBtnEl.style.display = 'block';
        getModalUpdateBtnEl.style.display = 'none';
        const today = new Date();
        const selectedStart = new Date(info.startStr);

        const selectedEnd = new Date(info.endStr);




        if (selectedStart < today.setHours(0, 0, 0, 0)) {
            // Search for events on the selected date
            const hasEvent = eventsData.some(event => {
                const eventStart = new Date(event.startStr);
                const eventEnd = new Date(event.end || event.endStr); // Use start if end is not defined
                return (
                    selectedStart >= eventStart &&
                    selectedStart <= eventEnd
                );
            });

            if (!hasEvent) {
                // No event exists, show error and unselect
                Snackbar.show({
                    text: 'لا يمكنك تحديد تاريخ سابق بدون حدث موجود.',
                    pos: 'top-center',
                    actionTextColor: '#fff',
                    backgroundColor: '#dc3545' // Error color (Red)
                });
                calendar.unselect();
                return;
            }
        }









        myModal.show()
        getModalStartDateEl.value = info.startStr;
        getModalEndDateEl.value = info.endStr;

    }

    // Calendar AddEvent fn.
    var calendarAddEvent = function() {
        var currentDate = new Date();
        var dd = String(currentDate.getDate()).padStart(2, '0');
        var mm = String(currentDate.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = currentDate.getFullYear();
        var combineDate = `${yyyy}-${mm}-${dd}T00:00:00`;
        getModalAddBtnEl.style.display = 'block';
        getModalUpdateBtnEl.style.display = 'none';
        myModal.show();
        getModalStartDateEl.value = combineDate;
    }

    // Calendar eventClick fn.
    var calendarEventClick = function(info) {
        // alert("Calendar event");
        // console.log(info);
        // console.log('----------------------------------------------------------------');

        var eventObj = info.event;
        // //  console.log(eventObj);

        // console.log('----------------------------------------------------------------');

        if (eventObj.url) {
          window.open(eventObj.url);

          info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
        } else {
            var getModalEventId = eventObj._def.publicId;
            var getModalEventLevel = eventObj._def.extendedProps['calendar'];
            var getModalCheckedRadioBtnEl = document.querySelector(`input[value="${getModalEventLevel}"]`);

            getModalTitleEl.value = eventObj.title;
            getModalCheckedRadioBtnEl.checked = true;
            document.querySelector('#event-title').value = eventObj.title;
            document.querySelector('#event-start-date').value = eventObj.startStr;
            document.querySelector('#event-end-date').value = eventObj.endStr || '';
            document.querySelector('#client_id').value = eventObj.extendedProps.client_id || '';
            document.querySelector('#location').value = eventObj.extendedProps.location || '';
            document.querySelector('#client_type').value = eventObj.extendedProps.client_type || '';
            document.querySelector('#phone').value = eventObj.extendedProps.phone || '';
            document.querySelector('#visit_goal').value = eventObj.extendedProps.visit_goal || '';

            if (getModalCheckedRadioBtnEl) {
            getModalCheckedRadioBtnEl.checked = true;
        }






            getModalUpdateBtnEl.setAttribute('data-fc-event-public-id', getModalEventId)
            getModalAddBtnEl.style.display = 'none';
            getModalUpdateBtnEl.style.display = 'block';
            myModal.show();
        }
    }

    // تهيئة `FullCalendar`
    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        height: checkWidowWidth() ? 900 : 1052,

        initialView: checkWidowWidth() ? 'listWeek' : 'dayGridMonth',
        // initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
        initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-01`,

        headerToolbar: calendarHeaderToolbar,



        editable: true,
        height: 'auto',

        events: eventsData ,
        select: calendarSelect,
        unselect: function() {
            console.log('unselected')
        },

        customButtons: {
            addEventButton: {
                text: 'إضافة  نشاط يومي  جديد ',
                click: function() {
                    $('#exampleModal').modal('show'); // عرض المودال لإضافة حدث جديد
                }
            }
        },
        eventClassNames: function ({ event: calendarEvent }) {
            const getColorValue = calendarsEvents[calendarEvent._def.extendedProps.calendar];
            return [
              // Background Color
              'event-fc-color fc-bg-' + getColorValue
            ];
        },
        eventClick: calendarEventClick,
        windowResize: function(arg) {
            if (checkWidowWidth()) {
                calendar.changeView('listWeek');
                calendar.setOption('height', 900);
            } else {
                calendar.changeView('dayGridMonth');
                calendar.setOption('height', 1052);
            }
        }



    });

      getModalAddBtnEl.addEventListener('click', function() {

        var getModalCheckedRadioBtnEl = document.querySelector('input[name="event_level"]:checked');

        var getTitleValue = getModalTitleEl.value;
        var setModalStartDateValue = getModalStartDateEl.value;
        var setModalEndDateValue = getModalEndDateEl.value;
        var getModalCheckedRadioBtnValue = (getModalCheckedRadioBtnEl !== null) ? getModalCheckedRadioBtnEl.value : '';


        var formData = $('#add-activity-form').serializeArray();
        // alert(formData);
        var formAction = $('#add-activity-form').attr('action');


        formData.push(
            { name: 'title', value: getTitleValue },
            { name: 'start', value: setModalStartDateValue },
            { name: 'end', value: setModalEndDateValue },
            { name: 'calendar', value: getModalCheckedRadioBtnValue }
        );


        $.ajax({
            url: formAction,
            method: 'POST',
            data: formData,
            success: function(response) {
                // إضافة الحدث في FullCalendar بعد نجاح الطلب
                calendar.addEvent({
                    id: response.id,
                    title: getTitleValue,
                    start: setModalStartDateValue,
                    end: setModalEndDateValue,
                    allDay: true,
                    extendedProps: { calendar: getModalCheckedRadioBtnValue }
                });
                myModal.hide();
            },
            error: function(xhr) {
                alert('حدث خطأ أثناء إضافة النشاط. حاول مرة أخرى.');
            }
        });
    });



    // Update Event
    getModalUpdateBtnEl.addEventListener('click', function() {
        var getPublicID = this.dataset.fcEventPublicId;

        var getTitleUpdatedValue = document.querySelector('#event-title').value;
        var getStartDateUpdatedValue = document.querySelector('#event-start-date').value;
        var getEndDateUpdatedValue = document.querySelector('#event-end-date').value;
        var getClientId = document.querySelector('#client_id').value;
        var getLocation = document.querySelector('#location').value;
        var getClientType = document.querySelector('#client_type').value;
        var getPhone = document.querySelector('#phone').value;
        var getVisitGoal = document.querySelector('#visit_goal').value;
        var getModalUpdatedCheckedRadioBtnEl = document.querySelector('input[name="event_level"]:checked');
        var getModalUpdatedCheckedRadioBtnValue = getModalUpdatedCheckedRadioBtnEl ? getModalUpdatedCheckedRadioBtnEl.value : '';

        // إعداد البيانات لإرسالها إلى السيرفر
        var updatedData = {
        id: getPublicID,
        title: getTitleUpdatedValue,
        start: getStartDateUpdatedValue,
        end: getEndDateUpdatedValue,
        client_id: getClientId,
        location: getLocation,
        client_type: getClientType,
        phone: getPhone,
        visit_goal: getVisitGoal,
        calendar: getModalUpdatedCheckedRadioBtnValue,
        _token: "{{ csrf_token() }}",
        };

        $.ajax({
        url: `/daily-activities/${getPublicID}`,
        method: 'GET',
        data: updatedData,
        success: function(response) {
            // تحديث الحدث في FullCalendar
            var getEvent = calendar.getEventById(getPublicID);
            getEvent.setProp('title', getTitleUpdatedValue);
            getEvent.setStart(getStartDateUpdatedValue);
            getEvent.setEnd(getEndDateUpdatedValue);
            getEvent.setExtendedProp('client_id', getClientId);
            getEvent.setExtendedProp('location', getLocation);
            getEvent.setExtendedProp('client_type', getClientType);
            getEvent.setExtendedProp('phone', getPhone);
            getEvent.setExtendedProp('visit_goal', getVisitGoal);
            getEvent.setExtendedProp('calendar', getModalUpdatedCheckedRadioBtnValue);

            // إخفاء الـ modal بعد التحديث
            myModal.hide();
        },
        error: function(xhr) {
            alert('حدث خطأ أثناء تحديث البيانات. حاول مرة أخرى.');
        }
    });




    })

    // Calendar Renderation
    calendar.render();


    const searchInput = document.getElementById('eventSearch');
    const filterSelect = document.getElementById('eventFilter');


    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedFilter = filterSelect.value;

        // Filtered Events
        console.log("Filter Events Called:", searchTerm, selectedFilter);

        // Filtered Events
        const filteredEvents = eventsData.filter((event) => {
            console.log(event); // Debug each event object to check its properties

            // Ensure the `calendar` property exists in your events
            const matchesFilter = selectedFilter
                ? event.calendar && event.calendar === selectedFilter
                : true;

            const matchesSearch = event.title
                .toLowerCase()
                .includes(searchTerm);

            return matchesSearch && matchesFilter;
        });

        console.log("Filtered Events:", filteredEvents);

        // Remove existing events and add filtered events
        calendar.removeAllEvents();
        calendar.addEventSource(filteredEvents);
    }

    // Event Listeners for Search and Filter
    searchInput.addEventListener('input', filterEvents);
    filterSelect.addEventListener('change', filterEvents);


    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'))
    var modalToggle = document.querySelector('.fc-addEventButton-button ')

    document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function (event) {
        getModalTitleEl.value = '';
        getModalStartDateEl.value = '';
        getModalEndDateEl.value = '';
        var getModalIfCheckedRadioBtnEl = document.querySelector('input[name="event_level"]:checked');
        if (getModalIfCheckedRadioBtnEl !== null) { getModalIfCheckedRadioBtnEl.checked = false; }
    })







    // كود لإضافة حدث جديد

});


*/

// document.addEventListener('DOMContentLoaded', function() {
//     var calendarEl = document.getElementById('calendar');
//     var eventsData = [];

//     function fetchSubscriptions() {
//         fetch('/api/subscriptions') // جلب بيانات الاشتراكات
//             .then(response => response.json())
//             .then(data => {
//                 eventsData = data.flatMap(subscription => {
//                     let color = subscription.subscription_type === 'weight_loss' ? 'green' :
//                                 subscription.subscription_type === 'fitness' ? 'blue' : 'orange';

//                     return subscription.meals.map(meal => ({
//                         id: meal.id,
//                         title: `${meal.meal.meal_type} - ${meal.meal.title}`,
//                         start: meal.meal_date,
//                         backgroundColor: color,
//                         borderColor: color,
//                         extendedProps: {
//                             subscription_id: subscription.id,
//                             meal_type: meal.meal.meal_type,
//                             calorie_level: meal.meal.calorie_level,
//                             description: meal.meal.description
//                         }
//                     }));
//                 });

//                 calendar.removeAllEvents();
//                 calendar.addEventSource(eventsData);
//             })
//             .catch(error => console.error('Error fetching subscriptions:', error));
//     }

//     var calendar = new FullCalendar.Calendar(calendarEl, {
//         selectable: true,
//         height: 'auto',
//         initialView: 'dayGridMonth',
//         headerToolbar: {
//             left: 'prev,next today',
//             center: 'title',
//             right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
//         },
//         editable: false,
//         events: eventsData,
//         eventClick: function(info) {
//             var eventObj = info.event;

//             $('#meal-type').text(eventObj.extendedProps.meal_type);
//             $('#meal-calories').html(`<span class="badge bg-success">${eventObj.extendedProps.calorie_level} kcal</span>`);
//             $('#meal-description').html(`<p class="text-break">${eventObj.extendedProps.description}</p>`);

//     $('#eventModal').modal('show');
//         }
//     });

//     fetchSubscriptions();
//     calendar.render();
// });




document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsData = [];
    var calendar;

    function fetchSubscriptions() {
        fetch('/api/subscriptions')
            .then(response => response.json())
            .then(data => {
                eventsData = data.flatMap(subscription => {
                    let color = subscription.subscription_type === 'weight_loss' ? 'green' :
                                subscription.subscription_type === 'fitness' ? 'blue' : 'orange';

                    return subscription.meals.map(meal => ({
                        id: meal.id,
                        title: `${meal.meal.meal_type} - ${meal.meal.title}`,
                        start: meal.meal_date,
                        backgroundColor: color,
                        borderColor: color,
                        extendedProps: {
                            subscription_id: subscription.id,
                            meal_type: meal.meal.meal_type,
                            calorie_level: meal.meal.calorie_level,
                            description: meal.meal.description,
                            subscription_type: subscription.subscription_type
                        }
                    }));
                });

                updateCalendar(eventsData);
            })
            .catch(error => console.error('Error fetching subscriptions:', error));
    }

    function updateCalendar(filteredEvents) {
        calendar.removeAllEvents();
        calendar.addEventSource(filteredEvents);
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        height: 'auto',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        // themeSystem: 'bootstrap', // يطابق ألوان البوتستراب
        eventBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--fc-event-bg-color'),
        eventTextColor: getComputedStyle(document.documentElement).getPropertyValue('--fc-event-text-color'),
        dayMaxEvents: true,
        editable: false,
        events: eventsData,
        eventClick: function(info) {
            var eventObj = info.event;
            $('#meal-calories').html(`<span class="badge bg-success">${eventObj.extendedProps.calorie_level} kcal</span>`);
            $('#meal-description').html(`<p class="text-break">${eventObj.extendedProps.description}</p>`);
            $('#eventModal').modal('show');
        }
    });

    fetchSubscriptions();
    calendar.render();

    // 🔹 وظيفة البحث داخل التقويم
    document.getElementById('searchEvent').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        let filteredEvents = eventsData.filter(event => event.title.toLowerCase().includes(searchTerm));
        updateCalendar(filteredEvents);
    });

    // 🔹 وظيفة الفلترة حسب نوع الاشتراك
    document.getElementById('filterSubscription').addEventListener('change', function() {
        let selectedFilter = this.value;
        let filteredEvents = selectedFilter
            ? eventsData.filter(event => event.extendedProps.subscription_type === selectedFilter)
            : eventsData;
        updateCalendar(filteredEvents);
    });
});


/*
$(document).ready(function() {
        // عند تغيير العميل المختار، يتم جلب البيانات باستخدام AJAX
        $('#client_id').change(function() {
            var clientId = $(this).val();
            if (clientId) {
                $.ajax({
                    url: '/ajax/clients/' + clientId,
                    type: 'GET',
                    success: function(data) {
                        $('#location').val(data.address);
                        $('#client_type').val(data.client_type == 'farm' ? 'مزرعة' : 'تاجر');
                        $('#phone').val(data.phone);
                        $('#location').val(data.region + ', ' + data.address);
                    },
                    error: function() {
                        alert('حدث خطأ ما في جلب بيانات العميل.');
                    }
                });
            } else {
                $('#location').val('');
                $('#client_type').val('');
                $('#phone').val('');
            }
        });
    });
*/
</script>
<script>
  document.querySelectorAll('.btn-delete-account').forEach(button => {
        button.addEventListener('click', function() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e7515a',
                cancelButtonColor: '#3b3f5c',
                confirmButtonText: 'نعم، قم بالحذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find the closest form and submit it
                    this.closest('form').submit();
                }
            });
        });
    });



</script>
@endpush



@push('extra-content')



    <div class="layout-px-spacing">

        <div class="middle-content container-xxl p-0">

            <div class="row layout-top-spacing layout-spacing" id="cancel-row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="calendar-container">
                        <div class="d-flex justify-content-between mb-3">
                            <input type="text" id="searchEvent" class="form-control w-50 me-2" placeholder="Search meals...">
                            <select id="filterSubscription" class="form-select w-25">
                                <option value="">All Subscriptions</option>
                                <option value="weight_loss">Weight Loss</option>
                                <option value="fitness">Fitness</option>
                                <option value="weight_gain">Weight Gain</option>
                            </select>
                        </div>

                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
         <!-- Modal for Adding New Activity -->


         <!-- Modal لعرض تفاصيل الوجبة -->
         <!-- تحسين شكل المودال -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="eventModalLabel">Meal Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Calories:</strong> <span id="meal-calories"></span></p>
          <hr>
          <p><strong>Description:</strong></p>
          <p id="meal-description" class="text-muted"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>




</div>

    </div>
</div>
@endpush
