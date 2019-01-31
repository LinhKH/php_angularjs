AgencyManagementApp.service('commonService', function ($http, $compile, $location) {
    var self = this;
    this.baseURL = $('base').attr('href');
    this.commonPopup = $('#listMainSubOfSub');
    this.LIST_API = {
        // Common

        getCommonMaster: {url: 'api/common/getcommonmst', method: 'POST'},
        searchOrderMng: {url: 'api/agency/ichiran/searchOrderMng', method: 'POST'},
        getAgency: {url: 'api/common/agency/getAgency', method: 'POST'},
        getEmployee: {url: 'api/common/employee/getEmployee', method: 'POST'},
        getPrefecture: {url: 'api/common/prefecture/getPrefecture', method: 'POST'},
        saveOrder: {url: 'api/agency/ichiran/saveOrder', method: 'POST'},
        getClientByZip: {url: 'api/common/mpostalcode/getDataByZipCode', method: 'GET'},
        getOrderMngInfo: {url: 'api/agency/detail/detail', method: 'GET'},
        getAgencyName: {url: 'api/agency/detail/agencyName', method: 'GET'},
        getDevice: {url: 'api/agency/detail/getDevice', method: 'GET'},
        agencyTypeCd: {url: 'api/agency/detail/agencyTypeCd', method: 'GET'},
        agencyProductNm: {url: 'api/agency/detail/agencyProductNm', method: 'GET'},
        agencyModelNm: {url: 'api/agency/detail/agencyModelNm', method: 'GET'},
        deleteOrder: {url: 'api/agency/ichiran/delete', method: 'POST'},
        

        // Master 
        listMasterCommon: {url: 'api/master/common/list', method: 'POST'},
        detailMasterCommon: {url: 'api/master/common/detail', method: 'GET'},
        upsertMasterCommon: {url: 'api/master/common/upsert', method: 'POST'},
        deleteMasterCommon: {url: 'api/master/common/delete', method: 'POST'},
        listMasterEmployee: {url: 'api/master/employee/list', method: 'POST'},
        detailMasterEmployee: {url: 'api/master/employee/detail', method: 'GET'},
        upsertMasterEmployee: {url: 'api/master/employee/upsert', method: 'POST'},
        deleteMasterEmployee: {url: 'api/master/employee/delete', method: 'POST'},
        resetPasswordMasterEmployee: {url: 'api/master/employee/resetPassword', method: 'POST'},
        listMasterAgency: {url: 'api/master/agency/list', method: 'POST'},
        detailMasterAgency: {url: 'api/master/agency/detail', method: 'GET'},
        upsertMasterAgency: {url: 'api/master/agency/upsert', method: 'POST'},
        deleteMasterAgency: {url: 'api/master/agency/delete', method: 'POST'},
        listMasterOamachine: {url: 'api/master/oamachine/list', method: 'POST'},
        detailMasterOamachine: {url: 'api/master/oamachine/detail', method: 'GET'},
        upsertMasterOamachine: {url: 'api/master/oamachine/upsert', method: 'POST'},
        deleteMasterOamachine: {url: 'api/master/oamachine/delete', method: 'POST'},
        listMasterMail: {url: 'api/master/mail/list', method: 'POST'},
        detailMasterMail: {url: 'api/master/mail/detail', method: 'GET'},
        upsertMasterMail: {url: 'api/master/mail/upsert', method: 'POST'},
        deleteMasterMail: {url: 'api/master/mail/delete', method: 'POST'},
        listMasterDoc: {url: 'api/master/document/list', method: 'POST'},
        detailMasterDoc: {url: 'api/master/document/detail', method: 'GET'},
        upsertMasterDoc: {url: 'api/master/document/upsert', method: 'POST'},
        deleteMasterDoc: {url: 'api/master/document/delete', method: 'POST'},

        // agency management deetail
        uploadOrderMngFile: {url: 'api/agency/detail/uploadOrderMngFile', method: 'POST'},
        uploadInspect: {url: 'api/agency/detail/uploadInspect', method: 'POST'},
        uploadAccept: {url: 'api/agency/detail/uploadAccept', method: 'POST'},
        updateOderMngInfo: {url: 'api/agency/detail/update', method: 'POST'},
        getCommentOfOrderMng: {url: 'api/agency/detail/getCommentOfOrderMng', method: 'POST'},
        insertCommentOfOrderMng: {url: 'api/agency/detail/insertCommentOfOrderMng', method: 'POST'},
        disableCommentOfOrderMng: {url: 'api/agency/detail/disableCommentOfOrderMng', method: 'POST'},
        
        sendAPOCallSystem: {url: 'api/agency/detail/sendAPOCallSystem', method: 'POST'},
        
        listAgencyDoc: {url: 'api/agency/document/list', method: 'POST'}
        
                

    };

    this.requestFunction = function (api, params, callback) {
        if (typeof api !== 'undefined') {
            var oAPI = self.LIST_API[api];
            var url = self.baseURL + oAPI.url;
            if (oAPI.method === 'POST') {
                var httpConfig = {
                    method: oAPI.method,
                    data: $.param(params),
                    url: url,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };
            } else if (oAPI.method === 'GET') {
                if ($.isEmptyObject(params) === false) {
                    url += '?' + $.param(params);
                }
                var httpConfig = {
                    method: oAPI.method,
                    url: url
                };
            }
            $http(httpConfig).then(function successCallback(response) {
                switch (response.data.code) {
                    case 200:
                        callback(response.data);
                        break;
                    case 5002:
                        if (typeof params.skip === 'undefined' || params.skip !== 5002) {
                            self.showAlert(response.data.message);
                        }
                        callback(response.data);
                        break;
                    case 3000:
                        self.showAlert(self.showError(response.data.error));
                        callback(response.data);
                        break;
                    case 5000:
                        $location.path('/logout');
                        break;
                    default:
                        self.showAlert(response.data.message);
                        callback(response.data);
                        break;
                }
            }, function errorCallback(response) {
                // console.log(response);
            });
        }
    };

    this.showAlert = function (mes) {
        self.commonPopup.find('.modal-title').html('');
        self.commonPopup.find('.content-popup').html(mes);
        self.commonPopup.find('.modal-button').html('');
        self.commonPopup.modal('show');
    };
    this.commonPopupClose = function () {
        self.commonPopup.modal('hide');
    };
    this.commonPopupOpen = function ($scope, $config) {
        var btnTemplate = '<button type="button" class="btn {class}" ng-click="{function}">{title}</button>';
        var obj = self.commonPopup;
        obj.find('.modal-title').html($config.title);
        obj.find('.content-popup').html($config.content);
        var btn = '';
        angular.forEach($config.button, function (v, k) {
            var temp = btnTemplate.replace('{function}', v._function).replace('{title}', v.title).replace('{class}', v.class);
            btn += temp;
        });
        obj.find('.modal-button').html(btn);
        $compile(obj)($scope);
        obj.modal({show: true, backdrop: true});
    };

    this.showError = function ($error) {
        var $return = '';
        switch (typeof $error) {
            case 'string':
                $return = $error;
                break;
            case 'object':
                for (var prop in $error) {
                    if ($error.hasOwnProperty(prop)) {
                        $return += $error[prop] + '</br>';
                    }
                }
                break;
            case 'array':
                $.each($error, function ($i, $v) {
                    $return += $v + '</br>';
                });
                break;
            default:
                break;
        }
        return $return;
    };

    this.serializeObject = function (form) {
        var o = {};
        var a = form.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    this.download = function (url, filename) {
        var sFilename = '';
        if (typeof filename !== 'undefined') {
            sFilename = filename;
        } else {
            sFilename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
        }
        var xhr = new XMLHttpRequest();
        xhr.responseType = 'blob';
        xhr.onload = function () {
            var a = document.createElement('a');
            a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
            a.download = sFilename;
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            delete a;
        };
        xhr.open('GET', url);
        xhr.send();
    };
});

