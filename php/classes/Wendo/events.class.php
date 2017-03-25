<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Wendo;

/**
 * Description of events
 *
 * @author Sammy N Ukavi Jr
 */
class events {

    /**
     *
     * App events
     */
    public $EVENT_ATTEMPTED_LOGIN = "attempted_login";
    public $EVENT_SETTING_UP_DATABASE_USING_ONLINE_COPY_COMPLETE = "setting_up_database_using_online_copy_complete";
    public $EVENT_ATTEMPTED_PASSWORD_RECOVERY = "attempted_password_recovery";
    public $EVENT_CHOSE_LANGUAGE = "chose_language";
    public $EVENT_VIEWED_HELP = "viewed_help";
    public $EVENT_VIEWED_ABOUT = "viewed_about";
    public $EVENT_VIEWED_DASHBOARD = "viewed_dashboard";
    public $EVENT_ATTEMPTED_LOGOUT = "attempted_login";
    public $EVENT_ADDED_SALE = "added_sale";
    public $EVENT_UPDATED_SALE = "updated_sale";
    public $EVENT_VIEWED_CARETAKER_SALES = "viewed_caretaker_sales";
    public $EVENT_SUBMITTED_CARETAKER_SALES = "submitted_caretaker_sales";
    public $EVENT_APPROVED_CARETAKER_SALES = "submitted_caretaker_sales";
    public $EVENT_CANCELED_CARETAKER_SALES = "canceled_caretaker_sales";
    public $EVENT_VIEWED_TREASURER_SAVINGS = "viewed_treasurer_savings";
    public $EVENT_SUBMITTED_TREASURER_SAVINGS = "submitted_treasurer_savings";
    public $EVENT_VIEWED_WATER_SOURCE_SAVINGS = "viewed_water_source_savings";
    public $EVENT_LOGGED_EXPENDITURE = "added_expense";
    public $EVENT_UPDATED_EXPENDITURE = "updated_expense";
    public $EVENT_DELETED_EXPENDITURE = "deleted_expense";
    public $EVENT_ATTEMPTED_TO_DELETE_EXPENDITURE = "attempted_to_delete_expense";
    public $EVENT_LISTED_EXPENDITURES = "listed_watersource_expenses";
    public $EVENT_VIEWED_ACCOUNT_BALANCE = "viewed_account_balance";
    public $EVENT_VIEWED_MINISTATEMENT = "viewed_ministatement";
    public $EVENT_SYNC_COMPLETE = "sync_completed";
    public $EVENT_SYNC_UNCOMPLETE = "sync_uncompleted";

