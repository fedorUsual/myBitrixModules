function openRequestSidePanel(requestId) {
    BX.SidePanel.Instance.open('/bitrix/admin/certificates_list.php', {
        requestMethod: "post",
        requestParams: {
            REQUEST_ID: requestId,
            TEMPLATE: ''
        },
        allowChangeHistory: false,
        cacheable: false,
        events: {
            onLoad: function (event) {
                // console.log('onLoad')
            },
            onClose: function (event) {
                // console.log('onClose')
            }
        }
    });
}
