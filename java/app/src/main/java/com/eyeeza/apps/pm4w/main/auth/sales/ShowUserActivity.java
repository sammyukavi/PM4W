package com.eyeeza.apps.pm4w.main.auth.sales;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.networking.NetworkFunctions;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Sammy N Ukavi Jr on 5/11/2016.
 */
public class ShowUserActivity extends Activity {
    private Pm4wUser pm4WUser;
    private ListView defaultedMonthsList;
    private TextView monthsCount;
    private ArrayList<HashMap<String, String>> defaultedMonthsArrayList = new ArrayList<HashMap<String, String>>();
    private long waterUserId;
    private String action = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_defaultedmonths);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);

        Bundle extras = getIntent().getExtras();
        waterUserId = Long.parseLong(extras.getString(Constants.WATER_USER_ID_TAG));
        action = extras.getString("action");
        if (action.equals("followUp")) {
            formName.setText(pm4WUser.language.DEFAULTED_MONTHS);
            RelativeLayout footer = (RelativeLayout) findViewById(R.id.footer);
            footer.setVisibility(View.VISIBLE);

            Button composeSMSButton = (Button) findViewById(R.id.composeSMSButton);
            if (pm4WUser.CAN_SEND_SMS) {
                composeSMSButton.setVisibility(View.VISIBLE);
            }

            Button reportUserButton = (Button) findViewById(R.id.reportUserButton);
            reportUserButton.setVisibility(View.VISIBLE);

        } else if (action.equals("viewPayments")) {
            formName.setText(pm4WUser.language.USER_PAYMENTS);
        }

        monthsCount = (TextView) findViewById(R.id.monthsCount);
        defaultedMonthsList = (ListView) findViewById(R.id.defaultersList);


        new fetchDefaultedMonths().execute();


    }

    public void composeSMS(View v) {
        if (!NetworkFunctions.isOnline(this)) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                    .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            }).show();
            return;
        }

        Intent composeSMS = new Intent(getApplicationContext(), ComposeSMS.class);
        composeSMS.putExtra(Constants.WATER_USER_ID_TAG, waterUserId);
        startActivity(composeSMS);
    }

    public void markReported(View v) {
        DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                switch (which) {
                    case DialogInterface.BUTTON_POSITIVE:
                        //Yes button clicked
                        WaterUsers waterUser = new WaterUsers(ShowUserActivity.this);
                        waterUser.getWaterUser(waterUserId);
                        waterUser.setReportedDefaulter(1);
                        waterUser.setLastUpdated(Utils.getMySQLDate());
                        long id = waterUser.saveWaterUser();
                        pm4WUser.logEvent(EventLogs.EVENT_DELETED_WATER_USER, waterUser.getIdUser());
                        new fetchDefaultedMonths().execute();
                        break;

                    case DialogInterface.BUTTON_NEGATIVE:
                        //No button clicked
                        break;
                }
            }
        };

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage(pm4WUser.language.SELECT_ACTION)
                .setPositiveButton(pm4WUser.language.REPORT, dialogClickListener)
                .setNegativeButton(pm4WUser.language.CANCEL, dialogClickListener)
                .show();
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new fetchDefaultedMonths().execute();
    }

    class fetchDefaultedMonths extends AsyncTask<String, String, String> {

        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new ProgressDialog(ShowUserActivity.this);
            pDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ShowUserActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... strings) {
            Caretakers caretaker = new Caretakers(ShowUserActivity.this);
            try {
                if (action.equals("followUp")) {
                    defaultedMonthsArrayList = caretaker.getDefaultedMonths(waterUserId);
                } else if (action.equals("viewPayments")) {
                    defaultedMonthsArrayList = caretaker.getPaidMonths(waterUserId);
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
                    monthsCount.setText(defaultedMonthsArrayList.size() + "");
                    if (defaultedMonthsArrayList.size() > 0) {
                        SimpleAdapter adapter = null;

                        if (action.equals("followUp")) {
                            adapter = new SimpleAdapter(
                                    ShowUserActivity.this, defaultedMonthsArrayList,
                                    R.layout.listitem_showdefaultedmonths, new String[]{Constants.DATE_TAG},
                                    new int[]{R.id.dateRenderer});

                        } else if (action.equals("viewPayments")) {
                            adapter = new SimpleAdapter(
                                    ShowUserActivity.this, defaultedMonthsArrayList,
                                    R.layout.listitem_userpayments, new String[]{Constants.DATE_TAG,
                                    Constants.TRANSACTION_COST_TAG},
                                    new int[]{R.id.transactionDate, R.id.transactionCost});
                        }
                        defaultedMonthsList.setAdapter(adapter);
                        adapter.notifyDataSetChanged();
                    } else {

                        String str = "";
                        if (action.equals("followUp")) {
                            str = pm4WUser.language.NO_DEFAULTED_MONTHS;
                        } else if (action.equals("viewPayments")) {
                            str = pm4WUser.language.NO_TRANSACTIONS;
                        }

                        new AlertDialog.Builder(ShowUserActivity.this)
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
