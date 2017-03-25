package com.eyeeza.apps.pm4w.user;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.location.Location;
import android.location.LocationManager;
import android.telephony.TelephonyManager;

import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.languages.Languages;
import com.eyeeza.apps.pm4w.utils.Utils;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by sammy-n-ukavi-jr on 7/27/15.
 */
public class Pm4wUser extends Group {

    public Languages language;
    private int idu = 0;
    private int groupId = 0;
    private String username = "";
    private String pNumber = "";
    private String email = "";
    private String fname = "";
    private String lname = "";
    private String authCode = "";
    private String authKey = "";
    private String appPreferredLanguage = "";
    private String deviceImei = "";
    private String lastKnownLocation = "";

    public Pm4wUser(Context context) {
        super(context);
        loadLanguage();
        setTrackers(context);
        try {
            Config.APP_VERSION = context.getPackageManager().getPackageInfo(context.getPackageName(), 0).versionName;
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    public void savePermissions(JSONObject permissions) {
        super.savePermissions(permissions);
    }

    public int getIdu() {
        return this.idu;
    }

    public int getGroupId() {
        return this.groupId;
    }

    public String getUsername() {
        return this.username;
    }

    public String getFname() {
        return this.fname;
    }

    public String getLname() {
        return this.lname;
    }

    public String getAuthCode() {
        return this.authCode;
    }

    public String getAuthKey() {
        return authKey;
    }

    public String getAppPreferredLanguage() {
        return this.appPreferredLanguage;
    }

    public void setAppPreferredLanguage(String appPreferredLanguage) {
        this.appPreferredLanguage = appPreferredLanguage;
    }

    public String getLastKnownLocation() {
        return this.lastKnownLocation;
    }

    public void setLastKnownLocation(String lastKnownLocation) {
        this.lastKnownLocation = lastKnownLocation;
    }

    public String getDeviceImei() {
        return this.deviceImei;
    }

    public void setDeviceImei(String deviceImei) {
        this.deviceImei = deviceImei;
    }

    private void setTrackers(Context context) {
        TelephonyManager telephonyManager = (TelephonyManager) context.getSystemService(Context.TELEPHONY_SERVICE);
        setDeviceImei(telephonyManager.getDeviceId());

        LocationManager locationManager = (LocationManager) context.getSystemService(context.LOCATION_SERVICE);
        Location location = locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
        if (location != null) {
            setLastKnownLocation(location.getLatitude() + "," + location.getLongitude());
        }
    }

    public float getPercentageComplete(int total, int current_progress) {
        float proportionCorrect = ((float) current_progress) / ((float) total);
        return proportionCorrect * 100;
    }

    public void getSesssionAccount(Context context) {
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = null;
        String selectQuery = "SELECT  * FROM " + Constants.USER_SESSION_TABLENAME + " WHERE " + Constants.ID_TAG + "=1";
        try {
            cursor = db.rawQuery(selectQuery, null);
            if (cursor != null) {
                cursor.moveToFirst();
                this.idu = cursor.getInt(cursor.getColumnIndex(Constants.IDU_TAG));
                this.groupId = cursor.getInt(cursor.getColumnIndex(Constants.GROUP_ID_TAG));
                this.username = cursor.getString(cursor.getColumnIndex(Constants.USERNAME_TAG));
                this.pNumber = cursor.getString(cursor.getColumnIndex(Constants.PNUMBER_TAG));
                this.email = cursor.getString(cursor.getColumnIndex(Constants.EMAIL_TAG));
                this.fname = cursor.getString(cursor.getColumnIndex(Constants.FNAME_TAG));
                this.lname = cursor.getString(cursor.getColumnIndex(Constants.LNAME_TAG));
                this.authCode = cursor.getString(cursor.getColumnIndex(Constants.AUTH_CODE_TAG));
                this.authKey = cursor.getString(cursor.getColumnIndex(Constants.AUTH_KEY_TAG));
                this.appPreferredLanguage = cursor.getString(cursor.getColumnIndex(Constants.APP_PREFERRED_LANGUAGE_TAG));
            }
        } catch (Exception ex) {
            //ex.printStackTrace();
        } finally {
            if (cursor != null) {
                if (!cursor.isClosed()) {
                    cursor.close();
                }
            }
            db.close();
        }
    }

    public void saveSessionAccount(JSONObject account) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_TAG, 1);
        values.put(Constants.IDU_TAG, account.getInt(Constants.IDU_TAG));
        values.put(Constants.GROUP_ID_TAG, account.getInt(Constants.GROUP_ID_TAG));
        values.put(Constants.USERNAME_TAG, account.getString(Constants.USERNAME_TAG));
        values.put(Constants.PNUMBER_TAG, account.getString(Constants.PNUMBER_TAG));
        values.put(Constants.EMAIL_TAG, account.getString(Constants.EMAIL_TAG));
        values.put(Constants.FNAME_TAG, account.getString(Constants.FNAME_TAG));
        values.put(Constants.LNAME_TAG, account.getString(Constants.LNAME_TAG));
        values.put(Constants.AUTH_CODE_TAG, account.getString(Constants.AUTH_CODE_TAG));
        values.put(Constants.AUTH_KEY_TAG, account.getString(Constants.AUTH_KEY_TAG));
        values.put(Constants.APP_PREFERRED_LANGUAGE_TAG, account.getString(Constants.APP_PREFERRED_LANGUAGE_TAG));
        db.insertWithOnConflict(Constants.USER_SESSION_TABLENAME, null, values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
    }

    public void saveSessionAccount() {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_TAG, 1);
        values.put(Constants.IDU_TAG, idu);
        values.put(Constants.GROUP_ID_TAG, groupId);
        values.put(Constants.USERNAME_TAG, username);
        values.put(Constants.PNUMBER_TAG, pNumber);
        values.put(Constants.EMAIL_TAG, email);
        values.put(Constants.FNAME_TAG, fname);
        values.put(Constants.LNAME_TAG, lname);
        values.put(Constants.AUTH_CODE_TAG, authCode);
        values.put(Constants.AUTH_KEY_TAG, authKey);
        values.put(Constants.APP_PREFERRED_LANGUAGE_TAG, appPreferredLanguage);
        db.insertWithOnConflict(Constants.USER_SESSION_TABLENAME, null, values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
    }

    public void logOut(Boolean clean_db) {
        if (clean_db) {
            setUpDB();
        }
        logOut();
    }

    public void logOut() {
        SQLiteDatabase db = getWritableDatabase();
        db.delete(Constants.USER_SESSION_TABLENAME, " " + Constants.ID_TAG + "=?", new String[]{String.valueOf(1)});
        db.close();
        this.idu = 0;
        this.groupId = 0;
        this.username = "";
        this.pNumber = "";
        this.email = "";
        this.fname = "";
        this.lname = "";
        this.authCode = "";
        this.authKey = "";
        this.appPreferredLanguage = "";
    }

    public void loadLanguage() {
        Languages.loadEnglish();
    }

    public void loadLanguage(String language) {
        if (language.equals(Config.RUTOORO)) {
            Languages.loadRutooro();
        } else if (language.equals(Config.English)) {
            Languages.loadEnglish();
        }
    }

    public String[] getAvailableLanguages() {
        String[] available = {Config.RUTOORO, Config.English};
        return available;
    }

    public long logEvent(String eventName) {
        EventLogs event = new EventLogs();
        event.setUid(idu);
        event.setEvent(eventName);
        event.setEventTime(Utils.getMySQLDate());
        event.setEventDescription("");
        event.setAffectedObjectId(0);
        return logEvent(event);
    }

    public long logEvent(String eventName, long affected_object_id) {
        EventLogs event = new EventLogs();
        event.setUid(idu);
        event.setEvent(eventName);
        event.setEventTime(Utils.getMySQLDate());
        event.setEventDescription("");
        event.setAffectedObjectId(affected_object_id);
        return logEvent(event);
    }

    public long logEvent(String eventName, String event_description) {
        EventLogs event = new EventLogs();
        event.setUid(idu);
        event.setEvent(eventName);
        event.setEventTime(Utils.getMySQLDate());
        event.setEventDescription(event_description);
        event.setAffectedObjectId(0);
        return logEvent(event);
    }

    public long logEvent(EventLogs event) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.EVENT_UID_TAG, event.getUid());
        values.put(Constants.EVENT_TAG, event.getEvent());
        values.put(Constants.EVENT_TIME_TAG, event.getEventTime());
        values.put(Constants.EVENT_DESCRIPTION_TAG, event.getEventDescription());
        values.put(Constants.EVENT_AFFECTED_OBJECT_ID_TAG, event.getAffectedObjectId());
        long id = db.insertWithOnConflict(Constants.EVENTS_LOG_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return id;
    }


}
