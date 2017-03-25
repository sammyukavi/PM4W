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
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import java.util.ArrayList;
import java.util.HashMap;


public class FollowUpActivity extends Activity {

    private Pm4wUser pm4WUser;
    private ArrayList<HashMap<String, String>> defaultersArrayList = new ArrayList<HashMap<String, String>>();
    private ListView defaultersList;
    private TextView usersCount;
    private String action = "";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_followup);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);

        Bundle extras = getIntent().getExtras();
        action = extras.getString("action");
        if (action.equals("followUp")) {
            formName.setText(pm4WUser.language.DEFAULTERS);
        } else if (action.equals("viewPayments")) {
            formName.setText(pm4WUser.language.USER_PAYMENTS);
        }

        usersCount = (TextView) findViewById(R.id.usersCount);

        defaultersList = (ListView) findViewById(R.id.defaultersList);
        defaultersList.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                TextView waterUserIdRenderer = (TextView) view.findViewById(R.id.waterUserIdRenderer);
                Intent ShowDefaultedMonthsActivity = new Intent(getApplicationContext(), ShowUserActivity.class);
                ShowDefaultedMonthsActivity.putExtra(Constants.WATER_USER_ID_TAG, waterUserIdRenderer.getText());
                ShowDefaultedMonthsActivity.putExtra("action", action);
                startActivity(ShowDefaultedMonthsActivity);
            }

        });

        new fetchDefaulters().execute();
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new fetchDefaulters().execute();
    }

    class fetchDefaulters extends AsyncTask<String, String, String> {

        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new ProgressDialog(FollowUpActivity.this);
            pDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    FollowUpActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... strings) {
            Caretakers caretaker = new Caretakers(FollowUpActivity.this);
            try {
                if (action.equals("followUp")) {
                    defaultersArrayList = caretaker.getDefaulters();
                } else if (action.equals("viewPayments")) {
                    defaultersArrayList = caretaker.getPaidUsers();
                }
            } catch (Exception e) {
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
                    usersCount.setText(defaultersArrayList.size() + "");
                    if (defaultersArrayList.size() > 0) {
                        SimpleAdapter adapter = new SimpleAdapter(
                                FollowUpActivity.this, defaultersArrayList,
                                R.layout.listitem_followup, new String[]{Constants.WATER_USER_ID_TAG,
                                Constants.COMBINED_FNAME_LNAME_TAG},
                                new int[]{R.id.waterUserIdRenderer, R.id.waterUserNameRenderer});

                        defaultersList.setAdapter(adapter);
                    } else {
                        String str = "";
                        if (action.equals("followUp")) {
                            str = pm4WUser.language.NO_DEFAULTERS;
                        } else if (action.equals("viewPayments")) {
                            str = pm4WUser.language.NO_TRANSACTIONS;
                        }
                        new AlertDialog.Builder(FollowUpActivity.this)
                                .setMessage(str)
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
