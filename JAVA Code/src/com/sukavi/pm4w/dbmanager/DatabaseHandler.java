package com.sukavi.pm4w.dbmanager;

import java.util.ArrayList;
import java.util.List;

import com.sukavi.pm4w.user.User;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DatabaseHandler extends SQLiteOpenHelper {

    private static final int DATABASE_VERSION = 1;	
    private static final String DATABASE_NAME = "pm4w";
    private static final String USERS_TABLE = "users";
    private static final String KEY_ID="id";
    private static final String KEY_IDU="idu";
    private static final String KEY_ROLE_ID="role_id";
    private static final String KEY_USERNAME = "username";
    private static final String KEY_PNUMBER = "pnumber";
    private static final String KEY_EMAIL = "email";
    private static final String KEY_FNAME = "fname";
    private static final String KEY_LNAME = "lname";
    private static final String KEY_REQUEST_HASH = "hash";
    private static final String KEY_LAST_LOGIN = "last_login";

    public DatabaseHandler(Context context) {
	super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // Creating Tables
    @Override
    public void onCreate(SQLiteDatabase db) {
	String CREATE_USERS_TABLE = "CREATE TABLE " + USERS_TABLE + "("
		+ KEY_ID + " INTEGER PRIMARY KEY," + KEY_IDU + " INTEGER,"+ KEY_ROLE_ID + " INTEGER,"
		+ KEY_USERNAME + " TEXT,"+KEY_PNUMBER+" TEXT,"+KEY_EMAIL+" TEXT,"
		+ KEY_FNAME + " TEXT,"+KEY_LNAME+" TEXT,"+KEY_REQUEST_HASH+" TEXT,"+KEY_LAST_LOGIN+" TEXT"
		+ ")";
	db.execSQL(CREATE_USERS_TABLE);
    }

    // Upgrading database
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
	// Drop older table if existed
	db.execSQL("DROP TABLE IF EXISTS " + USERS_TABLE);
	// Create tables again
	onCreate(db);
    }


    public void addUser(User user) {
	SQLiteDatabase db = this.getWritableDatabase();

	ContentValues values = new ContentValues();
	values.put(KEY_IDU, user.getIDU());
	values.put(KEY_ROLE_ID, user.getGROUP_ID()); 
	values.put(KEY_USERNAME, user.getUSERNAME()); 
	values.put(KEY_PNUMBER, user.getPNUMBER()); 
	values.put(KEY_EMAIL, user.getEMAIL());
	values.put(KEY_FNAME, user.getFNAME()); 
	values.put(KEY_LNAME, user.getLNAME()); 
	values.put(KEY_REQUEST_HASH, user.getREQUEST_HASH());
	values.put(KEY_LAST_LOGIN, user.getLAST_LOGIN());

	// Inserting Row
	db.insert(USERS_TABLE, null, values);
	db.close(); // Closing database connection
    }

    // Getting single contact
    public User getUser(int id) {
	SQLiteDatabase db = this.getReadableDatabase();
	User user = null;
	Cursor cursor = null;
	try{	    	
	    cursor = db.query(
		    USERS_TABLE,
		    new String[] { 
			    KEY_ID,
			    KEY_IDU,
			    KEY_ROLE_ID,
			    KEY_USERNAME,
			    KEY_PNUMBER,
			    KEY_EMAIL,
			    KEY_FNAME,
			    KEY_LNAME,
			    KEY_REQUEST_HASH,
			    KEY_LAST_LOGIN
		    }, KEY_ID + "=?",
		    new String[] { String.valueOf(id) }, null, null, null, null);

	    if (cursor != null){
		cursor.moveToFirst();
	    }			
	    user = new User(Integer.parseInt(cursor.getString(1)), Integer.parseInt(cursor.getString(2)), cursor.getString(3), cursor.getString(4), cursor.getString(5), cursor.getString(6), cursor.getString(7), cursor.getString(8), cursor.getString(9));
	    //cursor.close();

	}catch(Exception ex){	
	    ex.printStackTrace();
	    return null;
	}finally {
	    if(!cursor.isClosed()) {
		cursor.close();
	    }
	    db.close();
	}	
	return user;
    }


    public List<User> getAllUsers() {
	List<User> usersList = new ArrayList<User>();	
	String selectQuery = "SELECT  * FROM " + USERS_TABLE;
	SQLiteDatabase db = this.getWritableDatabase();
	Cursor cursor = db.rawQuery(selectQuery, null);
	// looping through all rows and adding to list
	if (cursor.moveToFirst()) {
	    do {
		User user = new User();
		user.setIDU(Integer.parseInt(cursor.getString(1)));
		user.setGROUP_ID(Integer.parseInt(cursor.getString(2)));
		user.setUSERNAME(cursor.getString(3));
		user.setPNUMBER(cursor.getString(4));
		user.setEMAIL(cursor.getString(5));
		user.setFNAME(cursor.getString(6));
		user.setLNAME(cursor.getString(7));
		user.setREQUEST_HASH(cursor.getString(8));
		user.setLAST_LOGIN(cursor.getString(9));				
		usersList.add(user);
	    } while (cursor.moveToNext());
	}
	cursor.close();	
	db.close();
	return usersList;
    }

    public void updateUser(User user) {
	SQLiteDatabase db = this.getWritableDatabase();
	ContentValues values = new ContentValues();
	values.put(KEY_ROLE_ID, user.getGROUP_ID()); 
	values.put(KEY_USERNAME, user.getUSERNAME()); 
	values.put(KEY_PNUMBER, user.getPNUMBER()); 
	values.put(KEY_EMAIL, user.getEMAIL());
	values.put(KEY_FNAME, user.getFNAME()); 
	values.put(KEY_LNAME, user.getLNAME()); 
	values.put(KEY_REQUEST_HASH, user.getREQUEST_HASH());
	values.put(KEY_LAST_LOGIN, user.getLAST_LOGIN());		
	db.update(USERS_TABLE, values, KEY_ID + " = ?",new String[] { String.valueOf(user.getIDU()) }		
		);
	db.close();
    }

    public void deleteUser(User user) {
	SQLiteDatabase db = this.getWritableDatabase();
	db.delete(USERS_TABLE, KEY_ID + " = ?",new String[] { String.valueOf(user.getIDU()) });
	db.close();
    }

    public void Logout() {
	SQLiteDatabase db = this.getWritableDatabase();
	db.execSQL("DROP TABLE " + USERS_TABLE);
	onCreate(db);
	db.close();	
    }

    public int getUserCount() {
	String countQuery = "SELECT * FROM " + USERS_TABLE;
	SQLiteDatabase db = this.getReadableDatabase();
	Cursor cursor = db.rawQuery(countQuery, null);
	cursor.close();	
	db.close();
	return cursor.getCount();
    }
}