package com.eyeeza.apps.pm4w.dbmanager;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * Created by sammy-n-ukavi-jr on 7/26/15.
 */
public class DBoperations extends SQLiteOpenHelper {


    public DBoperations(Context context) {
        super(context, Constants.DATABASE_NAME, null, Constants.DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        sqLiteDatabase.execSQL(Constants.CREATE_EVENTS_LOG_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_USER_SESSION_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_USER_GROUPS_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_ATTENDING_TO_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_COLLECTING_FROM_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_EXPENDITURES_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_REPAIR_TYPES_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_SALES_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_USERS_TABLE_SQL);
        sqLiteDatabase.execSQL(Constants.CREATE_WATER_USERS_TABLE_SQL);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        setUpDB();
    }

    public void setUpDB() {
        SQLiteDatabase db = getWritableDatabase();
        db.execSQL(Constants.DROP_EVENTS_LOG_TABLE_SQL);
        db.execSQL(Constants.DROP_USER_SESSION_TABLE_SQL);
        db.execSQL(Constants.DROP_USER_GROUPS_TABLE_SQL);
        db.execSQL(Constants.DROP_ATTENDING_TO_TABLE_SQL);
        db.execSQL(Constants.DROP_COLLECTING_FROM_TABLE_SQL);
        db.execSQL(Constants.DROP_EXPENDITURES_TABLE_SQL);
        db.execSQL(Constants.DROP_REPAIR_TYPES_TABLE_SQL);
        db.execSQL(Constants.DROP_SALES_TABLE_SQL);
        db.execSQL(Constants.DROP_USERS_TABLE_SQL);
        db.execSQL(Constants.DROP_WATER_USERS_TABLE_SQL);

        onCreate(db);
        db.close();
    }
}
