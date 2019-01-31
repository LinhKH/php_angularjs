AgencyManagementApp.controller('MasterMailCtrl', ['$scope', '$rootScope', 'commonService', '$timeout', '$compile',
    function ($scope, $rootScope, commonService, $timeout, $compile) {
        $scope.urlList = 'listMasterMail';
        $scope.urlUpsert = 'upsertMasterMail';
        $scope.urlDelete = 'deleteMasterMail';
        $scope.urlDetail = 'detailMasterMail';
        $scope.title = 'メールマスタ';

        /** use hold data for input like: select,radio check, checkbox */
        //1: To    2:Cc  3:Bcc
        $scope.arrType = [ 
            {'val':'To','key':'1'},
            {'val':'Cc','key':'2'}, 
            {'val':'Bcc','key':'3'}, 
        ];
        
        /** List form fields */
        $scope.arrFields = [
            {   
                'key':'name','label':'送信先者','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'mail_address','label':'メールアドレス','type': 'text','data_source':{},'attr':[]
            },
            {
                'key':'type','label':'区分','type': 'select','data_source':{'data':'arrType','disp_val':'val','code_val':'key'},'attr':[]
            }
        ];

        /** Config table seach result */
        $scope.arrTableData = [
            {
                'label':'No','content':'{{($index+1) + (currentPage-1)*pageSize}}'
            },
            {
                'label':'送信先者','content':'{{item.name}}'
            },
            {
                'label':'メールアドレス','content':'{{item.mail_address}}'
            },
            {
                'label':'区分','content':'{{item.type}}'
            },
            {
                'label':'編集','class':'text-center',
                'content': '<button ng-click="edit(item.id)" class="btn btn-primary" type="button">編集</button>'
                                    +'<button ng-click="confirmDelete(item.id)" class="btn btn-danger" type="button">削除</button>'
            }
        ];
       
        
        $scope.$on('$viewContentLoaded', function () {
            
        });


    }
]);
