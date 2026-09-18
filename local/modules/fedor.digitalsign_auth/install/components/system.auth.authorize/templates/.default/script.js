function CryptoproAuthAuthorize(params) {
    var originalBxOnCustomEvent = BX.onCustomEvent;
    BX.onCustomEvent = function (eventObject, eventName, eventParams, secureParams) {
        originalBxOnCustomEvent.apply(null, [eventObject, eventName, eventParams, secureParams]);

    };
    this.params = params;

    this.form = document.getElementById('formAuthentication');
    this.selectStoreCertificates = this.form.querySelector('#loadCertificate');
    this.btnAuth = this.form.querySelector('.js-btn-send');
    this.btnAuth.disabled = true;

    this.notify = new Notyf({
        duration: 3000,
        position: {
            x: 'right',
            y: 'top',
        },
        types: [
            {
                type: 'success',
                background: 'green',
                duration: 4000,
            },{
                type: 'info',
                background: 'orange',
                duration: 6000,
            },
            {
                type: 'error',
                background: 'indianred',
                duration: 8000,
                dismissible: true
            }
        ]
    });
    /*  Примеры вызова:
        this.notify.success('cadesplugin Загружен успешно!')
        this.notify.open({
            type: 'info',
            message: ''
        });
        this.notify.error('')
     */
    this.setEvents();
    this.init();
}

