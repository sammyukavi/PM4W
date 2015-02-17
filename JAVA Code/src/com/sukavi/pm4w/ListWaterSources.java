package com.sukavi.pm4w;

import java.util.ArrayList;
import java.util.HashMap;
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
import android.widget.AdapterView;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;

public class ListWaterSources extends Activity {
    ProgressDialog pDialog;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    private User userSession; 
    String api_url;
    private JSONParser jsonp = new JSONParser();
    private int server_status;
    private int request_status;
    private JSONArray msgs;
    private ArrayList<HashMap<String, String>> waterSourcesArrayList = new ArrayList<HashMap<String, String>>();
    private ListView waterSourcesListView;
    DatabaseHandler dbhandler = new DatabaseHandler(this);



    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.list_water_sources);
	waterSourcesListView = (ListView) findViewById(R.id.watersources_list);

	waterSourcesListView.setOnItemClickListener(new OnItemClickListener() {

	    @Override
	    public void onItemClick(AdapterView<?> parent, View view,
		    int position, long id) {		   
		TextView id_water_source_text_view = 	(TextView) view.findViewById(R.id.id_water_source_renderer);
		Intent showwatersource = new Intent(getApplicationContext(), ShowWaterSource.class);
		showwatersource.putExtra("id_water_source",id_water_source_text_view.getText());		
		startActivity(showwatersource);		   
	    }

	});


	userSession = dbhandler.getUser(1);
	if(isOnline()){	
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
	    super.onPreExecute();
	    pDialog = new ProgressDialog(ListWaterSources.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setIndeterminate(true);
	    pDialog.setCancelable(false);
	    pDialog.setButton(Config.CANCEL, new DialogInterface.OnClickListener() {
		@Override
		public void onClick(DialogInterface dialog, int which) {					
		    ListWaterSources.this.finish();
		}
	    });		
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... args) {

	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));


	    api_url = "?a=fetch-all-water-sources";


	    JSONObject json = jsonp.makeHttpRequest(api_url, "POST", params);	


	    try {
		JSONObject server_info = json.getJSONObject("server_info");
		server_status = server_info.getInt("server_status");
		JSONObject data = json.getJSONObject("data");	
		request_status = data.getInt("request_status");	
		msgs = data.getJSONArray("msgs");


		if(server_status==Config.SERVER_OFFLINE){

		}else if(server_status==Config.SERVER_UPGRADE){

		}else{	

		    if(request_status==Config.REQUEST_SUCCESSFUL){
			waterSourcesArrayList.clear();
			JSONArray  water_sources = data.getJSONArray("water_sources");				    
			for (int i = 0; i < water_sources.length(); i++) {
			    JSONObject water_source = water_sources.getJSONObject(i);
			    HashMap<String, String> map = new HashMap<String, String>();
			    map.put("id_water_source", water_source.getString("id_water_source"));
			    map.put("water_source_name", water_source.getString("water_source_name"));
			    map.put("monthly_charges", water_source.getString("monthly_charges"));
			    waterSourcesArrayList.add(map);
			}
		    }

		}
	    } catch (JSONException e) {
		e.printStackTrace();
	    }			


	    return null;
	}

	@Override
	protected void onPostExecute(String file_url) {	    
	    pDialog.dismiss();	

	    runOnUiThread(new Runnable() {
		@Override
		public void run() {		

		    String txt="";						
		    for(int index=0; index<msgs.length();index++){
			try {
			    txt += msgs.getString(index);
			} catch (JSONException e) {			   
			    e.printStackTrace();
			}
		    }

		    if(server_status==Config.SERVER_OFFLINE){
			new AlertDialog.Builder(ListWaterSources.this)
			.setTitle(Config.OFFLINE_TITLE)
			.setMessage(Config.OFFLINE_MSG)	    
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {
				finish();								
			    }
			})
			.show();

		    }else if(server_status==Config.SERVER_UPGRADE){
			new AlertDialog.Builder(ListWaterSources.this)
			.setTitle(Config.UPGRADE_TITLE)
			.setMessage(Config.UPGRADE_MSG)		    
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {
				finish();								
			    }
			})
			.show();
		    }else{	
			if(request_status==Config.REQUEST_SUCCESSFUL){			   

			    if(waterSourcesArrayList.size()>0){
				ListAdapter adapter = new SimpleAdapter(
					ListWaterSources.this,  waterSourcesArrayList,
					R.layout.list_water_sources_list_item, new String[] { "id_water_source",
						"water_source_name","monthly_charges"},
						new int[] { R.id.id_water_source_renderer, R.id.water_source_name_renderer,R.id.monthly_charges_renderer });	
				waterSourcesListView.setAdapter(adapter);	
			    }else{
				new AlertDialog.Builder(ListWaterSources.this)
				.setTitle(Config.INFO)
				.setMessage(Config.NO_TRANSACTIONS)	    
				.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

				    @Override
				    public void onCancel(DialogInterface dialog) {
					finish();								
				    }
				})							
				.show();
			    }

			}else{
			    new AlertDialog.Builder(ListWaterSources.this)
			    .setTitle(Config.ERROR)
			    .setMessage(txt)	    
			    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    finish();								
				}
			    })							
			    .show();
			}
		    }

		}
	    });	   
	}

    }

}
