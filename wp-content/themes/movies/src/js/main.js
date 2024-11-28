import './blocks/block-hero/block-hero.js';
import './blocks/block-filters/block-filters.js';

document.addEventListener( 'DOMContentLoaded', () => {

    // Sticky header
    class stickyHeader {
        constructor( headerSelector ) {
            this.navbar = document.querySelector(headerSelector);
            this.lastScrollTop = 0;
            this.headerHeight = this.navbar.scrollHeight;

            window.addEventListener( 'scroll', this.onScroll.bind(this) );

            window.addEventListener( 'load', this.onScroll() );
        }

        onScroll() {
            const scroll = window.scrollY || document.documentElement.scrollTop;

            if ( scroll > this.lastScrollTop ) {
                this.navbar.classList.add('scrolled-down');
                this.navbar.classList.remove('scrolled-up');
            } else if ( scroll === 0 ) {
                this.navbar.classList.remove('scrolled-down');
                this.navbar.classList.remove('scrolled-up');

                this.lastScrollTop = 0;
            } else if ( scroll < this.lastScrollTop && scroll > 100 ) {
                this.navbar.classList.remove('scrolled-down');
                this.navbar.classList.add('scrolled-up');
            };

            this.lastScrollTop = scroll;
        };
    };

    if ( document.querySelector('header') ) {
        new stickyHeader('.header');
    }

    //mobile menu
    let bodyLockStatus = true;
    let bodyLockToggle = (delay = 500) => {
        if (document.documentElement.classList.contains('lock')) {
            bodyUnlock(delay);
        } else {
            bodyLock(delay);
        }
    };

    let bodyUnlock = (delay = 500) => {
        let body = document.querySelector("body");
        let stickyHeader = document.querySelector("header._header-scroll");

        if (bodyLockStatus) {
            let lock_padding = document.querySelectorAll("[data-lp]");

            setTimeout(() => {
                for (let index = 0; index < lock_padding.length; index++) {
                    const el = lock_padding[index];
                    el.style.paddingRight = '0px';
                }
                body.style.paddingRight = '0px';
                if(stickyHeader){
                    stickyHeader.style.right = '0px';
                }
                document.documentElement.classList.remove("lock");
            }, delay);
            bodyLockStatus = false;
            setTimeout(function () {
                bodyLockStatus = true;
            }, delay);
        }
    };

    let bodyLock = (delay = 500) => {
        let body = document.querySelector("body");
        let stickyHeader = document.querySelector("header._header-scroll");

        if (bodyLockStatus) {
            let lock_padding = document.querySelectorAll("[data-lp]");

            for (let index = 0; index < lock_padding.length; index++) {
                const el = lock_padding[index];
                el.style.paddingRight = window.innerWidth - document.documentElement.scrollWidth + 'px';
            }

            body.style.paddingRight = window.innerWidth - document.documentElement.scrollWidth + 'px';

            if(stickyHeader){
                stickyHeader.style.right = (window.innerWidth - document.documentElement.scrollWidth) / 2 + 'px';
            }

            document.documentElement.classList.add("lock");
    
            bodyLockStatus = false;
            
            setTimeout(function () {
                bodyLockStatus = true;
            }, delay);
        }
    }

    (function menuInit() {
        if (document.querySelector(".icon-menu")) {
            document.addEventListener("click", function (e) {
                if (bodyLockStatus && e.target.closest('.icon-menu')) {
                    bodyLockToggle();
                    document.documentElement.classList.toggle("menu-open");
                }
            });
        };
    })();

    function menuOpen() {
        bodyLock();
        document.documentElement.classList.add("menu-open");
    }

    function menuClose() {
        bodyUnlock();
        document.documentElement.classList.remove("menu-open");
    }
} );