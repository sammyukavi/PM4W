package com.eyeeza.apps.pm4w.dbmanager;

public class Constants {

    /**
     * IDS and Stuff
     */
    public static final int NOTIFICATION_ID = 1;
    public static final String AUTHORITY = "com.eyeeza.apps.pm4w";
    public static final String ACCOUNT_TYPE = "com.eyeeza.apps.pm4w";
    public static final String ACCOUNT_NAME = "PM4W";

    /**
     * Time formats
     */
    public static final String DATE_TIME_FORMAT = "yyyy-MM-dd HH:mm:ss";
    public static final String DATE_TIME_FORMAT_2 = "dd-MM-yyyy HH:mm";
    public static final String DATE_TIME_FORMAT_3 = "MMM-dd-yyyy";
    public static final String DATE_TIME_FORMAT_4 = "MMM-yyyy";
    public static final String DEFAULT_DATETIME = "0000-00-00 00:00:00";

    /**
     * Database details
     */
    public static final String DATABASE_NAME = "pm4w_db";
    public static final int DATABASE_VERSION = 3;/*You will want to increment this in case you change the database structure.
     for now you will want to leave it like that to avoid losing data once the user updates the app.
    */
    /**
     * Server Status
     */
    public static final int SERVER_OFFLINE = 0;
    public static final int SERVER_UPGRADE = 2;
    /**
     * Information status codes
     */
    public static final int INFORMATION_STATUS_CODE = 3;
    public static final int SUCCESS_STATUS_CODE = 2;
    public static final int WARNING_STATUS_CODE = 1;
    public static final int ERROR_STATUS_CODE = 0;
    /**
     * POST,GET & Json Tags
     */
    public static final String SERVER_INFO_TAG = "server_info";
    public static final String SERVER_STATUS_TAG = "server_status";
    public static final String SERVER_TIME = "server_time";
    public static final String DATA_TAG = "data";
    public static final String REQUEST_STATUS_TAG = "request_status";
    public static final String MESSAGES_TAG = "msgs";
    public static final String APP_VERSION_TAG = "app_version";
    public static final String IMEI_TAG = "device_imei";
    public static final String LAST_KNOWN_LOCATION_TAG = "last_known_location";
    public static final String USER_ACCOUNT_TAG = "user_account";
    public static final String USER_PERMISSIONS_TAG = "user_permissions";
    public static final String ATTENDING_TO_TAG = "attending_to";
    public static final String COLLECTING_FROM_TAG = "collecting_from";
    public static final String EXPENDITURES_TAG = "expenditures";
    public static final String REPAIR_TYPES_TAG = "repair_types";
    public static final String SALES_TAG = "sales";
    public static final String USERS_TAG = "users";
    public static final String WATER_USERS_TAG = "water_users";
    public static final String EVENTS_LOGS_TAG = "event_logs";


    public static final String ID_EVENT_TAG = "id_event";
    public static final String EVENT_UID_TAG = "uid";
    public static final String EVENT_TAG = "event";
    public static final String EVENT_TIME_TAG = "event_time";
    public static final String EVENT_DESCRIPTION_TAG = "event_description";
    public static final String EVENT_AFFECTED_OBJECT_ID_TAG = "affected_object_id";

