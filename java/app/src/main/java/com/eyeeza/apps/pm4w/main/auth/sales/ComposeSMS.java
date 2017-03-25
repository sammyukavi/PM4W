package com.eyeeza.apps.pm4w.main.auth.sales;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
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

public class ComposeSMS extends Activity {

    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private Pm4wUser pm4WUser;
    private long userId;
    private JSONObject json;
    private JSONParser jsonp = new JSONParser();
    private TextView smsMessage;
    private TextView recipient;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_composesms);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        smsMessage = (TextView) findViewById(R.id.smsMessage);
        recipient = (TextView) findViewById(R.id.recipient);

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.COMPOSE_SMS);

        TextView recipientTextview = (TextView) findViewById(R.id.recipientTextview);
        recipientTextview.setText(pm4WUser.language.RECEPIENTS_NAME);

        TextView smsMessageTextview = (TextView) findViewById(R.id.smsMessageTextview);
        smsMessageTextview.setText(pm4WUser.language.TYPE_THE_MESSAGE);

        Button sendSMS = (Button) findViewById(R.id.sendSMS);
        sendSMS.setText(pm4WUser.language.SEND_SMS);

        Bundle extras = getIntent().getExtras();
        userId = extras.getLong(Constants.WATER_USER_ID_TAG);

        WaterUsers waterUser = new WaterUsers(this);
        waterUser.getWaterUser(userId);

        recipient.setText(waterUser.getFullname() + " <" + waterUser.getpNumber() + ">");

        if (waterUser.getpNumber().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.INFO)
                    .setMessage(pm4WUser.language.WATER_USER_NO_PHONE_NUMBER)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {
                    finish();
                }
            }).show();
            return;
        }


    }


    public void sendSMS(View view) {

        if (!NetworkFunctions.isOnline(this)) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                    .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {
                    finish();
                }
            }).show();
            return;
        }

        if (smsMessage.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.MESSAGE_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (recipient.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.NAME_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        params.add(new BasicNameValuePair(Constants.USERNAME_TAG, pm4WUser.getUsername()));
        params.add(new BasicNameValuePair(Constants.AUTH_CODE_TAG, pm4WUser.getAuthCode()));
        params.add(new BasicNameValuePair(Constants.AUTH_KEY_TAG, pm4WUser.getAuthKey()));
        params.add(new BasicNameValuePair(Constants.APP_VERSION_TAG, Config.APP_VERSION));
        params.add(new BasicNameValuePair(Constants.IMEI_TAG, pm4WUser.getDeviceImei()));
        params.add(new BasicNameValuePair(Constants.LAST_KNOWN_LOCATION_TAG, pm4WUser.getLastKnownLocation()));
        params.add(new BasicNameValuePair(Constants.APP_PREFERRED_LANGUAGE_TAG, pm4WUser.getAppPreferredLanguage()));


        params.add(new BasicNameValuePair("system_users[]", ""));
        params.add(new BasicNameValuePair("water_users[]", String.valueOf(userId)));
        params.add(new BasicNameValuePair("scheduled", "now"));
        params.add(new BasicNameValuePair("msg_content", smsMessage.getText().toString()));


        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_MINISTATEMENT);

        new PostToServer().execute();
    }

    class PostToServer extends AsyncTask<String, String, String> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new ProgressDialog(ComposeSMS.this);
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setCancelable(false);
            pDialog.setIndeterminate(true);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... arg0) {
            json = jsonp.makeHttpRequest("?a=send-sms-message", "POST", params);
            return null;
        }

        @Override
        protected void onPostExecute(String result) {
            pDialog.cancel();
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    try {
                        JSONObject server_info = json.getJSONObject(Constants.SERVER_INFO_TAG);
                        int server_status = server_info.getInt(Constants.SERVER_STATUS_TAG);
                        JSONObject data = json.getJSONObject(Constants.DATA_TAG);
                        int request_status = data.getInt(Constants.REQUEST_STATUS_TAG);
                        JSONArray msgs = data.getJSONArray(Constants.MESSAGES_TAG);

                        if (server_status == Constants.SERVER_OFFLINE) {
                            new AlertDialog.Builder(ComposeSMS.this)
                                    .setTitle(pm4WUser.language.OFFLINE_TITLE)
                                    .setMessage(pm4WUser.language.OFFLINE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_alert)
                                    .show();
                        } else if (server_status == Constants.SERVER_UPGRADE) {
                            new AlertDialog.Builder(ComposeSMS.this)
                                    .setTitle(pm4WUser.language.UPGRADE_TITLE)
                                    .setMessage(pm4WUser.language.UPGRADE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_alert)
                                    .show();
                        } else {

                            String txt = "";
                            for (int index = 0; index < msgs.length(); index++) {
                                txt += msgs.getString(index);
                            }
                            if (request_status == Constants.SUCCESS_STATUS_CODE) {
                                new AlertDialog.Builder(ComposeSMS.this)
                                        .setTitle(pm4WUser.language.INFO)
                                        .setMessage(txt)
                                        .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        finish();
                                    }
                                }).show();

                            } else {
                                new AlertDialog.Builder(ComposeSMS.this)
                                        .setTitle(pm4WUser.language.ERROR)
                                        .setMessage(txt)
                                        .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        //finish();
                                    }
                                }).show();
                            }
                        }


                    } catch (JSONException e) {
                        new AlertDialog.Builder(ComposeSMS.this)
                                .setTitle(pm4WUser.language.ERROR)
                                .setMessage(pm4WUser.language.ERROR_READING_FROM_SERVER)
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                finish();
                            }
                        }).show();
                        e.printStackTrace();
                    }
                }
            });

        }

    }
}