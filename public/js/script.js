/* =========================================================
   AprilianaFast — Custom Script
   Handles: hamburger toggle, smooth scroll, active nav link,
   navbar shadow on scroll, and scroll-reveal animation.
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    /* ---------------------------------------------------
       1. Hamburger Menu Toggle
    --------------------------------------------------- */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('active');

            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
            }
        });

        // Close mobile menu whenever a link inside it is clicked
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                hamburger.classList.remove('active');
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
            });
        });
    }

    /* ---------------------------------------------------
       2. Smooth Scrolling for all in-page anchor links
       (In addition to CSS `scroll-behavior: smooth`, this
       ensures correct offset below the fixed navbar and
       works consistently across all browsers.)
    --------------------------------------------------- */
    const navbar = document.getElementById('navbar');
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId.length <= 1) return; // ignore bare "#"

            const targetEl = document.querySelector(targetId);
            if (!targetEl) return;

            e.preventDefault();

            const navbarHeight = navbar ? navbar.offsetHeight : 0;
            const targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        });
    });

    /* ---------------------------------------------------
       3. Navbar background shadow on scroll
    --------------------------------------------------- */
    function handleNavbarShadow() {
        if (!navbar) return;
        if (window.scrollY > 20) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
        }
    }

    window.addEventListener('scroll', handleNavbarShadow);
    handleNavbarShadow();

    /* ---------------------------------------------------
       4. Active nav link highlighting based on scroll position
    --------------------------------------------------- */
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    function setActiveLink() {
        let currentSectionId = '';
        const scrollPosition = window.scrollY + (navbar ? navbar.offsetHeight : 0) + 40;

        sections.forEach(function (section) {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                currentSectionId = section.getAttribute('id');
            }
        });

        navLinks.forEach(function (link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + currentSectionId) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', setActiveLink);
    setActiveLink();

    /* ---------------------------------------------------
       5. Scroll Reveal Animation
       Adds ".reveal" behavior to key elements as they enter
       the viewport, using IntersectionObserver.
    --------------------------------------------------- */
    const revealTargets = document.querySelectorAll(
        '#about, #services .service-card, #price .price-card, #portfolio .portfolio-item, #contact .contact-card'
    );

    revealTargets.forEach(function (el) {
        el.classList.add('reveal');
    });

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -60px 0px'
    });

    revealTargets.forEach(function (el) {
        observer.observe(el);
    });

});