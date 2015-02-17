package com.sukavi.pm4w.user;

import org.json.JSONException;
import org.json.JSONObject;

public class User extends Permissions {

    //private variables
    private int IDU;
    private int GROUP_ID;
    private String USERNAME;
    private String PNUMBER;
    private String EMAIL;
    private String FNAME;
    private String LNAME;
    private String REQUEST_HASH;
    private String LAST_LOGIN;

    // Empty constructor
    public User() {
	Permissions.ID_GROUP = 0;
	Permissions.GROUP_NAME = null;
	Permissions.GROUP_IS_ENABLED = false;
	Permissions.CAN_ACCESS_SYSTEM_CONFIG = false;
	Permissions.CAN_RECEIVE_EMAILS = false;
	Permissions.CAN_ACCESS_APP = false;
	Permissions.CAN_SEND_SMS = false;
	Permissions.CAN_RECEIVE_PUSH_NOTIFICATIONS = false;
	Permissions.CAN_SUBMIT_ATTENDANT_DAILY_SALES = false;
	Permissions.CAN_APPROVE_ATTENDANTS_SUBMISSIONS = false;
	Permissions.CAN_APPROVE_TREASURERS_SUBMISSIONS = false;
	Permissions.CAN_CANCEL_ATTENDANT_DAILY_SALES = false;
	Permissions.CAN_CANCEL_ATTENDANTS_SUBMISSIONS = false;
	Permissions.CAN_CANCEL_TREASURERS_SUBMISSIONS = false;
	Permissions.CAN_ADD_WATER_USERS = false;
	Permissions.CAN_EDIT_WATER_USERS = false;
	Permissions.CAN_DELETE_WATER_USERS = false;
	Permissions.CAN_VIEW_WATER_USERS = false;
	Permissions.CAN_ADD_SALES = false;
	Permissions.CAN_EDIT_SALES = false;
	Permissions.CAN_DELETE_SALES = false;
	Permissions.CAN_VIEW_SALES = false;
	Permissions.CAN_VIEW_PERSONAL_SAVINGS = false;
	Permissions.CAN_VIEW_WATER_SOURCE_SAVINGS = false;
	Permissions.CAN_ADD_WATER_SOURCES = false;
	Permissions.CAN_EDIT_WATER_SOURCES = false;
	Permissions.CAN_DELETE_WATER_SOURCES = false;
	Permissions.CAN_VIEW_WATER_SOURCES = false;
	Permissions.CAN_ADD_REPAIR_TYPES = false;
	Permissions.CAN_EDIT_REPAIR_TYPES = false;
	Permissions.CAN_DELETE_REPAIR_TYPES = false;
	Permissions.CAN_VIEW_REPAIR_TYPES = false;
	Permissions.CAN_ADD_EXPENSES = false;
	Permissions.CAN_EDIT_EXPENSES = false;
	Permissions.CAN_DELETE_EXPENSES = false;
	Permissions.CAN_VIEW_EXPENSES = false;
	Permissions.CAN_ADD_SYSTEM_USERS = false;
	Permissions.CAN_EDIT_SYSTEM_USERS = false;
	Permissions.CAN_DELETE_SYSTEM_USERS = false;
	Permissions.CAN_VIEW_SYSTEM_USERS = false;
	Permissions.CAN_ADD_USER_PERMISSIONS = false;
	Permissions.CAN_EDIT_USER_PERMISSIONS = false;
	Permissions.CAN_DELETE_USER_PERMISSIONS = false;
	Permissions.CAN_VIEW_USER_PERMISSIONS = false;

    }

