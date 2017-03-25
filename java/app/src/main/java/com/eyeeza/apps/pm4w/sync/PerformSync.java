package com.eyeeza.apps.pm4w.sync;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.BitmapFactory;
import android.media.RingtoneManager;
import android.support.v4.app.NotificationCompat;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Expenditures;
import com.eyeeza.apps.pm4w.dbtables.RepairTypes;
import com.eyeeza.apps.pm4w.dbtables.Sales;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.dbtables.Users;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.main.auth.Dashboard;
import com.eyeeza.apps.pm4w.networking.JSONParser;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by sammy-n-ukavi-jr on 8/3/15.
 */

public class PerformSync extends DBoperations {
    private Context context;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private Pm4wUser pm4wUser;
    private JSONObject new_data;

    public PerformSync(Context context) {
        super(context);
        this.context = context;
    }


    public void Sync() throws Exception {
        pm4wUser = new Pm4wUser(context);
        pm4wUser.getSesssionAccount(context);
        if (pm4wUser.getIdu() == 0) {
            return;
        }

        if (!Utils.timeIsRelativelyValid(context)) {
            //CharSequence contentTitle = pm4wUser.language.INVALID_DATE;
            //CharSequence contentText = pm4wUser.language.INVALID_DATE_PROMPT;
            showNotification(pm4wUser.language.INVALID_DATE, pm4wUser.language.INVALID_DATE_PROMPT);
            return;
        } else {
            NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
            mNotificationManager.cancel(Constants.NOTIFICATION_ID);
        }

        params.add(new BasicNameValuePair(Constants.USERNAME_TAG, pm4wUser.getUsername()));
        params.add(new BasicNameValuePair(Constants.AUTH_CODE_TAG, pm4wUser.getAuthCode()));
        params.add(new BasicNameValuePair(Constants.AUTH_KEY_TAG, pm4wUser.getAuthKey()));
        params.add(new BasicNameValuePair(Constants.APP_VERSION_TAG, Config.APP_VERSION));
        params.add(new BasicNameValuePair(Constants.IMEI_TAG, pm4wUser.getDeviceImei()));
        params.add(new BasicNameValuePair(Constants.LAST_KNOWN_LOCATION_TAG, pm4wUser.getLastKnownLocation()));
        params.add(new BasicNameValuePair(Constants.APP_PREFERRED_LANGUAGE_TAG, pm4wUser.getAppPreferredLanguage()));

        SQLiteDatabase db = getReadableDatabase();
        JSONParser jsonp = new JSONParser();
        JSONObject json;

        JSONObject table = new JSONObject();
        JSONArray tableRows = new JSONArray();
        JSONObject columns = null;

        //We first test to see if we are authenticated
        params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
        json = jsonp.makeHttpRequest("?a=check-sync-auth", "POST", params);

        JSONObject server_info = json.getJSONObject(Constants.SERVER_INFO_TAG);
        int server_status = server_info.getInt(Constants.SERVER_STATUS_TAG);
        new_data = json.getJSONObject(Constants.DATA_TAG);
        int request_status = new_data.getInt(Constants.REQUEST_STATUS_TAG);
        JSONArray mMsgs = new_data.getJSONArray(Constants.MESSAGES_TAG);

        String txt = "";
        for (int index = 0; index < mMsgs.length(); index++) {
            try {
                txt += mMsgs.getString(index);
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

        if (server_status == Constants.SERVER_OFFLINE) {
            showNotification(pm4wUser.language.OFFLINE_TITLE, pm4wUser.language.OFFLINE_MSG);
            return;
        }

        if (server_status == Constants.SERVER_UPGRADE) {
            showNotification(pm4wUser.language.UPGRADE_TITLE, pm4wUser.language.UPGRADE_MSG);
            return;
        }

        if (request_status == Constants.ERROR_STATUS_CODE) {
            showNotification(pm4wUser.language.ERROR, txt);
        } else {

            table = new JSONObject();
            tableRows = new JSONArray();
            columns = null;

            //Fetch tables attending to
            table.accumulate(Constants.ATTENDING_TO_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);
            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.ATTENDING_TO_TAG)) {
                db.execSQL(Constants.DROP_ATTENDING_TO_TABLE_SQL);
                db.execSQL(Constants.CREATE_ATTENDING_TO_TABLE_SQL);
                JSONArray attendingToArray = new_data.getJSONArray(Constants.ATTENDING_TO_TAG);
                Caretakers caretaker = new Caretakers(context);
                for (int index = 0; index < attendingToArray.length(); index++) {
                    caretaker.saveWaterSource(attendingToArray.getJSONObject(index));
                }
            }

            //Fetch tables collecting from
            table.accumulate(Constants.COLLECTING_FROM_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);
            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.COLLECTING_FROM_TAG)) {
                db.execSQL(Constants.DROP_COLLECTING_FROM_TABLE_SQL);
                db.execSQL(Constants.CREATE_COLLECTING_FROM_TABLE_SQL);
                JSONArray collectingFromArray = new_data.getJSONArray(Constants.COLLECTING_FROM_TAG);
                Treasurers treasurer = new Treasurers(context);
                for (int index = 0; index < collectingFromArray.length(); index++) {
                    treasurer.saveWaterSource(collectingFromArray.getJSONObject(index));
                }
            }

