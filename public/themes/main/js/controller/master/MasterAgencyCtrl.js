AgencyManagementApp.controller('MasterAgencyCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile',
    function ($scope, $rootScope, commonService, $timeout, $compile) {
        $scope.urlList = 'listMasterAgency';
        $scope.urlUpsert = 'upsertMasterAgency';
        $scope.urlDelete = 'deleteMasterAgency';
        $scope.urlDetail = 'detailMasterAgency';
        $scope.title = '代理店マスタ';

        /** use hold data for input like: select,radio check, checkbox */
        $scope.arrBoolean = [ 
         
        ];
        
        /** List form fields */
        $scope.arrFields = [
            {   
                'key':'agency_cd','label':'代理店コード','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'agency_nm','label':'代理店名','type': 'text','data_source':{},'attr':[]
            }

        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}'
            },
            {
                'label':'代理店コード','content':'{{item.agency_cd}}'
            },
            {
                'label':'代理店名','content':'{{item.agency_nm}}'
            },
            {
                'label':'資料','content':'<p ng-repeat="doc in item.list_file"><a class="pointer" ng-click="download(doc.doc_url,doc.doc_filename)">{{doc.doc_filename}}</a></p>'
            },
            {
                'label':'編集','class':'text-center',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>'
            }
        ];

        
        $scope.createTableContent = function () {
            var content = '';
            var count = 1; 
            angular.forEach($scope.arrTableData, function (v, i) {
                if (typeof v.content == 'undefined') {
                    v.content = '';
                }
                if(count==1) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" style="width:3% !important;">' + v.content + '</td>';
                } else if (count==4) {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1" align="center">' + v.content + '</td>';
                } else {
                    content += '<td class="col-sm-1 col-md-1 col-lg-1 '+v.class+'">' + v.content + '</td>';
                }
                // align="center"
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
                } else {
                    header += '<th class="col-sm-1 col-md-1 col-lg-1 bg-color-grey">' + v.label + '</th>';
                }
                count++;
            });
            $scope.tableHeader = '<tr>' + header + '</tr>';
        };
        
        $scope.$on('$viewContentLoaded', function () {
            $scope.getDocument();
        });

        $scope.add = function(){
            $scope.mode = 'add';
            $scope.current = {};
            $('#modalUpsertAgency').modal('show');
        }

        $scope.edit = function ($id) {
            $scope.mode = 'edit';
            $rootScope.startUi();
            commonService.requestFunction($scope.urlDetail, {id: $id}, function (res) {
                if (res.code === 200) {
                    $scope.current = res.data;
                    $('#modalUpsertAgency').modal('show');
                }
                $rootScope.stopUi();
            });
        };

        $scope.saveUpsert = function () {
            $rootScope.startUi();
            commonService.requestFunction($scope.urlUpsert, $scope.current, function (res) {
                if (res.code === 200) {
                    $scope.getList();
                    $('#modalUpsertAgency').modal('hide');
                    commonService.showAlert($rootScope.msg.MSGI0002);
                }
                $rootScope.stopUi();
            });
        };

        $scope.getDocument = function(){
            commonService.requestFunction('listMasterDoc', {}, function (res) {
                if (res.code === 200) {
                    $scope.document = res.data.list;   
                }
            });
        };
    }
]);
