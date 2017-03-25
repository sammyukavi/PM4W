package com.eyeeza.apps.pm4w.dbtables;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;
import com.eyeeza.apps.pm4w.utils.Utils;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;

/**
 * Created by Sammy N Ukavi Jr on 4/29/2016.
 */
public class Caretakers extends DBoperations {
    private Context context;
    private int idWaterSource;
    private String waterSourceId;
    private String waterSourceName;
    private String waterSourceLocation;
    private String waterSourceCoordinates;
    private double waterSourceMonthlyCharges;
    private double waterSourcePercentageSaved;
    private String dateCreated;
    private String lastUpdated;

    public Caretakers(Context context) {
        super(context);
        this.context = context;
    }

    public int getIdWaterSource() {
        return idWaterSource;
    }

    public void setIdWaterSource(int idWaterSource) {
        this.idWaterSource = idWaterSource;
    }

    public String getWaterSourceId() {
        return waterSourceId;
    }

    public void setWaterSourceId(String waterSourceId) {
        this.waterSourceId = waterSourceId;
    }

    public String getWaterSourceName() {
        return waterSourceName;
    }

    public void setWaterSourceName(String waterSourceName) {
        this.waterSourceName = waterSourceName;
    }

    public String getWaterSourceLocation() {
        return waterSourceLocation;
    }

    public void setWaterSourceLocation(String waterSourceLocation) {
        this.waterSourceLocation = waterSourceLocation;
    }

    public String getWaterSourceCoordinates() {
        return waterSourceCoordinates;
    }

    public void setWaterSourceCoordinates(String waterSourceCoordinates) {
        this.waterSourceCoordinates = waterSourceCoordinates;
    }

    public double getWaterSourceMonthlyCharges() {
        return waterSourceMonthlyCharges;
    }

    public void setWaterSourceMonthlyCharges(double waterSourceMonthlyCharges) {
        this.waterSourceMonthlyCharges = waterSourceMonthlyCharges;
    }

    public double getWaterSourcePercentageSaved() {
        return waterSourcePercentageSaved;
    }

    public void setWaterSourcePercentageSaved(double waterSourcePercentageSaved) {
        this.waterSourcePercentageSaved = waterSourcePercentageSaved;
    }

    public String getDateCreated() {
        return dateCreated;
    }

    public void setDateCreated(String dateCreated) {
        this.dateCreated = dateCreated;
    }

    public String getLastUpdated() {
        return lastUpdated;
    }

    public void setLastUpdated(String lastUpdated) {
        this.lastUpdated = lastUpdated;
    }

