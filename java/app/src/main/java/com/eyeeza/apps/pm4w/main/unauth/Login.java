package com.eyeeza.apps.pm4w.main.unauth;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Expenditures;
import com.eyeeza.apps.pm4w.dbtables.RepairTypes;
import com.eyeeza.apps.pm4w.dbtables.Sales;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.dbtables.Users;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.main.auth.ChooseLanguage;
import com.eyeeza.apps.pm4w.networking.JSONParser;
import com.eyeeza.apps.pm4w.networking.NetworkFunctions;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Sammy N Ukavi Jr on 4/13/2016.
 */
public class Login extends Activity {

    private JSONObject json = null;
    private Pm4wUser pm4WUser;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private JSONObject data;
    private boolean progressSuccessful = false;
    private TextView usernameTextview;
    private TextView passwordTextview;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());

        if (pm4WUser.getIdu() != 0) {
            Intent chooseLanguage = new Intent(getApplicationContext(), ChooseLanguage.class);
            startActivity(chooseLanguage);
        }

        usernameTextview = (TextView) findViewById(R.id.username);
        passwordTextview = (TextView) findViewById(R.id.password);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.login, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == R.id.action_about) {
            Intent about = new Intent(getApplicationContext(), About.class);
            startActivity(about);
        } else if (item.getItemId() == R.id.action_help) {
            Intent help = new Intent(getApplicationContext(), Help.class);
            startActivity(help);
        }
        return false;
    }

    public void showPasswordRecovery(View v) {
        Intent password_recovery = new Intent(getApplicationContext(), RecoverPassword.class);
        startActivity(password_recovery);
    }

    public void login(View v) {


        if (usernameTextview.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(Login.this)
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


        if (passwordTextview.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(Login.this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.PASSWORD_REQUIRED)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            })
                    .show();
            return;
        }

        if (!NetworkFunctions.isOnline(this)) {
            new AlertDialog.Builder(Login.this)
                    .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                    .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                    .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            }).show();
            return;
        }

        params.clear();
        params.add(new BasicNameValuePair(Constants.USERNAME_TAG, usernameTextview.getText().toString().trim()));
        params.add(new BasicNameValuePair(Constants.PASSWORD_TAG, passwordTextview.getText().toString().trim()));
        //params.add(new BasicNameValuePair(Constants.AUTH_CODE_TAG, pm4WUser.getAuthCode()));
        // params.add(new BasicNameValuePair(Constants.AUTH_KEY_TAG, pm4WUser.getAuthKey()));
        params.add(new BasicNameValuePair(Constants.APP_VERSION_TAG, Config.APP_VERSION));
        params.add(new BasicNameValuePair(Constants.IMEI_TAG, pm4WUser.getDeviceImei()));
        params.add(new BasicNameValuePair(Constants.LAST_KNOWN_LOCATION_TAG, pm4WUser.getLastKnownLocation()));
        params.add(new BasicNameValuePair(Constants.APP_PREFERRED_LANGUAGE_TAG, pm4WUser.getAppPreferredLanguage()));

        new ExecuteLogin().execute();

    }

    class ExecuteLogin extends AsyncTask<String, String, String> {
        private ProgressDialog progressDialog;
        private JSONParser jsonParser = new JSONParser();

        @Override
        protected void onPreExecute() {
            progressDialog = new ProgressDialog(Login.this) {
                @Override
                public void onBackPressed() {
                    pm4WUser.setUpDB();
                    progressDialog.cancel();
                }
            };
            progressDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            progressDialog.setMessage(pm4WUser.language.LOGGING_IN);
            progressDialog.setCancelable(false);
            progressDialog.setIndeterminate(true);
            progressDialog.show();
        }

        @Override
        protected String doInBackground(String... arg0) {
            json = jsonParser.makeHttpRequest("?a=login", "POST", params);
            return null;
        }


        @Override
        protected void onPostExecute(String result) {
            try {
                progressDialog.cancel();
            } catch (Exception e) {
                e.printStackTrace();
            }
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    if (json == null) {
                        new AlertDialog.Builder(Login.this)
                                .setTitle(pm4WUser.language.INFO)
                                .setMessage(pm4WUser.language.SERVER_NOT_ACCESSIBLE)
                                .setIcon(android.R.drawable.ic_dialog_info)
                                .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {

                                    }
                                })
                                .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                    @Override
                                    public void onCancel(DialogInterface dialog) {

                                    }
                                }).show();
                        return;
                    }


                    try {
                        JSONObject server_info = json.getJSONObject(Constants.SERVER_INFO_TAG);
                        int server_status = server_info.getInt(Constants.SERVER_STATUS_TAG);
                        data = json.getJSONObject(Constants.DATA_TAG);
                        int request_status = data.getInt(Constants.REQUEST_STATUS_TAG);
                        JSONArray mMsgs = data.getJSONArray(Constants.MESSAGES_TAG);

                        String txt = "";
                        for (int index = 0; index < mMsgs.length(); index++) {
                            try {
                                txt += mMsgs.getString(index);
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                        if (server_status == Constants.SERVER_OFFLINE) {
                            new AlertDialog.Builder(Login.this)
                                    .setTitle(pm4WUser.language.OFFLINE_TITLE)
                                    .setMessage(pm4WUser.language.OFFLINE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {

                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {

                                        }
                                    })
                                    .show();
                            return;
                        }

                        if (server_status == Constants.SERVER_UPGRADE) {
                            new AlertDialog.Builder(Login.this)
                                    .setTitle(pm4WUser.language.UPGRADE_TITLE)
                                    .setMessage(pm4WUser.language.UPGRADE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {

                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {

                                        }
                                    }).show();
                            return;
                        }
                        pm4WUser.logEvent(EventLogs.EVENT_ATTEMPTED_LOGIN);
                        if (request_status == Constants.SUCCESS_STATUS_CODE) {

                            Utils.saveStringToFile(Login.this, server_info.getString(Constants.SERVER_TIME), Constants.SERVER_TIME + ".json");

                            pm4WUser.setUpDB();

                            new SetUpDb().execute();

                        } else {
                            new AlertDialog.Builder(Login.this)
                                    .setTitle(pm4WUser.language.ERROR)
                                    .setMessage(txt)
                                    .setIcon(android.R.drawable.ic_dialog_alert)
                                    .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialogInterface, int i) {

                                        }
                                    })
                                    .setOnCancelListener(new DialogInterface.OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            //finish();
                                        }
                                    }).show();
                        }

                    } catch (JSONException e) {
                        e.printStackTrace();
                        new AlertDialog.Builder(Login.this)
                                .setTitle(pm4WUser.language.ERROR)
                                .setMessage(pm4WUser.language.ERROR_READING_FROM_SERVER)
                                .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {

                                    }
                                })
                                //.setMessage(e.toString())
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                //finish();
                            }
                        }).show();
                    }
                }
            });

        }

    }

    class SetUpDb extends AsyncTask<String, Integer, String> {
        private ProgressDialog progressDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            progressDialog = new ProgressDialog(Login.this) {
                @Override
                public void onBackPressed() {
                    pm4WUser.setUpDB();
                    progressDialog.cancel();
                }
            };
            progressDialog.setTitle(pm4WUser.language.SETTING_UP_ACCOUNT);
            progressDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
            progressDialog.setCancelable(false);
            progressDialog.show();
            progressDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
                @Override
                public void onCancel(DialogInterface dialogInterface) {
                    cancel(true);
                }
            });
        }

        @Override
        protected String doInBackground(String... strings) {
            progressDialog.setIndeterminate(false);

            try {
                JSONArray attendingToArray = data.getJSONArray(Constants.ATTENDING_TO_TAG);
                JSONArray collectingFromArray = data.getJSONArray(Constants.COLLECTING_FROM_TAG);
                JSONArray expendituresArray = data.getJSONArray(Constants.EXPENDITURES_TAG);
                JSONArray repairTypesArray = data.getJSONArray(Constants.REPAIR_TYPES_TAG);
                JSONArray salesArray = data.getJSONArray(Constants.SALES_TAG);
                JSONArray usersArray = data.getJSONArray(Constants.USERS_TAG);
                JSONArray waterUsersArray = data.getJSONArray(Constants.WATER_USERS_TAG);

                int total_queries = 0;
                int counter = 0;
                total_queries += attendingToArray.length();
                total_queries += collectingFromArray.length();
                total_queries += expendituresArray.length();
                total_queries += repairTypesArray.length();
                total_queries += salesArray.length();

                Caretakers caretaker = new Caretakers(Login.this);
                for (int index = 0; index < attendingToArray.length(); index++) {
                    caretaker.saveWaterSource(attendingToArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                Treasurers treasurer = new Treasurers(Login.this);
                for (int index = 0; index < collectingFromArray.length(); index++) {
                    treasurer.saveWaterSource(collectingFromArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                Expenditures expenditures = new Expenditures(Login.this);

                for (int index = 0; index < expendituresArray.length(); index++) {
                    expenditures.saveExpenditure(expendituresArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                RepairTypes repairTypes = new RepairTypes(Login.this);
                for (int index = 0; index < repairTypesArray.length(); index++) {
                    repairTypes.saveRepairType(repairTypesArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                Sales sales = new Sales(Login.this);
                for (int index = 0; index < salesArray.length(); index++) {
                    sales.saveSale(salesArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                Users users = new Users(Login.this);
                for (int index = 0; index < usersArray.length(); index++) {
                    users.saveUser(usersArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                WaterUsers waterUsers = new WaterUsers(Login.this);
                for (int index = 0; index < waterUsersArray.length(); index++) {
                    waterUsers.saveWaterUser(waterUsersArray.getJSONObject(index));
                    counter += 1;
                    publishProgress((int) Math.floor(pm4WUser.getPercentageComplete(total_queries, counter)));
                }

                JSONObject account = data.getJSONObject(Constants.USER_ACCOUNT_TAG);
                JSONObject user_permissions = data.getJSONObject(Constants.USER_PERMISSIONS_TAG);

                pm4WUser.saveSessionAccount(account);

                pm4WUser.savePermissions(user_permissions);

                pm4WUser.logEvent(EventLogs.EVENT_SETTING_UP_DATABASE_USING_ONLINE_COPY_COMPLETE);
                progressSuccessful = true;
            } catch (JSONException e) {
                e.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(String s) {
            super.onPostExecute(s);
            progressDialog.cancel();
            String err_msg = "";

            if (progressSuccessful) {
                pm4WUser.getSesssionAccount(Login.this);
                Intent chooseLanguage = new Intent(getApplicationContext(), ChooseLanguage.class);
                startActivity(chooseLanguage);
                Login.this.finish();
            } else {
                err_msg = pm4WUser.language.ERROR_READING_FROM_SERVER;
            }

            if (err_msg.length() > 0) {
                new AlertDialog.Builder(Login.this)
                        .setTitle(pm4WUser.language.ERROR)
                        .setMessage(err_msg)
                        .setIcon(android.R.drawable.ic_dialog_info)
                        .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialogInterface, int i) {

                            }
                        })
                        .setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {

                            }
                        }).show();
            }
            return;
        }

        @Override
        protected void onProgressUpdate(Integer... values) {
            super.onProgressUpdate(values);
            progressDialog.setIndeterminate(false);
            progressDialog.setMax(100);
            progressDialog.setProgress(values[0]);
        }

        @Override
        protected void onCancelled() {
            super.onCancelled();
        }
    }


}
