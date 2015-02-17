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
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.ContextMenu;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;


public class ListWaterUsers extends Activity {

    private JSONParser jsonp = new JSONParser();
    private ProgressDialog pDialog;
    private ArrayList<HashMap<String, String>> customersList = new ArrayList<HashMap<String, String>>();
    private ListView water_users_list;
    private DatabaseHandler dbhandler = new DatabaseHandler(this);
    private User userSession; 
    private int server_status;
    private int request_status;
    private JSONArray msgs;
    private String nextActivity=null;
    String api_url;
    int current_stage=0;
    List<NameValuePair> params = new ArrayList<NameValuePair>();

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.list_water_users);
	userSession = dbhandler.getUser(1);
	nextActivity = getIntent().getStringExtra("nextActivity");	

	new PostToServer().execute();	

	water_users_list = (ListView) findViewById(R.id.waterusers_list);


	if(nextActivity==null) {
	    registerForContextMenu(water_users_list);   
	}else {
	    water_users_list.setOnItemClickListener(new OnItemClickListener() {

		@Override
		public void onItemClick(AdapterView<?> parent, View view,
			int position, long id) {
		    if(nextActivity.equals("add-monthly-sale")) {

			TextView id_customer_text_view = 	(TextView) view.findViewById(R.id.wateruser_id_renderer);	
			TextView name_text_view = 	(TextView) view.findViewById(R.id.wateruser_name_renderer);
			Intent addmonthlysale = new Intent(getApplicationContext(), AddMonthlySale.class);
			addmonthlysale.putExtra("id_user",id_customer_text_view.getText());
			addmonthlysale.putExtra("wateruser_name",name_text_view .getText());
			startActivity(addmonthlysale);

		    }else if(nextActivity.equals("do-follow-up")) { 
						
			Intent followup = new Intent(getApplicationContext(), FollowUp.class);			
			startActivity(followup);
		    }

		}
	    });

	}

    }   


    @Override
    public void onCreateContextMenu(ContextMenu menu, View view, ContextMenuInfo menuInfo) {
	menu.setHeaderTitle("Select action");
	String[] menuItems = getResources().getStringArray(R.array.user_account_menu);
	for (int i = 0; i<menuItems.length; i++) {
	    menu.add(Menu.NONE, i, i, menuItems[i]);
	}
    }

    @Override
    public boolean onContextItemSelected(MenuItem item) {
	final AdapterView.AdapterContextMenuInfo info = (AdapterView.AdapterContextMenuInfo)item.getMenuInfo();
	int menuItemIndex = item.getItemId();

	if(menuItemIndex==0) {

	    if(!Permissions.CAN_EDIT_WATER_USERS) { 
		new AlertDialog.Builder(ListWaterUsers.this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else {
		@SuppressWarnings("unchecked")
		HashMap<String, String> water_user = (HashMap<String, String>) water_users_list.getItemAtPosition(info.position);
		Intent editwateruser = new Intent(getApplicationContext(), EditWaterUser.class);
		editwateruser.putExtra("id_user",water_user.get("id_user"));	
		startActivity(editwateruser);
	    }
	}else {

	    if(!Permissions.CAN_DELETE_WATER_USERS) { 
		new AlertDialog.Builder(ListWaterUsers.this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else {
		new AlertDialog.Builder(ListWaterUsers.this)
		.setTitle(Config.INFO)
		.setMessage(Config.DELETE_WATER_USER_MSG)	    
		.setIcon(android.R.drawable.ic_menu_help).setPositiveButton(Config.DELETE, new OnClickListener() {

		    @Override
		    public void onClick(DialogInterface dialog, int which) {
			@SuppressWarnings("unchecked")
			HashMap<String, String> water_user = (HashMap<String, String>) water_users_list.getItemAtPosition(info.position);
			params.add(new BasicNameValuePair("id_user", water_user.get("id_user")));

			current_stage=1;
			new PostToServer().execute();

		    }
		}).setNegativeButton(Config.CANCEL, new OnClickListener() {

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



    @Override
    protected void onRestart() {
	super.onRestart();
	new PostToServer().execute();	
    }


    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    super.onPreExecute();
	    pDialog = new ProgressDialog(ListWaterUsers.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setIndeterminate(true);
	    pDialog.setCancelable(false);
	    pDialog.setButton(Config.CANCEL, new DialogInterface.OnClickListener() {
		@Override
		public void onClick(DialogInterface dialog, int which) {					
		    ListWaterUsers.this.finish();
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
		api_url = "?a=fetch-water-users";	
		params.add(new BasicNameValuePair("id_user", null));
	    }else {
		api_url = "?a=mark-water-user-for-delete";
	    }	    

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
			customersList.clear();
			JSONArray water_users = data.getJSONArray("water_users");
			for (int i = 0; i < water_users.length(); i++) {
			    JSONObject wateruser = water_users.getJSONObject(i);			   
			    HashMap<String, String> map = new HashMap<String, String>();
			    map.put("id_user", wateruser.getString("id_user"));
			    map.put("wateruser_name", wateruser.getString("fname")+" "+wateruser.getString("lname"));
			    customersList.add(map);							
			}
		    }

		}
	    } catch (JSONException e) {

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
			new AlertDialog.Builder(ListWaterUsers.this)
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
			new AlertDialog.Builder(ListWaterUsers.this)
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
					    ListWaterUsers.this, customersList,
					    R.layout.list_water_users_list_item, new String[] { "id_user",
					    "wateruser_name"},
					    new int[] { R.id.wateruser_id_renderer, R.id.wateruser_name_renderer });							
				    water_users_list.setAdapter(adapter);	
				}else{
				    new AlertDialog.Builder(ListWaterUsers.this)
				    .setTitle(Config.INFO)
				    .setMessage(Config.NO_CUSTOMERS_ON_MONTHLY_BILLING)	    
				    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    finish();								
					}
				    })							
				    .show();
				}
			    }else {
				new AlertDialog.Builder(ListWaterUsers.this)
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
			    new AlertDialog.Builder(ListWaterUsers.this)
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
