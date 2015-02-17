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
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.ViewGroup.LayoutParams;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;


public class MiniStatement extends Activity {

    JSONParser jsonp = new JSONParser();
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession; 	
    ProgressDialog pDialog;
    JSONObject json = null;	
    int server_status;
    JSONObject data;
    int request_status;
    JSONArray msgs;
    


    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	userSession = dbhandler.getUser(1);
	setContentView(R.layout.mini_statement_layout);
	pDialog = new ProgressDialog(MiniStatement.this);

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

	new FetchTransactions().execute();

    }

    public boolean isOnline() {
	ConnectivityManager cm =
		(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
	NetworkInfo netInfo = cm.getActiveNetworkInfo();
	return netInfo != null && netInfo.isConnectedOrConnecting();
    }

    class FetchTransactions extends AsyncTask<String, String, String>{

	@Override
	protected void onPreExecute() {					
	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(Config.SENDING_DATA);
	    pDialog.setIndeterminate(true);		
	    pDialog.setButton(Config.CANCEL, new DialogInterface.OnClickListener() {
		@Override
		public void onClick(DialogInterface dialog, int which) {					
		    MiniStatement.this.finish();
		}
	    });		
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... arg0) {	

	    List<NameValuePair> params = new ArrayList<NameValuePair>();
	    params.add(new BasicNameValuePair("uid",String.valueOf(userSession.getIDU())));
	    params.add(new BasicNameValuePair("username",userSession.getUSERNAME()));			
	    params.add(new BasicNameValuePair("request_hash", userSession.getREQUEST_HASH()));

	    String api_url = "?a=fetch-mini-statement";

	    json = jsonp.makeHttpRequest(api_url, "POST", params);

	    try {
		JSONObject server_info = json.getJSONObject("server_info");
		server_status = server_info.getInt("server_status");
		data = json.getJSONObject("data");	
		request_status = data.getInt("request_status");	
		msgs = data.getJSONArray("msgs");
	    } catch (JSONException e) {

	    }	

	    return null;
	}


	@Override
	protected void onPostExecute(String result) {			
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
			new AlertDialog.Builder(MiniStatement.this)
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
			new AlertDialog.Builder(MiniStatement.this)
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
			    try {
				JSONArray transactions = data.getJSONArray("transactions");
								
				if(transactions.length()<1){
				    new AlertDialog.Builder(MiniStatement.this)
				    .setTitle(Config.INFO)
				    .setMessage(Config.NO_TRANSACTIONS)	    
				    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

					@Override
					public void onCancel(DialogInterface dialog) {
					    finish();								
					}
				    })							
				    .show();

				}
				
				LayoutParams layout_params = new TableRow.LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT, 0.3f);
				float scale = getResources().getDisplayMetrics().density;
				int padding_top = (int) (5*scale + 0.5f);
				int padding_left = (int) (135*scale + 0.5f);


				try {
				    for (int index = 0; index < transactions.length(); index++) {							
					JSONObject transaction = transactions.getJSONObject(index);

					TableRow table_row = new TableRow(MiniStatement.this);
					table_row.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT,LayoutParams.WRAP_CONTENT));
					table_row.setPadding(padding_top, padding_top, padding_top, padding_top);

					TextView transaction_name = new TextView(MiniStatement.this);      
					transaction_name.setText(transaction.getString("type")); 
					transaction_name.setPadding(padding_top, padding_top, padding_top, padding_top);
					transaction_name.setLayoutParams(layout_params);  
					transaction_name.setTextColor(Color.parseColor("#333333"));
					table_row.addView(transaction_name);

					TextView transaction_cost = new TextView(MiniStatement.this);
					transaction_cost .setText(transaction.getString("amount"));
					transaction_cost.setPadding(padding_left, padding_top, padding_top, padding_top);								
					transaction_cost.setLayoutParams(layout_params);
					transaction_cost.setTextColor(Color.parseColor("#333333"));
					table_row.addView(transaction_cost );

					TextView transaction_date = new TextView(MiniStatement.this);       
					transaction_date.setText(transaction.getString("date"));
					transaction_date.setPadding(padding_top, padding_top, padding_top, padding_top);
					transaction_date.setLayoutParams(layout_params);
					transaction_date.setTextColor(Color.parseColor("#333333"));
					table_row.addView(transaction_date );

					TableLayout mini_statement_table = (TableLayout) findViewById(R.id.mini_statement_table);
					mini_statement_table.addView(table_row);

				    }
				} catch (JSONException e) {
				    new AlertDialog.Builder(MiniStatement.this)
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
				

			    } catch (JSONException e) {
				e.printStackTrace();
				new AlertDialog.Builder(MiniStatement.this)
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


			}else{
			    new AlertDialog.Builder(MiniStatement.this)
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

