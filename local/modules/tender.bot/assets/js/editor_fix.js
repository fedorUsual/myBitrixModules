BX.ready(function() {
    // Подписываемся на событие отрисовки карточки CRM
    BX.addCustomEvent('BX.Crm.EntityEditor:onLayout', function(editor) {
        initRichEditor();
    });

    // Подписка на обновление любого поля в карточке
    BX.addCustomEvent('BX.Crm.EntityEditor:onChanged', function(editor, eventArgs) {
      // Если изменилось наше поле или прилетел сигнал от сценария
      if (eventArgs.fieldName === 'UF_CRM_98_AI_SUMMARY') {
        console.log('Поле обновлено сценарием, пересоздаем редактор...');
        initBitrixEditor(); 
      }
    });

    // Форсированный запуск для тестов
    window.forceInitAiEditor = function() {
      initBitrixEditor();
    };

(function() {
    const initRichEditor = () => {
        const fieldName = 'UF_CRM_98_AI_SUMMARY';
        const container = document.querySelector(`[data-cid="${fieldName}"] .ui-entity-editor-content-block`);

        // Если контейнера нет или редактор уже сидит там — выходим
        if (!container || container.querySelector('.bx-html-editor')) return;
        if (typeof BX.HtmlEditor === 'undefined') {
            console.error('BX.HtmlEditor всё еще undefined. Проверьте Extension::load');
            return;
        }

        const content = container.innerHTML;
        const editorId = 'ai_summary_editor_' + Math.floor(Math.random() * 1000);
        container.innerHTML = `<div id="${editorId}_container"></div>`;

        const editor = new BX.HtmlEditor.Init({
            name: editorId,
            id: editorId,
            content: content,
            width: '100%',
            height: '300',
            controlsMap: [
                {id: 'Bold', compact: true, sort: 10},
                {id: 'Italic', compact: true, sort: 20},
                {id: 'OrderedList', compact: true, sort: 30},
                {id: 'UnorderedList', compact: true, sort: 40},
                {id: 'FontSize', compact: false, sort: 50},
            ],
        });
        
        // Рендерим
        editor.Show();
        document.getElementById(editorId + '_container').appendChild(editor.GetLayout());
    };

    // Подписываемся на события CRM
    BX.addCustomEvent('BX.Crm.EntityEditor:onLayout', initRichEditor);
    // На всякий случай запускаем сразу
    setTimeout(initRichEditor, 1000);
})();


    function initBitrixEditor() {
        const fieldName = 'UF_CRM_98_AI_SUMMARY';
        // Ищем контейнер, где лежит текст
        const container = document.querySelector(`[data-cid="${fieldName}"] .ui-entity-editor-content-block`);

        if (container && !container.dataset.editorInit) {
            container.dataset.editorInit = 'y';
            const content = container.innerHTML; // Берем текущее значение (уже с HTML от ИИ)
            container.innerHTML = ''; // Очищаем для вставки редактора

            const editorId = 'ai_rich_editor';
            
            // Инициализация встроенного редактора
            new BX.HtmlEditor.Init({
                name: editorId,
                id: editorId,
                content: content,
                width: '100%',
                height: '400',
                allowPhp: false,
                limitPhpAccess: false,
                display: true,
                controlsMap: [
                    {id: 'Bold', compact: true, sort: 10},
                    {id: 'Italic', compact: true, sort: 20},
                    {id: 'Underline', compact: true, sort: 30},
                    {id: 'RemoveFormat', compact: true, sort: 40},
                    {separator: true, compact: false, sort: 50},
                    {id: 'OrderedList', compact: true, sort: 60},
                    {id: 'UnorderedList', compact: true, sort: 70},
                    {id: 'FontSize', compact: false, sort: 80},
                    {id: 'ForeColor', compact: true, sort: 90},
                    {id: 'FontSelector', compact: false, sort: 100},
                ],
            });

            // Рендерим редактор в наш контейнер
            const editorInstance = BX.HtmlEditor.Get(editorId);
            if (editorInstance) {
                editorInstance.Show();
                container.appendChild(editorInstance.GetLayout());
                
                // Обработка сохранения: когда редактор меняется, пишем в скрытое поле CRM
                BX.addCustomEvent(editorInstance, 'OnContentChanged', function(newHtml) {
                    // Здесь логика синхронизации с моделью данных CRM, 
                    // чтобы кнопка "Сохранить" в карточке увидела изменения
                });
            }
        }
    }
});
