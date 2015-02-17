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
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.content.DialogInterface.OnClickListener;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.ContextMenu;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ContextMenu.ContextMenuInfo;
import android.widget.AdapterView;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;


public class FollowUp extends Activity {

    JSONParser jsonp = new JSONParser();
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession; 	
    ProgressDialog pDialog;
    JSONObject json = null;	
    int server_status;
    JSONObject data;
    int request_status;
    JSONArray msgs;
    JSONArray defaulters;
    String id_customer,customer_name;
    private ArrayList<HashMap<String, String>> defaultersList = new ArrayList<HashMap<String, String>>();
    ListView water_users_list;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    int current_stage=0;
    String api_url;

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	userSession = dbhandler.getUser(1);
	setContentView(R.layout.follow_up);
	pDialog = new ProgressDialog(FollowUp.this);

	if(!isOnline()){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.NO_INTERNET_TITLE)
	    .setMessage(Config.NO_INTERNET_MSG)	    
	    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

		@Override
		public void onCancel(DialogInterface dialog) {
		    finish();								
		}
	    })			
	    .show();
	}

	water_users_list = (ListView) findViewById(R.id.waterusers_list);

	water_users_list.setOnItemClickListener(new OnItemClickListener() {

	    @Override
	    public void onItemClick(AdapterView<?> arg0, View view, int arg2,
		    long arg3) {
		TextView id_customer_text_view = 	(TextView) view.findViewById(R.id.wateruser_id_renderer);	
		TextView name_text_view = 	(TextView) view.findViewById(R.id.wateruser_name_renderer);
		Intent followuppay = new Intent(getApplicationContext(), AddMonthlySale.class);
		followuppay.putExtra("id_user",id_customer_text_view.getText());
		followuppay.putExtra("wateruser_name",name_text_view .getText().toString().split("-")[0]);
		followuppay.putExtra("unpaid_month",name_text_view .getText().toString().split("-")[1]);
		followuppay.putExtra("paying_status","follow-up-pay");
		startActivity(followuppay);
	    }	    

	});

	registerForContextMenu(water_users_list);

	new PostToServer().execute();

    }




    @Override
    protected void onRestart() {	
	super.onRestart();
	new PostToServer().execute();
    }



    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    @Override
    public void onCreateContextMenu(ContextMenu menu, View v,
	    ContextMenuInfo menuInfo) {
	menu.setHeaderTitle(Config.SELECT_ACTION);
	String[] menuItems = getResources().getStringArray(R.array.follow_up_menu);
	for (int i = 0; i<menuItems.length; i++) {
	    menu.add(Menu.NONE, i, i, menuItems[i]);
	}
    }

    @Override
    public boolean onContextItemSelected(MenuItem item) {
	final AdapterView.AdapterContextMenuInfo info = (AdapterView.AdapterContextMenuInfo)item.getMenuInfo();
	int menuItemIndex = item.getItemId();
	if(menuItemIndex==0) {

	    if(!Permissions.CAN_SEND_SMS) { 
		new AlertDialog.Builder(FollowUp.this)
		.setTitle(Config.INFO)
		.setMessage(Config.ACTION_DISABLED)	    
		.setIcon(android.R.drawable.ic_dialog_info).show();
	    }else {
		@SuppressWarnings("unchecked")
		HashMap<String, String> water_user = (HashMap<String, String>) water_users_list.getItemAtPosition(info.position);
		Intent composesms = new Intent(getApplicationContext(), ComposeSMS.class);
		composesms.putExtra("id_user",water_user.get("id_user"));	
		startActivity(composesms);
	    }
	}else {
	    new AlertDialog.Builder(this)
	    .setTitle(Config.INFO)
	    .setMessage(Config.REPORT_WATER_USER_MSG)	    
	    .setIcon(android.R.drawable.ic_menu_help).setPositiveButton(Config.REPORT, new OnClickListener() {

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
	return true;
    }


    class PostToServer extends AsyncTask<String, String, String>{

	@Override
	protected void onPreExecute() {					
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setIndeterminate(true);		
	    pDialog.setButton(Config.CANCEL, new DialogInterface.OnClickListener() {
		@Override
		public void onClick(DialogInterface dialog, int which) {					
		    FollowUp.this.finish();
		}
	    });		
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... arg0) {	


	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
	    params.add(new BasicNameValuePair("id_customer", id_customer));

	    if(current_stage==0) {
		api_url = "?a=follow-up";
	    }else {
		api_url = "?a=report-water-user";
	    }

	    json = jsonp.makeHttpRequest(api_url, "POST", params);	

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
			defaultersList.clear();
			JSONArray defaulters = data.getJSONArray("defaulters");
			for (int i = 0; i < defaulters.length(); i++) {
			    JSONObject customer = defaulters.getJSONObject(i);
			    String id_user = customer.getString("id_user");
			    //String name = customer.getString("name")+" -  "+customer.getString("defaulted_month")+" -  "+customer.getString("debt");
			    String name = customer.getString("name")+" -  "+customer.getString("defaulted_month");
			    HashMap<String, String> map = new HashMap<String, String>();
			    map.put("id_user", id_user);
			    map.put("wateruser_name", name);
			    defaultersList.add(map);							
			}
		    }

		}
	    } catch (JSONException e) {
		e.printStackTrace();
	    }		

	    return null;
	}


	@Override
	protected void onPostExecute(String result) {			
	    pDialog.cancel();	   

	    runOnUiThread(new Runnable() {
		@Override
		public void run() {

		    if(json==null){

			new AlertDialog.Builder(FollowUp.this)
			.setTitle(Config.ERROR)
			.setMessage(Config.ERROR_READING_FROM_SERVER)	    
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {
				finish();								
			    }
			})							
			.show();
		    }

		    try {
			JSONObject server_info = json.getJSONObject("server_info");
			server_status = server_info.getInt("server_status");
			data = json.getJSONObject("data");	
			request_status = data.getInt("request_status");	
			msgs = data.getJSONArray("msgs");
			defaulters = data.getJSONArray("defaulters");
		    } catch (JSONException e) {			
			new AlertDialog.Builder(FollowUp.this)
			.setTitle(Config.ERROR)
			.setMessage(e.toString())	    
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {
				finish();								
			    }
			})							
			.show();
		    }	


		    if(server_status==Config.SERVER_OFFLINE){
			new AlertDialog.Builder(FollowUp.this)
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
			new AlertDialog.Builder(FollowUp.this)
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

			String txt="";

			for(int index=0; index<msgs.length();index++){
			    try {
				txt += msgs.getString(index);
			    } catch (JSONException e) {								
				e.printStackTrace();
			    }
			}

			if(request_status!=1){
			    new AlertDialog.Builder(FollowUp.this)
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
			if(request_status==Config.REQUEST_SUCCESSFUL){
			    if(current_stage==0) {
				if(defaultersList.size()>0){
				    ListAdapter adapter = new SimpleAdapter(
					    FollowUp.this, defaultersList,
					    R.layout.list_water_users_list_item, new String[] { "id_user",
					    "wateruser_name"},
					    new int[] { R.id.wateruser_id_renderer, R.id.wateruser_name_renderer });							
				    water_users_list.setAdapter(adapter);	
				}else{
				    new AlertDialog.Builder(FollowUp.this)
				    .setTitle(Config.INFO)
				    .setMessage(Config.NO_DEFAULTERS)	    
				    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    finish();								
					}
				    })							
				    .show();
				}
			    }else {				
				new AlertDialog.Builder(FollowUp.this)
				.setTitle(Config.SUCCESS)
				.setMessage(txt)	    
				.setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				    @Override
				    public void onCancel(DialogInterface dialog) {
					//finish();								
				    }
				})
				.show();
			    }
			}else{
			    new AlertDialog.Builder(FollowUp.this)
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
