//временный файл для скрипта, пока для вёрстки

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('header-selected-item').addEventListener('click', function() {
        const dropdownContent = document.getElementById('header-dropdown-list');
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';

        this.classList.toggle('active', dropdownContent.style.display === 'block');
    });

    document.querySelectorAll('.header-dropdown-content a').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            const selectedValue = item.getAttribute('data-value');

            document.querySelectorAll('.header-dropdown-item').forEach(el => {
                el.classList.remove('header-item-selected');
            });

            item.classList.add('header-item-selected');

            document.querySelector('.header-selected-item-title').textContent = selectedValue;

            document.getElementById('header-dropdown-list').style.display = 'none';

            document.getElementById('header-selected-item').classList.remove('active');
        });
    });

    window.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.header-dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('header-dropdown-list').style.display = 'none';
            document.getElementById('header-selected-item').classList.remove('active'); // Убираем класс active
        }
    });

});