CryptoproAuthAuthorize.prototype = {
    init: async function () {
        var _this = this;

        if (!_this.isExtensionNeeded()) {
            window.cadesplugin_extension_loaded = true;
        }

        var canPromise = !!window.Promise; //Проверка Promise
        if(canPromise) {
            cadesplugin.then(async function () { //Проверка загрузки плагина
                    try {
                        var versionStruct = {csp: null, os: null, plugin: null, uuid: null};

                        var oAbout = await cadesplugin.CreateObjectAsync("CAdESCOM.About");
                        var CurrentPluginVersion = await oAbout.PluginVersion;
                        versionStruct.plugin = await CurrentPluginVersion.toString();

                        if(versionStruct.plugin){
                            await _this.notify.success("Плагин загружен успешно!<br/>" +
                                "Версия плагина: " + versionStruct.plugin);
                        }

                        await oAbout.CSPName(80);
                        var ver = await oAbout.CSPVersion("", 80);
                        versionStruct.csp = (await ver.MajorVersion) + "." + (await ver.MinorVersion) + "." + (await ver.BuildVersion);

                        await _this.notify.success("Криптопровайдер загружен успешно!<br/>" +
                            "Версия криптопровайдера: " + versionStruct.csp);

                    }
                    catch (error) {  //Если их нет, вывод ошибки
                        console.log(error)
                        _this.notify.error(error);
                        return;
                    }

                    //Если все ок, подгружаем сертификаты
                    await _this.asyncGetCertificates();
                    await _this.asyncLoadCertificatesOnSelect();

                },
                function(error) {
                    _this.notify.error(error);

                    if (isYandex()) {
                        var extUrl = "https://addons.opera.com/en/extensions/details/cryptopro-extension-for-cades-browser-plug-in";
                        _this.notify.open({
                            type: 'info',
                            message: "Для работы в Yandex Browser требуется " + "<a href='" + extUrl + "'>расширение</a> из Opera Store. После установки перезагрузите Yandex Browser."
                        });
                    }
                }
            );
        } else {
            window.addEventListener("message", function (event){
                    if (event.data == "cadesplugin_loaded") {
                        _this.notify.success('Плагин загружен успешно!');

                        _this.asyncGetCertificates();
                        _this.asyncLoadCertificatesOnSelect();

                    } else if(event.data == "cadesplugin_load_error") {
                        // сообщение об ошибке
                        console.log(error,'error')
                        _this.notify.error(error);
                    }
                },
                false);
            window.postMessage("cadesplugin_echo_request", "*");
        }
    },
    isExtensionNeeded: function () {
        var browserSpecs = this.check_browser();

        if (isIE()) return false;
        if (browserSpecs.name == 'Edge') { return true; }
        if (browserSpecs.name == 'Opera') { if (browserSpecs.version >= 33) { return true; } else { return false; } }
        if (browserSpecs.name == 'Firefox') { if (browserSpecs.version >= 52) { return true; } else { return false; } }
        if (browserSpecs.name == 'Chrome') { if (browserSpecs.version >= 42) { return true; } else { return false; } }
        if (browserSpecs.name == 'Safari') { if (browserSpecs.version >= 11) { return true; } else { return false; } }
        return true;
    },
    setEvents: function () {
        BX.bind(this.form, 'submit', BX.proxy(this.eventSendForm, this));
        BX.bind(this.selectStoreCertificates, 'change', BX.proxy(this.eventSelectedSert, this));
    },
    eventSelectedSert: function (event) { //Выбрали сертификат
        this.selectedCertificate = [];

        var selectedCertID = event.target.selectedIndex;
        if (selectedCertID === 0){
            this.notify.error('Вы не выбрали сертификат!');
            return;
        }
        this.selectedCertificate =  this.loadCertificatesUser[--selectedCertID];
    },
    eventSendForm: async function (event) {
        event.preventDefault();

        this._lastError = null;

        //Подписать
        await this.signCreate();

        //Запрос на бек
        await this.fetch(this)
    },

    fetch: async function (_this) {

        if(_this.SignedMessage === undefined){
            this.notify.error("Ошибка! Сообщение: " + err);
        }
        var pubKey = await _this.selectedCertificate.PublicKey();
        var pubKeyAlgorithm = await pubKey.Algorithm;

        BX.ajax.runComponentAction('fedor.digitalsign_auth:system.auth.authorize', 'Send', {
            mode: 'class',
            //signedParameters: this.params.signedParameters,
            data: {
                'signature' : _this.SignedMessage,
                'certificateInfo' :
                    {
                        'SubjectName' :  await _this.selectedCertificate.SubjectName,
                        'IssuerName' : await _this.selectedCertificate.IssuerName,
                        'ValidFromDate' : await _this.selectedCertificate.ValidFromDate,
                        'ValidToDate' : await _this.selectedCertificate.ValidToDate,
                        'Thumbprint' : await _this.selectedCertificate.Thumbprint,
                        'pubKeyAlgorithm' : await pubKeyAlgorithm.FriendlyName,
                    }
            },
        }).then(function (response) {
            console.log(response,'response')
            if (response.data.status === "success") {
                window.location.href = "/";
                return true;
            }

            if (!_this._lastError || _this._lastError !== response.data.message) {
                _this._lastError = response.data.message;
                _this.notify.error(response.data.message);
            }

            //if (!!response.data.backUrl) {
            //window.location.href = response.data.backUrl;
            //}
        }, function (errorResponse) {
            const errMsg = errorResponse?.errors?.join(', ') || errorResponse;
            if (!_this._lastError || _this._lastError !== errMsg) {
                _this._lastError = errMsg;
                _this.notify.error(errMsg);
            }
        });


    },

    asyncGetCertificates: async function(){
        const store = await cadesplugin.CreateObjectAsync("CAdESCOM.Store")
        await store.Open(cadesplugin.CAPICOM_CURRENT_USER_STORE, cadesplugin.CAPICOM_MY_STORE,
            cadesplugin.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED);

        const Certificates = await store.Certificates;
        const CertificatesCnt = await Certificates.Count;

        this.loadCertificatesUser = [];
        for (let i = 1; i <= CertificatesCnt; i++) {
            try {
                var Certificate = await Certificates.Item(i);
                this.loadCertificatesUser.push(Certificate);
            }
            catch (ex) {
                err = cadesplugin.getLastError(ex);
                this.notify.error("Ошибка! Сообщение: " + err);
                return;
            }
        }
        await store.Close();
    },
    asyncLoadCertificatesOnSelect: async function(){
        if(this.loadCertificatesUser === undefined){
            return false;
        }

        this.btnAuth.disabled = false;
        for (let i = 0; i < this.loadCertificatesUser.length; i++) {
            try {
                let oOpt = document.createElement("OPTION");

                let certIssuerName = await this.loadCertificatesUser[i].IssuerName;
                let certSubjectName = await this.loadCertificatesUser[i].SubjectName;
                let certThumbprint = await this.loadCertificatesUser[i].Thumbprint;
                let ValidFromDate = new Date((await this.loadCertificatesUser[i].ValidFromDate));
                let ValidToDate = new Date(await this.loadCertificatesUser[i].ValidToDate);
                let IsValid = ValidToDate > Date.now();
                let emoji = CertStatusEmoji(IsValid);

                oOpt.text = emoji + this.getCertInfoString(certSubjectName, ValidFromDate);
                oOpt.value = certThumbprint;
                oOpt.setAttribute('certIssuerName',certIssuerName);

                this.selectStoreCertificates.options.add(oOpt);

            } catch (ex) {
                err = cadesplugin.getLastError(ex);
                this.notify.error("Ошибка! Сообщение: " + err);
            }
        }
        this.selectStoreCertificates.selectedIndex =0;
    },
    signCreate: async function () {
        var oSigner = await cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");
        await oSigner.propset_Certificate(this.selectedCertificate);

        var dataToSign = btoa(this.params.UID);
        var oSignedData = await cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");
        await oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
        await oSignedData.propset_Content(dataToSign);

        try {
            console.log('UID:', this.params.UID);
            console.log(
                'UID base64:',
                btoa(unescape(encodeURIComponent(this.params.UID)))
            );
            var sSignedMessage = await oSignedData.SignCades(oSigner, cadesplugin.CADESCOM_CADES_BES);
            this.SignedMessage = sSignedMessage;
        }
        catch (e) {
            if (!this._lastError || this._lastError !== e.message) {
                this._lastError = e.message;
                this.notify.error("Ошибка! Сообщение: " + e.message);
            }
        }
    },
    checkQuotes: function(str) {
        var result = 0, i = 0;
        for(i;i<str.length;i++)if(str[i]==='"')
            result++;
        return !(result%2);
    },
    extract: function(from, what) {
        var certName = "";

        var begin = from.indexOf(what);

        if(begin>=0)
        {
            var end = from.indexOf(', ', begin);
            while(end > 0) {
                if (this.checkQuotes(from.substr(begin, end-begin)))
                    break;
                end = from.indexOf(' , ', end + 1);
            }
            certName = (end < 0) ? from.substr(begin) : from.substr(begin, end - begin);
        }

        return certName;
    },
    Print2Digit: function(digit)  {
        return (digit<10) ? "0"+digit : digit;
    },
    GetCertDate: function(paramDate) {
        var certDate = new Date(paramDate);
        return this.Print2Digit(certDate.getUTCDate())+"."+this.Print2Digit(certDate.getUTCMonth()+1)+"."+certDate.getFullYear() + " " +
            this.Print2Digit(certDate.getUTCHours()) + ":" + this.Print2Digit(certDate.getUTCMinutes()) + ":" + this.Print2Digit(certDate.getUTCSeconds());
    },
    getCertInfoString: function(certSubjectName, certFromDate) {
        return this.extract(certSubjectName,'CN=') + " ; Выдан: " + this.GetCertDate(certFromDate);
    },
    check_browser: function () {
        var ua= navigator.userAgent, tem, M= ua.match(/(opera|yabrowser|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])){
            tem =  /\brv[ :]+(\d+)/g.exec(ua) || [];
            return { name:'IE', version:(tem[1] || '')};
        }
        if(M[1] === 'Chrome'){
            tem = ua.match(/\b(OPR|Edg|YaBrowser)\/(\d+)/);
            if (tem != null)
                return { name: tem[1].replace('OPR', 'Opera'), version: tem[2] };
        }
        M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if ((tem = ua.match(/version\/(\d+)/i)) != null)
            M.splice(1, 1, tem[1]);
        return {name:M[0],version:M[1]};
    }
}