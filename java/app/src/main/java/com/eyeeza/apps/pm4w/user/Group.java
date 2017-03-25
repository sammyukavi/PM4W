package com.eyeeza.apps.pm4w.user;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;

import org.json.JSONObject;

public class Group extends DBoperations {


    public static int ID_GROUP = 0;
    public static String GROUP_NAME = "";
    public static boolean GROUP_IS_ENABLED = false;
    public static boolean CAN_ACCESS_SYSTEM_CONFIG = false;
    public static boolean CAN_RECEIVE_EMAILS = false;
    public static boolean CAN_ACCESS_APP = false;
    public static boolean CAN_SEND_SMS = false;
    public static boolean CAN_RECEIVE_PUSH_NOTIFICATIONS = false;
    public static boolean CAN_SUBMIT_ATTENDANT_DAILY_SALES = false;
    public static boolean CAN_APPROVE_ATTENDANTS_SUBMISSIONS = false;
    public static boolean CAN_APPROVE_TREASURERS_SUBMISSIONS = false;
    public static boolean CAN_CANCEL_ATTENDANT_DAILY_SALES = false;
    public static boolean CAN_CANCEL_ATTENDANTS_SUBMISSIONS = false;
    public static boolean CAN_CANCEL_TREASURERS_SUBMISSIONS = false;
    public static boolean CAN_ADD_WATER_USERS = false;
    public static boolean CAN_EDIT_WATER_USERS = false;
    public static boolean CAN_DELETE_WATER_USERS = false;
    public static boolean CAN_VIEW_WATER_USERS = false;
    public static boolean CAN_ADD_SALES = false;
    public static boolean CAN_EDIT_SALES = false;
    public static boolean CAN_DELETE_SALES = false;
    public static boolean CAN_VIEW_SALES = false;
    public static boolean CAN_VIEW_PERSONAL_SAVINGS = false;
    public static boolean CAN_VIEW_WATER_SOURCE_SAVINGS = false;
    public static boolean CAN_ADD_WATER_SOURCES = false;
    public static boolean CAN_EDIT_WATER_SOURCES = false;
    public static boolean CAN_DELETE_WATER_SOURCES = false;
    public static boolean CAN_VIEW_WATER_SOURCES = false;
    public static boolean CAN_ADD_REPAIR_TYPES = false;
    public static boolean CAN_EDIT_REPAIR_TYPES = false;
    public static boolean CAN_DELETE_REPAIR_TYPES = false;
    public static boolean CAN_VIEW_REPAIR_TYPES = false;
    public static boolean CAN_ADD_EXPENSES = false;
    public static boolean CAN_EDIT_EXPENSES = false;
    public static boolean CAN_DELETE_EXPENSES = false;
    public static boolean CAN_VIEW_EXPENSES = false;
    public static boolean CAN_ADD_SYSTEM_USERS = false;
    public static boolean CAN_EDIT_SYSTEM_USERS = false;
    public static boolean CAN_DELETE_SYSTEM_USERS = false;
    public static boolean CAN_VIEW_SYSTEM_USERS = false;
    public static boolean CAN_ADD_USER_PERMISSIONS = false;
    public static boolean CAN_EDIT_USER_PERMISSIONS = false;
    public static boolean CAN_DELETE_USER_PERMISSIONS = false;
    public static boolean CAN_VIEW_USER_PERMISSIONS = false;
    public static String GROUP_DATE_CREATED = "0000-00-00 00:00:00";
    public static String GROUP_LAST_UPDATED = "0000-00-00 00:00:00";

    public Group(Context context) {
        super(context);
    }

