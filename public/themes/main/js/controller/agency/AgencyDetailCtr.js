AgencyManagementApp.controller('AgencyDetailCtr', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile', '$routeParams', 'fileUpload',
    function ($scope, $rootScope, commonService, $timeout, $compile, $routeParams,fileUpload) {
        $rootScope.app.class = '';
        $scope.orderMng = { current: [] };
        $scope.orderMng.status = 'view';
        $scope.$on('$viewContentLoaded', function () {
            //$scope.initApp();                   
            if (typeof $routeParams.id != 'underfine') {
                $oderMngId = $routeParams.id;
            }
            $scope.lease_mng_blank = {
                id: null,
                order_mng_id: $oderMngId,
                lease_comp_cd: null,
                lease_cost_mm: null,
                lease_period_cd: null,
                utm_lease_rate: null,
                inspect_recv_dt: null,
                inspect_sts_flg: null,
                inspect_approve_dt: null,
                inspect_approve_flg: null,
                inspect_determine_dt: null,
                inspect_note: null,
                accept_sts_flg: null,
                accept_recv_dt: null,
                accept_end_dt: null,
                accept_end_flg: null,
                accept_note: null,
                inspect_doc_nm: null,
                inspect_doc_path: null,
                accept_doc_nm: null
                // inspect_doc_path: null,
            };
            $scope.building_detail_blank = {
                id: null,
                order_mng_id: $oderMngId,
                unused_maker_nm: null,
                unused_type: null,
                model_cd: null,
                machine_id: null,
                item_amnt: null
            };
            $scope.work_mng_blank = {
                id: null,
                order_mng_id: $oderMngId,
                inst_place_nm: null,
                inst_zip_cd: null,
                inst_pref_cd: null,
                inst_city_nm: null,
                insta_tel_no: null,
                inst_chrgp_nm: null,
                work_candi_dt: null,
                work_plan_time_from: null,
                work_plan_time_to: null,
                work_confirmed_dt: null,
                work_confirmed_time_from: null,
                work_confirmed_time_to: null,
                work_plan_constructor: null,
                work_sts_flg: null,
                work_end_dt: null,
                order_detail : [{
                    order_detail_id: null,
                    work_order_id: null,
                    order_mng_id: null,
                    machine_id: null,
                    order_dt: null,

                }]
            };

            $scope.initOrderMng();
        });

        $scope.initOrderMng = function () {
            $scope.orderMng.status = 'view';
            var arrCommon = [
                'contract_type', 'project_sts', 'doc_sts', 'warranty_collect_sts', 'building_brand', 'building_type', 'building_model', 'lease_comp', 'lease_period',
                'inspect_sts', 'inspect_confirmed_flg', 'accept_sts', 'accept_confirmed_flg', 'work_sts', 'building_brand'
            ];
            $userLogin = $rootScope.app.userInfo.emp_nm;
            $rootScope.getCommonMst(arrCommon);
            $rootScope.getPrefectureList();
            $scope.orderMngGetInfo();
            $rootScope.getListEmployee(function () { });

        };
        $scope.orderMngGetInfo = function () {
            commonService.requestFunction('getOrderMngInfo', { order_mng_id: $oderMngId }, function (res) {                
                if (res.code === 200) {
                    if (res.data.length === 0) {
                        $scope.orderMng.current = {};
                        window.location = $rootScope.app.baseUrl+"management";
                       
                    } else { 
                        $scope.orderMng.current = res.data.order_mng;
                        var IDlengthAdd = 9 - ($scope.orderMng.current.id.length);
                        var strAdd = "";
                        for (var i = 0; i<IDlengthAdd; i++) {
                            strAdd+="0";
                        }
                        $scope.orderMng.current.idStr = "PTO"+strAdd+angular.copy($oderMngId);  

                        $scope.activeStatus = $scope.orderMng.current.prj_sts_flg; 
                        $scope.statusPrjSttFlg = ['審査待ち','発注待ち','工事待ち','検収待ち','原本待ち','完了']; 
                        if($scope.orderMng.current.accept_dt == '0000/00/00') {
                            $scope.orderMng.current.accept_dt = null;
                        }
                        if($scope.orderMng.current.order_dt == '0000/00/00') {
                            $scope.orderMng.current.order_dt = null;
                        }
                        if($scope.orderMng.current.target_dt == '0000/00') {
                            $scope.orderMng.current.target_dt = null;
                        }
                        if($scope.orderMng.current.app_ok_date == '0000/00/00') {
                            $scope.orderMng.current.app_ok_date = null;
                        }
                        if($scope.orderMng.current.doc_rtn_dt == '0000/00/00') {
                            $scope.orderMng.current.doc_rtn_dt = null;
                        }
                        if($scope.orderMng.current.doc_re_arrv_dt == '0000/00/00') {
                            $scope.orderMng.current.doc_re_arrv_dt = null;
                        }
                        if($scope.orderMng.current.vis_origin_arrv_dt == '0000/00/00') {
                            $scope.orderMng.current.vis_origin_arrv_dt = null;
                        }
                        angular.forEach($scope.orderMng.current.lease_mng, function(value,key) {
                            if(value.inspect_recv_dt == '0000/00/00'  ) {
                                value.inspect_recv_dt = null;
                            }
                            if(value.inspect_approve_dt == '0000/00/00') {
                                value.inspect_approve_dt = null;
                            }
                            if(value.inspect_determine_dt == '0000/00/00') {
                                value.inspect_determine_dt = null;
                            }
                            if(value.accept_recv_dt == '0000/00/00') {
                                value.accept_recv_dt = null;
                            }
                            if(value.accept_end_dt == '0000/00/00') {
                                value.accept_end_dt = null;
                            }
                        });
                        angular.forEach($scope.orderMng.current.work_mng, function(value, key) {
                            if(value.work_candi_dt == '0000/00/00') {
                                value.work_candi_dt = null;
                            }
                            if(value.work_confirmed_dt == '0000/00/00') {
                                value.work_confirmed_dt = null;
                            }
                            if(value.work_end_dt == '0000/00/00') {
                                value.work_end_dt = null;
                            }
                            if(value.work_confirmed_time_from == '00:00') {
                                value.work_confirmed_time_from = null;
                            }
                            if(value.work_confirmed_time_to == '00:00') {
                                value.work_confirmed_time_to = null;
                            }
                            if(value.work_plan_time_from == '00:00') {
                                value.work_plan_time_from = null;
                            }
                            if(value.work_plan_time_to == '00:00') {
                                value.work_plan_time_to = null;
                            }
                        });
                        angular.forEach($scope.orderMng.current.work_mng.order_detail, function(value, key) {
                            if(value.order_dt == '0000/00/00') {
                                value.order_dt = null;
                            }
                        });
                    }
                }
            });
            //get m_agency.agency_nm
            commonService.requestFunction('getAgencyName', {}, function (res) {
                if (res.code === 200) {
                    if (res.data === null) {
                        $scope.agencyNm = {};
                    } else {
                        $scope.agencyNm = res.data;                        
                    }
                }
            });
            //get m_oa_device
            commonService.requestFunction('getDevice', {}, function (res) {                
                if (res.code === 200) {
                    if (res.data === null) {
                        $scope.agencyOaDevice = {};
                    } else {
                        $scope.agencyOaDevice = res.data;                        
                    }
                }
            });  
            $scope.loadTypeCd();  
            $scope.loadProductNm(); 
            $scope.loadModelNm();
            $scope.getCommentOfOrderMng();
            $scope.flgOrderManagement = true;  
            $scope.flgAssetManagement = true;  
            $scope.flgRentManagement = true;  
            $scope.flgManagement4 = true;  
        };
        
        /* COMMENT */
        
        $scope.commentOfOrderMng = [];
        $scope.commentToInsert = null; 
        $scope.delete_id = null; 
        $scope.getCommentOfOrderMng = function() {
            commonService.requestFunction('getCommentOfOrderMng', {order_mng_id: $oderMngId}, function (res) {                
                if (res.code === 200) {
                    $scope.commentOfOrderMng = res.data;                    
                }
            });  
        };
        
        $scope.insertCommentOfOrderMng = function() {
            if($scope.commentToInsert) {
                commonService.requestFunction('insertCommentOfOrderMng', {order_mng_id: $oderMngId
                    , content: $scope.commentToInsert}, function (res) {                
                    if (res.code === 200) {  
                        $scope.getCommentOfOrderMng(); 
                        $scope.commentToInsert = null; 
                    }
                }); 
            }
        };
        
        $scope.disableCommentOfOrderMng = function(id) { 
            $scope.delete_id = id; 
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: $rootScope.msg.MSGI0001,
                button: [
                    {title: 'OK', _function: 'disableCommentOfOrder()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
        };
        
        $scope.disableCommentOfOrder = function() {
            commonService.requestFunction('disableCommentOfOrderMng', {id: $scope.delete_id}, function (res) {                
                if (res.code === 200) {  
                    $scope.getCommentOfOrderMng();
                    commonService.commonPopupClose();
                }
            });
        };
        
        /* END COMMENT */
        
        $scope.showOrderManagement = function() {
            $scope.flgOrderManagement = !$scope.flgOrderManagement;
        };
        $scope.showAssetManagement = function() {
            $scope.flgAssetManagement = !$scope.flgAssetManagement;
        };
        $scope.showRentManagement = function() {
            $scope.flgRentManagement = !$scope.flgRentManagement;
        };
        $scope.showManagement4 = function() {
            $scope.flgManagement4 = !$scope.flgManagement4;
        };
        $scope.resetData = function() {
           $scope.orderMng.current.building_detail[0]['maker_nm'] =  null;
        };
        $scope.resetDataAnother = function($index) {
            $scope.orderMng.current.building_detail[$index].type_cd = null;
            $scope.orderMng.current.building_detail[$index].product_nm = null;
            $scope.orderMng.current.building_detail[$index].machine_id = null;
        };
        $scope.resetDataOrderDetail = function($parent,$index) {
            $scope.orderMng.current.work_mng[$parent].order_detail[$index].type_cd = null;
            $scope.orderMng.current.work_mng[$parent].order_detail[$index].product_nm = null;
            $scope.orderMng.current.work_mng[$parent].order_detail[$index].machine_id = null;            
        };
        $scope.resetDataEmpId = function() {
            $scope.orderMng.current.emp_id = null;
        };
        $scope.loadTypeCd = function() {            
            commonService.requestFunction('agencyTypeCd', {}, function (res) {                
                if (res.code === 200) {
                    if (res.data === null) {
                        $scope.agencyTypeCd = {};
                    } else {
                        $scope.agencyTypeCd = res.data;                   
                    }
                }
            });
        };
        $scope.loadProductNm = function() {            
            commonService.requestFunction('agencyProductNm', {}, function (res) {                
                if (res.code === 200) {
                    if (res.data === null) {
                        $scope.agencyProductNm = {};
                    } else {
                        $scope.agencyProductNm = res.data;                 
                    }
                }
            });
        };
        $scope.loadModelNm = function() {           
            commonService.requestFunction('agencyModelNm', {}, function (res) {                
                if (res.code === 200) {
                    if (res.data === null) {
                        $scope.agencyModelNm = {};
                    } else {
                        $scope.agencyModelNm = res.data;        
                    }
                }
            });
        }; 
        
        // sendAPOCallSystem
        $scope.sendAPOCallSystem = function() {
            if($scope.orderMng.status !== 'view') {
                return false;
            }
            var msgAlert = ($scope.orderMng.current.apo_sent_flg==='1')? $rootScope.msg.MSGA0008 : $rootScope.msg.MSGA0007; 
            commonService.commonPopupOpen($scope, {
                title: '',
                content: msgAlert,
                button: [
                    {title: 'OK', _function: 'confirmSendAPOCallSystem()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
        };
        $scope.confirmSendAPOCallSystem = function() {
            commonService.requestFunction('sendAPOCallSystem', {orderMng: $scope.orderMng.current.id}, function (res) {                
                if (res.code === 200) {
                    commonService.showAlert($rootScope.msg.MSGI0006);
                } else {
                    commonService.showAlert(res.message);
                }
                $scope.initOrderMng();
            });
        };
        
        // End sendAPOCallSystem
        
        $scope.toggleButton = function() {
            $scope.orderMng.status = 'edit';
        };
        $scope.editOrderMng = function () {            
            if($scope.orderMng.status == 'view') {
                $scope.orderMng.status = 'edit';
            } else {
                var validatedContractType = true;
                var validatedInspectApproveFlg = true;

                // validatedContractType
                if ($scope.orderMng.current.lease_mng && $scope.orderMng.current.lease_mng.length > 0) {
                    angular.forEach($scope.orderMng.current.lease_mng, function(value, key) {
                        if(value.lease_comp_cd !== '07' && $scope.orderMng.current.contract_type_cd === '02') {                           
                            validatedContractType = false;
                        }                         
                    });                                   
                }
                if(validatedContractType === false) {
                    commonService.showAlert($rootScope.msg.MSGA0004);
                    return false;
                }
                 // validatedInspectApproveFlg
                 if($scope.orderMng.current.lease_mng) {
                    $scope.arrByInspectApproveFlg = $scope.orderMng.current.lease_mng.filter(function(item) {
                        if (item.inspect_approve_flg == '01') {
                            return true;
                        } 
                        return false;
                    });
                }
                

                if($scope.arrByInspectApproveFlg && $scope.arrByInspectApproveFlg.length > 1) {
                    validatedInspectApproveFlg = false;
                }
                if(validatedInspectApproveFlg === false) {
                    commonService.showAlert($rootScope.msg.MSGA0001);
                    return false;
                }
                var params = Object.assign({}, $scope.orderMng.current);
                params.order_mng_id = $oderMngId;
                params.order_mng = $scope.orderMng.current;
                commonService.requestFunction('updateOderMngInfo', params, function (res) {
                    if (res.code === 200) {
                        $scope.initOrderMng();
                        commonService.showAlert($rootScope.msg.MSGI0002);
                        $scope.orderMng.status = 'view';
                    }
                });
            }
        };
        $scope.addBuildingDetail = function () {
            $scope.orderMng.current.building_detail.push(angular.copy($scope.building_detail_blank));
        };
        $scope.delBuildingDetail = function ($index) {
            $del_index = $index;
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: '削除します。よろしいですか。',
                button: [
                    {title: 'OK', _function: 'confirmDelBuildingDetail()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
            
        };
        $scope.confirmDelBuildingDetail = function() {
            if ($scope.orderMng.current.building_detail.length > 0) {
                $scope.orderMng.current.building_detail.splice($del_index, 1);
                commonService.commonPopupClose();
            }
        };
        $scope.commonPopupClose = function() {
            commonService.commonPopupClose();
        };
        $scope.delOrderMngFile = function($index) {
            $del_indexMngFile = $index;
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: '削除します。よろしいですか。',
                button: [
                    {title: 'OK', _function: 'confirmDelOrderMngFile()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
            
        };
        $scope.confirmDelOrderMngFile = function() {            
            if ($scope.orderMng.current.order_mng_file.length > 0) {
                $scope.orderMng.current.order_mng_file.splice($del_indexMngFile, 1);
                commonService.commonPopupClose();
            }
        };
        $scope.addLeaseMngDetail = function () {
            $scope.orderMng.current.lease_mng.push(angular.copy($scope.lease_mng_blank));            
        };
        $scope.delLeaseMng = function ($index) {
            $del_indexLeaseMng = $index;
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: '削除します。よろしいですか。',
                button: [
                    {title: 'OK', _function: 'confirmDelLeaseMng()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
           
        };
        $scope.confirmDelLeaseMng = function() {                        
            if ($scope.orderMng.current.lease_mng.length > 0) {
                $scope.orderMng.current.lease_mng.splice($del_indexLeaseMng, 1);
                commonService.commonPopupClose();
            }
        };
        $scope.addWorkMng = function() {
            $scope.orderMng.current.work_mng.push(angular.copy($scope.work_mng_blank));
        };
        
        $scope.addWorkDetail = function($parentIndex) {            
            $scope.orderMng.current.work_mng[$parentIndex]['order_detail'].push({
                order_detail_id: null,
                work_order_id: null,
                order_mng_id: $oderMngId,
                machine_id: null,
                order_dt: null,
            });
        };
        $scope.delWorkMng = function($index) {            
            $del_indexWorkMng = $index;
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: '削除します。よろしいですか。',
                button: [
                    {title: 'OK', _function: 'confirmDelWorkMng()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
            
        };
        $scope.confirmDelWorkMng = function() {
            if ($scope.orderMng.current.work_mng.length > 0) {
                $scope.orderMng.current.work_mng.splice($del_indexWorkMng, 1);
                commonService.commonPopupClose();
            }
        };
        $scope.delWorkDetail = function($parentIndex, $index) {
            $del_parent = $parentIndex;
            $del_child = $index;            
            commonService.commonPopupOpen($scope, {
                title: '削除',
                content: '削除します。よろしいですか。',
                button: [
                    {title: 'OK', _function: 'confirmDelWorkDetail()'},
                    {title: 'キャンセル', _function: 'commonPopupClose()'}
                ]
            });
            
        };
        $scope.confirmDelWorkDetail = function() {            
            if ($scope.orderMng.current.work_mng[$del_parent]['order_detail'].length > 0) {
                $scope.orderMng.current.work_mng[$del_parent]['order_detail'].splice($del_child, 1);
                commonService.commonPopupClose();
            }
        };

        $scope.checkIfEnterKeyWasPressed = function ($event, $zipcode) {
            var keyCode = $event.which || $event.keyCode;
            if (keyCode === 13) {
                $scope.orderMng.current.pref_cd = '';
                $scope.orderMng.current.state = '';
                if (typeof $zipcode !== 'undefined' && $zipcode !== '' && $zipcode !== null) {
                    $rootScope.getDataByZipcd($zipcode, function (data) {
                        if (data !== null) {
                            $scope.orderMng.current.pref_cd = data.pref_cd;
                            $scope.orderMng.current.state = data.city_nm + data.town_area;
                        }
                    });
                }
            }

        };
        $scope.checkBlurEvent = function ($zipcode) {            
            $scope.orderMng.current.pref_cd = '';
            $scope.orderMng.current.state = '';
            if (typeof $zipcode !== 'undefined' && $zipcode !== '' && $zipcode !== null) {
                $rootScope.getDataByZipcd($zipcode, function (data) {
                    if (data !== null) {
                        $scope.orderMng.current.pref_cd = data.pref_cd;
                        $scope.orderMng.current.state = data.city_nm + data.town_area;
                    }
                });
            }
        };
        $scope.checkInstZipCd = function ($event, $zipcode,$index) {            
            var keyCode = $event.which || $event.keyCode;
            if (keyCode === 13) {
                $scope.orderMng.current.work_mng[$index].inst_pref_cd = '';
                $scope.orderMng.current.work_mng[$index].inst_city_nm = '';
                if (typeof $zipcode !== 'undefined' && $zipcode !== '' && $zipcode !== null) {
                    $rootScope.getDataByZipcd($zipcode, function (data) {
                        if (data !== null) {
                            $scope.orderMng.current.work_mng[$index].inst_pref_cd = data.pref_cd;
                            $scope.orderMng.current.work_mng[$index].inst_city_nm = data.city_nm + data.town_area;
                        }
                    });
                }
            }

        };
        $scope.checkInstZipCdBlur = function ($zipcode,$index) { 
                $scope.orderMng.current.work_mng[$index].inst_pref_cd = '';
                $scope.orderMng.current.work_mng[$index].inst_city_nm = '';
                if (typeof $zipcode !== 'undefined' && $zipcode !== '' && $zipcode !== null) {
                    $rootScope.getDataByZipcd($zipcode, function (data) {
                        if (data !== null) {
                            $scope.orderMng.current.work_mng[$index].inst_pref_cd = data.pref_cd;
                            $scope.orderMng.current.work_mng[$index].inst_city_nm = data.city_nm + data.town_area;
                        }
                    });
                }
        };
        $scope.uploadOrderMngFile = function (element) {
            // blockUI.start();            
            fileUpload.uploadFileToUrl(element, {}, 'uploadOrderMngFile', function (res) {
                element.prop('value', null);
                if (res.code === 200) {                                    
                    angular.forEach(res.message, function (value, key) {
                        $scope.orderMng.current.order_mng_file.push({
                            id: null,  
                            create_time: null,
                            create_user_id: value.create_user_id,
                            order_mng_id: $oderMngId,                                                   
                            file_nm: value.file_nm,
                            file_path: value.file_path,
                            url_preview: value.url_preview
                        });
                    });
                }
                // blockUI.stop();
            });
        };
        
        $scope.downloadFile = function(urlPreview, fileName){
            commonService.download(urlPreview,fileName)
        };

        $scope.uploadInspect = function(element){            
            var id = $(element).attr('data');        
            fileUpload.uploadFileToUrl(element, {id:id}, 'uploadInspect', function (res) {
                element.prop('value', null);
                if (res.code === 200) {   
                                                     
                    angular.forEach(res.message, function (value, key) {
                        $scope.orderMng.current.lease_mng[id].inspect_doc_nm = value.file_nm;
                        $scope.orderMng.current.lease_mng[id].inspect_doc_path = value.file_path;
                        $scope.orderMng.current.lease_mng[id].url_preview_inspect = value.url_preview;
                    });
                }
                // blockUI.stop();
            });
        };
        $scope.uploadAccept = function(element){            
            var id = $(element).attr('data');        
            fileUpload.uploadFileToUrl(element, {id:id}, 'uploadAccept', function (res) {
                element.prop('value', null);
                if (res.code === 200) {                                    
                    angular.forEach(res.message, function (value, key) {
                        $scope.orderMng.current.lease_mng[id].accept_doc_nm = value.file_nm;
                        $scope.orderMng.current.lease_mng[id].accept_doc_path = value.file_path;
                        $scope.orderMng.current.lease_mng[id].url_preview_accept = value.url_preview;
                    });
                }
                // blockUI.stop();
            });
        };


        $scope.toogleCollapse = function($bFlg){
            return $bFlg ? 'fa-caret-up' : 'fa-caret-down';
        };

        $scope.isAgency = function(){
            return ($rootScope.app.userInfo.system_role_cd == 10);
        };

        $scope.hasDisabledClass = function($type){
            if($type == 'agency'){
                return ($scope.isAgency() && $scope.orderMng.status ==='edit');
            }
        };


    }
]);
