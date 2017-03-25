package com.eyeeza.apps.pm4w.dbtables;

/**
 * Created by sammy-n-ukavi-jr on 7/31/15.
 */
public class EventLogs {

    private long idEvent;
    private long uid;
    private String event;
    private String eventTime;
    private String eventDescription;
    private long affectedObjectId;
    private String systemUsed;

    public static String EVENT_ATTEMPTED_LOGIN = "attempted_login";
    public static String EVENT_SETTING_UP_DATABASE_USING_ONLINE_COPY_COMPLETE = "setting_up_database_using_online_copy_complete";
    public static String EVENT_ATTEMPTED_PASSWORD_RECOVERY = "attempted_password_recovery";
    public static String EVENT_CHOSE_LANGUAGE = "chose_language";
    public static String EVENT_VIEWED_HELP = "viewed_help";
    public static String EVENT_VIEWED_ABOUT = "viewed_about";
    public static String EVENT_VIEWED_DASHBOARD = "viewed_dashboard";
    public static String EVENT_ATTEMPTED_LOGOUT = "attempted_login";
    public static String EVENT_ADDED_WATER_USER = "added_water_user";
    public static String EVENT_UPDATED_WATER_USER = "updated_water_user";
    public static String EVENT_DELETED_WATER_USER = "deleted_water_user";
    public static String EVENT_LISTED_WATER_USERS = "listed_water_users";
    public static String EVENT_ADDED_DAILY_SALE = "added_daily_sale";
    public static String EVENT_ADDED_MONTHLY_SALE = "added_monthly_sale";
    public static String EVENT_LISTED_CARETAKER_SALES = "listed_caretaker_sales";
    public static String EVENT_SUBMITTED_CARETAKER_SALES = "submitted_caretaker_sales";
    public static String EVENT_LISTED_TREASURER_COLLECTIONS = "listed_treasurer_collections";
    public static String EVENT_APPROVED_TREASURER_COLLECTIONS = "approved_treasurer_collections";
    public static String EVENT_DENIED_TREASURER_COLLECTIONS = "denied_treasurer_collections";
    public static String EVENT_VIEWED_WATER_SOURCE_SAVINGS = "viewed_water_source_savings";
    public static String EVENT_LOGGED_EXPENSE = "added_expense";
    public static String EVENT_UPDATED_EXPENSE = "updated_expense";
    public static String EVENT_DELETED_EXPENSE = "deleted_expense";
    public static String EVENT_LISTED_EXPENSES = "listed_watersource_expenses";
    public static String EVENT_VIEWED_EXPENSE = "viewed_watersource_expense";
    public static String EVENT_VIEWED_ACCOUNT_BALANCE = "viewed_account_balance";
    public static String EVENT_VIEWED_MINISTATEMENT = "viewed_ministatement";
    public static String SYNC_COMPLETE = "sync_completed";
    public static String SYNC_UNCOMPLETE = "sync_uncompleted";

    public long getIdEvent() {
        return idEvent;
    }

    public void setIdEvent(int value) {
        idEvent = value;
    }

    public long getUid() {
        return uid;
    }

    public void setUid(int value) {
        uid = value;
    }

    public String getEvent() {
        return event;
    }

    public void setEvent(String value) {
        event = value;
    }

    public String getEventTime() {
        return eventTime;
    }

    public void setEventTime(String value) {
        eventTime = value;
    }

    public String getEventDescription() {
        return eventDescription;
    }

    public void setEventDescription(String value) {
        eventDescription = value;
    }

    public long getAffectedObjectId() {
        return affectedObjectId;
    }

    public void setAffectedObjectId(long value) {
        affectedObjectId = value;
    }

    public String getSystemUsed() {
        return systemUsed;
    }

    public void setSystemUsed(String value) {
        systemUsed = value;
    }
}
