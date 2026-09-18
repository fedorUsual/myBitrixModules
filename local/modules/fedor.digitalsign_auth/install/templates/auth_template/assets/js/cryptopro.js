function CryptoProSignDocument(params) {
    this.params = params;

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

    this.init();
}
CryptoProSignDocument.prototype = {
    init: async function () {
        const _this = this;

        if (!_this.isExtensionNeeded()) {
            window.cadesplugin_extension_loaded = true;
        }

        const canPromise = !!window.Promise; //Проверка Promise
        if(canPromise) {
            cadesplugin.then(async function () { //Проверка загрузки плагина
                    try {
                        let versionStruct = {csp: null, os: null, plugin: null, uuid: null};

                        let oAbout = await cadesplugin.CreateObjectAsync("CAdESCOM.About");
                        let CurrentPluginVersion = await oAbout.PluginVersion;
                        versionStruct.plugin = await CurrentPluginVersion.toString();

                        if(versionStruct.plugin){
                            await _this.notify.success("Плагин загружен успешно!<br/>" +
                                "Версия плагина: " + versionStruct.plugin);
                        }

                        await oAbout.CSPName(80);
                        let ver = await oAbout.CSPVersion("", 80);
                        versionStruct.csp = (await ver.MajorVersion) + "." + (await ver.MinorVersion) + "." + (await ver.BuildVersion);

                        await _this.notify.success("Криптопровайдер загружен успешно!<br/>" +
                            "Версия криптопровайдера: " + versionStruct.csp);

                    }
                    catch (error) {  //Если их нет, вывод ошибки
                        console.log(error)
                        _this.notify.error(error);
                        return;
                    }

                    await _this.asyncGetCertificates();
                    await _this.signCreate()
                },
                function(error) {
                    _this.notify.error(error);

                    if (isYandex()) {
                        let extUrl = "https://addons.opera.com/en/extensions/details/cryptopro-extension-for-cades-browser-plug-in";
                        _this.notify.open({
                            type: 'info',
                            message: "Для работы в Yandex Browser требуется " + "<a href='" + extUrl + "'>расширение</a> из Opera Store. После установки перезагрузите Yandex Browser."
                        });
                    }
                }
            );
        } else {
            window.addEventListener("message", function (event){
                    if (event.data === "cadesplugin_loaded") {
                        _this.notify.success('Плагин загружен успешно!');

                        _this.asyncGetCertificates();
                        _this.signCreate()

                    } else if(event.data === "cadesplugin_load_error") {
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
        let browserSpecs = this.check_browser();

        //if (isIE()) return false;
        if (browserSpecs.name === 'Edge') { return true; }
        if (browserSpecs.name === 'Opera') { return browserSpecs.version >= 33; }
        if (browserSpecs.name === 'Firefox') { return browserSpecs.version >= 52; }
        if (browserSpecs.name === 'Chrome') { return browserSpecs.version >= 42; }
        if (browserSpecs.name === 'Safari') { return browserSpecs.version >= 11; }
        return true;
    },
    check_browser: function () {
        let ua= navigator.userAgent, tem, M= ua.match(/(opera|yabrowser|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
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
    },
    asyncGetCertificates: async function(){
        let _this = this;
        const store = await cadesplugin.CreateObjectAsync("CAdESCOM.Store")
        store.Open(cadesplugin.CAPICOM_CURRENT_USER_STORE, cadesplugin.CAPICOM_MY_STORE,
            cadesplugin.CAPICOM_STORE_OPEN_MAXIMUM_ALLOWED);

        const Certificates = await store.Certificates;

        const oCertificates = await Certificates.Find(
            cadesplugin.CAPICOM_CERTIFICATE_FIND_SUBJECT_NAME, _this.params.subjectName);

        let err;
        if (oCertificates.Count === 0) {
            err = cadesplugin.getLastError(e);
            console.log("Ошибка! Сообщение: " + (err))
            _this.notify.error("Ошибка! Сообщение: " + (err));
            return;
        }

        this.certificate = await oCertificates.Item(1);

        await store.Close();
    },
    signCreate: async function(){
        let _this = this;
        let oFReader = new FileReader();
        let fileResponse = await fetch(_this.params.filePath, {method: "GET", mode: "cors"});

        let oFile;
        if (fileResponse && fileResponse.ok) {
            oFile = await fileResponse.blob();
            oFile.name = _this.params.fileName;
        }

        if (typeof (oFReader.readAsDataURL) != "function") {
            alert("Method readAsDataURL() is not supported in FileReader.");
            return;
        }
        await oFReader.readAsDataURL(oFile);
        oFReader.onload = async function (oFREvent) {
            let header = ";base64,";
            let sFileData = oFREvent.target.result;
            let sBase64Data = sFileData.substr(sFileData.indexOf(header) + header.length);

            let certificate = await _this.certificate;

            let oSigner = await cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");
            oSigner.Options = await cadesplugin.CAPICOM_CERTIFICATE_INCLUDE_END_ENTITY_ONLY;
            await oSigner.propset_Certificate(certificate);

            let oSignedData = await cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");
            await oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
            await oSignedData.propset_Content(sBase64Data); //Добавить генерацию ключа

            try {
                let signedMessage = await oSignedData.SignCades(oSigner, cadesplugin.CADESCOM_CADES_BES);
                if (signedMessage) {

                    let validateSignedMessage = await _this.validation(signedMessage);
                    _this.notify.success('Подпись успешно создана!');

                    if (validateSignedMessage) {
                        await _this.createSignPdfFile(validateSignedMessage)
                    }
                }
            } catch (e) {
                let err = cadesplugin.getLastError(e);
                console.log("Ошибка! Сообщение: " + (err))
                _this.notify.error("Ошибка! Сообщение: " + (err));
            }
        }
    },
    validation: async function(signedMessage) {
        let oSignedData = await cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");

        try {
            await oSignedData.VerifyCades(signedMessage, cadesplugin.CADESCOM_CADES_BES);
        }
        catch (e) {
            let err = cadesplugin.getLastError(e);
            alert("Failed to verify signature. Error: " + err);
        }
        return args[0].value;
    },
    fileDownload: async function (filePath, fileName) {
        let link = document.createElement('a');

        link.href = filePath; // Путь к файлу, полученный из AJAX-ответа
        link.download = fileName; // Название файла для сохранения
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    },
    blobSignPDF: async function (base64) {
        let byteCharacters = atob(base64);
        let byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++)
            byteNumbers[i] = byteCharacters.charCodeAt(i);

        let byteArray = new Uint8Array(byteNumbers);

        return new Blob([byteArray], {type: 'application/pkcs7-signature'});
    },
    createSignPdfFile: async function (validateSignedMessage) {
        let _this = this;
        let blob = await _this.blobSignPDF(validateSignedMessage);
        let fileName = _this.params.fileName + '.pdf.p7s'
        blob.name = fileName

        let sigFile  = new File([blob], fileName, { type: 'application/pkcs7-signature' });
        let fileURL = window.URL.createObjectURL(sigFile);
        await _this.fileDownload(fileURL, fileName)
    }
}