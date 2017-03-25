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

public class Expenditures extends DBoperations {

    private Context context;
    private long idExpenditure = 0;
    private int waterSourceId;
    private String waterSourceName;
    private int repairTypeId;

    private String repairType;
    private String expenditureDate;
    private double expenditureCost;
    private String benefactor;
    private String description;
    private int loggedBy;
    private String addedBy;
    private int markedForDelete;
    private String dateCreated;
    private String lastUpdated;

    public Expenditures(Context context) {
        super(context);
        this.context = context;
    }

    public long getIdExpenditure() {
        return idExpenditure;
    }

    public void setIdExpenditure(long idExpenditure) {
        this.idExpenditure = idExpenditure;
    }

    public int getWaterSourceId() {
        return waterSourceId;
    }

    public void setWaterSourceId(int waterSourceId) {
        this.waterSourceId = waterSourceId;
    }

    public String getWaterSourceName() {
        return waterSourceName;
    }

    public void setWaterSourceName(String waterSourceName) {
        this.waterSourceName = waterSourceName;
    }

    public int getRepairTypeId() {
        return repairTypeId;
    }

    public void setRepairTypeId(int repairTypeId) {
        this.repairTypeId = repairTypeId;
    }

    public String getRepairType() {
        return repairType;
    }

    public void setRepairType(String repairType) {
        this.repairType = repairType;
    }

    public String getExpenditureDate() {
        return expenditureDate;
    }

    public void setExpenditureDate(String expenditureDate) {
        this.expenditureDate = expenditureDate;
    }

    public double getExpenditureCost() {
        return expenditureCost;
    }

    public void setExpenditureCost(double expenditureCost) {
        this.expenditureCost = expenditureCost;
    }

    public String getBenefactor() {
        return benefactor;
    }

