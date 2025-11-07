BX.ready(function () {
    BX.addCustomEvent("BX.UI.EntityConfigurationManager:onInitialize", BX.delegate((editor, settings) => {

        if (editor.getId() != "intranet-user-profile") {
            return;
        }

        let topMenuId = "#socialnetwork_profile_menu_user_" + editor._entityId;
        let topMenuNode = document.querySelector(topMenuId);

        if (!BX.type.isDomNode(topMenuNode)) {
            return;
        }

        // Получение списка ссылок
        BX.ajax.runAction('dmkuz:lkrouting.routingcontroller.list', {
        }).then(function(response) {
            // console.log('Успех:', response.data);
            const links = response.data;

            // Создаем элементы меню для каждой ссылки
            createMenuItems(links, topMenuNode);

        }).catch(function(response) {
            // console.error('Ошибка:', response.errors);
        });


        // Функция для создания элементов меню из массива ссылок
        function createMenuItems(linksArray, parentNode) {
            if (!linksArray || !Array.isArray(linksArray) || linksArray.length === 0) {
                console.warn('Нет данных для создания меню');
                return;
            }

            // Очищаем существующие элементы (если нужно)
            // const existingItems = parentNode.querySelectorAll('[data-menu-type="dynamic"]');
            // existingItems.forEach(item => item.remove());

            // Создаем элементы для каждой ссылки
            linksArray.forEach((link, index) => {
                const item = createMenuItem(link, index);

                // Добавляем элемент в меню
                // Можно добавить в начало, конец или в определенную позицию
                // BX.insertAfter(item, parentNode.firstElementChild);

                // Или добавить в конец:
                parentNode.appendChild(item);
            });
        }

        function createMenuItem(linkData, index) {

            // Генерируем уникальный ID для элемента
            const itemId = `menu_item_${linkData.ID || index}_${Date.now()}`;
            const url = linkData.URL + '/';
            //     BX.SidePanel.Instance.open("/learning/" + editor._entityId + "/", {
            //         cacheable: false,
            //     });
            // Если в данных есть entityId, используем его, иначе берем из editor
            // const entityId = linkData.entityId || editor._entityId;
            // const url = linkData.URL + '/' ? linkData.URL.replace('{entityId}', entityId) + '/' : `/${linkData.ID}/${entityId}/`;

            const item = BX.create("div", {
                attrs: {
                    className: "main-buttons-item",
                    id: itemId,
                    draggable: true,
                    tabindex: -1,
                },
                dataset: {
                    disabled: false,
                    id: linkData.ID || `link_${index}`,
                    topMenuId: topMenuId,
                    menuType: "dynamic",
                    item: '{"ID":"'+itemId+'","TEXT":"'+(linkData.TITLE || 'Без названия') +'","ON_CLICK":"BX.SidePanel.Instance.open(\''+url+'\')"}',
                },
            });

            // Создаем HTML содержимое
            item.innerHTML = '<span class="main-buttons-item-link">' +
                '<span class="main-buttons-item-text-title">' +
                '<span class="main-buttons-item-text-box">' +
                (linkData.TITLE || 'Без названия') +
                '</span>' +
                '</span>' +
                '</span>';

            return item;
        }
    }));
});