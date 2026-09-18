document.addEventListener('DOMContentLoaded', function() {

    const moreItems = document.querySelectorAll('.card__list-item--more');

    moreItems.forEach(item => {
        const list = item.parentElement;
        const hiddenItems = Array.from(list.querySelectorAll('.card__list-item:not(.card__list-item--more)')).slice(3); // Получаем скрытые элементы после третьего

        // Устанавливаем начальный текст кнопки с количеством скрытых пунктов
        const hiddenCount = hiddenItems.length;
        if (hiddenCount > 0) {
            item.innerHTML = `Еще ${hiddenCount} раздел(-а) <span class="bi bi-arrow-right icon-arrow"></span>`;
        } else {
            item.style.display = 'none'; // Скрываем кнопку, если нет дополнительных пунктов
        }

        // Обработчик клика для разворачивания/сворачивания
        item.addEventListener('click', function(e) {
            e.preventDefault();

            hiddenItems.forEach(el => {
                el.style.display = el.style.display === 'block' ? 'none' : 'block';
            });

            // Изменение текста кнопки в зависимости от состояния
            if (item.textContent.includes('Скрыть')) {
                item.innerHTML = `Еще ${hiddenCount} раздел(-а) <span class="bi bi-arrow-right icon-arrow"></span>`;
            } else {
                item.innerHTML = `Скрыть разделы <span class="bi bi-arrow-left icon-arrow"></span>`;
            }
        });
    });
});
