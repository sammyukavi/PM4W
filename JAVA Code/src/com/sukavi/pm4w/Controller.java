package com.sukavi.pm4w;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.google.android.gcm.GCMRegistrar; 
import com.sukavi.pm4w.config.Config;
import com.sukavi.pm4w.dbmanager.DatabaseHandler;
import com.sukavi.pm4w.http.JSONParser;
import com.sukavi.pm4w.user.User;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.Application;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.PowerManager;

public class Controller extends Application{

    JSONParser jsonp = new JSONParser();
    JSONObject json =null;
    private String api_url =null;
    DatabaseHandler dbhandler;
    User userSession;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    ProgressDialog	pDialog;

    // Register this account with the server.
    void register(final Context context, final String regId) {	
	dbhandler = new DatabaseHandler(context);	
	userSession = dbhandler.getUser(1);	
	pDialog = new ProgressDialog(context);

	params.clear();		
	params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));
	params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
	params.add(new BasicNameValuePair("uid", userSession.getIDU()+""));
	params.add(new BasicNameValuePair("gcm_regid", regId));	
	api_url = "?a=register-device";		


	//displayMessageOnScreen(context, context.getString(R.string.server_registering));


	AsyncTask<Void, Void, Void> mRegisterTask = new AsyncTask<Void, Void, Void>() {


	    @Override
	    protected void onPreExecute() {		
		super.onPreExecute();		
	    }

	    @Override
	    protected Void doInBackground(Void... paramz) {
		json = jsonp.makeHttpRequest(api_url, "POST", params);	
		return null;
	    }

	    @Override
	    protected void onPostExecute(Void result) {
		pDialog.cancel();

		try {
		    JSONObject server_info = json.getJSONObject("server_info");
		    server_info.getInt("server_status");
		    JSONObject data = json.getJSONObject("data");	
		    data.getInt("request_status");	
		    JSONArray msgs = data.getJSONArray("msgs");

		    for(int index=0; index<msgs.length();index++){
			try {
			    msgs.getString(index);
			} catch (JSONException e) {			   
			    e.printStackTrace();
			}
		    }
		} catch (JSONException e1) {		   
		    e1.printStackTrace();
		}		

	    }

	};

	// execute AsyncTask
	mRegisterTask.execute(null, null, null);

    }

    // Unregister this account/device pair within the server.
    void unregister(final Context context, final String regId) {

	GCMRegistrar.setRegisteredOnServer(context, false);
	String message = context.getString(R.string.server_unregistered);
	displayMessageOnScreen(context, message);	
    }    

    // Notifies UI to display a message.
    void displayMessageOnScreen(Context context, String message) {
	Intent intent = new Intent(Config.DISPLAY_MESSAGE_ACTION);
	intent.putExtra(Config.EXTRA_MESSAGE, message);
	// Send Broadcast to Broadcast receiver with message
	context.sendBroadcast(intent);
    }


    //Function to display simple Alert Dialog
    public void showAlertDialog(Context context, String title, String message,Boolean status) {
	AlertDialog alertDialog = new AlertDialog.Builder(context).create();

	// Set Dialog Title
	alertDialog.setTitle(title);

	// Set Dialog Message
	alertDialog.setMessage(message);

	if(status != null)
	    // Set alert dialog icon
	    alertDialog.setIcon((status) ? android.R.drawable.ic_dialog_info : android.R.drawable.ic_dialog_alert);

	// Set OK Button
	alertDialog.setButton("OK", new DialogInterface.OnClickListener() {
	    @Override
	    public void onClick(DialogInterface dialog, int which) {

	    }
	});

	// Show Alert Message
	alertDialog.show();
    }

    private PowerManager.WakeLock wakeLock;

    @SuppressLint("Wakelock")
    public  void acquireWakeLock(Context context) {
	if (wakeLock != null) { wakeLock.release();}

	PowerManager pm = (PowerManager) context.getSystemService(Context.POWER_SERVICE);

	wakeLock = pm.newWakeLock(PowerManager.FULL_WAKE_LOCK |	PowerManager.ACQUIRE_CAUSES_WAKEUP |	PowerManager.ON_AFTER_RELEASE, "WakeLock");

	wakeLock.acquire();
    }

    public  void releaseWakeLock() {
	if (wakeLock != null) wakeLock.release(); wakeLock = null;
    }

}