

function appendTaskView() {
    const taskContent = document.querySelector('div.tasks-full-card.print-fit-height');
    // если отобразились элементы слайдера задачи
    if (taskContent) {
        // убираем подписку с события загрузки аякс запросов и получаем id задачи
        BX.removeCustomEvent('onAjaxSuccessFinish', appendTaskView);
        let taskId = taskContent.dataset.taskId;
        if (taskId) {
            // смотрим отображается ли контейнер с полями крайних сроков и ответственных
            let taskFieldContainer = taskContent.querySelector('div.tasks-full-card-field-container.print-before-divider-accent');
            if (taskFieldContainer) {
                let taskFieldsList = taskFieldContainer.querySelector('div.b24-field-list');
                // добавляем новую строку в поля крайних сроков с кнопкой запроса переноса крайнего срока и вешаем
                // на эту кнопку обработчик клика
                if (taskFieldsList) {
                    const parser = new DOMParser();
                    const newElemString = '<div class="b24-field-list-row">' +
                        '<div class="b24-field-list-title --with-separator">Перенос срока:</div>' +
                        '<div class="b24-field-list-value --with-separator">' +
                            '<div class="tasks-field-status">' +
                                '<div class="b24-hover-pill">' +
                                    '<div class="b24-hover-pill-content">' +
                                        '<div class="tasks-field-status-text">Запросить</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                    const doc = parser.parseFromString(newElemString, "text/html");
                    const newElem = doc.querySelector('.b24-field-list-row');
                    taskFieldsList.insertAdjacentElement('beforeend', newElem);
                    let elementForClick = newElem.querySelector('div.tasks-field-status-text');


                    ifUserAreResponcibleCreate(taskId, elementForClick);
                }
            }
        }
    }
}


BX.ready(function(){
    // проверяем что находимся в задаче
    const url = window.location.pathname;
    const isMatch = /^\/company\/personal\/user\/\d+\/tasks\/task\/view\/\d+\//.test(url);
    if (isMatch) {
        BX.addCustomEvent('onAjaxSuccessFinish', appendTaskView);
    }
})

function createPopup(taskId, userId, taskData)
{
    var popup = BX.PopupWindowManager.create("popup-message", BX('element'), {
        content: '<form id="try-to-new-deadline-set" name="try_to_new_deadline_set">' +
            '    <div class="ui-form-row">' +
            '        <div class="ui-form-label">' +
            '            <div class="ui-ctl-label-text">Крайний срок</div>' +
            '        </div>' +
            '        <div class="ui-form-content">' +
            '            <div class="ui-ctl ui-ctl-after-icon ui-ctl-datetime">' +
            '                <div class="ui-ctl-after ui-ctl-icon-calendar"></div>' +
            '                <div class="ui-ctl-element"></div>' +
            '                <input type="hidden" value="" name="new_deadline_date">' +
            '                <input type="hidden" name="task_deadline_id" value="'+ taskId +'">' +
            '                <input type="hidden" name="user_id" value="'+ userId +'">' +
            '                <input type="hidden" name="current_deadline" value="'+ taskData.task.deadline +'">' +
            '                <input type="hidden" name="created_by" value="'+ taskData.task.createdBy +'">' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="ui-form-row">' +
            '        <div class="ui-form-label">' +
            '            <div class="ui-ctl-label-text">Причина переноса</div>' +
            '        </div>' +
            '        <div class="ui-form-content">' +
            '            <div class="ui-ctl ui-ctl-textarea">' +
            '                <label>' +
            '                    <textarea name="new_deadline_reason" class="ui-ctl-element"></textarea>' +
            '                </label>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '</form>',
        zIndex: 100, // z-index
        closeIcon: {
            // объект со стилями для иконки закрытия, при null - иконки не будет
            opacity: 1
        },
        titleBar: 'Запрос переноса крайнего срока',
        closeByEsc: true, // закрытие окна по esc
        darkMode: false, // окно будет светлым или темным
        autoHide: false, // закрытие при клике вне окна
        draggable: true, // можно двигать или нет
        resizable: true, // можно ресайзить
        min_height: 100, // минимальная высота окна
        min_width: 100, // минимальная ширина окна
        lightShadow: true, // использовать светлую тень у окна
        angle: true, // появится уголок
        overlay: {
            // объект со стилями фона
            backgroundColor: 'black',
            opacity: 500
        },
        buttons: [
            new BX.PopupWindowButton({
                text: 'Отправить', // текст кнопки
                id: 'save-btn', // идентификатор
                className: 'ui-btn ui-btn-success', // доп. классы
                events: {
                    click: function() {
                        resetPopupForm(true);
                        this.popupWindow.close();
                    }
                }
            }),
            new BX.PopupWindowButton({
                text: 'Отменить',
                id: 'copy-btn',
                className: 'ui-btn ui-btn-primary',
                events: {
                    click: function() {
                        resetPopupForm();
                        this.popupWindow.close();
                    }
                }
            })
        ],
        events: {
            onPopupShow: function() {
                const calendarIcon = document.querySelector('div.ui-ctl-datetime');
                calendarIcon.addEventListener('click', function (e) {
                    e.preventDefault();
                    const formChange = document.getElementById('try-to-new-deadline-set');
                    const dateInput = formChange.querySelector('input[name="new_deadline_date"]');
                    let dateCallback = function(oDate) {
                        const calendarField = document.querySelector('div.ui-ctl-element');
                        calendarField.textContent = BX.date.format("d.m.Y H:i", oDate);
                    }
                    BX.calendar({
                        node: this,
                        field: dateInput,
                        bTime: true,
                        callback_after: dateCallback
                    });

                })
            },
            onPopupClose: function() {
                // Событие при закрытии окна
            }
        }
    });

    popup.show();
}

// метод для очистки формы при закрытии попапа
function resetPopupForm(isForSend = false) {
    const formForSend = document.getElementById('try-to-new-deadline-set');
    const calendarField = document.querySelector('div.ui-ctl-element');

    if (isForSend) {
        BX.ajax.runAction('fedor:taskpermissions.api.Iblock.createElement', {
            data: {
                params: BX.ajax.prepareForm(formForSend).data,
            }
        }).then(function (response) {
            console.log('success');
        }, function (response) {
            //сюда будут приходить все ответы, у которых status !== 'success'
            if (response.status === 'error') {
                let respData = response.data;
                alert(response.errors[0].message);
            }
        });
    }

    formForSend.reset();
    calendarField.textContent = '';
}

// проверяем является ли текущий пользователь ответственным или соисполнителем по задаче
function ifUserAreResponcibleCreate(taskId, newElem) {
    BX.rest.callBatch({
        "task_info": ['tasks.task.get', {
            taskId: taskId,
            select: ['*']
        }],
        "user_info": ['user.current', {}]
    }, function (res) {
        let userData = res.user_info.answer.result;
        let taskData = res.task_info.answer.result;

        if (userData && taskData) {
            let userId = userData.ID;
            let accompliceUsers = taskData.task.accomplices;
            let responsibleUser = [taskData.task.responsibleId];
            if (BX.util.in_array(userId, BX.util.array_merge(accompliceUsers, responsibleUser))) {
                newElem.addEventListener('click', function (e) {
                    e.preventDefault();
                    createPopup(taskId, userId, taskData);
                });
            }
        }


    });
}