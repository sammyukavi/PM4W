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
import android.content.DialogInterface.OnCancelListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class ComposeSMS extends Activity {

    DatabaseHandler dbhandler = new DatabaseHandler(this);
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    User userSession;     
    String id_user;
    ProgressDialog pDialog;
    int current_stage=0;
    String api_url;
    JSONObject json;  
    JSONParser jsonp = new JSONParser();
    TextView recepients_pnumber;
    TextView sms_message ;
    TextView senders_name;

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	userSession = dbhandler.getUser(1);	
	setContentView(R.layout.compose_sms);
	recepients_pnumber = (TextView) findViewById(R.id.recepients_pnumber);
	sms_message = (TextView) findViewById(R.id.sms_message);
	senders_name = (TextView) findViewById(R.id.senders_name);

	id_user= getIntent().getStringExtra("id_user");
	if(isOnline()){	
	    params.clear();
	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
	    params.add(new BasicNameValuePair("id_user", id_user));

	    new PostToServer().execute();

	}else{			
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.NO_INTERNET_MSG)	    
	    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener( new OnCancelListener() {

		@Override
		public void onCancel(DialogInterface dialog) {
		    finish();	    	
		}
	    })
	    .show();
	}	
    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }


    public void sendSMS(View view) {

	if(recepients_pnumber.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.INVALID_PHONE_NUMBER_FORMAT)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}	

	if(sms_message.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.MESSAGE_REQUIRED_ERROR)		    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	if(senders_name.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.NAME_REQUIRED_ERROR)		    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	params.add(new BasicNameValuePair("msg_content",sms_message.getText().toString()));
	

	current_stage=1;
	
	new PostToServer().execute();
	

    }

    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(ComposeSMS.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);			
	    pDialog.show();	    
	}

	@Override
	protected String doInBackground(String... arg0) {	


	    if(current_stage==0) {
		api_url = "?a=fetch-water-user";		
	    }else {
		api_url = "?a=send-sms-message";
	    }

	    json = jsonp.makeHttpRequest(api_url, "POST", params);	

	    return null;
	}


	@Override
	protected void onPostExecute(String result) {			
	    pDialog.cancel();
	    runOnUiThread(new Runnable() {
		@Override
		public void run() {

		    JSONObject server_info;
		    try {
			server_info = json.getJSONObject("server_info");
			int server_status = server_info.getInt("server_status");

			if(server_status==Config.SERVER_OFFLINE){
			    new AlertDialog.Builder(ComposeSMS.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(ComposeSMS.this)
			    .setTitle(Config.UPGRADE_TITLE)
			    .setMessage(Config.UPGRADE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else{
			    JSONObject data = json.getJSONObject("data");	
			    int request_status = data.getInt("request_status");						
			    JSONArray msgs = data.getJSONArray("msgs");						
			    String txt="";						
			    for(int index=0; index<msgs.length();index++){
				txt += msgs.getString(index);
			    }
			    if(request_status==Config.REQUEST_SUCCESSFUL){		
				if(current_stage==0) {
				    JSONObject water_user = data.getJSONObject("water_user");
				    if(water_user.getString("pnumber").length()>0) {
					recepients_pnumber.setText(water_user.getString("fname")+" "+water_user.getString("lname")+" <"+water_user.getString("pnumber")+"> ");
					senders_name.setText(userSession.getFNAME()+" "+userSession.getLNAME());
				    }else {
					new AlertDialog.Builder(ComposeSMS.this)
					.setTitle(Config.ERROR)
					.setMessage(Config.WATER_USER_NO_PHONE_NUMBER)	    
					.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					    @Override
					    public void onCancel(DialogInterface dialog) {
						finish();								
					    }
					})
					.show();
				    }			   

				}else {
				    new AlertDialog.Builder(ComposeSMS.this)
				    .setTitle(Config.INFO)
				    .setMessage(txt)	    
				    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    finish();								
					}
				    })
				    .show();
				}

			    }else{						
				new AlertDialog.Builder(ComposeSMS.this)
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
			new AlertDialog.Builder(ComposeSMS.this)
			.setTitle(Config.ERROR)
			.setMessage(Config.ERROR_READING_FROM_SERVER)	    
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {
				finish();								
			    }
			})
			.show();
			e.printStackTrace();
		    }
		}
	    });

	}		

    }
}