    // constructor 1
    public User(JSONObject account) throws JSONException {

	this.IDU = account.getInt("idu");
	this.GROUP_ID = account.getInt("group_id");
	this.USERNAME = account.getString("username");
	this.PNUMBER = account.getString("pnumber");
	this.EMAIL = account.getString("email");
	this.FNAME = account.getString("fname");
	this.LNAME = account.getString("lname");
	this.REQUEST_HASH = account.getString("request_hash");
	this.LAST_LOGIN = account.getString("last_login");

	Permissions.ID_GROUP = account.getInt("ID_GROUP");
	Permissions.GROUP_NAME = account.getString("GROUP_NAME");
	Permissions.GROUP_IS_ENABLED = account.getBoolean("GROUP_IS_ENABLED");
	Permissions.CAN_ACCESS_SYSTEM_CONFIG = account.getBoolean("CAN_ACCESS_SYSTEM_CONFIG");
	Permissions.CAN_RECEIVE_EMAILS = account.getBoolean("CAN_RECEIVE_EMAILS");
	Permissions.CAN_ACCESS_APP = account.getBoolean("CAN_ACCESS_APP");
	Permissions.CAN_SEND_SMS = account.getBoolean("CAN_SEND_SMS");
	Permissions.CAN_RECEIVE_PUSH_NOTIFICATIONS = account.getBoolean("CAN_RECEIVE_PUSH_NOTIFICATIONS");
	Permissions.CAN_SUBMIT_ATTENDANT_DAILY_SALES = account.getBoolean("CAN_SUBMIT_ATTENDANT_DAILY_SALES");
	Permissions.CAN_APPROVE_ATTENDANTS_SUBMISSIONS = account.getBoolean("CAN_APPROVE_ATTENDANTS_SUBMISSIONS");
	Permissions.CAN_APPROVE_TREASURERS_SUBMISSIONS = account.getBoolean("CAN_APPROVE_TREASURERS_SUBMISSIONS");
	Permissions.CAN_CANCEL_ATTENDANT_DAILY_SALES = account.getBoolean("CAN_CANCEL_ATTENDANT_DAILY_SALES");
	Permissions.CAN_CANCEL_ATTENDANTS_SUBMISSIONS = account.getBoolean("CAN_CANCEL_ATTENDANTS_SUBMISSIONS");
	Permissions.CAN_CANCEL_TREASURERS_SUBMISSIONS = account.getBoolean("CAN_CANCEL_TREASURERS_SUBMISSIONS");
	Permissions.CAN_ADD_WATER_USERS = account.getBoolean("CAN_ADD_WATER_USERS");
	Permissions.CAN_EDIT_WATER_USERS = account.getBoolean("CAN_EDIT_WATER_USERS");
	Permissions.CAN_DELETE_WATER_USERS = account.getBoolean("CAN_DELETE_WATER_USERS");
	Permissions.CAN_VIEW_WATER_USERS = account.getBoolean("CAN_VIEW_WATER_USERS");
	Permissions.CAN_ADD_SALES = account.getBoolean("CAN_ADD_SALES");
	Permissions.CAN_EDIT_SALES = account.getBoolean("CAN_EDIT_SALES");
	Permissions.CAN_DELETE_SALES = account.getBoolean("CAN_DELETE_SALES");
	Permissions.CAN_VIEW_SALES = account.getBoolean("CAN_VIEW_SALES");
	Permissions.CAN_VIEW_PERSONAL_SAVINGS = account.getBoolean("CAN_VIEW_PERSONAL_SAVINGS");
	Permissions.CAN_VIEW_WATER_SOURCE_SAVINGS = account.getBoolean("CAN_VIEW_WATER_SOURCE_SAVINGS");
	Permissions.CAN_ADD_WATER_SOURCES = account.getBoolean("CAN_ADD_WATER_SOURCES");
	Permissions.CAN_EDIT_WATER_SOURCES = account.getBoolean("CAN_EDIT_WATER_SOURCES");
	Permissions.CAN_DELETE_WATER_SOURCES = account.getBoolean("CAN_DELETE_WATER_SOURCES");
	Permissions.CAN_VIEW_WATER_SOURCES = account.getBoolean("CAN_VIEW_WATER_SOURCES");
	Permissions.CAN_ADD_REPAIR_TYPES = account.getBoolean("CAN_ADD_REPAIR_TYPES");
	Permissions.CAN_EDIT_REPAIR_TYPES = account.getBoolean("CAN_EDIT_REPAIR_TYPES");
	Permissions.CAN_DELETE_REPAIR_TYPES = account.getBoolean("CAN_DELETE_REPAIR_TYPES");
	Permissions.CAN_VIEW_REPAIR_TYPES = account.getBoolean("CAN_VIEW_REPAIR_TYPES");
	Permissions.CAN_ADD_EXPENSES = account.getBoolean("CAN_ADD_EXPENSES");
	Permissions.CAN_EDIT_EXPENSES = account.getBoolean("CAN_EDIT_EXPENSES");
	Permissions.CAN_DELETE_EXPENSES = account.getBoolean("CAN_DELETE_EXPENSES");
	Permissions.CAN_VIEW_EXPENSES = account.getBoolean("CAN_VIEW_EXPENSES");
	Permissions.CAN_ADD_SYSTEM_USERS = account.getBoolean("CAN_ADD_SYSTEM_USERS");
	Permissions.CAN_EDIT_SYSTEM_USERS = account.getBoolean("CAN_EDIT_SYSTEM_USERS");
	Permissions.CAN_DELETE_SYSTEM_USERS = account.getBoolean("CAN_DELETE_SYSTEM_USERS");
	Permissions.CAN_VIEW_SYSTEM_USERS = account.getBoolean("CAN_VIEW_SYSTEM_USERS");
	Permissions.CAN_ADD_USER_PERMISSIONS = account.getBoolean("CAN_ADD_USER_PERMISSIONS");
	Permissions.CAN_EDIT_USER_PERMISSIONS = account.getBoolean("CAN_EDIT_USER_PERMISSIONS");
	Permissions.CAN_DELETE_USER_PERMISSIONS = account.getBoolean("CAN_DELETE_USER_PERMISSIONS");
	Permissions.CAN_VIEW_USER_PERMISSIONS = account.getBoolean("CAN_VIEW_USER_PERMISSIONS");       
    }

