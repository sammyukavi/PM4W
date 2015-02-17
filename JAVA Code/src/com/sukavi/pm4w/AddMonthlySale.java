package com.sukavi.pm4w;


import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

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
import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.DatePicker;
import android.widget.SimpleAdapter;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;


public class AddMonthlySale extends Activity {

    private ArrayList<HashMap<String, String>> WaterSourcesList = new ArrayList<HashMap<String, String>>();
    private JSONParser jsonp = new JSONParser();
    private DatabaseHandler dbhandler = new DatabaseHandler(this);
    private User userSession; 	
    private String id_user;
    private ProgressDialog pDialog;

    private JSONObject json;    
    private int current_stage=0;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private String api_url;
    private Spinner water_source_spinner;
    private int water_source_id=0;
    private TextView amount_sold;
    String paying_status="";
    TextView select_date_TextView;    
    TextView sale_datePicker;
    String unpaid_month="";
    int DATE_DIALOG_ID=3429;


    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);

	userSession = dbhandler.getUser(1);	

	setContentView(R.layout.add_monthly_sale);

	id_user= getIntent().getStringExtra("id_user");
	paying_status = getIntent().getStringExtra("paying_status");
	unpaid_month= getIntent().getStringExtra("unpaid_month");
	String name = getIntent().getStringExtra("wateruser_name");


	TextView wateruser_name = (TextView) findViewById(R.id.wateruser_name);
	wateruser_name.setText(name);
	amount_sold = (TextView) findViewById(R.id.amount_sold);

	if(paying_status!=null&&paying_status.equals("follow-up-pay")) {

	    select_date_TextView = (TextView) findViewById(R.id.select_date_TextView);
	    select_date_TextView.setVisibility(View.VISIBLE);
	    sale_datePicker = (TextView) findViewById(R.id.sale_datePicker);
	    sale_datePicker.setVisibility(View.VISIBLE);

	    String timeStamp = new SimpleDateFormat("dd-MM-yyyy").format(new Date(System.currentTimeMillis()));

	    sale_datePicker.setText(timeStamp);

	    final Calendar myCalendar = Calendar.getInstance();

	    // listener for date picker        
	    final DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {

		@Override
		public void onDateSet(DatePicker view, int year, int monthOfYear,
			int dayOfMonth) {		   
		    myCalendar.set(Calendar.YEAR, year);
		    myCalendar.set(Calendar.MONTH, monthOfYear);
		    myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
		    updateLabel();
		}

		private void updateLabel() {		  

		    String myFormat = "dd-MM-yyyy";
		    SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);
		    sale_datePicker.setText(sdf.format(myCalendar.getTime()));
		}

	    };



	    sale_datePicker.setOnClickListener(new View.OnClickListener() {

		@Override
		public void onClick(View v) {		   

		    DatePickerDialog datePickerDialog = new DatePickerDialog(AddMonthlySale.this, date, myCalendar.get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),myCalendar.get(Calendar.DAY_OF_MONTH));
		    datePickerDialog.show();	 

		}

	    });	    

	}


	if(isOnline()){	

	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));
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

	Spinner sItems = (Spinner) findViewById(R.id.water_source_spinner);
	sItems.setOnItemSelectedListener(new OnItemSelectedListener() {

	    @Override
	    public void onItemSelected(AdapterView<?> arg0, View view, int arg2, long arg3) {
		TextView id_water_source_text_view = 	(TextView) view.findViewById(R.id.id_water_source_renderer);		
		TextView monthly_charges_text_view = 	(TextView) view.findViewById(R.id.monthly_charges_renderer);
		water_source_id = Integer.parseInt(id_water_source_text_view.getText().toString());
		amount_sold.setText(monthly_charges_text_view.getText());
	    }

	    @Override
	    public void onNothingSelected(AdapterView<?> arg0) {
		amount_sold.setText(0);		
	    }
	});

    }	


    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    public void addSale(View v){
	water_source_spinner = (Spinner) findViewById(R.id.water_source_spinner);


	if(amount_sold.getText().toString().length()==0){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.AMOUNT_REQUIRED_ERROR)	    
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}

	if(water_source_spinner.getSelectedItem()==null){
	    new AlertDialog.Builder(this)
	    .setTitle(Config.ERROR)
	    .setMessage(Config.WATERSOURCE_REQUIRED_ERROR)
	    .setIcon(android.R.drawable.ic_dialog_alert)
	    .show();
	    return;
	}	

	current_stage=1;

	params.add(new BasicNameValuePair("water_source_id", water_source_id+""));
	params.add(new BasicNameValuePair("sold_to", id_user));
	params.add(new BasicNameValuePair("sale_ugx",amount_sold.getText().toString()));

	if(paying_status!=null&&paying_status.equals("follow-up-pay")) {
	    params.add(new BasicNameValuePair("sale_date", sale_datePicker.getText().toString()));
	}
	new PostToServer().execute();

    }


    class PostToServer extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {
	    pDialog = new ProgressDialog(AddMonthlySale.this);
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);			
	    pDialog.show();	    
	}

	@Override
	protected String doInBackground(String... arg0) {	


	    if(current_stage==0) {
		api_url = "?a=fetch-water-sources";		
	    }else {
		api_url = "?a=add-sale";
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
			    new AlertDialog.Builder(AddMonthlySale.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_alert)
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(AddMonthlySale.this)
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
					    AddMonthlySale.this, WaterSourcesList,
					    R.layout.list_water_sources_list_item, new String[] { "id_water_source",
						    "water_source_name","monthly_charges"},
						    new int[] { R.id.id_water_source_renderer, R.id.water_source_name_renderer,R.id.monthly_charges_renderer });


				    Spinner sItems = (Spinner) findViewById(R.id.water_source_spinner);
				    sItems.setAdapter(adapter);				   
				}else {
				    new AlertDialog.Builder(AddMonthlySale.this)
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
				new AlertDialog.Builder(AddMonthlySale.this)
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
			new AlertDialog.Builder(AddMonthlySale.this)
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