    /**
     *
     * Website events
     */
    public $EVENT_ATTEMPTED_TO_ADD_EXPENDITURE = "attempted_to_add_expenditure";
    public $EVENT_ATTEMPTED_TO_UPDATE_EXPENDITURE = "attempted_to_update_expenditure";
    public $EVENT_LOGGED_IN = "logged_in";
    public $EVENT_RESUMED_SESSION = "resumed_session";
    public $EVENT_ATTEMPTED_TO_ADD_SALE = "attempted_to_add_water_sale";
    public $EVENT_ATTEMPTED_TO_UPDATE_SALE = "attempted_to_update_sale";
    public $EVENT_ATTEMPTED_TO_DELETE_SALE = "attempted_to_delete_sale";
    public $EVENT_DELETED_SALE = "deleted_sale";
    public $EVENT_LISTED_SALES = "viewed_all_sales";
    public $EVENT_ATTEMPTED_TO_SUBMIT_CARETAKER_SALES = "attempted_to_submit_caretaker_sales";
    public $EVENT_ATTEMPTED_TO_APPROVE_CARETAKER_SALES = "attempted_to_approve_caretaker_sales";
    public $EVENT_ATTEMPTED_TO_CANCEL_CARETAKER_SALES = "attempted_to_cancel_caretaker_sales";
    public $EVENT_ATTEMPTED_TO_SUBMIT_TREASURER_SALES = "attempted_to_submit_treasurer_sales";
    public $EVENT_EDITTED_SETTINGS = "edited_settings";
    public $EVENT_ATTEMPTED_TO_EDIT_SETTINGS = "attempted_to_edit_settings";
    public $EVENT_CREATED_SYSTEM_USER_ACCOUNT = "created_system_user_account";
    public $EVENT_ATTEMPTED_TO_CREATE_SYSTEM_USER_ACCOUNT = "attempted_to_create_system_user_account";
    public $EVENT_UPDATED_SYSTEM_USER_ACCOUNT = "updated_system_user_account";
    public $EVENT_ATTEMPTED_TO_UPDATE_SYSTEM_USER_ACCOUNT = "attempted_to_update_system_user_account";
    public $EVENT_ATTEMPTED_TO_ACTIVATE_SYSTEM_USER_ACCOUNT = "attempted_to_activate_system_user_account";
    public $EVENT_ACTIVATED_SYSTEM_USER_ACCOUNT = "activated_system_user_account";
    public $EVENT_ATTEMPTED_TO_DEACTIVATE_SYSTEM_USER_ACCOUNT = "attempted_to_deactivate_system_user_account";
    public $EVENT_DEACTIVATED_SYSTEM_USER_ACCOUNT = "deactivated_system_user_account";
    public $EVENT_ATTEMPTED_TO_DELETE_SYSTEM_USER_ACCOUNT = "attempted_to_delete_system_user_account";
    public $EVENT_DELETED_SYSTEM_USER_ACCOUNT = "deleted_system_user_account";
    public $EVENT_CREATED_SYSTEM_USER_GROUP = "created_system_user_group";
    public $EVENT_ATTEMPTED_TO_CREATE_SYSTEM_USER_GROUP = "attempted_to_create_system_user_group";
    public $EVENT_UPDATED_SYSTEM_USER_GROUP = "updated_system_user_group";
    public $EVENT_ATTEMPTED_TO_UPDATE_SYSTEM_USER_GROUP = "attempted_to_update_system_user_group";
    public $EVENT_ATTEMPTED_TO_ACTIVATE_SYSTEM_USER_GROUP = "attempted_to_activate_system_user_group";
    public $EVENT_ACTIVATED_SYSTEM_USER_GROUP = "activated_system_user_group";
    public $EVENT_ATTEMPTED_TO_DEACTIVATE_SYSTEM_USER_GROUP = "attempted_to_deactivate_system_user_group";
    public $EVENT_DEACTIVATED_SYSTEM_USER_GROUP = "deactivated_system_user_group";
    public $EVENT_ATTEMPTED_TO_DELETE_SYSTEM_USER_GROUP = "attempted_to_delete_system_user_group";
    public $EVENT_DELETED_SYSTEM_USER_GROUP = "deleted_system_user_group";
    public $EVENT_LISTED_SYSTEM_USERS = "listed_system_users";
    public $EVENT_ATTEMPTED_TO_ADD_WATER_SOURCE = "attempted_to_add_water_source";
    public $EVENT_ADDED_WATER_SOURCE = "added_water_source";
    public $EVENT_ATTEMPTED_TO_UPDATE_WATER_SOURCE = "attempted_to_update_water_source";
    public $EVENT_UPDATED_WATER_SOURCE = "updated_water_source";
    public $EVENT_ATTEMPTED_TO_DELETE_WATER_SOURCE = "attempted_to_delete_water_source";
    public $EVENT_DELETED_WATER_SOURCE = "deleted_water_source";
    public $EVENT_LISTED_WATER_SOURCE = "listed_water_sources";
    public $EVENT_CREATED_WATER_USER_ACCOUNT = "created_water_user_account";
    public $EVENT_ATTEMPTED_TO_CREATE_WATER_USER_ACCOUNT = "attempted_to_create_water_user_account";
    public $EVENT_UPDATED_WATER_USER_ACCOUNT = "updated_water_user_account";
    public $EVENT_ATTEMPTED_TO_UPDATE_WATER_USER_ACCOUNT = "attempted_to_update_water_user_account";
    public $EVENT_ATTEMPTED_TO_ACTIVATE_WATER_USER_ACCOUNT = "attempted_to_activate_water_user_account";
    public $EVENT_ACTIVATED_WATER_USER_ACCOUNT = "activated_water_user_account";
    public $EVENT_ATTEMPTED_TO_DEACTIVATE_WATER_USER_ACCOUNT = "attempted_to_deactivate_water_user_account";
    public $EVENT_DEACTIVATED_WATER_USER_ACCOUNT = "deactivated_water_user_account";
    public $EVENT_ATTEMPTED_TO_DELETE_WATER_USER_ACCOUNT = "attempted_to_delete_water_user_account";
    public $EVENT_DELETED_WATER_USER_ACCOUNT = "deleted_water_user_account";
    public $EVENT_LISTED_WATER_USERS = "listed_water_users";
    public $EVENT_ATTEMPTED_TO_QUEUE_SMS_MESSAGE_FOR_SENDING = "attempted_to_queue_sms_message_for_sending";
    public $EVENT_QUEUED_SMS_MESSAGE_FOR_SENDING = "queued_sms_message_for_sending";
    public $EVENT_ATTEMPTED_TO_DELETE_A_MESSAGE = "attempted_to_delete_a_message";
    public $EVENT_DELETED_A_MESSAGE = "deleted_a_message";
    public $EVENT_ATTEMPTED_TO_DELETE_AN_EVENT_LOG = "attempted_to_delete_an_event_log";
    public $EVENT_DELETED_AN_EVENT_LOG = "deleted_an_event_log";
    public $EVENT_ATTEMPTED_TO_DELETE_REPAIR_TYPES = "attempted_to_delete_a_repair_type";
    public $EVENT_DELETED_A_REPAIR_TYPE = "deleted_a_repair_type";
    public $events = array();

    /**
     * Use this function to get all the events as an array 
     */
    private function eventsToArray() {
        $vars = get_class_vars("events");
        $str = "\$events = array(\n";
        foreach ($vars as $key => $var) {
            if (!is_array($var)) {
                $str.='' . $key . "=>'" . ucwords(str_replace("_", " ", $var)) . "'\n";
            }
        }
        $str.=");";
        echo nl2br($str);
    }

}
