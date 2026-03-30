(function () {
    var searchbox = $("#search"),
        autocomplete = $("#search-autocomplete"),
        loadMoreButton = $(".load-more", autocomplete),
        spinner = $(".loader", autocomplete),
        noResults = $(".no-results", autocomplete),
        error = $(".error", autocomplete);

    var searchUrl = "/search", // ربط مع Laravel
        searchTimeout = 500, // milliseconds
        searchTimer,
        lastSearch = "";

    function loadData() {
        noResults.hide();
        error.hide();
        spinner.show();
        loadMoreButton.hide();

        var query = searchbox.val().trim();

        if (query !== lastSearch) {
            $(".search-result, .category-header", autocomplete).remove();
        }

        lastSearch = query;

        $.ajax({
            type: "GET",
            url: searchUrl,
            dataType: "json",
            data: { search: query },
            success: function (response) {
                if (
                    response &&
                    (response.recipes.length ||
                        response.tips.length ||
                        response.meals.length ||
                        response.blogs.length)
                ) {
                    var groupedResults = {
                        Recipes: response.recipes,
                        Tips: response.tips,
                        Meals: response.meals,
                        Blogs: response.blogs,
                    };

                    $.each(groupedResults, function (category, results) {
                        if (results.length > 0) {
                            $(
                                '<li class="category-header">' +
                                    category +
                                    "</li>"
                            ).insertBefore(loadMoreButton);

                            $.each(results, function (index, result) {
                                // تحديد الـ slug الصحيح حسب النوع
                                let baseUrl = "";
                                switch (category.toLowerCase()) {
                                    case "tips":
                                        baseUrl = "/easy-tips/";
                                        break;
                                    case "recipes":
                                        baseUrl = "/recipes/";
                                        break;
                                    case "meals":
                                        baseUrl = "/meals/";
                                        break;
                                    case "blogs":
                                        baseUrl = "/blogs/";
                                        break;
                                    default:
                                        baseUrl = "/";
                                }

                                // بناء العنصر
                                $(
                                    '<li class="search-result">' +
                                        '<a href="' +
                                        baseUrl +
                                        '" target="_blank">' +
                                        result.title +
                                        '<span class="search-description">' +
                                        (
                                            result.description || result.content
                                        ).substring(0, 100) +
                                        "...</span>" +
                                        "</a>" +
                                        "</li>"
                                ).insertBefore(loadMoreButton);
                            });
                        }
                    });

                    noResults.hide();
                } else {
                    noResults.show();
                }

                spinner.hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                error.text("please try again latter").show();
                spinner.hide();
                console.error(errorThrown);
            },
        });
    }

    // الأحداث على الـ searchbox
    searchbox
        .on("focus", function () {
            if ($(this).val().length > 0) {
                autocomplete.slideDown("fast");
            }
        })
        .on("input propertychange paste keyup", function () {
            clearTimeout(searchTimer);
            if ($(this).val().length > 0) {
                searchTimer = setTimeout(loadData, searchTimeout);
                autocomplete.slideDown("fast");
            } else {
                autocomplete.slideUp("fast");
            }
        });

    $(document).on("click", function (e) {
        if (
            !$(e.target).closest(".searchbox").length &&
            autocomplete.is(":visible")
        ) {
            autocomplete.slideUp("fast");
        }
    });

    autocomplete.css("top", searchbox.outerHeight() + "px");
    loadMoreButton.hide();
    noResults.hide();
    error.hide();
})();
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 3, // Number of images shown at once in the row
    spaceBetween: 20, // spacing between images
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    breakpoints: {
        768: {
            slidesPerView: 3,
        },
        576: {
            slidesPerView: 2,
        },
        0: {
            slidesPerView: 1,
        },
    },
});