    public void savePermissions(JSONObject group_permissions) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        try {
            values.put(Constants.ID_GROUP_TAG, group_permissions.getInt(Constants.ID_GROUP_TAG));
            values.put(Constants.GROUP_NAME_TAG, group_permissions.getString(Constants.GROUP_NAME_TAG));
            values.put(Constants.GROUP_IS_ENABLED_TAG, group_permissions.getBoolean(Constants.GROUP_IS_ENABLED_TAG));
            values.put(Constants.CAN_ACCESS_SYSTEM_CONFIG_TAG, group_permissions.getBoolean(Constants.CAN_ACCESS_SYSTEM_CONFIG_TAG));
            values.put(Constants.CAN_RECEIVE_EMAILS_TAG, group_permissions.getBoolean(Constants.CAN_RECEIVE_EMAILS_TAG));
            values.put(Constants.CAN_ACCESS_APP_TAG, group_permissions.getBoolean(Constants.CAN_ACCESS_APP_TAG));
            values.put(Constants.CAN_SEND_SMS_TAG, group_permissions.getBoolean(Constants.CAN_SEND_SMS_TAG));
            values.put(Constants.CAN_RECEIVE_PUSH_NOTIFICATIONS_TAG, group_permissions.getBoolean(Constants.CAN_RECEIVE_PUSH_NOTIFICATIONS_TAG));
            values.put(Constants.CAN_SUBMIT_ATTENDANT_DAILY_SALES_TAG, group_permissions.getBoolean(Constants.CAN_SUBMIT_ATTENDANT_DAILY_SALES_TAG));
            values.put(Constants.CAN_APPROVE_ATTENDANTS_SUBMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_APPROVE_ATTENDANTS_SUBMISSIONS_TAG));
            values.put(Constants.CAN_APPROVE_TREASURERS_SUBMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_APPROVE_TREASURERS_SUBMISSIONS_TAG));
            values.put(Constants.CAN_CANCEL_ATTENDANT_DAILY_SALES_TAG, group_permissions.getBoolean(Constants.CAN_CANCEL_ATTENDANT_DAILY_SALES_TAG));
            values.put(Constants.CAN_CANCEL_ATTENDANTS_SUBMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_CANCEL_ATTENDANTS_SUBMISSIONS_TAG));
            values.put(Constants.CAN_CANCEL_TREASURERS_SUBMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_CANCEL_TREASURERS_SUBMISSIONS_TAG));
            values.put(Constants.CAN_ADD_WATER_USERS_TAG, group_permissions.getBoolean(Constants.CAN_ADD_WATER_USERS_TAG));
            values.put(Constants.CAN_EDIT_WATER_USERS_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_WATER_USERS_TAG));
            values.put(Constants.CAN_DELETE_WATER_USERS_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_WATER_USERS_TAG));
            values.put(Constants.CAN_VIEW_WATER_USERS_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_WATER_USERS_TAG));
            values.put(Constants.CAN_ADD_SALES_TAG, group_permissions.getBoolean(Constants.CAN_ADD_SALES_TAG));
            values.put(Constants.CAN_EDIT_SALES_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_SALES_TAG));
            values.put(Constants.CAN_DELETE_SALES_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_SALES_TAG));
            values.put(Constants.CAN_VIEW_SALES_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_SALES_TAG));
            values.put(Constants.CAN_VIEW_PERSONAL_SAVINGS_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_PERSONAL_SAVINGS_TAG));
            values.put(Constants.CAN_VIEW_WATER_SOURCE_SAVINGS_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_WATER_SOURCE_SAVINGS_TAG));
            values.put(Constants.CAN_ADD_WATER_SOURCES_TAG, group_permissions.getBoolean(Constants.CAN_ADD_WATER_SOURCES_TAG));
            values.put(Constants.CAN_EDIT_WATER_SOURCES_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_WATER_SOURCES_TAG));
            values.put(Constants.CAN_DELETE_WATER_SOURCES_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_WATER_SOURCES_TAG));
            values.put(Constants.CAN_VIEW_WATER_SOURCES_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_WATER_SOURCES_TAG));
            values.put(Constants.CAN_ADD_REPAIR_TYPES_TAG, group_permissions.getBoolean(Constants.CAN_ADD_REPAIR_TYPES_TAG));
            values.put(Constants.CAN_EDIT_REPAIR_TYPES_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_REPAIR_TYPES_TAG));
            values.put(Constants.CAN_DELETE_REPAIR_TYPES_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_REPAIR_TYPES_TAG));
            values.put(Constants.CAN_VIEW_REPAIR_TYPES_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_REPAIR_TYPES_TAG));
            values.put(Constants.CAN_ADD_EXPENSES_TAG, group_permissions.getBoolean(Constants.CAN_ADD_EXPENSES_TAG));
            values.put(Constants.CAN_EDIT_EXPENSES_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_EXPENSES_TAG));
            values.put(Constants.CAN_DELETE_EXPENSES_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_EXPENSES_TAG));
            values.put(Constants.CAN_VIEW_EXPENSES_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_EXPENSES_TAG));
            values.put(Constants.CAN_ADD_SYSTEM_USERS_TAG, group_permissions.getBoolean(Constants.CAN_ADD_SYSTEM_USERS_TAG));
            values.put(Constants.CAN_EDIT_SYSTEM_USERS_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_SYSTEM_USERS_TAG));
            values.put(Constants.CAN_DELETE_SYSTEM_USERS_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_SYSTEM_USERS_TAG));
            values.put(Constants.CAN_VIEW_SYSTEM_USERS_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_SYSTEM_USERS_TAG));
            values.put(Constants.CAN_ADD_USER_PERMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_ADD_USER_PERMISSIONS_TAG));
            values.put(Constants.CAN_EDIT_USER_PERMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_EDIT_USER_PERMISSIONS_TAG));
            values.put(Constants.CAN_DELETE_USER_PERMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_DELETE_USER_PERMISSIONS_TAG));
            values.put(Constants.CAN_VIEW_USER_PERMISSIONS_TAG, group_permissions.getBoolean(Constants.CAN_VIEW_USER_PERMISSIONS_TAG));

            values.put(Constants.GROUP_DATE_CREATED_TAG, group_permissions.getString(Constants.GROUP_DATE_CREATED_TAG));
            values.put(Constants.GROUP_LAST_UPDATED_TAG, group_permissions.getString(Constants.GROUP_LAST_UPDATED_TAG));

            db.insertWithOnConflict(Constants.USER_GROUPS_TABLENAME, null, values, SQLiteDatabase.CONFLICT_REPLACE);

        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            db.close();
        }

    }

    public void getAccountPermissions(int group_Id) {
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = null;
        try {
            String selectQuery = "SELECT  * FROM " + Constants.USER_GROUPS_TABLENAME + " WHERE " + Constants.ID_GROUP_TAG + "=" + group_Id;

            cursor = db.rawQuery(selectQuery, null);
            if (cursor != null) {
                cursor.moveToFirst();
            }

            this.ID_GROUP = cursor.getInt(cursor.getColumnIndex(Constants.ID_GROUP_TAG));
            this.GROUP_NAME = cursor.getString(cursor.getColumnIndex(Constants.GROUP_NAME_TAG));
            this.GROUP_IS_ENABLED = cursor.getInt(cursor.getColumnIndex(Constants.GROUP_IS_ENABLED_TAG)) == 1 ? true : false;
            this.CAN_ACCESS_SYSTEM_CONFIG = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ACCESS_SYSTEM_CONFIG_TAG)) == 1 ? true : false;
            this.CAN_RECEIVE_EMAILS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_RECEIVE_EMAILS_TAG)) == 1 ? true : false;
            this.CAN_ACCESS_APP = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ACCESS_APP_TAG)) == 1 ? true : false;
            this.CAN_SEND_SMS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_SEND_SMS_TAG)) == 1 ? true : false;
            this.CAN_RECEIVE_PUSH_NOTIFICATIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_RECEIVE_PUSH_NOTIFICATIONS_TAG)) == 1 ? true : false;
            this.CAN_SUBMIT_ATTENDANT_DAILY_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_SUBMIT_ATTENDANT_DAILY_SALES_TAG)) == 1 ? true : false;
            this.CAN_APPROVE_ATTENDANTS_SUBMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_APPROVE_ATTENDANTS_SUBMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_APPROVE_TREASURERS_SUBMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_APPROVE_TREASURERS_SUBMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_CANCEL_ATTENDANT_DAILY_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_CANCEL_ATTENDANT_DAILY_SALES_TAG)) == 1 ? true : false;
            this.CAN_CANCEL_ATTENDANTS_SUBMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_CANCEL_ATTENDANTS_SUBMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_CANCEL_TREASURERS_SUBMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_CANCEL_TREASURERS_SUBMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_ADD_WATER_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_WATER_USERS_TAG)) == 1 ? true : false;
            this.CAN_EDIT_WATER_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_WATER_USERS_TAG)) == 1 ? true : false;
            this.CAN_DELETE_WATER_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_WATER_USERS_TAG)) == 1 ? true : false;
            this.CAN_VIEW_WATER_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_WATER_USERS_TAG)) == 1 ? true : false;
            this.CAN_ADD_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_SALES_TAG)) == 1 ? true : false;
            this.CAN_EDIT_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_SALES_TAG)) == 1 ? true : false;
            this.CAN_DELETE_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_SALES_TAG)) == 1 ? true : false;
            this.CAN_VIEW_SALES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_SALES_TAG)) == 1 ? true : false;
            this.CAN_VIEW_PERSONAL_SAVINGS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_PERSONAL_SAVINGS_TAG)) == 1 ? true : false;
            this.CAN_VIEW_WATER_SOURCE_SAVINGS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_WATER_SOURCE_SAVINGS_TAG)) == 1 ? true : false;
            this.CAN_ADD_WATER_SOURCES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_WATER_SOURCES_TAG)) == 1 ? true : false;
            this.CAN_EDIT_WATER_SOURCES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_WATER_SOURCES_TAG)) == 1 ? true : false;
            this.CAN_DELETE_WATER_SOURCES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_WATER_SOURCES_TAG)) == 1 ? true : false;
            this.CAN_VIEW_WATER_SOURCES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_WATER_SOURCES_TAG)) == 1 ? true : false;
            this.CAN_ADD_REPAIR_TYPES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_REPAIR_TYPES_TAG)) == 1 ? true : false;
            this.CAN_EDIT_REPAIR_TYPES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_REPAIR_TYPES_TAG)) == 1 ? true : false;
            this.CAN_DELETE_REPAIR_TYPES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_REPAIR_TYPES_TAG)) == 1 ? true : false;
            this.CAN_VIEW_REPAIR_TYPES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_REPAIR_TYPES_TAG)) == 1 ? true : false;
            this.CAN_ADD_EXPENSES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_EXPENSES_TAG)) == 1 ? true : false;
            this.CAN_EDIT_EXPENSES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_EXPENSES_TAG)) == 1 ? true : false;
            this.CAN_DELETE_EXPENSES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_EXPENSES_TAG)) == 1 ? true : false;
            this.CAN_VIEW_EXPENSES = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_EXPENSES_TAG)) == 1 ? true : false;
            this.CAN_ADD_SYSTEM_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_SYSTEM_USERS_TAG)) == 1 ? true : false;
            this.CAN_EDIT_SYSTEM_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_SYSTEM_USERS_TAG)) == 1 ? true : false;
            this.CAN_DELETE_SYSTEM_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_SYSTEM_USERS_TAG)) == 1 ? true : false;
            this.CAN_VIEW_SYSTEM_USERS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_SYSTEM_USERS_TAG)) == 1 ? true : false;
            this.CAN_ADD_USER_PERMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_ADD_USER_PERMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_EDIT_USER_PERMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_EDIT_USER_PERMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_DELETE_USER_PERMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_DELETE_USER_PERMISSIONS_TAG)) == 1 ? true : false;
            this.CAN_VIEW_USER_PERMISSIONS = cursor.getInt(cursor.getColumnIndex(Constants.CAN_VIEW_USER_PERMISSIONS_TAG)) == 1 ? true : false;
            this.GROUP_DATE_CREATED = cursor.getString(cursor.getColumnIndex(Constants.GROUP_DATE_CREATED_TAG));
            this.GROUP_LAST_UPDATED = cursor.getString(cursor.getColumnIndex(Constants.GROUP_LAST_UPDATED_TAG));

        } catch (Exception ex) {
            // ex.printStackTrace();
        } finally {
            if (!cursor.isClosed()) {
                cursor.close();
            }
            db.close();
        }
    }

}
