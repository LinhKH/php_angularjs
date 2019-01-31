AgencyManagementApp.service('fileUpload', function ($http, $location, commonService) {
    this.uploadFileToUrl = function (files, params, urlUpload, callback) {
        var fd = new FormData();
        
        for (var i = 0; i < files.length; i++) {
            var isMulti = $(files[i]).attr('multiple');
            if(isMulti === 'multiple'){
                for (var index = 0; index < files[i].files.length; index++) {
                    fd.append('file[]', files[i].files[index]);    
                }
            }else{
                fd.append('file[]', files[i].files[0]);
            }
        }
        if ($.isEmptyObject(params) === false) {
            for (var pro in params) {
                fd.append(pro, params[pro]);
            }
        }

        $http.post(commonService.baseURL + commonService.LIST_API[urlUpload].url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function (response) {
            switch (response.code) {
                case 200:
                    callback(response);
                    break;
                case 3000:
                    var $sError = commonService.showError(response.error);
                    commonService.showAlert($sError);
                    callback(response);
                    break;
                case 5000:
                    $location.path('/logout');
                    break;
                case 5002:
                    commonService.showAlert(response.data.message);
                    callback(response.data);
                    break;
                default:
                    commonService.showAlert(response.message);
                    callback(response);
                    break;
            }
        }).error(function () {
            //
        });
    };
    
    // Use for uploading a list of files which was added one by one before. 
    this.uploadListFileToUrl = function (fd, params, urlUpload, callback) {
        if ($.isEmptyObject(params) === false) {
            for (var pro in params) {
                fd.append(pro, params[pro]);
            }
        }
        $http.post(commonService.baseURL + commonService.LIST_API[urlUpload].url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function (response) {
            switch (response.code) {
                case 200:
                    callback(response);
                    break;
                case 3000:
                    var $sError = commonService.showError(response.error);
                    commonService.showAlert($sError);
                    callback(response);
                    break;
                case 5000:
                    $location.path('/logout');
                    break;
                case 5002:
                    commonService.showAlert(response.data.message);
                    callback(response.data);
                    break;
                default:
                    commonService.showAlert(response.message);
                    callback(response);
                    break;
            }
        }).error(function () {
            //
        });
    };
});
