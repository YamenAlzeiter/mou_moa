document.addEventListener('DOMContentLoaded', function() {
    loader();
    loadSideNavSaved();
});

var subs = document.querySelectorAll('.collapse-container');
const sidebarState = localStorage.getItem('sidebarState');
const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId);

    // Validate that all variables exist
    if (toggle && nav && bodypd && headerpd) {
        toggle.addEventListener('click', () => {

            // Toggle sidebar visibility
            nav.classList.toggle('show-sidenav');
            nav.classList.toggle('collapse-sidenav');

            // Toggle body and header padding
            bodypd.classList.toggle('body-pd');
            headerpd.classList.toggle('body-pd');

            handelSubMenu();

            const isExpanded = nav.classList.contains('show-sidenav');
            localStorage.setItem('sidebarState', isExpanded ? 'expanded' : 'collapsed');
        });
    }
};

// Select all collapse triggers and containers
var subMenus = document.querySelectorAll('.collapse-trigger');
subMenus.forEach(function(submenu) {
    submenu.addEventListener('click', function(event) {
        event.preventDefault();

        var targetId = submenu.getAttribute('data-target');
        var nav = document.querySelector('.collapse-sidenav');

        if (!nav) {
            var target = document.querySelector(targetId);
            if (target) {
                target.classList.toggle('show');
                submenu.classList.toggle('mb-1');
            }

            var chevronIcon = submenu.querySelector('.ti-chevron-down');
            if (chevronIcon) {
                chevronIcon.classList.toggle('chevron-rotate');
            }
        }
    });
});

function handelSubMenu() {

    subs.forEach(sub => {
        const subUrl = sub.querySelector('.sub-active');
        const nav = document.querySelector('.l-navbar');
        if (nav.classList.contains('collapse-sidenav')) {
            if (subUrl) {
                const parent = subUrl.parentNode;
                const submenu = document.querySelector(`[data-target="#${parent.getAttribute('id')}"]`);
                console.log('l')
                if (submenu) {
                    const chevronIcon = submenu.querySelector('.ti-chevron-down');
                    submenu.classList.toggle('mb-1');
                    parent.classList.toggle('show');
                    if (chevronIcon) {
                        chevronIcon.classList.toggle('chevron-rotate');
                    }
                }
            }
        }else{
            if (subUrl) {
                const parent = subUrl.parentNode;
                const submenu = document.querySelector(`[data-target="#${parent.getAttribute('id')}"]`);

                if (submenu) {
                    const chevronIcon = submenu.querySelector('.ti-chevron-down');
                    submenu.classList.toggle('mb-1');
                    parent.classList.toggle('show');
                    if (chevronIcon) {
                        chevronIcon.classList.toggle('chevron-rotate');
                    }
                }
            }
        }
    });
}

function loader() {
    var minLoadTime = 700;
    var isPageLoaded = false;

    function hideLoader() {
        var loader = document.getElementById('preloader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    setTimeout(function() {
        if (isPageLoaded) {
            hideLoader();
        } else {
            window.addEventListener('load', hideLoader);
        }
    }, minLoadTime);

    window.addEventListener('load', function() {
        isPageLoaded = true;
        setTimeout(hideLoader, Math.max(0, minLoadTime - performance.now()));
    });
}

function loadSideNavSaved() {
    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

    // Retrieve the sidebar state from local storage


    const nav = document.getElementById('nav-bar');
    const bodypd = document.getElementById('body-pd');
    const headerpd = document.getElementById('header');

    if (sidebarState === 'expanded') {
        bodypd.classList.add('body-pd');
        headerpd.classList.add('body-pd');
        nav.classList.add('show-sidenav');
        nav.classList.remove('collapse-sidenav');
        handelSubMenu();
    } else if (sidebarState === 'collapsed') {
        nav.classList.remove('show-sidenav');
        bodypd.classList.remove('body-pd');
        headerpd.classList.remove('body-pd');
        nav.classList.add('collapse-sidenav');
        // Ensure submenus are hidden
        const subItems = nav.querySelectorAll('.nav__link.collapsed');
        subItems.forEach(item => {
            const subMenu = item.nextElementSibling;
            subMenu.classList.add('collapse-hide');
        });
    } else {
        bodypd.classList.add('body-pd');
        headerpd.classList.add('body-pd');
        nav.classList.add('show-sidenav');
    }
}