    public static final String ID_TAG = "id";
    public static final String IDU_TAG = "idu";
    public static final String GROUP_ID_TAG = "group_id";
    public static final String USERNAME_TAG = "username";
    public static final String PASSWORD_TAG = "password";
    public static final String PNUMBER_TAG = "pnumber";
    public static final String EMAIL_TAG = "email";
    public static final String FNAME_TAG = "fname";
    public static final String LNAME_TAG = "lname";
    public static final String AUTH_CODE_TAG = "auth_code";
    public static final String AUTH_KEY_TAG = "auth_key";
    public static final String APP_PREFERRED_LANGUAGE_TAG = "app_preferred_language";
    public static final String ID_GROUP_TAG = "id_group";
    public static final String GROUP_NAME_TAG = "group_name";
    public static final String GROUP_IS_ENABLED_TAG = "group_is_enabled";
    public static final String CAN_ACCESS_SYSTEM_CONFIG_TAG = "can_access_system_config";
    public static final String CAN_RECEIVE_EMAILS_TAG = "can_receive_emails";
    public static final String CAN_ACCESS_APP_TAG = "can_access_app";
    public static final String CAN_SEND_SMS_TAG = "can_send_sms";
    public static final String CAN_RECEIVE_PUSH_NOTIFICATIONS_TAG = "can_receive_push_notifications";
    public static final String CAN_SUBMIT_ATTENDANT_DAILY_SALES_TAG = "can_submit_attendant_daily_sales";
    public static final String CAN_APPROVE_ATTENDANTS_SUBMISSIONS_TAG = "can_approve_attendants_submissions";
    public static final String CAN_APPROVE_TREASURERS_SUBMISSIONS_TAG = "can_approve_treasurers_submissions";
    public static final String CAN_CANCEL_ATTENDANT_DAILY_SALES_TAG = "can_cancel_attendant_daily_sales";
    public static final String CAN_CANCEL_ATTENDANTS_SUBMISSIONS_TAG = "can_cancel_attendants_submissions";
    public static final String CAN_CANCEL_TREASURERS_SUBMISSIONS_TAG = "can_cancel_treasurers_submissions";
    public static final String CAN_ADD_WATER_USERS_TAG = "can_add_water_users";
    public static final String CAN_EDIT_WATER_USERS_TAG = "can_edit_water_users";
    public static final String CAN_DELETE_WATER_USERS_TAG = "can_delete_water_users";
    public static final String CAN_VIEW_WATER_USERS_TAG = "can_view_water_users";
    public static final String CAN_ADD_SALES_TAG = "can_add_sales";
    public static final String CAN_EDIT_SALES_TAG = "can_edit_sales";
    public static final String CAN_DELETE_SALES_TAG = "can_delete_sales";
    public static final String CAN_VIEW_SALES_TAG = "can_view_sales";
    public static final String CAN_VIEW_PERSONAL_SAVINGS_TAG = "can_view_personal_savings";
    public static final String CAN_VIEW_WATER_SOURCE_SAVINGS_TAG = "can_view_water_source_savings";
    public static final String CAN_ADD_WATER_SOURCES_TAG = "can_add_water_sources";
    public static final String CAN_EDIT_WATER_SOURCES_TAG = "can_edit_water_sources";
    public static final String CAN_DELETE_WATER_SOURCES_TAG = "can_delete_water_sources";
    public static final String CAN_VIEW_WATER_SOURCES_TAG = "can_view_water_sources";
    public static final String CAN_ADD_REPAIR_TYPES_TAG = "can_add_repair_types";
    public static final String CAN_EDIT_REPAIR_TYPES_TAG = "can_edit_repair_types";
    public static final String CAN_DELETE_REPAIR_TYPES_TAG = "can_delete_repair_types";
    public static final String CAN_VIEW_REPAIR_TYPES_TAG = "can_view_repair_types";
    public static final String CAN_ADD_EXPENSES_TAG = "can_add_expenses";
    public static final String CAN_EDIT_EXPENSES_TAG = "can_edit_expenses";
    public static final String CAN_DELETE_EXPENSES_TAG = "can_delete_expenses";
    public static final String CAN_VIEW_EXPENSES_TAG = "can_view_expenses";
    public static final String CAN_ADD_SYSTEM_USERS_TAG = "can_add_system_users";
    public static final String CAN_EDIT_SYSTEM_USERS_TAG = "can_edit_system_users";
    public static final String CAN_DELETE_SYSTEM_USERS_TAG = "can_delete_system_users";
    public static final String CAN_VIEW_SYSTEM_USERS_TAG = "can_view_system_users";
    public static final String CAN_ADD_USER_PERMISSIONS_TAG = "can_add_user_permissions";
    public static final String CAN_EDIT_USER_PERMISSIONS_TAG = "can_edit_user_permissions";
    public static final String CAN_DELETE_USER_PERMISSIONS_TAG = "can_delete_user_permissions";
    public static final String CAN_VIEW_USER_PERMISSIONS_TAG = "can_view_user_permissions";
    public static final String GROUP_DATE_CREATED_TAG = "date_created";
    public static final String GROUP_LAST_UPDATED_TAG = "last_updated";