    public long saveWaterSource(JSONObject waterSource) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_WATER_SOURCE_TAG, waterSource.getInt(Constants.ID_WATER_SOURCE_TAG));
        values.put(Constants.WATER_SOURCE_ID_TAG, waterSource.getString(Constants.WATER_SOURCE_ID_TAG));
        values.put(Constants.WATER_SOURCE_NAME_TAG, waterSource.getString(Constants.WATER_SOURCE_NAME_TAG));
        values.put(Constants.WATER_SOURCE_LOCATION_TAG, waterSource.getString(Constants.WATER_SOURCE_LOCATION_TAG));
        values.put(Constants.WATER_SOURCE_COORDINATES_TAG, waterSource.getString(Constants.WATER_SOURCE_COORDINATES_TAG));
        values.put(Constants.WATER_SOURCE_MONTHLY_CHARGES_TAG, waterSource.getDouble(Constants.WATER_SOURCE_MONTHLY_CHARGES_TAG));
        values.put(Constants.WATER_SOURCE_PERCENTAGE_SAVED_TAG, waterSource.getDouble(Constants.WATER_SOURCE_PERCENTAGE_SAVED_TAG));
        values.put(Constants.WATER_SOURCE_DATE_CREATED_TAG, waterSource.getString(Constants.WATER_SOURCE_DATE_CREATED_TAG));
        values.put(Constants.WATER_SOURCE_LAST_UPDATED_TAG, waterSource.getString(Constants.WATER_SOURCE_LAST_UPDATED_TAG));
        long no = db.insertWithOnConflict(Constants.ATTENDING_TO_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public ArrayList<HashMap<String, String>> getAllWaterSources() {
        ArrayList<HashMap<String, String>> waterSourcesList = new ArrayList<>();
        String selectQuery = " SELECT  * FROM " + Constants.ATTENDING_TO_TABLENAME + "  ORDER BY " + Constants.WATER_SOURCE_NAME_TAG;

        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> map = new HashMap<String, String>();
                map.put(Constants.ID_WATER_SOURCE_TAG, cursor.getInt(cursor.getColumnIndex(Constants.ID_WATER_SOURCE_TAG)) + "");
                map.put(Constants.WATER_SOURCE_NAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_NAME_TAG)));
                waterSourcesList.add(map);
            } while (cursor.moveToNext());
        }
        db.close();
        return waterSourcesList;
    }

    public void getWaterSource(int waterSourceId) {
        String selectQuery = " SELECT  * FROM " + Constants.ATTENDING_TO_TABLENAME + "  WHERE  " + Constants.ID_WATER_SOURCE_TAG + "=" + waterSourceId;

        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                this.idWaterSource = cursor.getInt(cursor.getColumnIndex(Constants.ID_WATER_SOURCE_TAG));
                this.waterSourceId = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_ID_TAG));
                this.waterSourceName = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_NAME_TAG));
                this.waterSourceLocation = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_LOCATION_TAG));
                this.waterSourceCoordinates = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_COORDINATES_TAG));
                this.waterSourceMonthlyCharges = cursor.getDouble(cursor.getColumnIndex(Constants.WATER_SOURCE_MONTHLY_CHARGES_TAG));
                this.waterSourcePercentageSaved = cursor.getDouble(cursor.getColumnIndex(Constants.WATER_SOURCE_PERCENTAGE_SAVED_TAG));
                this.dateCreated = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_DATE_CREATED_TAG));
                this.lastUpdated = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_LAST_UPDATED_TAG));
            } while (cursor.moveToNext());
        }
        db.close();
    }

    public ArrayList<HashMap<String, String>> fetchCareTakerCollections() {
        ArrayList<HashMap<String, String>> collections = new ArrayList<>();
        String selectQuery = "SELECT " + Constants.USER_IDU_TAG + "," +
                (Constants.USER_FNAME_TAG + " || \" \" ||" + Constants.USER_LNAME_TAG) + " AS " + Constants.COMBINED_FNAME_LNAME_TAG + ", " +
                Constants.WATER_SOURCE_NAME_TAG + ", " +
                " SUM(" + Constants.SALE_UGX_TAG + ") AS " + Constants.SALE_UGX_TAG + "," +
                " CASE WHEN " + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " > 0 THEN SUM(CAST(" + Constants.SALE_UGX_TAG + " AS FLOAT)*(CAST(" + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " AS FLOAT)/CAST(100 AS FLOAT))) ELSE SUM(" + Constants.SALE_UGX_TAG + ") END AS " + Constants.SAVINGS_TAG +
                " FROM " + Constants.SALES_TABLENAME + " LEFT OUTER JOIN " + Constants.COLLECTING_FROM_TABLENAME + " ON " +
                Constants.SALES_TABLENAME + "." + Constants.SALE_WATER_SOURCE_ID_TAG + "=" + Constants.ID_WATER_SOURCE_TAG +
                " LEFT OUTER JOIN " + Constants.USERS_TABLENAME + " ON " + Constants.SOLD_BY_TAG + "=" + Constants.USER_IDU_TAG +
                " WHERE " + Constants.SALE_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "=0 AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "<>1 AND " + Constants.SALE_UGX_TAG + " >0" +
                " GROUP BY(" + Constants.USER_IDU_TAG + ") ORDER BY " + Constants.USER_FNAME_TAG;
        String selectQuery2 = " SELECT * FROM (" + selectQuery + ") A WHERE " + Constants.SAVINGS_TAG + ">0";
        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery2, null);
        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> saving = new HashMap<>();
                saving.put(Constants.USER_IDU_TAG, cursor.getString(cursor.getColumnIndex(Constants.USER_IDU_TAG)));
                saving.put(Constants.COMBINED_FNAME_LNAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.COMBINED_FNAME_LNAME_TAG)));
                saving.put(Constants.WATER_SOURCE_NAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_NAME_TAG)));
                saving.put(Constants.SALE_UGX_TAG, cursor.getString(cursor.getColumnIndex(Constants.SALE_UGX_TAG)));
                saving.put(Constants.SAVINGS_TAG, cursor.getString(cursor.getColumnIndex(Constants.SAVINGS_TAG)));
                collections.add(saving);
            } while (cursor.moveToNext());
        }
        db.close();
        return collections;
    }

    public ArrayList<HashMap<String, String>> getPaidUsers() throws Exception {
        ArrayList<HashMap<String, String>> defaulters = new ArrayList<>();

        String selectUsersQuery = "SELECT "
                + Constants.WATER_USER_ID_TAG + ","
                + Constants.WATER_USER_FNAME_TAG + ","
                + Constants.WATER_USER_LNAME_TAG + ","
                + Constants.WATER_USER_DATE_ADDED_TAG + " "
                + " FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0 ORDER BY " + Constants.WATER_USER_FNAME_TAG + " ASC ";

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectUsersQuery, null);

        if (cursor.moveToFirst()) {
            do {
                final Calendar calendar = Calendar.getInstance();
                SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
                try {
                    Date dateAdded = formatter.parse(cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_DATE_ADDED_TAG)));
                    calendar.setTime(dateAdded);
                    while (!calendar.getTime().after(new Date())) {
                        int year = calendar.get(Calendar.YEAR);
                        int month = (calendar.get(Calendar.MONTH) + 1);//You have to add 1. Calendar starts counting months from 0

                        String sql = " SELECT COUNT(" + Constants.ID_SALE_TAG + ") AS total_transactions, " + Constants.SOLD_TO_TAG + ", CAST(strftime('%m', " + Constants.SALE_DATE_TAG + ") AS INTEGER) month, CAST(strftime('%Y', " + Constants.SALE_DATE_TAG + ") AS INTEGER) year," + Constants.SALE_DATE_TAG
                                + " FROM  " + Constants.SALES_TABLENAME
                                + " WHERE month=" + month
                                + " AND year=" + year
                                + " AND " + Constants.SOLD_TO_TAG + "=" + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG));
                        Cursor userCursor = null;
                        try {
                            userCursor = db.rawQuery(sql, null);
                            if (userCursor.moveToFirst()) {
                                int totalTransactions = userCursor.getInt(userCursor.getColumnIndex("total_transactions"));
                                if (totalTransactions > 0) {
                                    /*Utils.var_dump("User is " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)),
                                            "User has not paid for " + month + "/" + year);*/
                                    HashMap<String, String> defaulter = new HashMap<String, String>();
                                    defaulter.put(Constants.WATER_USER_ID_TAG, cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG)) + "");
                                    defaulter.put(Constants.COMBINED_FNAME_LNAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)) + " " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_LNAME_TAG)));
                                    defaulters.add(defaulter);
                                    break;
                                }
                            }
                        } finally {
                            if (userCursor != null) {
                                userCursor.close();
                            }
                        }
                        calendar.add(Calendar.MONTH, 1);
                    }
                } catch (ParseException e) {
                    e.printStackTrace();
                }

            } while (cursor.moveToNext());
        }
        db.close();
        return defaulters;
    }

    public ArrayList<HashMap<String, String>> getDefaulters() throws Exception {
        ArrayList<HashMap<String, String>> defaulters = new ArrayList<>();

        String selectUsersQuery = "SELECT "
                + Constants.WATER_USER_ID_TAG + ","
                + Constants.WATER_USER_FNAME_TAG + ","
                + Constants.WATER_USER_LNAME_TAG + ","
                + Constants.WATER_USER_DATE_ADDED_TAG + " "
                + " FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0 ORDER BY " + Constants.WATER_USER_FNAME_TAG + " ASC ";

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectUsersQuery, null);

        if (cursor.moveToFirst()) {
            do {
                final Calendar calendar = Calendar.getInstance();
                SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
                try {
                    Date dateAdded = formatter.parse(cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_DATE_ADDED_TAG)));
                    calendar.setTime(dateAdded);
                    while (!calendar.getTime().after(new Date())) {
                        int year = calendar.get(Calendar.YEAR);
                        int month = (calendar.get(Calendar.MONTH) + 1);//You have to add 1. Calendar starts counting months from 0

                        String sql = " SELECT COUNT(" + Constants.ID_SALE_TAG + ") AS total_transactions, " + Constants.SOLD_TO_TAG + ", CAST(strftime('%m', " + Constants.SALE_DATE_TAG + ") AS INTEGER) month, CAST(strftime('%Y', " + Constants.SALE_DATE_TAG + ") AS INTEGER) year," + Constants.SALE_DATE_TAG
                                + " FROM  " + Constants.SALES_TABLENAME
                                + " WHERE month=" + month
                                + " AND year=" + year
                                + " AND " + Constants.SOLD_TO_TAG + "=" + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG));
                        Cursor userCursor = null;
                        try {
                            userCursor = db.rawQuery(sql, null);
                            if (userCursor.moveToFirst()) {
                                int totalTransactions = userCursor.getInt(userCursor.getColumnIndex("total_transactions"));
                                if (totalTransactions == 0) {
                                    /*Utils.var_dump("User is " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)),
                                            "User has not paid for " + month + "/" + year);*/
                                    HashMap<String, String> defaulter = new HashMap<String, String>();
                                    defaulter.put(Constants.WATER_USER_ID_TAG, cursor.getInt(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG)) + "");
                                    defaulter.put(Constants.COMBINED_FNAME_LNAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)) + " " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_LNAME_TAG)));
                                    defaulters.add(defaulter);
                                    break;
                                }
                            }
                        } finally {
                            if (userCursor != null) {
                                userCursor.close();
                            }
                        }
                        calendar.add(Calendar.MONTH, 1);
                    }
                } catch (ParseException e) {
                    e.printStackTrace();
                }

            } while (cursor.moveToNext());
        }
        db.close();
        return defaulters;
    }


    public ArrayList<HashMap<String, String>> getDefaultedMonths(long waterUserId) throws Exception {
        ArrayList<HashMap<String, String>> defaultedMonths = new ArrayList<>();
        String selectUsersQuery = "SELECT "
                + Constants.WATER_USER_ID_TAG + ","
                + Constants.WATER_USER_FNAME_TAG + ","
                + Constants.WATER_USER_LNAME_TAG + ","
                + Constants.WATER_USER_DATE_ADDED_TAG + " "
                + " FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.WATER_USER_ID_TAG + "=" + waterUserId;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectUsersQuery, null);
        if (cursor.moveToFirst()) {
            final Calendar calendar = Calendar.getInstance();
            SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
            try {
                Date dateAdded = formatter.parse(cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_DATE_ADDED_TAG)));
                calendar.setTime(dateAdded);
                formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT_4);
                while (!calendar.getTime().after(new Date())) {
                    Date defaultedDate = calendar.getTime();
                    int year = calendar.get(Calendar.YEAR);
                    int month = (calendar.get(Calendar.MONTH) + 1);//You have to add 1. Calendar starts counting months from 0

                    String sql = " SELECT COUNT(" + Constants.ID_SALE_TAG + ") AS total_transactions, " + Constants.SOLD_TO_TAG + ", CAST(strftime('%m', " + Constants.SALE_DATE_TAG + ") AS INTEGER) month, CAST(strftime('%Y', " + Constants.SALE_DATE_TAG + ") AS INTEGER) year," + Constants.SALE_DATE_TAG
                            + " FROM  " + Constants.SALES_TABLENAME
                            + " WHERE month=" + month
                            + " AND year=" + year
                            + " AND " + Constants.SOLD_TO_TAG + "=" + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG));
                    Cursor userCursor = null;
                    try {
                        userCursor = db.rawQuery(sql, null);
                        if (userCursor.moveToFirst()) {
                            int totalTransactions = userCursor.getInt(userCursor.getColumnIndex("total_transactions"));
                            if (totalTransactions == 0) {
                                    /*Utils.var_dump("User is " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)),
                                            "User has not paid for " + month + "/" + year);*/
                                HashMap<String, String> defaulter = new HashMap<String, String>();
                                defaulter.put(Constants.DATE_TAG, formatter.format(defaultedDate));
                                defaultedMonths.add(defaulter);
                            }
                        }
                    } finally {
                        if (userCursor != null) {
                            userCursor.close();
                        }
                    }
                    calendar.add(Calendar.MONTH, 1);
                }
            } catch (ParseException e) {
                e.printStackTrace();
            }

        }
        db.close();
        return defaultedMonths;
    }

    public ArrayList<HashMap<String, String>> getPaidMonths(long waterUserId) throws Exception {
        ArrayList<HashMap<String, String>> defaultedMonths = new ArrayList<>();
        String selectUsersQuery = "SELECT "
                + Constants.WATER_USER_ID_TAG + ","
                + Constants.WATER_USER_FNAME_TAG + ","
                + Constants.WATER_USER_LNAME_TAG + ","
                + Constants.WATER_USER_DATE_ADDED_TAG + " "
                + " FROM " + Constants.WATER_USERS_TABLENAME + " WHERE " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.WATER_USER_ID_TAG + "=" + waterUserId;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(selectUsersQuery, null);
        if (cursor.moveToFirst()) {
            final Calendar calendar = Calendar.getInstance();
            SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
            try {
                Date dateAdded = formatter.parse(cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_DATE_ADDED_TAG)));
                calendar.setTime(dateAdded);
                formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT_4);
                while (!calendar.getTime().after(new Date())) {
                    Date defaultedDate = calendar.getTime();
                    int year = calendar.get(Calendar.YEAR);
                    int month = (calendar.get(Calendar.MONTH) + 1);//You have to add 1. Calendar starts counting months from 0

                    String sql = " SELECT SUM(" + Constants.SALE_UGX_TAG + ") AS " + Constants.TRANSACTION_COST_TAG + ", COUNT(" + Constants.ID_SALE_TAG + ") AS total_transactions, " + Constants.SOLD_TO_TAG + ", CAST(strftime('%m', " + Constants.SALE_DATE_TAG + ") AS INTEGER) month, CAST(strftime('%Y', " + Constants.SALE_DATE_TAG + ") AS INTEGER) year," + Constants.SALE_DATE_TAG
                            + " FROM  " + Constants.SALES_TABLENAME
                            + " WHERE month=" + month
                            + " AND year=" + year
                            + " AND " + Constants.SOLD_TO_TAG + "=" + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_ID_TAG));
                    Cursor userCursor = null;
                    try {
                        userCursor = db.rawQuery(sql, null);
                        if (userCursor.moveToFirst()) {
                            int totalTransactions = userCursor.getInt(userCursor.getColumnIndex("total_transactions"));
                            if (totalTransactions > 0) {
                                    /*Utils.var_dump("User is " + cursor.getString(cursor.getColumnIndex(Constants.WATER_USER_FNAME_TAG)),
                                            "User has not paid for " + month + "/" + year);*/
                                HashMap<String, String> defaulter = new HashMap<String, String>();
                                defaulter.put(Constants.DATE_TAG, formatter.format(defaultedDate));
                                defaulter.put(Constants.TRANSACTION_COST_TAG, Utils.numberFormat(userCursor.getDouble(userCursor.getColumnIndex(Constants.TRANSACTION_COST_TAG))));
                                defaultedMonths.add(defaulter);
                            }
                        }
                    } finally {
                        if (userCursor != null) {
                            userCursor.close();
                        }
                    }
                    calendar.add(Calendar.MONTH, 1);
                }
            } catch (ParseException e) {
                e.printStackTrace();
            }

        }
        db.close();
        return defaultedMonths;
    }

}
