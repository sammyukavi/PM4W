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
import com.sukavi.pm4w.user.Permissions;
import com.sukavi.pm4w.user.User;

import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.DialogInterface.OnCancelListener;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;


public class Login extends Activity {

    String dialogMsg = null;
    JSONParser jsonp = new JSONParser();
    JSONObject json =null;
    private String api_url =null;
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    ProgressDialog	pDialog;
    Controller controller;

    @Override
    protected void onCreate(Bundle savedInstanceState) {		
	super.onCreate(savedInstanceState);
	setContentView(R.layout.login);	

	new User();

	pDialog = new ProgressDialog(this);
	userSession = dbhandler.getUser(1);	
	controller = new Controller();

	try {
	    Config.APP_VERSION=getPackageManager().getPackageInfo(getPackageName(), 0).versionName;
	} catch (Exception e) {
	    e.printStackTrace();
	}

	if(userSession!=null){
	    if(!isOnline()){	
		new AlertDialog.Builder(this)
		.setTitle(Config.NO_INTERNET_TITLE)
		.setMessage(Config.ERROR_READING_FROM_SERVER)	    
		.setIcon(android.R.drawable.ic_dialog_alert)
		.show();
	    }else{			

		params.clear();
		params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));
		params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));

		api_url = "?a=resume-session";

		dialogMsg = Config.RESUMING_SESSION;

		new ExecuteLogin().execute();
	    }
	}

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
	// Inflate the menu; this adds items to the action bar if it is present.
	getMenuInflater().inflate(R.menu.login, menu);
	return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {	
	if(item.getItemId()==R.id.action_about){	    
	    Intent about = new Intent(getApplicationContext(), About.class);
	    startActivity(about);	   
	}/*else if(item.getItemId()==R.id.action_help){	    
	    Intent help = new Intent(getApplicationContext(), Help.class);
	    startActivity(help);	   
	}*/
	return false;
    }	

    public void login(View v) {
	TextView usernameTextview = (TextView) findViewById(R.id.username);
	TextView passwordTextview = (TextView) findViewById(R.id.password);

	if(usernameTextview.getText().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.USERNAME_REQUIRED)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	if(passwordTextview.getText().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.PASSWORD_REQUIRED)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	if(!isOnline()){	
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.ERROR_READING_FROM_SERVER)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	}else{

	    params.clear();
	    params.add(new BasicNameValuePair("username",usernameTextview.getText().toString().trim()));
	    params.add(new BasicNameValuePair("password",passwordTextview.getText().toString().trim()));

	    api_url = "?a=login";

	    dialogMsg = Config.LOGGING_IN;

	    new ExecuteLogin().execute();

	}

    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    public void showPasswordRecovery(View v){
	Intent password_recovery = new Intent(getApplicationContext(), RecoverPassword.class);
	startActivity(password_recovery);
    }

    private final BroadcastReceiver handleMessageReceiver = new BroadcastReceiver() {

	@Override
	public void onReceive(Context context, Intent intent) {

	    String newMessage = intent.getExtras().getString(Config.EXTRA_MESSAGE);

	    // Waking up mobile if it is sleeping
	    controller.acquireWakeLock(getApplicationContext());

	    // Display message on the screen
	    //lblMessage.append(newMessage + "");         

	    Toast.makeText(getApplicationContext(), 
		    "Recived Message: " + newMessage, 
		    Toast.LENGTH_LONG).show();
	    // Releasing wake lock
	    controller.releaseWakeLock();
	}
    };

    @Override
    protected void onPause() {
	super.onPause();

	try {
	    unregisterReceiver(handleMessageReceiver);
	} catch (Exception e) {

	}

    }

    class ExecuteLogin extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {

	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(dialogMsg);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);			
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... arg0) {		

	    json = jsonp.makeHttpRequest(api_url, "POST", params);		

	    return null;
	}


	@Override
	protected void onPostExecute(String result) {			
	    pDialog.cancel();
	    runOnUiThread(new Runnable() {
		@Override
		public void run() {
		    if(json==null) {		
			new AlertDialog.Builder(Login.this)
			.setTitle(Config.INFO)
			.setMessage(Config.SERVER_NOT_ACCESSIBLE)	
			.setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {								
				//finish();						
			    }
			})
			.show();		
			return;			    
		    }
		    try {
			JSONObject server_info = json.getJSONObject("server_info");
			int server_status = server_info.getInt("server_status");
			JSONObject data = json.getJSONObject("data");	
			int request_status = data.getInt("request_status");	
			JSONArray msgs = data.getJSONArray("msgs");

			String txt="";						
			for(int index=0; index<msgs.length();index++){
			    try {
				txt += msgs.getString(index);
			    } catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			    }
			}

			if(server_status==Config.SERVER_OFFLINE){
			    new AlertDialog.Builder(Login.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    //finish();								
				}
			    })
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(Login.this)
			    .setTitle(Config.UPGRADE_TITLE)
			    .setMessage(Config.UPGRADE_MSG)		    
			    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    //finish();								
				}
			    })
			    .show();
			}else{
			    if(request_status==Config.REQUEST_SUCCESSFUL){

				String action = data.getString("action");	
				JSONObject account = data.getJSONObject("account");

				Intent dashboard = new Intent(getApplicationContext(), Dashboard.class);

				GCMRegistrar.checkDevice(Login.this);

				GCMRegistrar.checkManifest(Login.this);				

				registerReceiver(handleMessageReceiver, new IntentFilter(Config.DISPLAY_MESSAGE_ACTION));				

				final String regId = GCMRegistrar.getRegistrationId(Login.this);		

				if(action.equals("resume-session")||action.equals("login")){

				    if(action.equals("resume-session")){
					//dbhandler.updateUser(new User(account.getInt("idu"), account.getInt("group_id"), account.getString("username"), account.getString("pnumber"), account.getString("email"), account.getString("fname"), account.getString("lname"), account.getString("request_hash"), account.getString("last_login")));
					dbhandler.updateUser(new User(account));
				    }else if(action.equals("login")){
					dbhandler.Logout();
					//dbhandler.addUser(new User(account.getInt("idu"), account.getInt("group_id"), account.getString("username"), account.getString("pnumber"), account.getString("email"), account.getString("fname"), account.getString("lname"), account.getString("request_hash"), account.getString("last_login")));
					dbhandler.addUser(new User(account));
				    }

				    if (regId==null) {

					GCMRegistrar.register(Login.this, Config.GOOGLE_PROJECT_ID);

				    }else {  
					if(GCMRegistrar.isRegisteredOnServer(Login.this)!=true) {												
					    controller.register(Login.this, regId);
					}
				    }			    

				    if(Permissions.GROUP_IS_ENABLED) {
					startActivity(dashboard);
				    }else {
					new AlertDialog.Builder(Login.this)
					.setTitle(Config.ERROR)
					.setMessage(Config.GROUP_DISABLED)	    
					.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					    @Override
					    public void onCancel(DialogInterface dialog) {
						//finish();								
					    }
					})							
					.show();
				    }

				}else{
				    new AlertDialog.Builder(Login.this)
				    .setTitle(Config.ERROR)
				    .setMessage(Config.UNDEFINED_REQUEST)	    
				    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    //finish();								
					}
				    })							
				    .show();
				}


			    }else{
				//dbhandler.deleteUser(userSession);
				new AlertDialog.Builder(Login.this)
				.setTitle(Config.ERROR)
				.setMessage(txt)	    
				.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

				    @Override
				    public void onCancel(DialogInterface dialog) {
					//finish();								
				    }
				})							
				.show();
			    }
			}


		    } catch (JSONException e) {				
			new AlertDialog.Builder(Login.this)
			.setTitle(Config.ERROR)
			//.setMessage(Config.ERROR_READING_FROM_SERVER)	    
			.setMessage(e.toString())
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {								
				//finish();						
			    }
			})
			.show();
		    }

		}
	    });

	}		

    }

}