    public static final String ID_WATER_SOURCE_TAG = "id_water_source";
    public static final String WATER_SOURCE_ID_TAG = "water_source_id";
    public static final String WATER_SOURCE_NAME_TAG = "water_source_name";
    public static final String WATER_SOURCE_LOCATION_TAG = "water_source_location";
    public static final String WATER_SOURCE_COORDINATES_TAG = "water_source_coordinates";
    public static final String WATER_SOURCE_MONTHLY_CHARGES_TAG = "monthly_charges";
    public static final String WATER_SOURCE_PERCENTAGE_SAVED_TAG = "percentage_saved";
    public static final String WATER_SOURCE_DATE_CREATED_TAG = "date_created";
    public static final String WATER_SOURCE_LAST_UPDATED_TAG = "last_updated";

    public static final String ID_EXPENDITURE_TAG = "id_expenditure";
    public static final String EXPENDITURE_WATER_SOURCE_ID_TAG = "water_source_id";
    public static final String EXPENDITURE_REPAIR_TYPE_ID_TAG = "repair_type_id";
    public static final String EXPENDITURE_DATE_TAG = "expenditure_date";
    public static final String EXPENDITURE_COST_TAG = "expenditure_cost";
    public static final String BENEFACTOR_TAG = "benefactor";
    public static final String DESCRIPTION_TAG = "description";
    public static final String LOGGED_BY_TAG = "logged_by";
    public static final String EXPENDITURE_MARKED_FOR_DELETE_TAG = "marked_for_delete";
    public static final String EXPENDITURE_DATE_CREATED_TAG = "date_created";
    public static final String EXPENDITURE_LAST_UPDATED_TAG = "last_updated";

    public static final String ID_REPAIR_TYPE_TAG = "id_repair_type";
    public static final String REPAIR_TYPE_TAG = "repair_type";
    public static final String REPAIR_TYPE_ACTIVE_TAG = "active";
    public static final String REPAIR_TYPE_DATE_CREATED_TAG = "date_created";
    public static final String REPAIR_TYPE_LAST_UPDATED_TAG = "last_updated";

    public static final String USER_IDU_TAG = "idu";
    public static final String USER_FNAME_TAG = "fname";
    public static final String USER_LNAME_TAG = "lname";

    public static final String ID_SALE_TAG = "id_sale";
    public static final String SALE_WATER_SOURCE_ID_TAG = "water_source_id";
    public static final String SOLD_BY_TAG = "sold_by";
    public static final String SOLD_TO_TAG = "sold_to";
    public static final String SALE_UGX_TAG = "sale_ugx";
    public static final String SALE_DATE_TAG = "sale_date";
    public static final String PERCENTAGE_SAVED_TAG = "percentage_saved";
    public static final String SUBMITTED_TO_TREASURER_TAG = "submitted_to_treasurer";
    public static final String SUBMITTED_BY_TAG = "submitted_by";
    public static final String SUBMISSION_TO_TREASURER_DATE_TAG = "submittion_to_treasurer_date";
    public static final String TREASURERER_APPROVAL_STATUS_TAG = "treasurerer_approval_status";
    public static final String REVIEWED_BY_TAG = "reviewed_by";
    public static final String DATE_REVIEWED_TAG = "date_reviewed";
    public static final String SALE_MARKED_FOR_DELETE_TAG = "marked_for_delete";
    public static final String DATE_CREATED_TAG = "date_created";
    public static final String LAST_UPDATED_TAG = "last_updated";

