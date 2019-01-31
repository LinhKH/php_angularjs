<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li>
            <a href="{{app.baseUrl}}logout"><i class="fas fa-fw fa-sign-out-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ログアウト</a>
        </li>
        <li>
            <a href="#project-ichiran" data-toggle="collapse" aria-expanded="true"><i class="far fa-fw fa-file-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;案件管理<span class="pull-right"><i class="fas fa-chevron-down"></i></span></a>
            <ul id="project-ichiran" class="collapse">
                <li ng-class="{'is-active': app.module === 'Management' && app.controller === 'AgencyIchiranCtr'}">
                    <a href="<?php echo Uri::base(true).'management'?>">案件一覧</a>
                </li>
            </ul>
        </li>
        <?php if(!empty($user['system_role_cd']) && $user['system_role_cd'] >=50 ): ?>
        <li>
            <a href="#module-master" data-toggle="collapse" aria-expanded="true"><i class="fas fa-th"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;マスタ管理<span class="pull-right"><i class="fas fa-chevron-down"></i></span></a>
            <ul id="module-master" class="collapse">
                <li class="" ng-class="{'is-active': app.module === 'Master' && app.controller === 'MasterEmployeeCtrl'}">
                    <a href="<?php echo Uri::base(true).'master/employee'?>">ユーザーマスタ</a>
                </li>
                <li class="" ng-class="{'is-active': app.module === 'Master' && app.controller === 'MasterAgencyCtrl'}">
                    <a href="<?php echo Uri::base(true).'master/agency'?>">代理店マスタ</a>
                </li>
                <li class="" ng-class="{'is-active': app.module === 'Master' && app.controller === 'MasterOamachineCtrl'}">
                    <a href="<?php echo Uri::base(true).'master/oamachine'?>">OA機器マスタ</a>
                </li>
                <li class="" ng-class="{'is-active': (app.module == 'Master' && app.controller == 'MasterCommonCtrl')}">
                    <a href="<?php echo Uri::base(true).'master/common'?>">汎用マスタ</a>
                </li>
                <li class="" ng-class="{'is-active': (app.module == 'Master' && app.controller == 'MasterMailCtrl')}">
                    <a href="<?php echo Uri::base(true).'master/mail'?>">メール送信先マスタ</a>
                </li>
                <li class="" ng-class="{'is-active': (app.module == 'Master' && app.controller == 'MasterDocumentCtrl')}">
                    <a href="<?php echo Uri::base(true).'master/document'?>">資料マスタ</a>
                </li>
            </ul>
        </li>
        <?php endif;?>

         <li ng-class="{'is-active': app.module === 'Management' && app.controller === 'AgencyDocumentCtr'}">
            <a href="<?php echo Uri::base(true).'management/document'?>"><i class="fas fa-file-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;資料ダウンロード</a>
        </li>

    </ul>
</div>