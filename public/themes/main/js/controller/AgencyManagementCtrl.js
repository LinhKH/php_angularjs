AgencyManagementApp.controller('AgencyManagementCtrl', ['$scope', '$rootScope', '$q', '$location', '$sce', 'commonService', 'blockUI',
    function ($scope, $rootScope, $q, $location, $sce, commonService, blockUI) {
        $rootScope.listApoBusinessId = [];
        $rootScope.currentBusinessId = '';
        $rootScope.mode = 'detail';
        $rootScope.unitcdSearch = 1;
        $rootScope.app = {
            baseUrl: commonService.baseURL,
            imgUrl: commonService.baseURL + 'assets/img/',
            userInfo: userInfo,
            class: '',
            callTab: userInfo.main_sale_item_cd,
            windowWidth: $(window).width(),
            windowHeight: $(window).height()
        };
        $rootScope.commonMst = {};
        $rootScope.master = {};
        $rootScope.master.callNameByPosition = [];
        $rootScope.callStorage = {};
        $rootScope.callStorage.cachePicListIndex = [];
        $rootScope.callStorage.cachePicList = [];

        //save flg when redirect from call search to call detail
        $rootScope.callSearchFlg = '';
        $rootScope.callOrder = {field: 'create_time', type: 'desc'};
        $rootScope.keepCallOrder = false;
        $rootScope.cachelistSelectListUser = {};
        $rootScope.cachelistSelectListUser.listuserid = [];
        $rootScope.cachelistSelectListUser.total = [];
        $rootScope.pmIchiranSearch = {};

        $rootScope.getPre = function (x) {
            return $sce.trustAsHtml(x);
        };
        $rootScope.viewDate = function (x) {
            if (x === '0000-00-00 00:00:00' || x === '0000-00-00' || x === '00:00:00') {
                x = null;
            }
            return x ? x.replace(/[-]/g, '/') : null;
        };

        // get common master
        $rootScope.getCommonMst = function (mstId, callback) {
            var newMstId = [];
            var deferred = $q.defer();
            var promise = deferred.promise;
            promise.then(function () {
                angular.forEach(mstId, function (value) {
                    if (!$rootScope.commonMst.hasOwnProperty(value)) {
                        newMstId.push(value);
                    }
                });
            }).then(function () {
                if (newMstId.length > 0) {
                    commonService.requestFunction('getCommonMaster', {mst_id: newMstId}, function (res) {
                        if (res.code === 200) {
                            res.data.forEach(function (data) {
                                if ($rootScope.commonMst.hasOwnProperty(data.mst_id)) {
                                    $rootScope.commonMst[data.mst_id].push(data);
                                } else {
                                    $rootScope.commonMst[data.mst_id] = [];
                                    $rootScope.commonMst[data.mst_id].push(data);
                                }
                            });
                            if (typeof callback === 'function') {
                                callback(res);
                            }
                        }
                    });
                }
            });
            deferred.resolve();
        };  
        
        // get m_employee.emp_nm
        $rootScope.getListEmployee = function (callBack) {
            if (typeof $rootScope.callStorage.list_employee === 'undefined' || $rootScope.callStorage.list_employee.length <= 0) {
                commonService.requestFunction('getEmployee', {}, function (res) {
                    if (res.code === 200) {
                        $rootScope.callStorage.list_employee = res;
                        callBack(res);
                    }
                });
            } else {
                callBack($rootScope.callStorage.list_employee);
            }
        };
        
        // get pref list
        $rootScope.getPrefectureList = function () {
            if (typeof $rootScope.master.prefecture === 'undefined' || $rootScope.master.prefecture.length <= 0) {
                commonService.requestFunction('getPrefecture', {}, function (res) {
                    if (res.code === 200) {
                        $rootScope.master.prefecture = res.data;
                    }
                });
            }
        };
        // get pref_nm, city_nm, address3 by zip_cd
        $rootScope.getDataByZipcd = function (zip_cd, callBack) {
            if (zip_cd !== '' && typeof zip_cd !== 'undefined') {
                commonService.requestFunction('getClientByZip', {zipcode: zip_cd}, function (res) {
                    callBack(res.data);
                });
            }
        };

        $rootScope.copyToClipboard = function (val) {
            var $temp = $('<input style="height: 0px">');
            $('body').append($temp);
            $temp.val(val).select();
            document.execCommand('copy');
            $temp.remove();
        };

        $rootScope.diffDate = function ($date) {
            var dateFormat = $date.replace("/", "-");
            var df = new Date(dateFormat);
            var dt = new Date();
            var allYears = dt.getFullYear() - df.getFullYear();
            var partialMonths = dt.getMonth() - df.getMonth();
            if (partialMonths < 0) {
                allYears--;
                partialMonths = partialMonths + 12;
            }
            var result = {year: allYears, month: partialMonths};
            return result;
        };

        $rootScope.stopUi = function (){
            blockUI.stop();
        };

        $rootScope.startUi = function(){
            blockUI.start();
        };
        
        // get pref_nm, city_nm, address3 by zip_cd
        $rootScope.getDataByZipcd = function (zip_cd, callBack) {
            if (zip_cd !== '' && typeof zip_cd !== 'undefined') {
                commonService.requestFunction('getClientByZip', {zipcode: zip_cd}, function (res) {
                    callBack(res.data);
                });
            }
        };
        
        // Msg 
        $rootScope.msg = {
            'MSGI0001': '削除しても宜しいですか？',
            'MSGI0002':	'変更内容が保存されました。',
            'MSGI0003': '新規受注(${param})が登録されました。詳細画面へ移動しますか？',
            'MSGI0004':	'該当データがありませんでした。',
            'MSGI0005': 'データ（{param}件）を削除します。宜しいですか。',
            'MSGI0006': 'APO送信しました。',
            
            'MSGE0001':	'ファイルサイズが10MBを超えましたため、アップロードできません。',
            'MSGE0002': '',
            
            'MSGA0001':	'リース管理で、『審査確定』が「確定」となった2レコード以上は設定できません。',
            'MSGA0002': '必須項目を入力してください。',
            'MSGA0003':	'代理店を入力してください。',
            'MSGA0004': '契約種別が卸になっているため、リース会社名に現金一括を設定してください。',
            'MSGA0005':	'対象データを選択してください。',
            'MSGA0007': 'APO送信を実施します。宜しいですか。',
            'MSGA0008': '当案件はAPO送信済みです。再送信を実施します。宜しいですか。'            
        };
        // close common popup
        $rootScope.commonPopupClose = function () {
            commonService.commonPopupClose();
        };

        $rootScope.download = function(url,filename){
            commonService.download(url,filename);
        };
        
        $scope.$on('$viewContentLoaded', function () {
            setTimeout(function () {
                
            }, 200);
        });
    }
]);