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
import com.sukavi.pm4w.user.Permissions;
import com.sukavi.pm4w.user.User;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.content.DialogInterface.OnClickListener;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.ContextMenu;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;


public class ListSavingsForSubmissions extends Activity {

    JSONParser jsonp = new JSONParser();
    private ProgressDialog pDialog;
    private ArrayList<HashMap<String, String>> customersList = new ArrayList<HashMap<String, String>>();
    ListView water_users_list;
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession; 
    int server_status;
    int request_status;
    JSONArray msgs;
    String request;
    String api_url;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    int current_stage=0;

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.list_savings_for_submissions);
	userSession = dbhandler.getUser(1);

	request = getIntent().getStringExtra("request");

	new PostToServer().execute();	

	water_users_list = (ListView) findViewById(R.id.waterusers_list);

	registerForContextMenu(water_users_list);

    }   


    @Override
    public void onCreateContextMenu(ContextMenu menu, View v,
	    ContextMenuInfo menuInfo) {	
	menu.setHeaderTitle(Config.SELECT_ACTION);	

	String[] menuItems = getResources().getStringArray(R.array.transactions_menu);
	for (int i = 0; i<menuItems.length; i++) {
	    menu.add(Menu.NONE, i, i, menuItems[i]);
	}   

    }

    @Override
    public boolean onContextItemSelected(MenuItem item) {

	final AdapterView.AdapterContextMenuInfo info = (AdapterView.AdapterContextMenuInfo)item.getMenuInfo();
	int menuItemIndex = item.getItemId();

	if(menuItemIndex==0) {

	    if(request.equals("attendants-submissions")&&!Permissions.CAN_SUBMIT_ATTENDANT_DAILY_SALES) {
		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else if(request.equals("treasurers-submissions")&&!Permissions.CAN_APPROVE_ATTENDANTS_SUBMISSIONS) {
		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else {

		@SuppressWarnings("unchecked")
		HashMap<String, String> transaction = (HashMap<String, String>) water_users_list.getItemAtPosition(info.position);

		params.add(new BasicNameValuePair("t", transaction.get("timestamp")));
		params.add(new BasicNameValuePair("id", transaction.get("water_source")));
		params.add(new BasicNameValuePair("idu", transaction.get("sold_by")));

		current_stage=1;
		new PostToServer().execute();
	    }
	}else {
	    if(request.equals("attendants-submissions")&&!Permissions.CAN_CANCEL_ATTENDANT_DAILY_SALES) {
		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else if(request.equals("treasurers-submissions")&&!Permissions.CAN_CANCEL_ATTENDANTS_SUBMISSIONS) {
		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else {

		new AlertDialog.Builder(this)
		.setTitle(Config.INFO)
		.setMessage(Config.CANCEL_SUBMITTION_MESSAGE)	    
		.setIcon(android.R.drawable.ic_menu_help).setPositiveButton(Config.YES, new OnClickListener() {

		    @Override
		    public void onClick(DialogInterface dialog, int which) {
			@SuppressWarnings("unchecked")
			HashMap<String, String> transaction = (HashMap<String, String>) water_users_list.getItemAtPosition(info.position);

			params.add(new BasicNameValuePair("t", transaction.get("timestamp")));
			params.add(new BasicNameValuePair("id", transaction.get("water_source")));
			params.add(new BasicNameValuePair("idu", transaction.get("sold_by")));

			current_stage=2;
			new PostToServer().execute();

		    }
		}).setNegativeButton(Config.NO, new OnClickListener() {

		    @Override
		    public void onClick(DialogInterface dialog, int which) {


		    }
		}).setOnCancelListener(new OnCancelListener() {

		    @Override
		    public void onCancel(DialogInterface dialog) {
			//finish();								
		    }
		})
		.show();

	    }	
	}
	return true;
    }


    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    super.onPreExecute();
	    pDialog = new ProgressDialog(ListSavingsForSubmissions.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setIndeterminate(true);
	    pDialog.setCancelable(false);
	    pDialog.setButton(Config.CANCEL, new DialogInterface.OnClickListener() {
		@Override
		public void onClick(DialogInterface dialog, int which) {					
		    ListSavingsForSubmissions.this.finish();
		}
	    });		
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... args) {


	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));

	    if(current_stage==0) {
		api_url ="?a="+request;
	    }else if(current_stage==1) {
		if(request.equals("attendants-submissions")) {
		    api_url="?a="+"submit-attendants-sales";
		}else {
		    api_url="?a="+"submit-treasurers-sales";
		}
	    }else if(current_stage==2) {
		if(request.equals("attendants-submissions")) {
		    api_url="?a="+"cancel-attendants-sales";
		}else {
		    api_url="?a="+"cancel-treasurers-sales";
		}
	    }

	    JSONObject json = jsonp.makeHttpRequest(api_url, "POST", params);	

	    customersList.clear();

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
			JSONArray submissions = data.getJSONArray("submissions");
			for (int i = 0; i < submissions.length(); i++) {
			    JSONObject user = submissions.getJSONObject(i);			  	 
			    HashMap<String, String> map = new HashMap<String, String>();
			    map.put("sold_by", user.getString("idu"));
			    map.put("name", user.getString("fname")+" "+user.getString("lname")+" - "+user.getString("sale_date"));
			    map.put("timestamp", user.getString("sale_date"));
			    map.put("water_source", user.getString("id_water_source"));
			    customersList.add(map);							
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
			new AlertDialog.Builder(ListSavingsForSubmissions.this)
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
			new AlertDialog.Builder(ListSavingsForSubmissions.this)
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
			    if(current_stage==0) {
				if(customersList.size()>0){
				    ListAdapter adapter = new SimpleAdapter(
					    ListSavingsForSubmissions.this, customersList,
					    R.layout.list_savings_list_item, new String[] { "sold_by",
						    "name","timestamp","water_source_id"},
						    new int[] { R.id.sold_by_renderer, R.id.name_renderer,R.id.timestamp_renderer,R.id.water_source_id_renderer });							
				    water_users_list.setAdapter(adapter);	
				}else{
				    new AlertDialog.Builder(ListSavingsForSubmissions.this)
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
			    }else {
				new AlertDialog.Builder(ListSavingsForSubmissions.this)
				.setTitle(Config.SUCCESS)
				.setMessage(txt)	    
				.setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				    @Override
				    public void onCancel(DialogInterface dialog) {
					new PostToServer().execute();								
				    }
				})							
				.show();
			    }
			}else{
			    new AlertDialog.Builder(ListSavingsForSubmissions.this)
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
	    current_stage=0;
	}

    }
}
