var App = function() {
    var MediaSize = {
        xl: 1200,
        lg: 992,
        md: 991,
        sm: 576
    };
    var ToggleClasses = {
        headerhamburger: '.toggle-sidebar',
        inputFocused: 'input-focused',
    };

    var Selector = {
        getBody: 'body',
        mainHeader: '.header.navbar',
        headerhamburger: '.toggle-sidebar',
        fixed: '.fixed-top',
        mainContainer: '.main-container',
        sidebar: '#sidebar',
        sidebarContent: '#sidebar-content',
        sidebarSubmenu: '.submenu-sidebar',
        sidebarStickyContent: '.sticky-sidebar-content',
        ariaExpandedTrue: '#sidebar [aria-expanded="true"]',
        ariaExpandedFalse: '#sidebar [aria-expanded="false"]',
        contentWrapper: '#content',
        contentWrapperContent: '.container',
        mainContentArea: '.main-content',
        searchFull: '.toggle-search',
        overlay: {
            sidebar: '.overlay',
            cs: '.cs-overlay',
            search: '.search-overlay'
        }
    };

    function getElement(selector) {
        if (!selector) {
            return null;
        }

        return document.querySelector(selector);
    }

    function createPerfectScrollbarIfPresent(selector, options) {
        var element = getElement(selector);

        if (!element) {
            return null;
        }

        return new PerfectScrollbar(element, options);
    }

    var toggleFunction = {
        sidebar: function($recentSubmenu) {
            $('.sidebarCollapse').on('click', function (sidebar) {
                sidebar.preventDefault();

                get_SidebarWrapper = document.querySelector('.sidebar-wrapper');
                get_CompactSubmenuShow = document.querySelector('#compact_submenuSidebar');
                get_CompactSubmenuShowContainerActive = document.querySelector('#compact_submenuSidebar > .submenu.show');
                get_overlay = document.querySelector('.overlay');
                get_mainContainer = document.querySelector('.main-container');
                
                if (get_CompactSubmenuShow && (get_CompactSubmenuShow.classList.contains('show') || get_CompactSubmenuShow.classList.contains('submenu-enable')) ) {
                    if ( window.innerWidth >= 1200 ) {

                        get_CompactSubmenuShow.classList.remove('show');
                        get_CompactSubmenuShow.classList.remove('submenu-enable');
                        if (get_SidebarWrapper) {
                            get_SidebarWrapper.classList.remove('hide-mainMenu');
                        }
                        
                        if (get_CompactSubmenuShowContainerActive) {
                            get_CompactSubmenuShowContainerActive.classList.remove('show');
                        }
                        $(this).removeClass('bt-menu-open');
                        
                    } else {
                        if (get_mainContainer) {
                            get_mainContainer.classList.remove('sidebar-closed');
                            get_mainContainer.classList.add('sbar-open');
                        }
                        if (get_overlay) {
                            get_overlay.classList.add('show');
                        }
                    }

                } else  {
                    $(Selector.mainContainer).toggleClass("sidebar-closed");
                    $(Selector.mainContainer).toggleClass("sbar-open");
                    if (window.innerWidth <= 1199) {
                        if (get_overlay) {
                            if (get_overlay.classList.contains('show')) {
                                get_overlay.classList.remove('show');
                            } else {
                                get_overlay.classList.add('show');
                            }
                        }
                    }
                    $('html,body').toggleClass('sidebar-noneoverflow');
                    $('footer .footer-section-1').toggleClass('f-close');
                }
            });
        },
        fixedSubmenuCollapser: function() {
            var fixedSidebarCollapse = getElement('.sidebarCollapseFixed');

            if (!fixedSidebarCollapse) {
                return;
            }

            fixedSidebarCollapse.addEventListener('click', function() {
                var submenuSidebar = this.parentNode ? this.parentNode.querySelector('.submenu-sidebar') : null;
                var activeSubmenu = this.parentNode ? this.parentNode.querySelector('.submenu-sidebar .submenu.show') : null;
                var sidebarCollapse = getElement('.sidebarCollapse');

                if (this.parentNode) {
                    this.parentNode.classList.remove('hide-mainMenu');
                }

                if (sidebarCollapse) {
                    sidebarCollapse.classList.remove('bt-menu-open');
                }

                if (submenuSidebar) {
                    submenuSidebar.classList.remove('submenu-enable');
                    submenuSidebar.classList.remove('show');
                }

                if (activeSubmenu) {
                    activeSubmenu.classList.remove('show');
                }

                if (!submenuSidebar || !(submenuSidebar.classList.contains('submenu-enable'))) {
                    this.classList.remove('show');
                }
                
            })
        },
        overlay: function() {
            $('.overlay').on('click', function () {
                var sidebarWrapper = getElement('.sidebar-wrapper');
                var sidebarSubmenu = getElement(Selector.sidebarSubmenu);
                var activeSubmenu = getElement('.submenu.show');

                // hide sidebar
                var windowWidth = window.innerWidth;
                if (windowWidth <= 1199) {
                    // mainContainer
                    $(Selector.mainContainer).addClass('sidebar-closed');
                    $(Selector.mainContainer).removeClass('sbar-open');
                    $('.sidebarCollapse').removeClass('bt-menu-open');

                    if (sidebarWrapper) {
                        sidebarWrapper.classList.remove('hide-mainMenu');
                    }

                    if (sidebarSubmenu) {
                        sidebarSubmenu.classList.remove('show');
                        sidebarSubmenu.classList.remove('submenu-enable');
                    }

                    if (activeSubmenu) {
                        activeSubmenu.classList.remove('show');
                    }
                }
                // hide overlay
                $('.overlay').removeClass('show');
                $('html,body').removeClass('sidebar-noneoverflow');
                $('.col-right').removeClass('show');

            });
        },
        search: function() {
            $(Selector.searchFull).click(function(event) {
               $(this).parents('.search-animated').find('.search-full').addClass(ToggleClasses.inputFocused);
               $(this).parents('.search-animated').addClass('show-search');
               $(Selector.overlay.search).addClass('show');
               $(Selector.overlay.search).addClass('show');
            });

            $(Selector.overlay.search).click(function(event) {
               $(this).removeClass('show');
               $(Selector.searchFull).parents('.search-animated').find('.search-full').removeClass(ToggleClasses.inputFocused);
               $(Selector.searchFull).parents('.search-animated').removeClass('show-search');
            });
        },
        notificationSidebar: function() {

            toogleNotification = document.querySelector('.toggle-notification-bar');
            
            if (toogleNotification) {
                document.querySelector('.toggle-notification-bar').addEventListener('click', function() {
                    document.querySelector('.col-right').classList.add('show');
                    document.querySelector('.overlay').classList.add('show');
                    document.querySelector('body').classList.add('sidebar-noneoverflow');
                    document.querySelector('html').classList.add('sidebar-noneoverflow');
                })
            }
            
            
        }
    }


    var inBuiltfunctionality = {
        mainCatActivateScroll: function() {
            createPerfectScrollbarIfPresent('.menu-categories', {
                wheelSpeed:.5,
                swipeEasing:!0,
                minScrollbarLength:40,
                maxScrollbarLength:100,
                suppressScrollX: true

            });
        },
        subCatScroll: function() {
            createPerfectScrollbarIfPresent('#compact_submenuSidebar', {
                wheelSpeed:.5,
                swipeEasing:!0,
                minScrollbarLength:40,
                maxScrollbarLength:100,
                suppressScrollX: true

            });
        },
        colRightContentScroll: function() {

            createPerfectScrollbarIfPresent('.col-right-content-container', {
                wheelSpeed:.8,
                swipeEasing:!0,
                minScrollbarLength:40,
                maxScrollbarLength:100,
                suppressScrollX: true

            });
        },
        onSidebarHover: function() {
            var getMenu = document.querySelectorAll('.menu');

            for (var i = 0; i < getMenu.length; i++) {
                getMenu[i].addEventListener('click', function() {
                    
                    if ( this.classList.contains('menu-single') ) {

                        return;
                        
                    } else {
                        var menuToggle = this.querySelectorAll('.menu-toggle')[0];

                        if (!menuToggle) {
                            return;
                        }

                        get_SidebarWrapper = document.querySelector('.sidebar-wrapper');
                        getHref = menuToggle.getAttribute('href');
                        getElement = document.querySelectorAll('#compact_submenuSidebar > ' + getHref)[0];
                        getCompactSubmenu = document.querySelector('#compact_submenuSidebar');
                        getElementActiveClass = document.querySelector('#compact_submenuSidebar > .show');
                        get_mainContainer = document.querySelector('.main-container');
                        hamburgerMenuElement = document.querySelector('.sidebarCollapse');

                        if (!getCompactSubmenu || !getElement || !hamburgerMenuElement || !get_SidebarWrapper) {
                            return;
                        }

                        if (getCompactSubmenu) {
                            getCompactSubmenu.classList.add("show");
                            getCompactSubmenu.classList.add('submenu-enable');
                            get_SidebarWrapper.classList.add('hide-mainMenu');
                        }

                        if (getElementActiveClass) {
                            getElementActiveClass.classList.remove("show");
                        }

                        getElement.className += " show";

                        hamburgerMenuElement.classList.add('bt-menu-open');
                        if (typeof getAdminDataContentElement !== 'undefined' && getAdminDataContentElement && document.documentElement.scrollTop >= getAdminDataContentElement.offsetTop ) {
                            var fixedToggle = document.querySelector('.sidebarCollapseFixed');

                            if (fixedToggle && getCompactSubmenu.classList.contains('submenu-enable')) {
                                fixedToggle.classList.add('show');
                            }
                        }
                        
                    }
                    
                })
                getMenu[i].addEventListener('click', function(ev) {
                    if ( this.classList.contains('menu-single') ) {
                        return;
                    } else {
                        ev.preventDefault();
                    }
                })
            }

        },
        preventScrollBody: function() {
            $('#compactSidebar, #compact_submenuSidebar, .col-right-content-container').bind('mousewheel DOMMouseScroll', function(e) {
                var scrollTo = null;

                if (e.type == 'mousewheel') {
                    scrollTo = (e.originalEvent.wheelDelta * -1);
                }
                else if (e.type == 'DOMMouseScroll') {
                    scrollTo = 40 * e.originalEvent.detail;
                }

                if (scrollTo) {
                    e.preventDefault();
                    $(this).scrollTop(scrollTo + $(this).scrollTop());
                }
            });
        },
        toggleFixedSubmenuCollapserOnScroll() {
            getAdminDataContentElement = document.querySelector('.admin-data-content');
            var submenuSidebar = document.querySelector('.submenu-sidebar');
            var fixedToggle = document.querySelector('.sidebarCollapseFixed');

            if (!getAdminDataContentElement || !submenuSidebar || !fixedToggle) {
                return;
            }

            document.addEventListener('scroll', function() {

                if (submenuSidebar.classList.contains('submenu-enable')) {

                    if ( document.documentElement.scrollTop >= getAdminDataContentElement.offsetTop ) {
                        fixedToggle.classList.add('show');
                    } else {
                        fixedToggle.classList.remove('show');
                    }
                    
                } else {
                    fixedToggle.classList.remove('show');
                }
                
            })
        },
        languageDropdown: function() {
            var getDropdownElement = document.querySelectorAll('.more-dropdown .dropdown-item');
            for (var i = 0; i < getDropdownElement.length; i++) {
                getDropdownElement[i].addEventListener('click', function() {
                    document.querySelectorAll('.more-dropdown .dropdown-toggle > span')[0].innerText = this.getAttribute('data-value');
                    document.querySelectorAll('.more-dropdown .dropdown-toggle > img')[0].setAttribute('src', 'assets/img/' + this.getAttribute('data-img-value') + '.svg' );
                })
            }
        },
        openSubmenuOnLoad: function() {
            const getListMenu = document.querySelector('li.menu.active')
            if (!getListMenu) {
                return;
            }

            getChild = getListMenu.children;
            var getHref;
            for (let i = 0; i < getChild.length; i++) {
                const element = getChild[i];
                getHref = element.getAttribute('href');
            }

            if (!getHref || getHref.charAt(0) !== '#') {
                return;
            }

            if ( getHref != '#dashboard' && !(getListMenu.classList.contains('menu-single')) ) {
                if( !(getListMenu.classList.contains('submenu-closed'))) {

                    const selectSubmenu = document.querySelector(getHref);
                    if (!selectSubmenu || !selectSubmenu.parentNode) {
                        return;
                    }

                    selectSubmenu.parentNode.classList.add('show');
                    selectSubmenu.parentNode.classList.add('submenu-enable');
                    selectSubmenu.classList.add('show');
        
                    if ( window.innerWidth >= 1200 ) {
                        const sidebarCollapse = document.querySelector('.sidebarCollapse');

                        if (sidebarCollapse) {
                            sidebarCollapse.classList.add('bt-menu-open');
                        }
                    }
                }
                
            }
        }
    }

    var _mobileResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth <= 1199 ) {
                toggleFunction.sidebar();
            }
        },
        
        onResize: function() {
            $(window).on('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth <= 1199 ) {
                }
            });
        }
        
    }

    var _desktopResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth > 1199 ) {
                toggleFunction.sidebar(true);
                toggleFunction.fixedSubmenuCollapser();
            }
        },
        
        onResize: function() {
            $(window).on('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth > 1199 ) {
                }
            });
        }
        
    }

    function sidebarFunctionality() {
        function sidebarCloser() {

            if (window.innerWidth <= 1199 ) {


                if (!$('body').hasClass('collapsable-menu')) {

                    $('.main-container').removeClass('sbar-open');
                    $("#container").addClass("sidebar-closed");
                    $('.overlay').removeClass('show');
                    $('#compact_submenuSidebar').removeClass('show');

                } else {
                    $(".navbar").removeClass("expand-header");
                    $('.overlay').removeClass('show');
                    $('#container').removeClass('sbar-open');
                    $('html, body').removeClass('sidebar-noneoverflow');
                }

            } else if (window.innerWidth > 1199 ) {

                if (!$('body').hasClass('collapsable-menu')) {
                    $("#container").removeClass("sidebar-closed");
                    $('#container').removeClass('sbar-open');
                } else {
                    $('html, body').addClass('sidebar-noneoverflow');
                    $("#container").addClass("sidebar-closed");
                    $(".navbar").addClass("expand-header");
                    $('.overlay').addClass('show');
                    $('#container').addClass('sbar-open');
                    $('.sidebar-wrapper [aria-expanded="true"]').parents('li.menu').find('.collapse').removeClass('show');
                }
            }

        }

        function sidebarMobCheck() {
            if (window.innerWidth <= 1199 ) {

                if ( $('.main-container').hasClass('sbar-open') || $('#compact_submenuSidebar').hasClass('show') || $('.col-right').hasClass('show') ) {
                    return;
                } else {
                    sidebarCloser()
                }
            } else if (window.innerWidth > 1199 ) {
                sidebarCloser();
            }
        }

        sidebarCloser();

        $(window).resize(function(event) {
            sidebarMobCheck();
        });

    }

    return {
        init: function() {
            toggleFunction.overlay();
            toggleFunction.search();
            toggleFunction.notificationSidebar();
            
            /*
                Desktop Resoltion fn
            */
            _desktopResolution.onRefresh();
            _desktopResolution.onResize();

            /*
                Mobile Resoltion fn
            */
            _mobileResolution.onRefresh();            
            _mobileResolution.onResize();

            sidebarFunctionality();

            /*
                In Built Functionality fn
            */
            inBuiltfunctionality.mainCatActivateScroll();
            inBuiltfunctionality.subCatScroll();
            inBuiltfunctionality.preventScrollBody();
            inBuiltfunctionality.languageDropdown();
            inBuiltfunctionality.onSidebarHover();
            inBuiltfunctionality.toggleFixedSubmenuCollapserOnScroll();
            inBuiltfunctionality.colRightContentScroll();

            if (document.querySelector('.alt-menu')) {
                inBuiltfunctionality.colRightContentScroll()
            }

            inBuiltfunctionality.openSubmenuOnLoad();
        }
    }

}();
