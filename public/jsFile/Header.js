document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('mainNavbar');
    var sticky = 200; // Scroll threshold

    function handleScroll() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add('sticky-top');
            document.body.classList.add('fixed-nav');
        } else {
            navbar.classList.remove('sticky-top');
            document.body.classList.remove('fixed-nav');
        }
    }

    window.onscroll = handleScroll;

   
});

