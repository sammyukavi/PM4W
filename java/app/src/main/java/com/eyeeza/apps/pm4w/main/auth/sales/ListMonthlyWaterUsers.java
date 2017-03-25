package com.eyeeza.apps.pm4w.main.auth.sales;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import java.util.ArrayList;
import java.util.HashMap;


public class ListMonthlyWaterUsers extends Activity {


    private ProgressDialog pDialog;
    private ArrayList<HashMap<String, String>> waterUsersArrayList = new ArrayList<HashMap<String, String>>();
    private ListView waterUsersList;
    private TextView usersCount;
    private Pm4wUser pm4WUser;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listmonthlybilledwaterusers);
        usersCount = (TextView) findViewById(R.id.usersCount);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.WATER_USERS);

        pm4WUser.logEvent(EventLogs.EVENT_LISTED_WATER_USERS);

        new FetchMonthlyWaterUsers().execute();

        waterUsersList = (ListView) findViewById(R.id.waterUsersList);
        waterUsersList.setOnItemClickListener(new OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                TextView waterUserIdRenderer = (TextView) view.findViewById(R.id.waterUserIdRenderer);
                Intent addmonthlysale = new Intent(getApplicationContext(), AddMonthlySale.class);
                addmonthlysale.putExtra(Constants.WATER_USER_ID_TAG, waterUserIdRenderer.getText());
                startActivity(addmonthlysale);
            }
        });

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchMonthlyWaterUsers().execute();
    }


    class FetchMonthlyWaterUsers extends AsyncTask<String, String, String> {

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(ListMonthlyWaterUsers.this);
            pDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListMonthlyWaterUsers.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            WaterUsers waterUsers = new WaterUsers(ListMonthlyWaterUsers.this);
            waterUsersArrayList = waterUsers.getAllWaterUsers();
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            pDialog.dismiss();

            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    usersCount.setText(waterUsersArrayList.size() + "");
                    if (waterUsersArrayList.size() > 0) {
                        SimpleAdapter adapter = new SimpleAdapter(
                                ListMonthlyWaterUsers.this, waterUsersArrayList,
                                R.layout.listitem_waterusers, new String[]{Constants.WATER_USER_ID_TAG,
                                Constants.COMBINED_FNAME_LNAME_TAG},
                                new int[]{R.id.waterUserIdRenderer, R.id.waterUserNameRenderer});
                        waterUsersList.setAdapter(adapter);
                    } else {
                        new AlertDialog.Builder(ListMonthlyWaterUsers.this)
                                .setTitle(pm4WUser.language.INFO)
                                .setMessage(pm4WUser.language.NO_CUSTOMERS_ON_MONTHLY_BILLING)
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                finish();
                            }
                        }).show();
                    }

                }
            });
        }

    }
}