    public void setBenefactor(String benefactor) {
        this.benefactor = benefactor;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public int getLoggedBy() {
        return loggedBy;
    }

    public void setLoggedBy(int loggedBy) {
        this.loggedBy = loggedBy;
    }

    public String getAddedBy() {
        return addedBy;
    }

    public void setAddedBy(String addedBy) {
        this.addedBy = addedBy;
    }

    public int getMarkedForDelete() {
        return markedForDelete;
    }

    public void setMarkedForDelete(int markedForDelete) {
        this.markedForDelete = markedForDelete;
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

    public long saveExpenditure(JSONObject expenditure) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_EXPENDITURE_TAG, expenditure.getInt(Constants.ID_EXPENDITURE_TAG));
        values.put(Constants.EXPENDITURE_WATER_SOURCE_ID_TAG, expenditure.getInt(Constants.EXPENDITURE_WATER_SOURCE_ID_TAG));
        values.put(Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG, expenditure.getInt(Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG));
        values.put(Constants.EXPENDITURE_DATE_TAG, expenditure.getString(Constants.EXPENDITURE_DATE_TAG));
        values.put(Constants.EXPENDITURE_COST_TAG, expenditure.getString(Constants.EXPENDITURE_COST_TAG));
        values.put(Constants.BENEFACTOR_TAG, expenditure.getString(Constants.BENEFACTOR_TAG));
        values.put(Constants.DESCRIPTION_TAG, expenditure.getString(Constants.DESCRIPTION_TAG));
        values.put(Constants.LOGGED_BY_TAG, expenditure.getString(Constants.LOGGED_BY_TAG));
        values.put(Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG, expenditure.getString(Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG));
        values.put(Constants.EXPENDITURE_DATE_CREATED_TAG, expenditure.getString(Constants.EXPENDITURE_DATE_CREATED_TAG));
        values.put(Constants.EXPENDITURE_LAST_UPDATED_TAG, expenditure.getString(Constants.EXPENDITURE_LAST_UPDATED_TAG));
        long no = db.insertWithOnConflict(Constants.EXPENDITURES_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public long saveExpenditure() {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        if (idExpenditure != 0) {
            values.put(Constants.ID_EXPENDITURE_TAG, idExpenditure);
        }
        values.put(Constants.EXPENDITURE_WATER_SOURCE_ID_TAG, waterSourceId);
        values.put(Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG, repairTypeId);
        values.put(Constants.EXPENDITURE_DATE_TAG, expenditureDate);
        values.put(Constants.EXPENDITURE_COST_TAG, expenditureCost);
        values.put(Constants.BENEFACTOR_TAG, benefactor);
        values.put(Constants.DESCRIPTION_TAG, description);
        values.put(Constants.LOGGED_BY_TAG, loggedBy);
        values.put(Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG, markedForDelete);
        values.put(Constants.EXPENDITURE_DATE_CREATED_TAG, dateCreated);
        values.put(Constants.EXPENDITURE_LAST_UPDATED_TAG, lastUpdated);
        long no = db.insertWithOnConflict(Constants.EXPENDITURES_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public void getExpenditure(long expenditureId) {
        SQLiteDatabase db = getReadableDatabase();

        String selectQuery = " SELECT  *," + Constants.EXPENDITURES_TABLENAME + "." + Constants.EXPENDITURE_DATE_CREATED_TAG + " t " +
                "," + Constants.EXPENDITURES_TABLENAME + "." + Constants.EXPENDITURE_DATE_CREATED_TAG + " u, " +
                (Constants.USER_FNAME_TAG + " || \" \" ||" + Constants.USER_LNAME_TAG) + " AS " + Constants.COMBINED_FNAME_LNAME_TAG +
                " FROM " + Constants.EXPENDITURES_TABLENAME + " " +
                " LEFT JOIN " + Constants.COLLECTING_FROM_TABLENAME + " ON " + Constants.COLLECTING_FROM_TABLENAME + "." + Constants.ID_WATER_SOURCE_TAG + "=" + Constants.EXPENDITURES_TABLENAME + "." + Constants.EXPENDITURE_WATER_SOURCE_ID_TAG +
                " LEFT JOIN " + Constants.REPAIR_TYPES_TABLENAME + " ON " + Constants.ID_REPAIR_TYPE_TAG + "=" + Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG +
                " LEFT OUTER JOIN " + Constants.USERS_TABLENAME + " ON " + Constants.LOGGED_BY_TAG + "=" + Constants.USER_IDU_TAG +
                " WHERE " + Constants.ID_EXPENDITURE_TAG + "=" + expenditureId + " AND " + Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG + "=0" +
                " ORDER BY " + Constants.EXPENDITURE_DATE_TAG + " DESC ";

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null) {
            cursor.moveToFirst();
        }
        try {
            this.idExpenditure = cursor.getInt(cursor.getColumnIndex(Constants.ID_EXPENDITURE_TAG));
            this.waterSourceId = cursor.getInt(cursor.getColumnIndex(Constants.EXPENDITURE_WATER_SOURCE_ID_TAG));
            this.waterSourceName = cursor.getString(cursor.getColumnIndex(Constants.WATER_SOURCE_NAME_TAG));
            this.repairTypeId = cursor.getInt(cursor.getColumnIndex(Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG));
            this.repairType = cursor.getString(cursor.getColumnIndex(Constants.REPAIR_TYPE_TAG));
            this.expenditureDate = cursor.getString(cursor.getColumnIndex(Constants.EXPENDITURE_DATE_TAG));
            this.expenditureCost = cursor.getDouble(cursor.getColumnIndex(Constants.EXPENDITURE_COST_TAG));
            this.benefactor = cursor.getString(cursor.getColumnIndex(Constants.BENEFACTOR_TAG));
            this.description = cursor.getString(cursor.getColumnIndex(Constants.DESCRIPTION_TAG));
            this.loggedBy = cursor.getInt(cursor.getColumnIndex(Constants.LOGGED_BY_TAG));
            this.addedBy = cursor.getString(cursor.getColumnIndex(Constants.COMBINED_FNAME_LNAME_TAG));
            this.markedForDelete = cursor.getInt(cursor.getColumnIndex(Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG));
            this.dateCreated = cursor.getString(cursor.getColumnIndex("t"));
            this.lastUpdated = cursor.getString(cursor.getColumnIndex("u"));
        } catch (Exception e) {
            e.printStackTrace();
        }
        db.close();
    }

    public ArrayList<HashMap<String, String>> fetchExpenditures(String waterSourceId) {
        ArrayList<HashMap<String, String>> collections = new ArrayList<>();

        String selectQuery = " SELECT  * FROM " + Constants.EXPENDITURES_TABLENAME + " " +
                " LEFT JOIN " + Constants.REPAIR_TYPES_TABLENAME + " ON " + Constants.ID_REPAIR_TYPE_TAG + "=" + Constants.EXPENDITURE_REPAIR_TYPE_ID_TAG +
                " WHERE " + Constants.EXPENDITURE_WATER_SOURCE_ID_TAG + "=" + waterSourceId + " AND " + Constants.EXPENDITURE_MARKED_FOR_DELETE_TAG + "=0" +
                " ORDER BY " + Constants.EXPENDITURE_DATE_TAG + " DESC ";

        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> map = new HashMap<String, String>();
                map.put(Constants.ID_EXPENDITURE_TAG, String.valueOf(cursor.getInt(cursor.getColumnIndex(Constants.ID_EXPENDITURE_TAG))));
                map.put(Constants.EXPENDITURE_DATE_TAG, Utils.formatDate(cursor.getString(cursor.getColumnIndex(Constants.EXPENDITURE_DATE_TAG))));
                map.put(Constants.BENEFACTOR_TAG, cursor.getString(cursor.getColumnIndex(Constants.BENEFACTOR_TAG)));
                map.put(Constants.EXPENDITURE_COST_TAG, Utils.numberFormat(cursor.getDouble(cursor.getColumnIndex(Constants.EXPENDITURE_COST_TAG))));
                collections.add(map);
            } while (cursor.moveToNext());
        }
        db.close();

        return collections;
    }


}