    public static final String WATER_USER_ID_TAG = "id_user";
    public static final String WATER_USER_FNAME_TAG = "fname";
    public static final String WATER_USER_LNAME_TAG = "lname";
    public static final String WATER_USER_PNUMBER_TAG = "pnumber";
    public static final String WATER_USER_WATER_SOURCE_ID_TAG = "water_source_id";
    public static final String WATER_USER_DATE_ADDED_TAG = "date_added";
    public static final String ADDED_BY_TAG = "added_by";
    public static final String REPORTED_DEFAULTER_TAG = "reported_defaulter";
    public static final String WATER_USER_MARKED_FOR_DELETE_TAG = "marked_for_delete";
    public static final String WATER_USER_LAST_UPDATED_TAG = "last_updated";


    /**
     * Other tags
     */
    public static final String COMBINED_FNAME_LNAME_TAG = "fname_lname";
    public static final String SAVINGS_TAG = "savings";
    public static final String USER_COUNT_TAG = "user_count";
    public static final String APPROVED_TRANSACTIONS_COUNT_TAG = "approved_transactions_count";
    public static final String DATE_TAG = "date";
    public static final String TRANSACTION_COST_TAG = "transaction_cost";

    /**
     * Database tablenames
     */
    public static final String USER_SESSION_TABLENAME = "app_user_sessions";
    public static final String EVENTS_LOG_TABLENAME = "event_logs";
    public static final String USER_GROUPS_TABLENAME = "user_groups";
    public static final String ATTENDING_TO_TABLENAME = "attending_to";
    public static final String COLLECTING_FROM_TABLENAME = "collecting_from";
    public static final String EXPENDITURES_TABLENAME = "expenditures";
    public static final String REPAIR_TYPES_TABLENAME = "repair_types";
    public static final String SALES_TABLENAME = "sales";
    public static final String USERS_TABLENAME = "users";
    public static final String WATER_USERS_TABLENAME = "water_users";

    /**
     * Queries to drop the database tables
     */
    public static final String DROP_USER_SESSION_TABLE_SQL = "DROP TABLE IF EXISTS " + USER_SESSION_TABLENAME;
    public static final String DROP_EVENTS_LOG_TABLE_SQL = "DROP TABLE IF EXISTS " + EVENTS_LOG_TABLENAME;
    public static final String DROP_USER_GROUPS_TABLE_SQL = "DROP TABLE IF EXISTS " + USER_GROUPS_TABLENAME;
    public static final String DROP_ATTENDING_TO_TABLE_SQL = "DROP TABLE IF EXISTS " + ATTENDING_TO_TABLENAME;
    public static final String DROP_COLLECTING_FROM_TABLE_SQL = "DROP TABLE IF EXISTS " + COLLECTING_FROM_TABLENAME;
    public static final String DROP_EXPENDITURES_TABLE_SQL = "DROP TABLE IF EXISTS " + EXPENDITURES_TABLENAME;
    public static final String DROP_REPAIR_TYPES_TABLE_SQL = "DROP TABLE IF EXISTS " + REPAIR_TYPES_TABLENAME;
    public static final String DROP_SALES_TABLE_SQL = "DROP TABLE IF EXISTS " + SALES_TABLENAME;
    public static final String DROP_USERS_TABLE_SQL = "DROP TABLE IF EXISTS " + USERS_TABLENAME;
    public static final String DROP_WATER_USERS_TABLE_SQL = "DROP TABLE IF EXISTS " + WATER_USERS_TABLENAME;

