package com.sukavi.pm4w;



import java.io.File;

import org.json.JSONArray;
import org.json.JSONObject;

import com.sukavi.pm4w.config.Config;
import com.sukavi.pm4w.dbmanager.DatabaseHandler;
import com.sukavi.pm4w.http.JSONParser;
import com.sukavi.pm4w.user.Permissions;
import com.sukavi.pm4w.user.User;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.DialogInterface.OnCancelListener;
import android.content.IntentFilter;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;

public class Dashboard extends Activity {
    DatabaseHandler dbhandler = new DatabaseHandler(this);
    User userSession;
    JSONParser jsonp = new JSONParser();
    int server_status;
    int request_status;
    JSONArray msgs;
    ProgressDialog pDialog;
    public JSONObject json;
    int count=0;
    public static boolean isDownloadingUpdate = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);	
	setContentView(R.layout.dashboard);

	userSession = dbhandler.getUser(1);	
	//water users
	Button btn_water_users = (Button) findViewById(R.id.btn_water_users);

	btn_water_users.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent i = new Intent(getApplicationContext(), WaterUsers.class);
		startActivity(i);
	    }
	});

	btn_water_users.setVisibility(View.GONE);
	if(Permissions.CAN_ADD_WATER_USERS||Permissions.CAN_EDIT_WATER_USERS||Permissions.CAN_DELETE_WATER_USERS||Permissions.CAN_VIEW_WATER_USERS) {
	    btn_water_users.setVisibility(View.VISIBLE);
	}

	//sales

	Button btn_sales = (Button) findViewById(R.id.btn_sales);

	btn_sales.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent i = new Intent(getApplicationContext(), Sales.class);
		startActivity(i);

	    }
	});

	btn_sales.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_SALES||Permissions.CAN_EDIT_SALES||Permissions.CAN_DELETE_SALES||Permissions.CAN_VIEW_SALES) {
	    btn_sales.setVisibility(View.VISIBLE);
	    count+=1;
	}

	//savings

	Button btn_savings = (Button) findViewById(R.id.btn_savings);

	btn_savings.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent i = new Intent(getApplicationContext(), Savings.class);
		startActivity(i);

	    }
	});

	btn_savings.setVisibility(View.GONE);

	//System.out.println(Permissions.CAN_VIEW_WATER_SOURCE_SAVINGS);

	if(Permissions.CAN_SUBMIT_ATTENDANT_DAILY_SALES||Permissions.CAN_CANCEL_ATTENDANT_DAILY_SALES||
		Permissions.CAN_APPROVE_ATTENDANTS_SUBMISSIONS||Permissions.CAN_CANCEL_ATTENDANTS_SUBMISSIONS||
		Permissions.CAN_APPROVE_TREASURERS_SUBMISSIONS||Permissions.CAN_CANCEL_TREASURERS_SUBMISSIONS
		||Permissions.CAN_VIEW_WATER_SOURCE_SAVINGS) {
	    btn_savings.setVisibility(View.VISIBLE);
	    count+=1;
	}

	//Expenditures

	Button btn_expenditures = (Button) findViewById(R.id.btn_expenditures);   
	btn_expenditures.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {

		Intent i = new Intent(getApplicationContext(), AddExpenditures.class);
		startActivity(i);
	    }
	});

	btn_expenditures.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_EXPENSES) {
	    btn_expenditures.setVisibility(View.VISIBLE);
	    count+=1;
	}

	//My account

	Button btn_my_account = (Button) findViewById(R.id.btn_account);   
	btn_my_account.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {

		Intent i = new Intent(getApplicationContext(), Account.class);
		startActivity(i);
	    }
	});

	btn_my_account.setVisibility(View.GONE);

	if(Permissions.CAN_VIEW_PERSONAL_SAVINGS) {
	    btn_my_account.setVisibility(View.VISIBLE);
	    count+=1;
	}

	if(count==3) {
	    Button btn_transparent= (Button) findViewById(R.id.btn_transparent);
	    btn_transparent.setVisibility(View.GONE);
	}



	Intent intent = new Intent(this, DownloadService.class);
	startService(intent);


    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
	// Inflate the menu; this adds items to the action bar if it is present.
	getMenuInflater().inflate(R.menu.dashboard, menu);
	return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {	
	if(item.getItemId()==R.id.action_logout){
	    //dbhandler.deleteUser(userSession);
	    dbhandler.Logout();
	    Intent login = new Intent(getApplicationContext(), Login.class);
	    startActivity(login);
	    this.finish();
	}else if(item.getItemId()==R.id.action_help){	    
	    Intent help = new Intent(getApplicationContext(), Help.class);
	    startActivity(help);	   
	}else if(item.getItemId()==R.id.action_about){	    
	    Intent about = new Intent(getApplicationContext(), About.class);
	    startActivity(about);	   
	}else if(item.getItemId()==R.id.action_check_update){	    
	    Intent aCheckForUpdates = new Intent(getApplicationContext(), CheckForUpdates.class);
	    startActivity(aCheckForUpdates);	   
	}
	return false;
    }	

    @Override
    public void onBackPressed() {
	//	super.onBackPressed();
	//android.os.Process.killProcess(android.os.Process.myPid());
	if(isDownloadingUpdate) {
	    new AlertDialog.Builder(Dashboard.this)
	    .setTitle(Config.INFO)
	    .setMessage(Config.CANCEL_DISABLED_DOWNLOADING_UPDATE)	    
	    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

		@Override
		public void onCancel(DialogInterface dialog) {
		    return;							
		}
	    }).show();
	}else {
	    android.os.Process.killProcess(android.os.Process.myPid());
	}
    }

    @Override
    protected void onResume() {
	super.onResume();
	registerReceiver(receiver, new IntentFilter(DownloadService.NOTIFICATION));
    }
    @Override
    protected void onPause() {
	super.onPause();
	unregisterReceiver(receiver);
    }


    private BroadcastReceiver receiver = new BroadcastReceiver() {

	@Override
	public void onReceive(Context context, Intent intent) {
	    Bundle bundle = intent.getExtras();
	    if (bundle != null) {

		int resultCode = bundle.getInt(DownloadService.RESULT);
		final String name = bundle.getString(DownloadService.NAME);
		if (resultCode == RESULT_OK) {		   

		    new AlertDialog.Builder(Dashboard.this)
		    .setTitle(Config.INFO)
		    .setMessage(Config.UPDATE_AVAILABLE)	    
		    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

			@Override
			public void onCancel(DialogInterface dialog) {
			    //finish();								
			}
		    }).setPositiveButton(Config.UPDATE, new DialogInterface.OnClickListener() {

			@Override
			public void onClick(DialogInterface dialog, int which) {
			    Intent intent = new Intent(Intent.ACTION_VIEW);
			    intent.setDataAndType(Uri.fromFile(new File(Environment.getExternalStorageDirectory() + "/"+Config.STORAGE_DRIECTORY_NAME+"/" + name)), "application/vnd.android.package-archive");
			    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			    startActivity(intent);
			}
		    })							
		    .show();		

		}	    

	    } 
	}    
    };

}