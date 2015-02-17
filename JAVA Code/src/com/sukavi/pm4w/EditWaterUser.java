package com.sukavi.pm4w;

import java.util.ArrayList;
import java.util.Arrays;
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


public class EditWaterUser extends Activity {

    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession; 	
    String id_user;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    ProgressDialog pDialog;
    int current_stage=0;
    String api_url;
    JSONObject json;  
    JSONParser jsonp = new JSONParser();
    TextView fname;
    TextView lname ;
    TextView pnumber;

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	userSession = dbhandler.getUser(1);	
	setContentView(R.layout.edit_water_user);
	fname = (TextView) findViewById(R.id.fname);
	lname = (TextView) findViewById(R.id.lname);
	pnumber = (TextView) findViewById(R.id.pnumber);
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

    public void updateUser(View view) {

	if(fname.getText().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.FIRST_NAME_REQUIRED_ERROR)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	if(lname.getText().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.LAST_NAME_REQUIRED_ERROR)		    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	if(pnumber.getText().length()>0){
	    String p_number = pnumber.getText().toString();
	    p_number = p_number.replaceAll("\\d", "#");					

	    if(!Arrays.asList(Config.PNUMBER_FORMATS).contains(p_number)){
		/*new AlertDialog.Builder(this)
		.setTitle(Config.ERROR)
		.setMessage(Config.INVALID_PHONE_NUMBER_FORMAT)	    
		.setIcon(android.R.drawable.ic_dialog_alert)
		.show();
		return;*/
	    }
	}

	params.add(new BasicNameValuePair("fname",fname.getText().toString()));
	params.add(new BasicNameValuePair("lname", lname.getText().toString()));
	params.add(new BasicNameValuePair("pnumber", pnumber.getText().toString()));

	current_stage=1;
	
	new PostToServer().execute();
	
    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(EditWaterUser.this);
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
		api_url = "?a=update-water-user";
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
			    new AlertDialog.Builder(EditWaterUser.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(EditWaterUser.this)
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
				    fname.setText(water_user.getString("fname"));
				    lname.setText(water_user.getString("lname"));
				    pnumber.setText(water_user.getString("pnumber"));

				}else {
				    new AlertDialog.Builder(EditWaterUser.this)
				    .setTitle(Config.SUCCESS)
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
				new AlertDialog.Builder(EditWaterUser.this)
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
			new AlertDialog.Builder(EditWaterUser.this)
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
