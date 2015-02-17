package com.sukavi.pm4w;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.sukavi.pm4w.config.Config;
import com.sukavi.pm4w.dbmanager.DatabaseHandler;
import com.sukavi.pm4w.http.JSONParser;
import com.sukavi.pm4w.user.User;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;



public class MyAccount extends Activity {

    String dialogMsg = null;
    JSONParser jsonp = new JSONParser();
    JSONObject json =null;
    private String  api_url = "?a=fetch-account-balance";  
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    ProgressDialog	pDialog;    

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.my_account);
	userSession = dbhandler.getUser(1);  

	if(isOnline()){		   

	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));

	    new ExecuteLogin().execute();	    

	}else{			
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.NO_INTERNET_MSG)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	}


    }

    public void fetchMiniStatement(View v){
	Intent mini_statement_activity = new Intent(getApplicationContext(), MiniStatement.class);
	startActivity(mini_statement_activity);
    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    class ExecuteLogin extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(MyAccount.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    //pDialog.setCancelable(false);
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
			new AlertDialog.Builder(MyAccount.this)
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
			    new AlertDialog.Builder(MyAccount.this)
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
			    new AlertDialog.Builder(MyAccount.this)
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


				TextView account_name_TextView = (TextView) findViewById(R.id.account_name);
				TextView available_balance_TextView = (TextView) findViewById(R.id.available_balance);

				account_name_TextView.setText(data.getString("account_name"));
				available_balance_TextView.setText(data.getString("account_balance"));

			    }else{
				//dbhandler.deleteUser(userSession);
				new AlertDialog.Builder(MyAccount.this)
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
			new AlertDialog.Builder(MyAccount.this)
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