    // constructor 2
    public User(int IDU, int GROUP_ID, String USERNAME, String PNUMBER, String EMAIL, String FNAME, String LNAME, String REQUEST_HASH, String LAST_LOGIN) {
	this.IDU = IDU;
	this.GROUP_ID = GROUP_ID;
	this.USERNAME = USERNAME;
	this.PNUMBER = PNUMBER;
	this.EMAIL = EMAIL;
	this.FNAME = FNAME;
	this.LNAME = LNAME;
	this.REQUEST_HASH = REQUEST_HASH;
	this.LAST_LOGIN = LAST_LOGIN;
    }

    public int getIDU() {
	return this.IDU;
    }

    public void setIDU(int IDU) {
	this.IDU = IDU;
    }

    public int getGROUP_ID() {
	return this.GROUP_ID;
    }

    public void setGROUP_ID(int ROLE_ID) {
	this.GROUP_ID = ROLE_ID;
    }

    public String getUSERNAME() {
	return this.USERNAME;
    }

    public void setUSERNAME(String USERNAME) {
	this.USERNAME = USERNAME;
    }

    public String getPNUMBER() {
	return this.PNUMBER;
    }

    public void setPNUMBER(String PNUMBER) {
	this.PNUMBER = PNUMBER;
    }

    public String getEMAIL() {
	return this.EMAIL;
    }

    public void setEMAIL(String EMAIL) {
	this.EMAIL = EMAIL;
    }

    public String getFNAME() {
	return this.FNAME;
    }

    public void setFNAME(String FNAME) {
	this.FNAME = FNAME;
    }

    public String getLNAME() {
	return this.LNAME;
    }

    public void setLNAME(String LNAME) {
	this.LNAME = LNAME;
    }

    public String getREQUEST_HASH() {
	return this.REQUEST_HASH;
    }

    public void setREQUEST_HASH(String REQUEST_HASH) {
	this.REQUEST_HASH = REQUEST_HASH;
    }

    public String getLAST_LOGIN() {
	return this.LAST_LOGIN;
    }

    public void setLAST_LOGIN(String LAST_LOGIN) {
	this.LAST_LOGIN = LAST_LOGIN;
    }
}
