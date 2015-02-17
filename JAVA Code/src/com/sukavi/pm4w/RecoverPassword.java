package com.sukavi.pm4w;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.sukavi.pm4w.config.Config;
import com.sukavi.pm4w.http.JSONParser;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;


public class RecoverPassword extends Activity {

    JSONParser jsonp = new JSONParser();
    private String api_url = "?a=recover-password";

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);

	setContentView(R.layout.recover_password);
    }

    public void recoverPassword(View v){
	EditText recovery_username = (EditText) findViewById(R.id.recovery_username);
	if(recovery_username.getText().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.USERNAME_REQUIRED)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	ProgressDialog	pDialog = new ProgressDialog(this);
	pDialog.setCancelable(true);
	pDialog.setMessage(Config.CHECKING_CONNECTIVITY);
	pDialog.show();


	if(!isOnline()){
	    pDialog.cancel();
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.NO_INTERNET_MSG)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	}else{
	    List<NameValuePair> params = new ArrayList<NameValuePair>();
	    params.add(new BasicNameValuePair("username",recovery_username.getText().toString() ));			
	    JSONObject json = jsonp.makeHttpRequest(api_url, "POST", params);	
	    pDialog.cancel();
	    if(json==null){
		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.SERVER_NOT_ACCESSIBLE)	    
		.setIcon(android.R.drawable.ic_dialog_info)
		.show();
	    }else{
		try {
		    JSONObject server_info = json.getJSONObject("server_info");	
		    int server_status = server_info.getInt("server_status");
		    if(server_status==Config.SERVER_OFFLINE){
			new AlertDialog.Builder(this)
			.setTitle(Config.OFFLINE_TITLE)
			.setMessage(Config.OFFLINE_MSG)	     
			.setIcon(android.R.drawable.ic_dialog_alert)
			.show();
		    }else if(server_status==Config.SERVER_UPGRADE){
			new AlertDialog.Builder(this)
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
			    new AlertDialog.Builder(this)
			    .setTitle(Config.SUCCESS)
			    .setMessage(txt)	    
			    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    Intent i = new Intent(getApplicationContext(), Login.class);
				    startActivity(i);
				    finish();						
				}
			    })
			    .show();
			}else{
			    new AlertDialog.Builder(this)
			    .setTitle(Config.ERROR)
			    .setMessage(txt)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}						
		    }

		} catch (JSONException e) {
		    e.printStackTrace();	
		    new AlertDialog.Builder(this)
		    .setTitle(Config.ERROR)
		    .setMessage(Config.ERROR_READING_FROM_SERVER)	    
		    .setIcon(android.R.drawable.ic_dialog_alert)
		    .show();
		}
	    }

	}
    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    @Override
    public void onBackPressed() {
	// TODO Auto-generated method stub
	//	super.onBackPressed();
	Intent i = new Intent(getApplicationContext(), Login.class);
	startActivity(i);
	this.finish();
    }
}