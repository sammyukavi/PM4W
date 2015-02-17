<?php
require_once '../config.php';
require_once SITE_PATH . '/includes/header.php';
require_once SITE_PATH . '/includes/top-nav.php';
?>
<div class="container"> 
    <!--ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
    </ol-->
    <div class="row">
        <div class="col-md-4 col-md-offset-4">                     
            <?php printMessage(); ?>                   
        </div>
    </div>
    <?php
    if (is_logged_in()) {

        switch ($action) {
            case '':
                the_dashboard();
                break;
            case 'add-water-user':
                if ($USER->can_add_water_users) {
                    the_add_water_user_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'edit-water-user':
                if ($USER->can_edit_water_users) {
                    the_edit_water_user_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'view-water-user-transactions':
                if ($USER->can_edit_water_users || $USER->can_view_water_users || $USER->can_delete_water_users) {
                    show_water_user_transactions();
                } else {
                    the_access_denied();
                }
                break;
            case 'water-users':
                if ($USER->can_add_sales || $USER->can_edit_sales || $USER->can_delete_sales) {
                    show_water_users();
                } else {
                    the_access_denied();
                }
                break;

            case 'add-sale':
                if ($USER->can_add_sales) {
                    the_add_sale_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'edit-sale':
                if ($USER->can_edit_sales) {
                    the_edit_sale_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'sales':
                if ($USER->can_add_sales || $USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales) {
                    show_sales();
                } else {
                    the_access_denied();
                }
                break;
            case 'attendants-sales':
                if (($USER->can_add_sales || $USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales)) {
                    show_attendant_sales();
                } else {
                    the_access_denied();
                }
                break;
            case 'user-statement':
                if ($USER->can_add_sales || $USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales) {
                    show_user_statement();
                } else {
                    the_access_denied();
                }
                break;           
            case 'savings':
                if ($USER->can_view_water_source_savings) {
                    show_savings();
                } else {
                    the_access_denied();
                }
                break;
            case 'attendants-submissions':
                if ($USER->can_submit_attendant_daily_sales) {
                    show_submissions_for_attendants();
                } else {
                    the_access_denied();
                }
                break;
            case 'treasurers-submissions':
                if ($USER->can_approve_attendants_submissions) {
                    show_submissions_for_tresurers();
                } else {
                    the_access_denied();
                }
                break;

            case 'add-water-source':
                if ($USER->can_add_water_sources) {
                    the_add_water_source_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'edit-water-source':
                if ($USER->can_edit_water_sources) {
                    the_edit_water_source_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'water-sources':
                if ($USER->can_edit_water_sources || $USER->can_delete_water_sources || $USER->can_view_water_sources) {
                    show_water_sources();
                } else {
                    the_access_denied();
                }
                break;
            case 'water-sources-map':
                the_full_size_map();
                break;
            case 'show-water-source-sales':

                if (($USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales || $USER->can_view_sales ) && $USER->can_view_water_source_savings) {
                    show_water_source_sales();
                } elseif ($USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales || $USER->can_view_sales) {
                    show_personal_sales();
                } else {
                    the_access_denied();
                }
                break;
            case 'show-water-source-attendants':
                if ($USER->can_view_water_source_savings) {
                    show_water_source_caretakers();
                } else {
                    the_access_denied();
                }
                break;
            case 'show-water-source-treasurers':
                if ($USER->can_view_water_source_savings) {
                    show_water_source_treasurers();
                } else {
                    the_access_denied();
                }
                break;
            case 'treasurer-submissions':
                if ($USER->can_view_water_source_savings) {
                    show_submissions();
                } else {
                    the_access_denied();
                }
                break;
            case 'show-water-source-users':
                if ($USER->can_edit_water_users || $USER->can_view_water_users || $USER->can_delete_water_users) {
                    show_water_source_users();
                } else {
                    the_access_denied();
                }
                break;

            case 'add-user':
                if ($USER->can_add_system_users) {
                    the_add_user_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'edit-user':
                if ($USER->can_edit_system_users || $USER->idu == getArrayVal($_GET, 'id')) {
                    the_edit_user_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'users':
                if ($USER->can_edit_system_users || $USER->can_view_system_users || $USER->can_delete_system_users) {
                    show_users();
                } else {
                    the_access_denied();
                }
                break;
            case'add-user-group':
                if ($USER->can_add_user_permissions) {
                    the_add_user_group_form();
                } else {
                    the_access_denied();
                }
                break;
            case'edit-user-group':
                if ($USER->can_edit_user_permissions) {
                    the_edit_user_group_form();
                } else {
                    the_access_denied();
                }
                break;
            case'user-groups':
                if ($USER->can_edit_user_permissions || $USER->can_delete_user_permissions || $USER->can_view_user_permissions) {
                    show_user_groups();
                } else {
                    the_access_denied();
                }
                break;

            case'add-repair-type':
                if ($USER->can_add_repair_types) {
                    the_add_repair_type_form();
                } else {
                    the_access_denied();
                }
                break;
            case'edit-repair-type':
                if ($USER->can_edit_repair_types) {
                    the_edit_repair_type_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'repair-types':
                if ($USER->can_edit_repair_types || $USER->can_delete_repair_types || $USER->can_view_repair_types) {
                    show_all_repair_types();
                } else {
                    the_access_denied();
                }
                break;
            case 'add-expenditure':
                if ($USER->can_add_expenses) {
                    the_add_expenditure_form();
                } else {
                    the_access_denied();
                }
                break;
            case 'edit-expenditure':
                if ($USER->can_edit_expenses) {
                    the_edit_expenditure_form();
                } else {
                    the_access_denied();
                }
                break;
            case'all-expenditures':
                if ($USER->can_edit_expenses || $USER->can_delete_expenses || $USER->can_view_expenses) {
                    show_all_expenditures();
                } else {
                    the_access_denied();
                }
                break;
                
            case 'send-sms':
                the_compose_sms();
                break;
            case 'all-sms':
                show_outgoing_sms();
                break;
            case 'all-notifications':
                show_outgoing_push_messages();
                break;
            case 'send-notification':
                the_compose_notification();
                break;


            case 'configurations':
                if ($USER->can_access_system_config) {
                    the_config_form();
                } else {
                    the_access_denied();
                }
                break;

            default:
                the_you_are_lost();
                break;
        }
    } else {
        switch ($action) {
            case 'forgot-password':
                the_recovery_form();
                break;
            default :
                the_login_form();
        }
    }
    ?>
</div>
<?php
require_once SITE_PATH . '/includes/footer.php';
