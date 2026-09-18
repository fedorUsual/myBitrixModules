function UserCertificatesDetail(params) {

    console.log(params)

    this.signedParameters = params.signedParameters;
    this.componentName = params.componentName;

    this.repairStandBtn = BX('repair-stand');
    this.requestAdditionalStandBtn = BX('request-additional-stand');

    this.init();
}

UserCertificatesDetail.prototype = {
    init: function () {
        this.lock = false;
        this.setEvents();
    },
    setEvents: function () {
        // BX.bind(this.repairStandBtn, 'click', BX.proxy(this.testAction, this));
        //BX.bind(this.requestAdditionalStandBtn, 'click', BX.proxy(this.test2Action, this));
    },
    testAction: function () {
        if (this.lock) {
            return;
        }
        this.lock = true;

        const _this = this;
        this.addWaitBtn(this.repairStandBtn);
        this.runAction('repairStand')
            .then(function (response) {
                _this.removeWaitBtn(_this.repairStandBtn);
                _this.lock = false;
                _this.showMessage(response.data.message);
            });
    },
    test2Action: function () {
        if (this.lock) {
            return;
        }
        this.lock = true;

        const _this = this;
        this.addWaitBtn(this.requestAdditionalStandBtn);
        this.runAction('requestAdditionalStand')
            .then(function (response) {
                _this.removeWaitBtn(_this.requestAdditionalStandBtn)
                _this.lock = false;
                _this.showMessage(response.data.message);
            });
    },

    runAction: function (action) {
        const _this = this;
        return new Promise(function (resolve, reject) {
            BX.ajax.runComponentAction(_this.componentName, action, {
                mode: 'class',
                signedParameters: _this.signedParameters
            })
                .then(function (response) {
                    resolve(response);
                })
        });
    },
    addWaitBtn: function (btn) {
        btn.classList.add('ui-btn-wait');
    },
    removeWaitBtn: function (btn) {
        btn.classList.remove('ui-btn-wait');
    },
    showMessage: function (message) {
        BX.UI.Dialogs.MessageBox.show({
            message: message,
            modal: true,
            buttons: BX.UI.Dialogs.MessageBoxButtons.OK,
            onOk: function (messageBox) {
                messageBox.close();
            }
        });
    }
}
