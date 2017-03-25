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

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Sammy N Ukavi Jr on 4/29/2016.
 */
public class Treasurers extends DBoperations {
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
    private int monthlyBilledUsers;
    private int verifiedTransactions;
    private double totalSales;
    private double availableSavings;

    public Treasurers(Context context) {
        super(context);
        this.context = context;
    }

    public double getTotalSales() {
        return totalSales;
    }

    public void setTotalSales(double totalSales) {
        this.totalSales = totalSales;
    }

    public double getAvailableSavings() {
        return availableSavings;
    }

    public void setAvailableSavings(double availableSavings) {
        this.availableSavings = availableSavings;
    }

    public int getVerifiedTransactions() {
        return verifiedTransactions;
    }

    public void setVerifiedTransactions(int verifiedTransactions) {
        this.verifiedTransactions = verifiedTransactions;
    }

    public int getMonthlyBilledUsers() {
        return monthlyBilledUsers;
    }

    public void setMonthlyBilledUsers(int monthlyBilledUsers) {
        this.monthlyBilledUsers = monthlyBilledUsers;
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

    /**
     * Save a water source under which the user is collecting from
     *
     * @param waterSource
     * @return
     * @throws JSONException
     */
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
        long no = db.insertWithOnConflict(Constants.COLLECTING_FROM_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public void markSubmittedByCommiteeTreasurer(String soldBy, int submittedBy) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.SUBMITTED_TO_TREASURER_TAG, 1);
        values.put(Constants.SUBMITTED_BY_TAG, submittedBy);
        values.put(Constants.SUBMISSION_TO_TREASURER_DATE_TAG, Utils.getMySQLDate());
        values.put(Constants.TREASURERER_APPROVAL_STATUS_TAG, 0);
        values.put(Constants.LAST_UPDATED_TAG, Utils.getMySQLDate());

        String[] args = new String[]{soldBy, "1", "1"};
        db.update(Constants.SALES_TABLENAME, values, Constants.SOLD_BY_TAG + "=? AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "<>? AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "<>?", args);
        db.close();
    }

