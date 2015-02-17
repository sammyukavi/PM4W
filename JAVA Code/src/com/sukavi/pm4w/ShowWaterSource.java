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
import android.widget.TextView;

public class ShowWaterSource extends Activity {

    String id_water_source;
    private ProgressDialog pDialog;
    private String api_url;
    private JSONParser jsonp = new JSONParser();
    private JSONObject json;    
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private DatabaseHandler dbhandler = new DatabaseHandler(this);
    private User userSession; 	

    @Override
    protected void onCreate(Bundle savedInstanceState) {	
	super.onCreate(savedInstanceState);
	setContentView(R.layout.show_water_source);
	id_water_source= getIntent().getStringExtra("id_water_source");
	userSession = dbhandler.getUser(1);
	if(isOnline()){	

	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
	    params.add(new BasicNameValuePair("id_water_source", id_water_source));

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

    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(ShowWaterSource.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);			
	    pDialog.show();	    
	}

	@Override
	protected String doInBackground(String... arg0) {

	    api_url = "?a=fetch-water-sources-data";		

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
			    new AlertDialog.Builder(ShowWaterSource.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(ShowWaterSource.this)
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


				TextView water_source_name = (TextView) findViewById(R.id.water_source_name);
				water_source_name.setText(data.getString("water_source_name"));

				TextView water_source_location = (TextView) findViewById(R.id.water_source_location);
				water_source_location.setText(data.getString("water_source_location"));


				TextView water_source_users_count = (TextView) findViewById(R.id.water_source_users_count);
				water_source_users_count.setText(data.getString("count_total_water_users"));

				TextView transactions = (TextView) findViewById(R.id.transactions);
				transactions.setText(data.getString("count_total_tansactions"));

				TextView available_balance = (TextView) findViewById(R.id.available_balance);
				available_balance.setText(data.getString("count_total_savings"));



			    }else{						
				new AlertDialog.Builder(ShowWaterSource.this)
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
			new AlertDialog.Builder(ShowWaterSource.this)
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

