AgencyManagementApp.controller('AgencyIchiranCtr', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile', 'fileUpload','blockUI',
    function ($scope, $rootScope, commonService, $timeout, $compile, fileUpload, blockUI) {
        $rootScope.app.class = '';
        $scope.$on('$viewContentLoaded', function () {
            var arrCommon = ['project_sts', 'warranty_collect_sts','doc_sts'];
            $rootScope.getCommonMst(arrCommon);
            $scope.getAgency();
            $scope.clickSearch();
            $scope.getEmployee();
            $scope.getPrefecture();
        });
        
        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.pageSize = 10;
        
        $scope.listAgency = [];
        $scope.searchInput = {};
        $scope.listOrders = [];
        $scope.listEmployee = [];
        $scope.listPrefecture = [];
        $scope.list_ids = [];
        
        $scope.addOrderItem = {};
        $scope.addOrderItem.listFileUpload = []; 
        
        // Get data
        $scope.clickSearch = function() {
            $scope.list_ids = [];
            $scope.isCheckAll = false;
            $scope.searchInput.pageSize = angular.copy($scope.pageSize);
            $scope.searchInput.currentPage = angular.copy($scope.currentPage);
            commonService.requestFunction('searchOrderMng', $scope.searchInput, function (res) {
                if(res.code === 200){
                    $scope.listOrders = res.data['data'];
                    $scope.totalItems = res.data['totalItems'];
                    $scope.currentPage = res.data['currentPage'];
                    $scope.pageSize = res.data['pageSize'];
                    $scope.setCheckBoxValue();
                }
            });
        };


        $scope.checkAll = function ($event) {
            $scope.isCheckAll = false;
            if($event.currentTarget.checked){
                $scope.isCheckAll = true;
            }else{
                $scope.list_ids = [];
            }
            $scope.setCheckBoxValue();
        };

        $scope.checkOne = function ($id, $event) {
            $scope.isCheckAll = false;
            var ele = $event.currentTarget;
            var idx = $scope.list_ids.indexOf($id);
            if(ele.checked && (idx == -1)){
                $scope.list_ids.push($id);
            }else{
                $scope.list_ids.splice(idx, 1);
            }
        };

        $scope.setCheckBoxValue = function(){
            if ($scope.isCheckAll) {
                $scope.list_ids = [];
                angular.forEach($scope.listOrders, function (item) {
                    $scope.list_ids.push(item.id);
                });
            }
        };

        $scope.comfirmDeleteOrder = function(){

            if((!$scope.isCheckAll && $scope.list_ids.length == 0) || ($scope.isCheckAll && $scope.totalItems == 0)  ){
                commonService.showAlert($rootScope.msg.MSGA0005);
                return false;
            }
            var mes = $rootScope.msg.MSGI0005;
            var numRows = $scope.isCheckAll ? $scope.totalItems : $scope.list_ids.length;
            mes = mes.replace('{param}',numRows);

            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: mes,
                button: [
                    {title: 'OK', _function: 'deleteOrder()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });

        };


        $scope.deleteOrder = function(){
            blockUI.start();
            var params = {
                check_all: angular.copy($scope.isCheckAll),
                list_ids : $scope.isCheckAll ? [] : angular.copy($scope.list_ids),
                query_search: $scope.isCheckAll ? angular.copy($scope.searchInput) : [],
            };
            commonService.requestFunction('deleteOrder', params, function (res) {
                if(res.code === 200){
                    $rootScope.commonPopupClose();
                    $scope.clickSearch();
                }
                blockUI.stop();
            });
        };

        
        // Save order
        $scope.idOrder = "";
        $scope.saveOrder = function() {
            // Formdata: list of file to upload 
            var fd = new FormData();
            angular.forEach($scope.addOrderItem.listFileUpload, function(files, key) {
                fd.append('file[]', files);
            });
            
            // Validate required fields
            if(!$scope.addOrderItem.agency_cd || !$scope.addOrderItem.emp_id || !$scope.addOrderItem.company_name || !$scope.addOrderItem.tel) {
                commonService.showAlert($rootScope.msg.MSGA0002);
                return false;
            }
            
            // Save order
            fileUpload.uploadListFileToUrl(fd, $scope.addOrderItem, 'saveOrder', function (res) {
                if (res.code === 200) {
                    $('#modal-ichiran').modal('hide');
                    $scope.clickSearch();
                    $scope.idOrder = res.data;
                    var IDlengthAdd = (9 - ($scope.idOrder.toString().length));
                    var strAdd = "";
                    for (var i = 0; i<IDlengthAdd; i++) {
                        strAdd+="0";
                    }
                    // Msg ask if you want to go to detail page of this order 
                    $scope.idOrderFullMsg = "PTO"+strAdd+angular.copy($scope.idOrder);                    
                    var msg = $rootScope.msg.MSGI0003.replace('(${param})', $scope.idOrderFullMsg);
                    
                    commonService.commonPopupOpen($scope, {
                        title: '',
                        content: msg,
                        button: [
                            {title: 'はい', _function: 'goToDetailPage()', class:'btn-primary'},
                            {title: 'キャンセル', _function: 'commonPopupClose()', class:'btn-default'}
                        ]
                    });
                } // End if 
            }); // End fileUpload
        };
        
        // Go to detail page of this inserted order 
        $scope.goToDetailPage = function () {
            window.location = $rootScope.app.baseUrl+"management/detail/"+$scope.idOrder;
        };
        
        // Upload each file 
        $scope.uploadFile = function(files) {
            if(files.length>0) {
                var filePath = files[0]['name'];
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                if(!allowedExtensions.exec(filePath)){
                    commonService.showAlert("IMAGE/PDFファイルを使用してください。");
                    return false;
                }
                if(files[0]['size'] > 25*1024*1024) {
                    commonService.showAlert("アップロードされたファイルは定義された最大サイズを超えました。");
                    return false; 
                }
                $scope.addOrderItem.listFileUpload.push(files[0]);
            }
            $('#uploadOrderFile').val('');
            $scope.$apply();
        };
        
        // Remove each file upload 
        $scope.removeFileUpload = function(item) {
            var index = $scope.addOrderItem.listFileUpload.indexOf(item);
            $scope.addOrderItem.listFileUpload.splice(index, 1); 
        };
        
        // Open modal add order 
        $scope.openModalAdd = function() {
            $scope.addOrderItem = {listFileUpload:[]};
            
            if($rootScope.app.userInfo.system_role_cd < 20) {
                $scope.addOrderItem.agency_cd = $rootScope.app.userInfo.agency_cd;
                $scope.addOrderItem.emp_id = $rootScope.app.userInfo.emp_id;
            }
        }; 
        
        // search address by zip cd
        $scope.searchAddressByZipCd = function($zipcode){
            if (typeof $zipcode !== 'undefined' && $zipcode) {
                $rootScope.getDataByZipcd($zipcode, function (data) {
                    if (data !== null) {
                        $scope.addOrderItem.pref_cd = data.pref_cd;
                        $scope.addOrderItem.state = data.city_nm + data.town_area;
                    }
                });
            }
        };
        
        // Get agency 
        $scope.getAgency = function() {
            commonService.requestFunction('getAgency', {}, function (res) {
                if(res.code === 200){
                    $scope.listAgency = res.data;
                }
            });
        };
        
        // Get employee
        $scope.getEmployee = function() {
            commonService.requestFunction('getEmployee', {}, function (res) {
                if(res.code === 200){
                    $scope.listEmployee = res.data;
                }
            });
        };
        
        // Get prefecture 
        $scope.getPrefecture = function() {
            commonService.requestFunction('getPrefecture', {}, function (res) {
                if(res.code === 200){
                    $scope.listPrefecture = res.data;
                }
            });
        };
        
        // Pagination 
        $scope.setPagination = function (page) {
            if (typeof page !== 'undefined' && typeof page.pageSize !== 'undefined' && page.pageSize !== $scope.pageSize) {
                $scope.pageSize = page.pageSize;
                if ($scope.currentPage == 1) {
                    $scope.clickSearch();
                } else {
                    $scope.currentPage = 1;
                }
            } else {
                $scope.clickSearch();
            }
        };
    }
]);
