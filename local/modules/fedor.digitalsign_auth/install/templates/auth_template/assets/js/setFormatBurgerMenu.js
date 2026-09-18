document.addEventListener('DOMContentLoaded', () => {
    const navMenu = document.querySelector('#nav');
    const burgerMenu = document.querySelector('.burger-menu');

    if (!navMenu || !burgerMenu) {
        return;
    }

    const sublists = Array.from(navMenu.querySelectorAll('.nav-sublist'));

    const isThin = localStorage.getItem('sercons_burger_nav') === 'thin';
    if (isThin) {
        navMenu.classList.add('thin');
        sublists.forEach(el => el.classList.remove('open'));
    } else {
        navMenu.classList.remove('thin');
        sublists.forEach(el => {
            if (el.classList.contains('must-open')) {
                el.classList.add('open');
            }
        });
    }

    burgerMenu.addEventListener('click', () => {
        const thinNow = navMenu.classList.toggle('thin');
        localStorage.setItem('sercons_burger_nav', thinNow ? 'thin' : '');

        sublists.forEach(el => {
            el.classList.toggle('open', !thinNow && el.classList.contains('must-open'));
        });
        
        navMenu.classList.toggle('open');
    });
});
