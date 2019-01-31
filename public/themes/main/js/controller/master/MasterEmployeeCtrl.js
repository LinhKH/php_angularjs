AgencyManagementApp.controller('MasterEmployeeCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile',
    function ($scope, $rootScope, commonService, $timeout, $compile) {
        $scope.urlList = 'listMasterEmployee';
        $scope.urlUpsert = 'upsertMasterEmployee';
        $scope.urlDelete = 'deleteMasterEmployee';
        $scope.urlDetail = 'detailMasterEmployee';
        $scope.title = 'ユーザーマスタ';

        /** use hold data for input like: select,radio check, checkbox */
        $scope.arrRole = [ 
            {'val':'代理店','key':'10'},
            {'val':'ビージョン','key':'20'},
            {'val':'管理者','key':'50'}
        ];
        
        $scope.arrRoleSearch = { 
            '10':'代理店',
            '20':'ビージョン',
            '50':'管理者'
        };
        
        $scope.arrActive = [ 
            {'val':'Active','key':'0'},
            {'val':'Not active','key':'1'} 
        ];
        
        /** List form fields */
        $scope.arrFields = [
            // {   
            //     'key':'emp_id','label':'社員ID','type': 'text','data_source':{},'attr':[]
            // },
            {
                'key':'emp_nm','label':'氏名','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'emp_kana_nm','label':'カナ名','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'agency_cd','label':'代理店','type': 'select','data_source':{'data':'listAgency','disp_val':'agency_nm','code_val':'agency_cd'},'attr':['select2','style="width:100%;"']
            },
            {
                'key':'system_role_cd','label':'システム業務役割','type': 'select','data_source':{'data':'arrRole','disp_val':'val','code_val':'key'},'attr':[]
            },
            {
                'key':'user_id','label':'ユーザーID','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'email','label':'メールアドレス','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'active','label':'有効フラグ','type': 'select','data_source':{'data':'arrActive','disp_val':'val','code_val':'key'},'attr':[]
            }
        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}'
            },
            // {
            //     'label':'社員ID','content':'{{item.emp_id}}'
            // },
            {
                'label':'氏名','content':'{{item.emp_nm}}'
            },
            {
                'label':'カナ名','content':'{{item.emp_kana_nm}}'
            },
            {
                'label':'ユーザーID','content':'{{item.user_id}}'
            },
            {
                'label':'メールアドレス','content':'{{item.email}}'
            },
            {
                'label':'有効フラグ','content':'{{arrActive[item.active].val}}'
            },

            {
                'label':'システム業務役割','content':'{{arrRoleSearch[item.system_role_cd]}}'
            },
            {
                'label':'編集',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="showResetpassword(item.id)" class="btn btn-danger" type="button">Reset Password</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>'
            }
        ];

        // Get agency 
        $scope.listAgency = [];
        $scope.getAgency = function() {
            commonService.requestFunction('getAgency', {}, function (res) {
                if(res.code === 200){
                    $scope.listAgency = res.data;
                }
            });
        };
        $scope.add = function () {
            $scope.current = {isAdd:true};
            $('#modalAddEmployee').modal('show');
        };
        
        $scope.edit = function ($id) {
            $rootScope.startUi();
            commonService.requestFunction($scope.urlDetail, {id: $id}, function (res) {
                if (res.code === 200) {
                    $scope.current = res.data;
                    $scope.current.isAdd = false;
                    $('#modalAddEmployee').modal('show');
                }
                $rootScope.stopUi();
            });
        };
        
        $scope.saveUpsert = function () {
            $rootScope.startUi();
            commonService.requestFunction($scope.urlUpsert, $scope.current, function (res) {
                if (res.code === 200) {
                    $scope.getList();
                    $('#modalAddEmployee').modal('hide');
                }
                $rootScope.stopUi();
            });
        };
        
        $scope.showResetpassword = function ($id) {
            $scope.rspassword = {
                id: $id,
                password: '',
                password_confirm: ''
            };
            $('#modalResetPassword').modal('show');
        };

        $scope.resetPassword = function () {
            commonService.requestFunction('resetPasswordMasterEmployee', $scope.rspassword, function (res) {
                if (res.code === 200) {
                    $scope.getList();
                    $('#modalResetPassword').modal('hide');
                }
            });
        };
        
        $scope.createTableContent = function () {
            var content = '';
            var count = 1; 
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.content == 'undefined') {
                    v.content = '';
                }
                if(count==1) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:3% !important;">' + v.content + '</td>';
                } else if(count==7) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:5% !important;">' + v.content + '</td>';
                } else if(count==9) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:15% !important;" align="center">' + v.content + '</td>';
                } else {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1">' + v.content + '</td>';
                }
                count++;
            });
            $scope.tableContent = '<tr dir-paginate="item in list|itemsPerPage:pageSize" current-page="currentPage" total-items="totalItems" class="view">' + content + '</tr>';
        };
        
        $scope.createTableHeader = function () {
            var header = '';
            var count = 1; 
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.label == 'undefined') {
                    v.label = '';
                }
                if(count==1) {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey" style="width:3% !important;">' + v.label + '</th>';
                } else if(count==7) {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey" style="width:5% !important;">' + v.label + '</th>';
                } else if(count==9) {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey" style="width:15% !important;">' + v.label + '</th>';
                } else {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey">' + v.label + '</th>';
                }
                count++;
            });
            $scope.tableHeader = '<tr>' + header + '</tr>';
        };
        
        /**
         * 
         * @returns {undefined}
         */
        $scope.saveUpsert = function () {
            if($scope.current.system_role_cd=='10' && !$scope.current.agency_cd) {
                commonService.showAlert($rootScope.msg.MSGA0003);
                return false;
            }
            $rootScope.startUi();
            commonService.requestFunction($scope.urlUpsert, $scope.current, function (res) {
                if (res.code === 200) {
                    $scope.getList();
                    $('#modalAddEmployee').modal('hide');
                    commonService.showAlert($rootScope.msg.MSGI0002);
                }
                $rootScope.stopUi();
            });
        };
        
        $scope.$on('$viewContentLoaded', function () {
            $scope.getAgency();
        });
    }
]);
