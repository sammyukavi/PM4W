package com.eyeeza.apps.pm4w.dbtables;

import android.content.ContentValues;
import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by Sammy N Ukavi Jr on 5/1/2016.
 */
public class Users extends DBoperations {
    private int iDu = 0;
    private String fName = null;
    private String lname = null;
    private Context context;

    public Users(Context context) {
        super(context);
        this.context = context;
    }

    public long saveUser(JSONObject user) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.USER_IDU_TAG, user.getInt(Constants.USER_IDU_TAG));
        values.put(Constants.USER_FNAME_TAG, user.getString(Constants.USER_FNAME_TAG));
        values.put(Constants.USER_LNAME_TAG, user.getString(Constants.USER_LNAME_TAG));

        long no = db.insertWithOnConflict(Constants.USERS_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }
}
