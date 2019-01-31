<script type="text/javascript">
    var baseURL = '<?php echo \Uri::base(false); ?>';
    var userInfo = {
        emp_id: '<?php echo empty($user['emp_id']) ? '' : $user['emp_id']; ?>',
        emp_nm: '<?php echo empty($user['emp_nm']) ? '' : $user['emp_nm']; ?>',
        system_role_cd: '<?php echo empty($user['system_role_cd']) ? '' : $user['system_role_cd']; ?>',
        agency_cd: '<?php echo empty($user['agency_cd']) ? '' : $user['agency_cd']; ?>',
    };
</script>


<?php
echo \Asset::js([
    'admin/plugins/jquery-1.11.3.min.js',
    'admin/angular/angular.min.js',
    'admin/plugins/bootstrap.min.js',
    'admin/plugins/bootstrap-toggle.min.js',
    'admin/plugins/uniform.min.js',
    'admin/plugins/select2.min.js',
    'admin/plugins/jquery.mCustomScrollbar.js',
    'admin/plugins/jquery-ui.min.js',
    'admin/plugins/moment.min.js',
    'admin/plugins/bootstrap-datepicker.min.js',
    'admin/plugins/bootstrap-datepicker.ja.min.js',
    'admin/plugins/moment/locale/ja.js',
    'admin/plugins/slider_pips.min.js',
    'admin/plugins/jquery.fixedheadertable.min.js',
    'admin/plugins/jquery.nicescroll.js',
    'admin/plugins/monthpicker.min.js',
    'admin/angular/angular-route.min.js',
    'admin/angular/angular-sanitize.min.js',
    'admin/angular/angular-gettext.js',
    'admin/angular/angular-multi-select.js',
    'admin/angular/xeditable.min.js',
    'admin/angular/dirPagination.js',
    'admin/angular/angular-block-ui.min.js',
    'admin/plugins/checklist-model.js',
    'admin/plugins/jquery.dataTables.min.js',
    'admin/plugins/dataTables.fixedColumns.min.js',
    'admin/plugins/signature/base64.js',
    'admin/plugins/signature/draw.js',
    'admin/clockpicker.js'
]);
?>

<?php
$arrFile = [
    'admin/script.js',
    'app.js'
];

// require route
foreach (glob('themes/main/js/route/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require config
foreach (glob('themes/main/js/config/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require service
foreach (glob('themes/main/js/service/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require filter
foreach (glob('themes/main/js/filter/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require directive
foreach (glob('themes/main/js/directive/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require controller
foreach (glob('themes/main/js/controller/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

// require controller
foreach (glob('themes/main/js/controller/*/*.js') as $file) {
    $arrFile[] = str_replace('themes/main/js/', '', $file);
}

echo \Asset::js($arrFile);