    public void approveSubmittedByCommiteeTreasurer(String submittedBy, int reviewedBy) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.SUBMITTED_TO_TREASURER_TAG, 1);
        values.put(Constants.TREASURERER_APPROVAL_STATUS_TAG, 1);
        values.put(Constants.REVIEWED_BY_TAG, reviewedBy);
        values.put(Constants.DATE_REVIEWED_TAG, Utils.getMySQLDate());
        values.put(Constants.LAST_UPDATED_TAG, Utils.getMySQLDate());

        String[] args = new String[]{submittedBy, "1", "1"};
        db.update(Constants.SALES_TABLENAME, values, Constants.SUBMITTED_BY_TAG + "=? AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "=? AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "<>?", args);
        db.close();
    }

    public void denySubmittedByCommiteeTreasurer(String submittedBy, int reviwedBy) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.SUBMITTED_TO_TREASURER_TAG, 0);
        values.put(Constants.TREASURERER_APPROVAL_STATUS_TAG, 2);
        values.put(Constants.REVIEWED_BY_TAG, reviwedBy);
        values.put(Constants.DATE_REVIEWED_TAG, Utils.getMySQLDate());
        values.put(Constants.LAST_UPDATED_TAG, Utils.getMySQLDate());

        String[] args = new String[]{submittedBy, "1", "0"};
        db.update(Constants.SALES_TABLENAME, values, Constants.SUBMITTED_BY_TAG + "=? AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "=? AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "=?", args);
        db.close();
    }

    public ArrayList<HashMap<String, String>> fetchTresurerCollections() {
        ArrayList<HashMap<String, String>> collections = new ArrayList<>();
        String selectQuery = "SELECT " + Constants.USER_IDU_TAG + "," +
                (Constants.USER_FNAME_TAG + " || \" \" ||" + Constants.USER_LNAME_TAG) + " AS " + Constants.COMBINED_FNAME_LNAME_TAG + ", " +
                Constants.WATER_SOURCE_NAME_TAG + ", " +
                " SUM(" + Constants.SALE_UGX_TAG + ") AS " + Constants.SALE_UGX_TAG + "," +
                " CASE WHEN " + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " > 0 THEN SUM(CAST(" + Constants.SALE_UGX_TAG + " AS FLOAT)*(CAST(" + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " AS FLOAT)/CAST(100 AS FLOAT))) ELSE SUM(" + Constants.SALE_UGX_TAG + ") END AS " + Constants.SAVINGS_TAG +
                " FROM " + Constants.SALES_TABLENAME + " LEFT OUTER JOIN " + Constants.COLLECTING_FROM_TABLENAME + " ON " +
                Constants.SALES_TABLENAME + "." + Constants.SALE_WATER_SOURCE_ID_TAG + "=" + Constants.ID_WATER_SOURCE_TAG +
                " LEFT OUTER JOIN " + Constants.USERS_TABLENAME + " ON " + Constants.SUBMITTED_BY_TAG + "=" + Constants.USER_IDU_TAG +
                " WHERE " + Constants.SALE_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "=1 AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "=0 AND " + Constants.SALE_UGX_TAG + " >0" +
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

    public ArrayList<HashMap<String, String>> getAllWaterSources() {
        ArrayList<HashMap<String, String>> waterSourcesList = new ArrayList<>();
        String selectQuery = " SELECT  * FROM " + Constants.COLLECTING_FROM_TABLENAME + " ORDER BY " + Constants.WATER_SOURCE_NAME_TAG;

        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> map = new HashMap<String, String>();
                map.put(Constants.ID_WATER_SOURCE_TAG, String.valueOf(cursor.getInt(cursor.getColumnIndex(Constants.ID_WATER_SOURCE_TAG))));
                map.put(Constants.WATER_SOURCE_NAME_TAG, cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_NAME_TAG)));
                waterSourcesList.add(map);
            } while (cursor.moveToNext());
        }
        db.close();
        return waterSourcesList;
    }

    private void calculateTransactionsAndSavings() {
        SQLiteDatabase db = getReadableDatabase();
        String selectQuery = " SELECT  COUNT(" + Constants.ID_SALE_TAG + ") AS " + Constants.APPROVED_TRANSACTIONS_COUNT_TAG +
                " , SUM(" + Constants.SALE_UGX_TAG + ") AS " + Constants.SALE_UGX_TAG + "," +
                " CASE WHEN " + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " > 0 THEN SUM(CAST(" + Constants.SALE_UGX_TAG + " AS FLOAT)*(CAST(" + Constants.SALES_TABLENAME + "." + Constants.PERCENTAGE_SAVED_TAG + " AS FLOAT)/CAST(100 AS FLOAT))) ELSE SUM(" + Constants.SALE_UGX_TAG + ") END AS " + Constants.SAVINGS_TAG +
                " FROM " + Constants.SALES_TABLENAME + " " +
                " WHERE " + Constants.SALES_TABLENAME + "." + Constants.SALE_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.SUBMITTED_TO_TREASURER_TAG + "=1 AND " + Constants.TREASURERER_APPROVAL_STATUS_TAG + "=1 AND " + Constants.SALE_WATER_SOURCE_ID_TAG + "=" + waterSourceId;
        Cursor cursor = db.rawQuery(selectQuery, null);
        try {
            if (cursor.moveToFirst()) {
                do {
                    this.totalSales = cursor.getInt(cursor.getColumnIndex(Constants.SALE_UGX_TAG));
                    this.verifiedTransactions = cursor.getInt(cursor.getColumnIndex(Constants.APPROVED_TRANSACTIONS_COUNT_TAG));
                    this.availableSavings = cursor.getDouble(cursor.getColumnIndex(Constants.SAVINGS_TAG));
                } while (cursor.moveToNext());
            }
        } finally {
            if (cursor != null) {
                cursor.close();
            }
        }

        selectQuery = "SELECT SUM(" + Constants.EXPENDITURE_COST_TAG + ") AS " + Constants.EXPENDITURE_COST_TAG + " FROM " + Constants.EXPENDITURES_TABLENAME +
                " WHERE " + Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG + "=0 AND " + Constants.EXPENDITURE_WATER_SOURCE_ID_TAG + "=" + waterSourceId;
        cursor = db.rawQuery(selectQuery, null);
        try {
            if (cursor.moveToFirst()) {
                do {
                    //this.availableSavings -= cursor.getDouble(cursor.getColumnIndex(Constants.EXPENDITURE_COST_TAG));
                } while (cursor.moveToNext());
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        } finally {
            if (cursor != null) {
                cursor.close();
            }
        }

        db.close();
    }

    public void getWaterSource(int waterSourceId) {
        SQLiteDatabase db = getReadableDatabase();
        String selectQuery = " SELECT  *, COUNT(" + Constants.WATER_USER_ID_TAG + ") AS " + Constants.USER_COUNT_TAG + " FROM " + Constants.COLLECTING_FROM_TABLENAME + " " +
                " LEFT JOIN " + Constants.WATER_USERS_TABLENAME + " ON " + Constants.WATER_USERS_TABLENAME + "." + Constants.WATER_USER_WATER_SOURCE_ID_TAG + "=" + Constants.COLLECTING_FROM_TABLENAME + "." + Constants.ID_WATER_SOURCE_TAG +
                //" WHERE (" + Constants.COLLECTING_FROM_TABLENAME + "." + Constants.ID_WATER_SOURCE_TAG + "=" + waterSourceId + " AND " + Constants.WATER_USER_MARKED_FOR_DELETE_TAG + "=0)";
                " WHERE " + Constants.COLLECTING_FROM_TABLENAME + "." + Constants.ID_WATER_SOURCE_TAG + "=" + waterSourceId;
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
                this.monthlyBilledUsers = cursor.getInt(cursor.getColumnIndex(Constants.USER_COUNT_TAG));
            } while (cursor.moveToNext());
        }
        db.close();
        calculateTransactionsAndSavings();
    }


}
