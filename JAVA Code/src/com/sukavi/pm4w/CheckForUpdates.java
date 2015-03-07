package com.sukavi.pm4w;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
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
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.os.PowerManager;
import android.widget.TextView;
import android.widget.Toast;

public class CheckForUpdates extends Activity {

    String PATH = Environment.getExternalStorageDirectory() + "/"+Config.STORAGE_DRIECTORY_NAME+"/";//with backslash
    String dialogMsg = null;
    JSONParser jsonp = new JSONParser();
    JSONObject json =null;
    private String api_url ="?a=check-update";
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    ProgressDialog pDialog;
    Controller controller;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);	
	pDialog = new ProgressDialog(this);
	setContentView(R.layout.check_for_updates);
	TextView current_version = (TextView) findViewById(R.id.current_version);
	current_version.setText(Config.APP_VERSION);
	new ExecuteCheckForUpdates().execute();

    } 

    public boolean deleteDownloadedFile(String name) {	 
	File file = new File(PATH+name);
	return file.delete();
    }



    class ExecuteCheckForUpdates extends AsyncTask<String, String, String> {

	@Override
	protected void onPreExecute() {

	    pDialog.setTitle(Config.PLEASE_WAIT);
	    pDialog.setMessage(dialogMsg);
	    pDialog.setCancelable(false);
	    pDialog.setIndeterminate(true);	
	    pDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
	    pDialog.show();
	}

	@Override
	protected String doInBackground(String... arg0) {		

	    json = jsonp.makeHttpRequest(api_url, "POST", params);		

	    return null;
	}


	@Override
	protected void onPostExecute(String result) {			
	    pDialog.cancel();
	    runOnUiThread(new Runnable() {
		@Override
		public void run() {
		    if(json==null) {		
			new AlertDialog.Builder(CheckForUpdates.this)
			.setTitle(Config.INFO)
			.setMessage(Config.SERVER_NOT_ACCESSIBLE)	
			.setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {								
				finish();						
			    }
			})
			.show();		
			return;			    
		    }
		    try {
			JSONObject server_info = json.getJSONObject("server_info");
			int server_status = server_info.getInt("server_status");
			JSONObject data = json.getJSONObject("data");	
			int request_status = data.getInt("request_status");	
			JSONArray msgs = data.getJSONArray("msgs");

			String txt="";						
			for(int index=0; index<msgs.length();index++){
			    try {
				txt += msgs.getString(index);
			    } catch (JSONException e) {				
				e.printStackTrace();
			    }
			}

			if(server_status==Config.SERVER_OFFLINE){
			    new AlertDialog.Builder(CheckForUpdates.this)
			    .setTitle(Config.OFFLINE_TITLE)
			    .setMessage(Config.OFFLINE_MSG)	    
			    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    finish();								
				}
			    })
			    .show();
			}else if(server_status==Config.SERVER_UPGRADE){
			    new AlertDialog.Builder(CheckForUpdates.this)
			    .setTitle(Config.UPGRADE_TITLE)
			    .setMessage(Config.UPGRADE_MSG)		    
			    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

				@Override
				public void onCancel(DialogInterface dialog) {
				    finish();								
				}
			    })
			    .show();
			}else{
			    if(request_status==Config.REQUEST_SUCCESSFUL){

				JSONArray updates=data.getJSONArray("updates");
				final JSONObject latest_update= updates.getJSONObject((updates.length()-1));
				final String url = latest_update.getString("url");
				final String name = latest_update.getString("name");

				if(latest_update.length()>0) {
				    if(latest_update.getDouble("version")>Double.parseDouble(Config.APP_VERSION)) {

					new AlertDialog.Builder(CheckForUpdates.this)
					.setTitle(Config.INFO)
					.setMessage(Config.UPDATE_AVAILABLE)	    
					.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

					    @Override
					    public void onCancel(DialogInterface dialog) {
						finish();								
					    }
					}).setPositiveButton(Config.UPDATE, new DialogInterface.OnClickListener() {

					    @Override
					    public void onClick(DialogInterface dialog, int which) {			
						pDialog = new ProgressDialog(CheckForUpdates.this);
						pDialog.setMessage(Config.DOWNLOADING_UPDATE);
						pDialog.setIndeterminate(true);
						pDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
						pDialog.setCancelable(true);

						final DownloadTask downloadTask = new DownloadTask(CheckForUpdates.this);

						downloadTask.execute(url,name);
						//downloadTask.execute("http://pm4w.uct.ac.za/downloads/android-av-develop.zip",name);

						pDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
						    @Override
						    public void onCancel(DialogInterface dialog) {													
							new AlertDialog.Builder(CheckForUpdates.this)
							.setTitle(Config.INFO)
							.setMessage(Config.CONFIRM_CANCEL_DOWNLOAD)	
							.setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(Config.CANCEL_DOWNLOAD, new DialogInterface.OnClickListener() {

							    @Override
							    public void onClick(DialogInterface dialog, int which) {
								downloadTask.cancel(true);
								deleteDownloadedFile(name);
								finish();

							    }
							}).setNegativeButton(Config.FINISH_DOWNLOADING, new DialogInterface.OnClickListener() {

							    @Override
							    public void onClick(DialogInterface dialog, int which) {
								pDialog.show();

							    }
							}).setOnCancelListener(new DialogInterface.OnCancelListener() {

							    @Override
							    public void onCancel(DialogInterface dialog) {
								pDialog.show();

							    }
							})
							.show();

						    }
						});
					    }
					})							
					.show();	

				    }else {
					new AlertDialog.Builder(CheckForUpdates.this)
					.setTitle(Config.INFO)
					.setMessage(Config.NO_UPDATE_AVAILABLE)	    
					.setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

					    @Override
					    public void onCancel(DialogInterface dialog) {
						finish();								
					    }
					})							
					.show();
				    }
				}else {
				    new AlertDialog.Builder(CheckForUpdates.this)
				    .setTitle(Config.INFO)
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
				new AlertDialog.Builder(CheckForUpdates.this)
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
			new AlertDialog.Builder(CheckForUpdates.this)
			.setTitle(Config.ERROR)
			//.setMessage(Config.ERROR_READING_FROM_SERVER)	    
			.setMessage(e.toString())
			.setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			    @Override
			    public void onCancel(DialogInterface dialog) {								
				//finish();						
			    }
			})
			.show();
		    }

		}
	    });
	}		

    }

    class DownloadTask extends AsyncTask<String, Integer, String> {

	private Context context;
	private PowerManager.WakeLock mWakeLock;

	public DownloadTask(Context context) {
	    this.context = context;
	}

	@Override
	protected void onPreExecute() {
	    super.onPreExecute();
	    // take CPU lock to prevent CPU from going off if the user 
	    // presses the power button during download
	    PowerManager pm = (PowerManager) context.getSystemService(Context.POWER_SERVICE);
	    mWakeLock = pm.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK,
		    getClass().getName());
	    mWakeLock.acquire();
	    pDialog.show();
	}

	@Override
	protected void onProgressUpdate(Integer... progress) {
	    super.onProgressUpdate(progress);
	    // if we get here, length is known, now set indeterminate to false
	    pDialog.setIndeterminate(false);
	    pDialog.setMax(100);
	    pDialog.setProgress(progress[0]);
	}

	@Override
	protected void onPostExecute(String name) {
	    pDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
	    mWakeLock.release();
	    pDialog.dismiss();
	    if (name == null) {
		Toast.makeText(context,"Download error: "+name, Toast.LENGTH_LONG).show();
	    }
	    else {
		Intent intent = new Intent(Intent.ACTION_VIEW);
		intent.setDataAndType(Uri.fromFile(new File(PATH + name)), "application/vnd.android.package-archive");
		intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		startActivity(intent);

	    }
	}

	@Override
	protected String doInBackground(String... params) {

	    String name =params[1];


	    InputStream input = null;
	    OutputStream output = null;
	    HttpURLConnection connection = null;

	    try {		
		URL url = new URL(params[0]);
		connection = (HttpURLConnection) url.openConnection();
		connection.connect();

		// expect HTTP 200 OK, so we don't mistakenly save error report
		// instead of the file
		if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
		    return "Server returned HTTP " + connection.getResponseCode()
			    + " " + connection.getResponseMessage();
		}

		// this will be useful to display download percentage
		// might be -1: server did not report the length
		int fileLength = connection.getContentLength();

		// download the file
		input = connection.getInputStream();
		output = new FileOutputStream(PATH+name);

		byte data[] = new byte[4096];
		long total = 0;
		int count;
		while ((count = input.read(data)) != -1) {		   
		    if (isCancelled()) {
			input.close();
			return null;
		    }
		    total += count;	   
		    output.write(data, 0, count);
		    if (fileLength > 0) {
			publishProgress((int) (total * 100 / fileLength));
		    }
		}
	    } catch (Exception e) {
		return e.toString();
	    } finally {
		try {
		    if (output != null) {
			output.close();
		    }
		    if (input != null) {
			input.close();
		    }
		} catch (IOException ignored) {
		}

		if (connection != null) {
		    connection.disconnect();
		}
	    }

	    File pm4w_directory = new File(PATH);        
	    String[] myFiles;      

	    myFiles = pm4w_directory.list();  
	    for (int i=0; i<myFiles.length; i++) {  
		File myFile = new File(pm4w_directory, myFiles[i]);   
		if(!myFile.getName().equals(name)) {
		    myFile.delete(); 
		}					   
	    }	

	    return name;
	}
    }

}


