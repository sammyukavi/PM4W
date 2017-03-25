package com.eyeeza.apps.pm4w.main.auth.accounts;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.os.AsyncTask;
import android.os.Bundle;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.networking.JSONParser;
import com.eyeeza.apps.pm4w.networking.NetworkFunctions;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;


public class ShowAccountBalanceActivity extends Activity {

    private JSONParser jsonp = new JSONParser();
    private JSONObject json = null;
    private Pm4wUser pm4WUser;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_balance);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.BALANCE);

        TextView waterSourceNameTextView = (TextView) findViewById(R.id.waterSourceNameTextView);
        waterSourceNameTextView.setText(pm4WUser.language.WATER_SOURCE_NAME);

        TextView account_name_TextView = (TextView) findViewById(R.id.nameTextView);
        account_name_TextView.setText(pm4WUser.language.NAME);

        TextView available_balance_Textview = (TextView) findViewById(R.id.availableBalanceTextview);
        available_balance_Textview.setText(pm4WUser.language.AVAILABLE_BALANCE);

        if (!NetworkFunctions.isOnline(this)) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                    .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                    .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                @Override
                public void onCancel(DialogInterface dialog) {
                    finish();
                }
            }).show();
        }

        params.add(new BasicNameValuePair(Constants.USERNAME_TAG, pm4WUser.getUsername()));
        params.add(new BasicNameValuePair(Constants.AUTH_CODE_TAG, pm4WUser.getAuthCode()));
        params.add(new BasicNameValuePair(Constants.AUTH_KEY_TAG, pm4WUser.getAuthKey()));
        params.add(new BasicNameValuePair(Constants.APP_VERSION_TAG, Config.APP_VERSION));
        params.add(new BasicNameValuePair(Constants.IMEI_TAG, pm4WUser.getDeviceImei()));
        params.add(new BasicNameValuePair(Constants.LAST_KNOWN_LOCATION_TAG, pm4WUser.getLastKnownLocation()));
        params.add(new BasicNameValuePair(Constants.APP_PREFERRED_LANGUAGE_TAG, pm4WUser.getAppPreferredLanguage()));

        params.add(new BasicNameValuePair(Constants.ID_WATER_SOURCE_TAG, getIntent().getStringExtra(Constants.ID_WATER_SOURCE_TAG)));


        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_ACCOUNT_BALANCE);

        new FetchAccountBalance().execute();

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchAccountBalance().execute();
    }

    class FetchAccountBalance extends AsyncTask<String, String, String> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new ProgressDialog(ShowAccountBalanceActivity.this) {
                @Override
                public void onBackPressed() {
                    pDialog.cancel();
                    ShowAccountBalanceActivity.this.finish();
                }
            };
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.show();
            pDialog.setOnCancelListener(new OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialogInterface) {
                    cancel(true);
                }
            });
        }

        @Override
        protected String doInBackground(String... arg0) {
            json = jsonp.makeHttpRequest("?a=fetch-account-balance", "POST", params);
            return null;
        }


        @Override
        protected void onPostExecute(String result) {
            pDialog.cancel();
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    if (json == null) {
                        new AlertDialog.Builder(ShowAccountBalanceActivity.this)
                                .setTitle(pm4WUser.language.INFO)
                                .setMessage(pm4WUser.language.SERVER_NOT_ACCESSIBLE)
                                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                finish();
                            }
                        }).show();
                        return;
                    }
                    try {
                        JSONObject server_info = json.getJSONObject(Constants.SERVER_INFO_TAG);
                        int server_status = server_info.getInt(Constants.SERVER_STATUS_TAG);
                        JSONObject data = json.getJSONObject(Constants.DATA_TAG);
                        int request_status = data.getInt(Constants.REQUEST_STATUS_TAG);
                        JSONArray msgs = data.getJSONArray(Constants.MESSAGES_TAG);

                        String txt = "";
                        for (int index = 0; index < msgs.length(); index++) {
                            try {
                                txt += msgs.getString(index);
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                        if (server_status == Constants.SERVER_OFFLINE) {
                            new AlertDialog.Builder(ShowAccountBalanceActivity.this)
                                    .setTitle(pm4WUser.language.OFFLINE_TITLE)
                                    .setMessage(pm4WUser.language.OFFLINE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                                @Override
                                public void onCancel(DialogInterface dialog) {
                                    finish();
                                }
                            })
                                    .show();
                        } else if (server_status == Constants.SERVER_UPGRADE) {
                            new AlertDialog.Builder(ShowAccountBalanceActivity.this)
                                    .setTitle(pm4WUser.language.UPGRADE_TITLE)
                                    .setMessage(pm4WUser.language.UPGRADE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                                @Override
                                public void onCancel(DialogInterface dialog) {
                                    finish();
                                }
                            }).show();
                        } else {

                            if (request_status == Constants.SUCCESS_STATUS_CODE) {

                                TextView waterSourceName = (TextView) findViewById(R.id.waterSourceName);
                                waterSourceName.setText(data.getString(Constants.WATER_SOURCE_NAME_TAG));

                                TextView name = (TextView) findViewById(R.id.name);
                                name.setText(data.getString("account_name"));

                                TextView availableBalance = (TextView) findViewById(R.id.availableBalance);
                                availableBalance.setText(data.getString("account_balance"));

                            } else {
                                new AlertDialog.Builder(ShowAccountBalanceActivity.this)
                                        .setTitle(pm4WUser.language.ERROR)
                                        .setMessage(txt)
                                        .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        finish();
                                    }
                                }).show();
                            }
                        }


                    } catch (JSONException e) {
                        new AlertDialog.Builder(ShowAccountBalanceActivity.this)
                                .setTitle(pm4WUser.language.ERROR)
                                //.setMessage(pm4WUser.language.ERROR_READING_FROM_SERVER)
                                .setMessage(e.toString())
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

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