            table = new JSONObject();
            tableRows = new JSONArray();

            //Post Event Logs
            String selectQuery = "SELECT  * FROM " + Constants.EVENTS_LOG_TABLENAME;
            Cursor cursor = db.rawQuery(selectQuery, null);
            if (cursor.moveToFirst()) {
                do {
                    columns = new JSONObject();
                    for (int index = 0; index < cursor.getColumnCount(); index++) {
                        columns.accumulate(cursor.getColumnName(index), cursor.getString(index));
                    }
                    tableRows.put(columns);
                } while (cursor.moveToNext());
            }
            table.accumulate(Constants.EVENTS_LOGS_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);
            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.EVENTS_LOGS_TAG)) {
                db.execSQL(Constants.DROP_EVENTS_LOG_TABLE_SQL);
                db.execSQL(Constants.CREATE_EVENTS_LOG_TABLE_SQL);
            }

            //Post and Fetch Expenditures
            table = new JSONObject();
            tableRows = new JSONArray();

            selectQuery = "SELECT  * FROM " + Constants.EXPENDITURES_TABLENAME;
            cursor = db.rawQuery(selectQuery, null);
            if (cursor.moveToFirst()) {
                do {
                    columns = new JSONObject();
                    for (int index = 0; index < cursor.getColumnCount(); index++) {
                        columns.accumulate(cursor.getColumnName(index), cursor.getString(index));
                    }
                    tableRows.put(columns);
                } while (cursor.moveToNext());
            }
            table.accumulate(Constants.EXPENDITURES_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.EXPENDITURES_TAG)) {
                db.execSQL(Constants.DROP_EXPENDITURES_TABLE_SQL);
                db.execSQL(Constants.CREATE_EXPENDITURES_TABLE_SQL);

                JSONArray expendituresArray = new_data.getJSONArray(Constants.EXPENDITURES_TAG);
                Expenditures expenditures = new Expenditures(context);

                for (int index = 0; index < expendituresArray.length(); index++) {
                    expenditures.saveExpenditure(expendituresArray.getJSONObject(index));
                }
            }

            //Fetch repair types
            table = new JSONObject();
            tableRows = new JSONArray();

            table.accumulate(Constants.REPAIR_TYPES_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);
            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.REPAIR_TYPES_TAG)) {
                db.execSQL(Constants.DROP_REPAIR_TYPES_TABLE_SQL);
                db.execSQL(Constants.CREATE_REPAIR_TYPES_TABLE_SQL);
                JSONArray repairTypesArray = new_data.getJSONArray(Constants.REPAIR_TYPES_TAG);
                RepairTypes repairTypes = new RepairTypes(context);
                for (int index = 0; index < repairTypesArray.length(); index++) {
                    repairTypes.saveRepairType(repairTypesArray.getJSONObject(index));
                }
            }

            //Post and fetch sales
            table = new JSONObject();
            tableRows = new JSONArray();

            selectQuery = "SELECT  * FROM " + Constants.SALES_TABLENAME;
            cursor = db.rawQuery(selectQuery, null);
            if (cursor.moveToFirst()) {
                do {
                    columns = new JSONObject();
                    for (int index = 0; index < cursor.getColumnCount(); index++) {
                        columns.accumulate(cursor.getColumnName(index), cursor.getString(index));
                    }
                    tableRows.put(columns);
                } while (cursor.moveToNext());
            }
            table.accumulate(Constants.SALES_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.SALES_TAG)) {
                db.execSQL(Constants.DROP_SALES_TABLE_SQL);
                db.execSQL(Constants.CREATE_SALES_TABLE_SQL);

                JSONArray salesArray = new_data.getJSONArray(Constants.SALES_TAG);
                Sales sales = new Sales(context);
                for (int index = 0; index < salesArray.length(); index++) {
                    sales.saveSale(salesArray.getJSONObject(index));
                }
            }

            //Fetch system Users
            table = new JSONObject();
            tableRows = new JSONArray();
            table.accumulate(Constants.USERS_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.USERS_TAG)) {
                db.execSQL(Constants.DROP_USERS_TABLE_SQL);
                db.execSQL(Constants.CREATE_USERS_TABLE_SQL);

                JSONArray usersArray = new_data.getJSONArray(Constants.USERS_TAG);
                Users users = new Users(context);
                for (int index = 0; index < usersArray.length(); index++) {
                    users.saveUser(usersArray.getJSONObject(index));
                }
            }


            //Fetch water Users
            table = new JSONObject();
            tableRows = new JSONArray();
            columns = null;

            selectQuery = "SELECT  * FROM " + Constants.WATER_USERS_TABLENAME;
            cursor = db.rawQuery(selectQuery, null);
            if (cursor.moveToFirst()) {
                do {
                    columns = new JSONObject();
                    for (int index = 0; index < cursor.getColumnCount(); index++) {
                        columns.accumulate(cursor.getColumnName(index), cursor.getString(index));
                    }
                    tableRows.put(columns);
                } while (cursor.moveToNext());
            }
            table.accumulate(Constants.WATER_USERS_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.WATER_USERS_TAG)) {
                db.execSQL(Constants.DROP_WATER_USERS_TABLE_SQL);
                db.execSQL(Constants.CREATE_WATER_USERS_TABLE_SQL);

                JSONArray waterUsersArray = new_data.getJSONArray(Constants.WATER_USERS_TAG);
                WaterUsers waterUsers = new WaterUsers(context);
                for (int index = 0; index < waterUsersArray.length(); index++) {
                    waterUsers.saveWaterUser(waterUsersArray.getJSONObject(index));
                }
            }


            //Fetch system Users
            table = new JSONObject();
            tableRows = new JSONArray();
            table.accumulate(Constants.USERS_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.USERS_TAG)) {
                db.execSQL(Constants.DROP_USERS_TABLE_SQL);
                db.execSQL(Constants.CREATE_USERS_TABLE_SQL);

                JSONArray usersArray = new_data.getJSONArray(Constants.USERS_TAG);
                Users users = new Users(context);
                for (int index = 0; index < usersArray.length(); index++) {
                    users.saveUser(usersArray.getJSONObject(index));
                }
            }

            //Fetch Group Permissions
            table = new JSONObject();
            tableRows = new JSONArray();
            table.accumulate(Constants.USER_PERMISSIONS_TAG, tableRows);
            params.add(new BasicNameValuePair(Constants.DATA_TAG, table.toString()));
            json = jsonp.makeHttpRequest("?a=perform-sync", "POST", params);

            new_data = json.getJSONObject(Constants.DATA_TAG);
            if (new_data.getString("data_type").equals(Constants.USER_PERMISSIONS_TAG)) {
                db.execSQL(Constants.DROP_USER_GROUPS_TABLE_SQL);
                db.execSQL(Constants.CREATE_USER_GROUPS_TABLE_SQL);
                JSONObject user_permissions = new_data.getJSONObject(Constants.USER_PERMISSIONS_TAG);
                pm4wUser.savePermissions(user_permissions);
            }

            pm4wUser.logEvent(EventLogs.SYNC_COMPLETE);
        }
        db.close();

    }

    private void showNotification(CharSequence contentTitle, CharSequence contentText) {
        /*Uri notificationToneUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        Ringtone ringtone = RingtoneManager.getRingtone(context, notificationToneUri);
        ringtone.play();
        NotificationManager notificationManager = (NotificationManager) context.getSystemService(context.NOTIFICATION_SERVICE);
        Notification notification = new Notification(R.drawable.ic_notification, pm4wUser.language.INVALID_DATE, System.currentTimeMillis());
        notification.flags |= Notification.FLAG_NO_CLEAR;
        //CharSequence contentTitle = pm4wUser.language.INVALID_DATE;
        //CharSequence contentText = pm4wUser.language.INVALID_DATE_PROMPT;
        Intent notificationIntent = new Intent(context, Dashboard.class);
        PendingIntent contentIntent = PendingIntent.getActivity(context, 0, notificationIntent, 0);
        notification.setLatestEventInfo(context, contentTitle, contentText, contentIntent);
        notificationManager.notify(Constants.NOTIFICATION_ID, notification);*/

        int icon = R.drawable.ic_notification;
        Intent notificationIntent = new Intent(context, Dashboard.class);
        PendingIntent resultPendingIntent = PendingIntent.getActivity(context, 0, notificationIntent, PendingIntent.FLAG_CANCEL_CURRENT);

        NotificationCompat.Builder mBuilder = new NotificationCompat.Builder(context);
        Notification notification = mBuilder.setSmallIcon(icon).setTicker(contentTitle).setWhen(0)
                .setAutoCancel(false)
                .setContentTitle(contentTitle)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(contentText))
                .setContentIntent(resultPendingIntent)
                .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
                .setLargeIcon(BitmapFactory.decodeResource(context.getResources(), R.drawable.ic_launcher))
                .setContentText(contentText).build();

        NotificationManager notificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.notify(Constants.NOTIFICATION_ID, notification);
    }

}