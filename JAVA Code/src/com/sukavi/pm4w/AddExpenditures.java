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
import android.content.DialogInterface.OnCancelListener;
import android.content.DialogInterface.OnClickListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.SimpleAdapter;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;
import android.widget.AdapterView.OnItemSelectedListener;


public class AddExpenditures extends Activity {

    JSONParser jsonp = new JSONParser();
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession; 	
    String id_customer;
    ProgressDialog pDialog;
    private ArrayList<HashMap<String, String>> WaterSourcesList = new ArrayList<HashMap<String, String>>();
    private ArrayList<HashMap<String, String>> RepairTypesList = new ArrayList<HashMap<String, String>>();
    JSONObject json;    
    int Current_stage=0;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    String api_url;
    Spinner water_source_spinner,repair_types_spinner;    
    private int water_source_id,repair_type_id=0;


    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);

	userSession = dbhandler.getUser(1);	

	setContentView(R.layout.expenses);
	water_source_spinner = (Spinner) findViewById(R.id.water_source_spinner);
	repair_types_spinner = (Spinner) findViewById(R.id.repairtypes);

	if(isOnline()){	

	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
	    new PostToServer().execute();

	}else{			
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.NO_INTERNET_MSG)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	}	
	
	water_source_spinner.setOnItemSelectedListener(new OnItemSelectedListener() {

	    @Override
	    public void onItemSelected(AdapterView<?> arg0, View view, int arg2, long arg3) {
		TextView id_water_source_text_view = 	(TextView) view.findViewById(R.id.id_water_source_renderer);
		water_source_id = Integer.parseInt(id_water_source_text_view.getText().toString());		
	    }

	    @Override
	    public void onNothingSelected(AdapterView<?> arg0) {
			
	    }
	});
	
	repair_types_spinner.setOnItemSelectedListener(new OnItemSelectedListener() {

	    @Override
	    public void onItemSelected(AdapterView<?> arg0, View view, int arg2, long arg3) {
		TextView id_repair_type_text_view = 	(TextView) view.findViewById(R.id.repair_type_id_renderer);
		repair_type_id = Integer.parseInt(id_repair_type_text_view.getText().toString());
	    }

	    @Override
	    public void onNothingSelected(AdapterView<?> arg0) {
			
	    }
	});

    }	

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    public void addExpense(View v){	
	TextView expenditure_cost = (TextView) findViewById(R.id.expenditure_cost);
	TextView benefactor = (TextView) findViewById(R.id.benefactor);
	TextView description = (TextView) findViewById(R.id.description);

	if(water_source_spinner.getSelectedItem()==null){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.WATERSOURCE_REQUIRED_ERROR)
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	if(repair_types_spinner.getSelectedItem()==null){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.EXPENDITURETYPE_REQUIRED_ERROR)
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	if(expenditure_cost.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.EXPENDITURE_COST_REQUIRED_ERROR)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	if(benefactor.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.EXPENDITURE_BENEFACTOR_REQUIRED_ERROR)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}
	
	if(description.getText().toString().trim().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.EXPENDITURE_DESCRIPTION_REQUIRED_ERROR)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	Current_stage=1;

	params.add(new BasicNameValuePair("water_source_id", water_source_id+""));
	params.add(new BasicNameValuePair("repair_type_id", repair_type_id+""));
	params.add(new BasicNameValuePair("expenditure_cost",expenditure_cost.getText().toString()));
	params.add(new BasicNameValuePair("benefactor",benefactor.getText().toString()));
	params.add(new BasicNameValuePair("description",description.getText().toString()));

	new PostToServer().execute();

    }


    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(AddExpenditures.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);			
	    pDialog.show();	    
	}

	@Override
	protected String doInBackground(String... arg0) {	


	    if(Current_stage==0) {
		api_url = "?a=fetch-expenses-data";		
	    }else {
		api_url = "?a=add-expense";
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
			    new AlertDialog.Builder(AddExpenditures.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(AddExpenditures.this)
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
				if(Current_stage==0) {
				    JSONArray  water_sources = data.getJSONArray("water_sources");				    
				    for (int i = 0; i < water_sources.length(); i++) {
					JSONObject water_source = water_sources.getJSONObject(i);
					HashMap<String, String> map = new HashMap<String, String>();
					map.put("id_water_source", water_source.getString("id_water_source"));
					map.put("water_source_name", water_source.getString("water_source_name"));
					map.put("monthly_charges", water_source.getString("monthly_charges"));
					WaterSourcesList.add(map);
				    }
				    
				    
				    SpinnerAdapter adapter = new SimpleAdapter(
					    AddExpenditures.this, WaterSourcesList,
					    R.layout.list_water_sources_list_item, new String[] { "id_water_source",
						    "water_source_name","monthly_charges"},
						    new int[] { R.id.id_water_source_renderer, R.id.water_source_name_renderer,R.id.monthly_charges_renderer });


				    Spinner sItems = (Spinner) findViewById(R.id.water_source_spinner);
				    sItems.setAdapter(adapter);	
				    
				    JSONArray  repair_types = data.getJSONArray("repair_types");				    
				    for (int i = 0; i < repair_types.length(); i++) {
					JSONObject repair_type = repair_types.getJSONObject(i);
					HashMap<String, String> map = new HashMap<String, String>();
					map.put("id_repair_type", repair_type.getString("id_repair_type"));
					map.put("repair_type", repair_type.getString("repair_type"));					
					RepairTypesList.add(map);
				    }
				    
				    SpinnerAdapter repairs_adapter = new SimpleAdapter(
					    AddExpenditures.this, RepairTypesList,
					    R.layout.repair_types_list_item, new String[] { "id_repair_type","repair_type"},
						    new int[] { R.id.repair_type_id_renderer, R.id.repair_type_renderer });


				    Spinner repairtypes = (Spinner) findViewById(R.id.repairtypes);
				    repairtypes.setAdapter(repairs_adapter);	

				    
				}else {
				    new AlertDialog.Builder(AddExpenditures.this)
				    .setTitle(Config.SUCCESS)
				    .setMessage(txt)	    
				    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    finish();								
					}
				    }).setNegativeButton(Config.FINISH, new OnClickListener() {

					@Override
					public void onClick(DialogInterface dialog, int which) {
					    finish();	
					}
				    }).show();
				}

			    }else{						
				new AlertDialog.Builder(AddExpenditures.this)
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
			new AlertDialog.Builder(AddExpenditures.this)
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
