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
public class RepairTypes extends DBoperations {

    private Context context;

    public RepairTypes(Context context) {
        super(context);
        this.context = context;
    }

    public long saveRepairType(JSONObject repairType) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_REPAIR_TYPE_TAG, repairType.getInt(Constants.ID_REPAIR_TYPE_TAG));
        values.put(Constants.REPAIR_TYPE_TAG, repairType.getString(Constants.REPAIR_TYPE_TAG));
        values.put(Constants.REPAIR_TYPE_ACTIVE_TAG, repairType.getString(Constants.REPAIR_TYPE_ACTIVE_TAG));
        values.put(Constants.REPAIR_TYPE_DATE_CREATED_TAG, repairType.getString(Constants.REPAIR_TYPE_DATE_CREATED_TAG));
        values.put(Constants.REPAIR_TYPE_LAST_UPDATED_TAG, repairType.getString(Constants.REPAIR_TYPE_LAST_UPDATED_TAG));
        long no = db.insertWithOnConflict(Constants.REPAIR_TYPES_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public ArrayList<HashMap<String, String>> getAllRepairTypes() {
        ArrayList<HashMap<String, String>> repairTypesList = new ArrayList<>();
        String selectQuery = " SELECT  * FROM " + Constants.REPAIR_TYPES_TABLENAME + " WHERE " + Constants.REPAIR_TYPE_ACTIVE_TAG + "=1 ORDER BY " + Constants.REPAIR_TYPE_TAG;

        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                HashMap<String, String> map = new HashMap<String, String>();
                map.put(Constants.ID_REPAIR_TYPE_TAG, String.valueOf(cursor.getInt(cursor.getColumnIndex(Constants.ID_REPAIR_TYPE_TAG))));
                map.put(Constants.REPAIR_TYPE_TAG, cursor.getString(cursor.getColumnIndex(Constants.REPAIR_TYPE_TAG)));
                repairTypesList.add(map);
            } while (cursor.moveToNext());
        }
        db.close();
        return repairTypesList;
    }
}
