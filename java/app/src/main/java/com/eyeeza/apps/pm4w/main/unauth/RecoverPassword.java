package com.eyeeza.apps.pm4w.main.unauth;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
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


public class RecoverPassword extends Activity {

    Intent intent;
    private String dialogMsg = null;
    private JSONParser jsonp = new JSONParser();
    private JSONObject json = null;
    private String api_url = "?a=recover-password";
    private Pm4wUser pm4WUser;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private ProgressDialog pDialog;
    private JSONObject data;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_recoverpassword);
        pm4WUser = new Pm4wUser(this);
        pDialog = new ProgressDialog(RecoverPassword.this);
        intent = new Intent(getApplicationContext(), Login.class);
    }

    public void recoverPassword(View v) {
        TextView usernameTextview = (TextView) findViewById(R.id.username);
        if (usernameTextview.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.USERNAME_REQUIRED)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i) {

                        }
                    })
                    .show();
            return;
        }

        if (!NetworkFunctions.isOnline(this)) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                    .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            })
                    .show();
            return;
        }

        pm4WUser.logEvent(EventLogs.EVENT_ATTEMPTED_PASSWORD_RECOVERY, usernameTextview.getText().toString().trim());
        params.clear();
        params.add(new BasicNameValuePair(Constants.USERNAME_TAG, usernameTextview.getText().toString().trim()));


        dialogMsg = pm4WUser.language.RECOVERING_PASSWORD;

        new ExecuteLogin().execute();

    }

    @Override
    public void onBackPressed() {
        Intent i = new Intent(getApplicationContext(), Login.class);
        startActivity(i);
        this.finish();
    }

    class ExecuteLogin extends AsyncTask<String, String, String> {

        @Override
        protected void onPreExecute() {
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(dialogMsg);
            pDialog.setCancelable(false);
            pDialog.setIndeterminate(true);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... arg0) {
            json = jsonp.makeHttpRequest(api_url, "POST", params);
            return null;
        }


        @Override
        protected void onPostExecute(String result) {
            try {
                pDialog.cancel();
            } catch (Exception e) {
                e.printStackTrace();
            }
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    if (json == null) {
                        new AlertDialog.Builder(RecoverPassword.this)
                                .setTitle(pm4WUser.language.INFO)
                                .setMessage(pm4WUser.language.SERVER_NOT_ACCESSIBLE)
                                .setIcon(android.R.drawable.ic_dialog_info)
                                .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                        startActivity(intent);
                                        finish();
                                    }
                                })
                                .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        startActivity(intent);
                                        finish();
                                    }
                                }).show();
                        return;
                    }
                    try {
                        JSONObject server_info = json.getJSONObject(Constants.SERVER_INFO_TAG);
                        int server_status = server_info.getInt(Constants.SERVER_STATUS_TAG);
                        data = json.getJSONObject(Constants.DATA_TAG);
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
                            new AlertDialog.Builder(RecoverPassword.this)
                                    .setTitle(pm4WUser.language.OFFLINE_TITLE)
                                    .setMessage(pm4WUser.language.OFFLINE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    })
                                    .show();
                        } else if (server_status == Constants.SERVER_UPGRADE) {
                            new AlertDialog.Builder(RecoverPassword.this)
                                    .setTitle(pm4WUser.language.UPGRADE_TITLE)
                                    .setMessage(pm4WUser.language.UPGRADE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    }).show();
                        } else {
                            new AlertDialog.Builder(RecoverPassword.this)
                                    .setTitle(pm4WUser.language.ERROR)
                                    .setMessage(txt)
                                    .setIcon(android.R.drawable.ic_dialog_info)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            startActivity(intent);
                                            finish();
                                        }
                                    }).show();

                        }


                    } catch (JSONException e) {
                        e.printStackTrace();
                        new AlertDialog.Builder(RecoverPassword.this)
                                .setTitle(pm4WUser.language.ERROR)
                                .setMessage(pm4WUser.language.ERROR_READING_FROM_SERVER)
                                .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                        startActivity(intent);
                                        finish();
                                    }
                                })
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                startActivity(intent);
                                finish();
                            }
                        }).show();
                    }
                }
            });
        }

    }
}