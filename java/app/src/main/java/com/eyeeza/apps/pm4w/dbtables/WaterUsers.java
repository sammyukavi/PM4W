package com.eyeeza.apps.pm4w.dbtables;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by sammy-n-ukavi-jr on 7/27/15.
 */
public class WaterUsers extends DBoperations {
    private long idUser = 0;
    private String fName = null;
    private String lName = null;
    private String pNumber = null;
    private int waterSourceId = 0;
    private String dateAdded = null;
    private int addedBy = 0;
    private int reportedDefaulter = 0;
    private int markedForDelete = 0;
    private String lastUpdated = null;
    private Context context;
    private String fullname;

    public WaterUsers(Context context) {
        super(context);
        this.context = context;
    }

    public String getFullname() {
        return fName + " " + lName;
    }

    public void setFullname(String fullname) {
        this.fullname = fullname;
    }

    public long getIdUser() {
        return idUser;
    }

    public void setIdUser(long value) {
        idUser = value;
    }

    public String getfName() {
        return fName;
    }

    public void setfName(String value) {
        fName = value;
    }

    public String getlName() {
        return lName;
    }

    public void setlName(String value) {
        lName = value;
    }

    public String getpNumber() {
        return pNumber;
    }

    public void setpNumber(String value) {
        pNumber = value;
    }

    public int getWaterSourceId() {
        return waterSourceId;
    }

    public void setWaterSourceId(int value) {
        waterSourceId = value;
    }

    public String getDateAdded() {
        return dateAdded;
    }

    public void setDateAdded(String value) {
        dateAdded = value;
    }

    public int getAddedBy() {
        return addedBy;
    }

    public void setAddedBy(int value) {
        addedBy = value;
    }

    public int getReportedDefaulter() {
        return reportedDefaulter;
    }

    public void setReportedDefaulter(int value) {
        reportedDefaulter = value;
    }

    public int getMarkedForDelete() {
        return markedForDelete;
    }

    public void setMarkedForDelete(int value) {
        markedForDelete = value;
    }

    public String getLastUpdated() {
        return lastUpdated;
    }

    public void setLastUpdated(String value) {
        lastUpdated = value;
    }

    public String getName() {
        return fName + " " + lName;
    }


    public long saveWaterUser(JSONObject waterUser) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.WATER_USER_ID_TAG, waterUser.getInt(Constants.WATER_USER_ID_TAG));
        values.put(Constants.WATER_USER_FNAME_TAG, waterUser.getString(Constants.WATER_USER_FNAME_TAG));
        values.put(Constants.WATER_USER_LNAME_TAG, waterUser.getString(Constants.WATER_USER_LNAME_TAG));
        values.put(Constants.WATER_USER_PNUMBER_TAG, waterUser.getString(Constants.WATER_USER_PNUMBER_TAG));
        values.put(Constants.WATER_USER_WATER_SOURCE_ID_TAG, waterUser.getInt(Constants.WATER_USER_WATER_SOURCE_ID_TAG));
        values.put(Constants.WATER_USER_DATE_ADDED_TAG, waterUser.getString(Constants.WATER_USER_DATE_ADDED_TAG));
        values.put(Constants.ADDED_BY_TAG, waterUser.getInt(Constants.ADDED_BY_TAG));
        values.put(Constants.REPORTED_DEFAULTER_TAG, waterUser.getInt(Constants.REPORTED_DEFAULTER_TAG));
        values.put(Constants.WATER_USER_MARKED_FOR_DELETE_TAG, waterUser.getInt(Constants.WATER_USER_MARKED_FOR_DELETE_TAG));
        values.put(Constants.WATER_USER_LAST_UPDATED_TAG, waterUser.getString(Constants.WATER_USER_LAST_UPDATED_TAG));
        long id = db.insertWithOnConflict(Constants.WATER_USERS_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return id;
    }

    public void getWaterUser(long userId) {
        SQLiteDatabase db = getReadableDatabase();
        String selectQuery = "SELECT  * FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_ID_TAG + "=" + userId;
        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null) {
            cursor.moveToFirst();
        }
        try {
            this.idUser = cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG));
            this.fName = cursor.getString(cursor.getColumnIndex(Constants.FNAME_TAG));
            this.lName = cursor.getString(cursor.getColumnIndex(Constants.LNAME_TAG));
            this.pNumber = cursor.getString(cursor.getColumnIndex(Constants.PNUMBER_TAG));
            this.waterSourceId = cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_WATER_SOURCE_ID_TAG));
            this.dateAdded = cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_DATE_ADDED_TAG));
            this.addedBy = cursor.getInt(cursor.getColumnIndex(Constants.ADDED_BY_TAG));
            this.reportedDefaulter = cursor.getInt(cursor.getColumnIndex(Constants.REPORTED_DEFAULTER_TAG));
            this.markedForDelete = cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_MARKED_FOR_DELETE_TAG));
            this.lastUpdated = cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_LAST_UPDATED_TAG));
        } catch (Exception e) {
            e.printStackTrace();
        }
        db.close();
    }

    public long saveWaterUser() {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        if (idUser != 0) {
            values.put(Constants.WATER_USER_ID_TAG, getIdUser());
        }
        values.put(Constants.FNAME_TAG, getfName());
        values.put(Constants.LNAME_TAG, getlName());
        values.put(Constants.PNUMBER_TAG, getpNumber());
        values.put(Constants.WATER_USER_WATER_SOURCE_ID_TAG, getWaterSourceId());
        values.put(Constants.WATER_USER_DATE_ADDED_TAG, getDateAdded());
        values.put(Constants.ADDED_BY_TAG, getAddedBy());
        values.put(Constants.REPORTED_DEFAULTER_TAG, getReportedDefaulter());
        values.put(Constants.WATER_USER_MARKED_FOR_DELETE_TAG, getMarkedForDelete());
        values.put(Constants.WATER_USER_LAST_UPDATED_TAG, getLastUpdated());
        long id = db.insertWithOnConflict(Constants.WATER_USERS_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return id;
    }

    public ArrayList<HashMap<String, String>> getAllWaterUsers() {
        ArrayList<HashMap<String, String>> customersList = new ArrayList<>();
        String selectQuery = "SELECT  * FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0 ORDER BY " + Constants.FNAME_TAG + ", " + Constants.LNAME_TAG;
        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> map = new HashMap<String, String>();
                map.put(Constants.WATER_USER_ID_TAG, cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG)) + "");
                map.put(Constants.COMBINED_FNAME_LNAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)) + " " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_LNAME_TAG)));
                customersList.add(map);
            } while (cursor.moveToNext());
        }
        db.close();
        return customersList;
    }
}