    /**
     * Queries to create the database tables
     */
    public static final String CREATE_EVENTS_LOG_TABLE_SQL = "CREATE TABLE \"" + EVENTS_LOG_TABLENAME + "\" (\n" +
            "\t`" + ID_EVENT_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + EVENT_UID_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + EVENT_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + EVENT_TIME_TAG + "`\tNUMERIC NOT NULL,\n" +
            "\t`" + EVENT_DESCRIPTION_TAG + "`\tTEXT,\n" +
            "\t`" + EVENT_AFFECTED_OBJECT_ID_TAG + "`\tINTEGER\n" +
            ");";
    public static final String CREATE_EXPENDITURES_TABLE_SQL = "CREATE TABLE \"" + EXPENDITURES_TABLENAME + "\" (\n" +
            "\t`" + ID_EXPENDITURE_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + EXPENDITURE_WATER_SOURCE_ID_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + EXPENDITURE_REPAIR_TYPE_ID_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + EXPENDITURE_DATE_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + EXPENDITURE_COST_TAG + "`\tNUMERIC,\n" +
            "\t`" + BENEFACTOR_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + DESCRIPTION_TAG + "`\tTEXT,\n" +
            "\t`" + LOGGED_BY_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + EXPENDITURE_MARKED_FOR_DELETE_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + EXPENDITURE_DATE_CREATED_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + EXPENDITURE_LAST_UPDATED_TAG + "`\tTEXT NOT NULL\n" +
            ")";
    public static final String CREATE_SALES_TABLE_SQL = "CREATE TABLE " + SALES_TABLENAME + " (\n" +
            "\t`" + ID_SALE_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + SALE_WATER_SOURCE_ID_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + SOLD_BY_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + SOLD_TO_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + SALE_UGX_TAG + "`\tNUMERIC NOT NULL,\n" +
            "\t`" + SALE_DATE_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + PERCENTAGE_SAVED_TAG + "`\tNUMERIC NOT NULL,\n" +
            "\t`" + SUBMITTED_TO_TREASURER_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + SUBMITTED_BY_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + SUBMISSION_TO_TREASURER_DATE_TAG + "`\tTEXT,\n" +
            "\t`" + TREASURERER_APPROVAL_STATUS_TAG + "`\tINTEGER,\n" +
            "\t`" + REVIEWED_BY_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + DATE_REVIEWED_TAG + "`\tTEXT,\n" +
            "\t`" + SALE_MARKED_FOR_DELETE_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + DATE_CREATED_TAG + "`\tTEXT \n," +
            "\t`" + LAST_UPDATED_TAG + "`\tTEXT \n" +
            ")";
    public static final String CREATE_USERS_TABLE_SQL = " CREATE TABLE " + USERS_TABLENAME + " (\n" +
            "\t`" + USER_IDU_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + USER_FNAME_TAG + "`\tTEXT,\n" +
            "\t`" + USER_LNAME_TAG + "`\tTEXT\n" +
            ")";
    public static final String CREATE_ATTENDING_TO_TABLE_SQL = " CREATE TABLE " + ATTENDING_TO_TABLENAME + " (\n" +
            "\t`" + ID_WATER_SOURCE_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + WATER_SOURCE_ID_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_NAME_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_LOCATION_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_COORDINATES_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_MONTHLY_CHARGES_TAG + "`\tNUMERIC,\n" +
            "\t`" + WATER_SOURCE_PERCENTAGE_SAVED_TAG + "`\tNUMERIC,\n" +
            "\t`" + WATER_SOURCE_DATE_CREATED_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_LAST_UPDATED_TAG + "`\tTEXT\n" +
            ")";
    public static final String CREATE_COLLECTING_FROM_TABLE_SQL = " CREATE TABLE " + COLLECTING_FROM_TABLENAME + " (\n" +
            "\t`" + ID_WATER_SOURCE_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + WATER_SOURCE_ID_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_NAME_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_LOCATION_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_COORDINATES_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_MONTHLY_CHARGES_TAG + "`\tNUMERIC,\n" +
            "\t`" + WATER_SOURCE_PERCENTAGE_SAVED_TAG + "`\tNUMERIC,\n" +
            "\t`" + WATER_SOURCE_DATE_CREATED_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_SOURCE_LAST_UPDATED_TAG + "`\tTEXT\n" +
            ")";
    public static final String CREATE_REPAIR_TYPES_TABLE_SQL = " CREATE TABLE " + REPAIR_TYPES_TABLENAME + " (\n" +
            "\t`" + ID_REPAIR_TYPE_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + REPAIR_TYPE_TAG + "`\tTEXT,\n" +
            "\t`" + REPAIR_TYPE_ACTIVE_TAG + "`\tTEXT,\n" +
            "\t`" + REPAIR_TYPE_DATE_CREATED_TAG + "`\tTEXT,\n" +
            "\t`" + REPAIR_TYPE_LAST_UPDATED_TAG + "`\tTEXT\n" +
            ")";
    public static final String CREATE_WATER_USERS_TABLE_SQL = "CREATE TABLE " + WATER_USERS_TABLENAME + " (\n" +
            "\t`" + WATER_USER_ID_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + WATER_USER_FNAME_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + WATER_USER_LNAME_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + WATER_USER_PNUMBER_TAG + "`\tTEXT,\n" +
            "\t`" + WATER_USER_WATER_SOURCE_ID_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + WATER_USER_DATE_ADDED_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + ADDED_BY_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + REPORTED_DEFAULTER_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + WATER_USER_MARKED_FOR_DELETE_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + WATER_USER_LAST_UPDATED_TAG + "`\tTEXT NOT NULL\n" +
            ")";
    public static final String CREATE_USER_GROUPS_TABLE_SQL = "CREATE TABLE " + USER_GROUPS_TABLENAME + " (\n" +
            "\t`" + ID_GROUP_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + GROUP_NAME_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + GROUP_IS_ENABLED_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + CAN_ACCESS_SYSTEM_CONFIG_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_RECEIVE_EMAILS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ACCESS_APP_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_SEND_SMS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_RECEIVE_PUSH_NOTIFICATIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_SUBMIT_ATTENDANT_DAILY_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_APPROVE_ATTENDANTS_SUBMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_APPROVE_TREASURERS_SUBMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_CANCEL_ATTENDANT_DAILY_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_CANCEL_ATTENDANTS_SUBMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_CANCEL_TREASURERS_SUBMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_WATER_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_WATER_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_WATER_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_WATER_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_SALES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_PERSONAL_SAVINGS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_WATER_SOURCE_SAVINGS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_WATER_SOURCES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_WATER_SOURCES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_WATER_SOURCES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_WATER_SOURCES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_REPAIR_TYPES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_REPAIR_TYPES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_REPAIR_TYPES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_REPAIR_TYPES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_EXPENSES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_EXPENSES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_EXPENSES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_EXPENSES_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_SYSTEM_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_SYSTEM_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_SYSTEM_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_SYSTEM_USERS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_ADD_USER_PERMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_EDIT_USER_PERMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_DELETE_USER_PERMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + CAN_VIEW_USER_PERMISSIONS_TAG + "`\tINTEGER NOT NULL,\n" +
            "\t`" + GROUP_DATE_CREATED_TAG + "`\tTEXT NOT NULL,\n" +
            "\t`" + GROUP_LAST_UPDATED_TAG + "`\tTEXT NOT NULL\n" +
            ")";
    protected static final String CREATE_USER_SESSION_TABLE_SQL = " CREATE TABLE " + USER_SESSION_TABLENAME + " (\n" +
            "\t`" + ID_TAG + "`\tINTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,\n" +
            "\t`" + IDU_TAG + "`\tINTEGER,\n" +
            "\t`" + GROUP_ID_TAG + "`\tINTEGER,\n" +
            "\t`" + USERNAME_TAG + "`\tTEXT,\n" +
            "\t`" + PNUMBER_TAG + "`\tTEXT,\n" +
            "\t`" + EMAIL_TAG + "`\tTEXT,\n" +
            "\t`" + FNAME_TAG + "`\tTEXT,\n" +
            "\t`" + LNAME_TAG + "`\tTEXT,\n" +
            "\t`" + AUTH_CODE_TAG + "`\tTEXT,\n" +
            "\t`" + AUTH_KEY_TAG + "`\tTEXT,\n" +
            "\t`" + APP_PREFERRED_LANGUAGE_TAG + "`\tTEXT\n" +
            ")";
    public static boolean SyncInProgress = false;
}
