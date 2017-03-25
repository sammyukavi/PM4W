<?php

$id_group = $App->getValue('id');
$errors = array();
if (isset($_POST['submit'])) {
    if ($App->can_edit_user_permissions) {
        $params = array(
            'group_name' => $App->postValue('group_name'),
            'group_is_enabled' => $App->postValue('group_is_enabled'),
            'can_access_system_config' => $App->postValue('can_access_system_config'),
            'can_receive_emails' => $App->postValue('can_receive_emails'),
            'can_access_app' => $App->postValue('can_access_app'),
            'can_send_sms' => $App->postValue('can_send_sms'),
            'can_receive_push_notifications' => $App->postValue('can_receive_push_notifications'),
            'can_send_sms' => $App->postValue('can_send_sms'),
            'can_submit_attendant_daily_sales' => $App->postValue('can_submit_attendant_daily_sales'),
            'can_approve_attendants_submissions' => $App->postValue('can_approve_attendants_submissions'),
            'can_approve_treasurers_submissions' => $App->postValue('can_approve_treasurers_submissions'),
            'can_cancel_attendant_daily_sales' => $App->postValue('can_cancel_attendant_daily_sales'),
            'can_cancel_attendants_submissions' => $App->postValue('can_cancel_attendants_submissions'),
            'can_cancel_treasurers_submissions' => $App->postValue('can_cancel_treasurers_submissions'),
            'can_add_water_users' => $App->postValue('can_add_water_users'),
            'can_edit_water_users' => $App->postValue('can_edit_water_users'),
            'can_delete_water_users' => $App->postValue('can_delete_water_users'),
            'can_view_water_users' => $App->postValue('can_view_water_users'),
            'can_add_sales' => $App->postValue('can_add_sales'),
            'can_edit_sales' => $App->postValue('can_edit_sales'),
            'can_delete_sales' => $App->postValue('can_delete_sales'),
            'can_view_sales' => $App->postValue('can_view_sales'),
            'can_view_personal_savings' => $App->postValue('can_view_personal_savings'),
            'can_view_water_source_savings' => $App->postValue('can_view_water_source_savings'),
            'can_add_water_sources' => $App->postValue('can_add_water_sources'),
            'can_edit_water_sources' => $App->postValue('can_edit_water_sources'),
            'can_delete_water_sources' => $App->postValue('can_delete_water_sources'),
            'can_view_water_sources' => $App->postValue('can_view_water_sources'),
            'can_add_repair_types' => $App->postValue('can_add_repair_types'),
            'can_edit_repair_types' => $App->postValue('can_edit_repair_types'),
            'can_delete_repair_types' => $App->postValue('can_delete_repair_types'),
            'can_view_repair_types' => $App->postValue('can_view_repair_types'),
            'can_add_expenses' => $App->postValue('can_add_expenses'),
            'can_edit_expenses' => $App->postValue('can_edit_expenses'),
            'can_delete_expenses' => $App->postValue('can_delete_expenses'),
            'can_view_expenses' => $App->postValue('can_view_expenses'),
            'can_add_system_users' => $App->postValue('can_add_system_users'),
            'can_edit_system_users' => $App->postValue('can_edit_system_users'),
            'can_delete_system_users' => $App->postValue('can_delete_system_users'),
            'can_view_system_users' => $App->postValue('can_view_system_users'),
            'can_add_user_permissions' => $App->postValue('can_add_user_permissions'),
            'can_edit_user_permissions' => $App->postValue('can_edit_user_permissions'),
            'can_delete_user_permissions' => $App->postValue('can_delete_user_permissions'),
            'can_view_user_permissions' => $App->postValue('can_view_user_permissions')
        );

        if (empty($params['group_name'])) {
            $errors[] = "A group's  name is required";
        }

        foreach ($params as $key => $param) {
            if ($key !== 'group_name') {
                if (is_numeric($param)) {
                    $params[$key] = intval($param);
                } else {
                    $params[$key] = 0;
                }
            }
        } if (empty($errors)) {
            $params['id_group'] = $id_group;
            $params['last_updated'] = $App->getCurrentDateTime();
            $uid = $App->saveUserGroup($params);
            if (is_int($uid)) {
                $App->setSessionMessage("User group updated", SUCCESS_STATUS_CODE);
                $App->navigate('/manage/user-groups');
            } else {
                $App->setSessionMessage("An error occured adding the user. Please  try again later", 0);
            }
        } else {
            foreach ($errors as $error) {
                $App->setSessionMessage($error, 0);
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
}

$App->con->where('id_group', $id_group);
$group = $App->con->getOne('user_groups');

if (!isset($group['id_group'])) {
    $App->setSessionMessage("User group does not exist");
    $App->navigate('/manage/user-groups');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
