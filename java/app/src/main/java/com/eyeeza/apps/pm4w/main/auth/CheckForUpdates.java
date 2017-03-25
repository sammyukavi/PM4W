package com.eyeeza.apps.pm4w.main.auth;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.os.PowerManager;
import android.widget.TextView;
import android.widget.Toast;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.networking.JSONParser;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import org.apache.http.NameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

public class CheckForUpdates extends Activity {

    private String PATH = Environment.getExternalStorageDirectory() + "/" + Config.STORAGE_DRIECTORY_NAME + "/";//with backslash
    private String dialogMsg = null;
    private JSONParser jsonp = new JSONParser();
    private JSONObject json = null;
    private Pm4wUser pm4WUser;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();
    private ProgressDialog pDialog;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        pDialog = new ProgressDialog(this);
        pm4WUser = new Pm4wUser(this);
        setContentView(R.layout.activity_checkforupdates);
        TextView current_version = (TextView) findViewById(R.id.current_version);
        current_version.setText(Config.APP_VERSION);
        File folder = new File(PATH);
        if (!folder.exists()) {
            folder.mkdir();
        }

        new ExecuteCheckForUpdates().execute();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        // finish();
    }

    @Override
    protected void onResume() {
        super.onResume();
        // finish();
    }

    public boolean deleteDownloadedFile(String name) {
        File file = new File(PATH + name);
        return file.delete();
    }


    class ExecuteCheckForUpdates extends AsyncTask<String, String, String> {

        @Override
        protected void onPreExecute() {
            pDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            pDialog.setCancelable(false);
            pDialog.setIndeterminate(true);
            pDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... arg0) {
            json = jsonp.makeHttpRequest("?a=check-update", "POST", params);
            return null;
        }


        @Override
        protected void onPostExecute(String result) {
            pDialog.cancel();
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    if (json == null) {
                        new AlertDialog.Builder(CheckForUpdates.this)
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
                        JSONObject serverInfo = json.getJSONObject(Constants.SERVER_INFO_TAG);
                        int serverStatus = serverInfo.getInt(Constants.SERVER_STATUS_TAG);
                        JSONObject data = json.getJSONObject(Constants.DATA_TAG);
                        int requestStatus = data.getInt(Constants.REQUEST_STATUS_TAG);
                        JSONArray msgs = data.getJSONArray(Constants.MESSAGES_TAG);

                        String txt = "";
                        for (int index = 0; index < msgs.length(); index++) {
                            try {
                                txt += msgs.getString(index);
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                        if (serverStatus == Constants.SERVER_OFFLINE) {
                            new AlertDialog.Builder(CheckForUpdates.this)
                                    .setTitle(pm4WUser.language.OFFLINE_TITLE)
                                    .setMessage(pm4WUser.language.OFFLINE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                                @Override
                                public void onCancel(DialogInterface dialog) {
                                    finish();
                                }
                            }).show();
                        } else if (serverStatus == Constants.SERVER_UPGRADE) {
                            new AlertDialog.Builder(CheckForUpdates.this)
                                    .setTitle(pm4WUser.language.UPGRADE_TITLE)
                                    .setMessage(pm4WUser.language.UPGRADE_MSG)
                                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                                @Override
                                public void onCancel(DialogInterface dialog) {
                                    finish();
                                }
                            }).show();
                        } else {
                            if (requestStatus == Constants.SUCCESS_STATUS_CODE) {

                                JSONObject update = data.getJSONObject("update");
                                final String url = update.getString("url");
                                final String name = update.getString("name");

                                if (update.getDouble("version") > Double.parseDouble(Config.APP_VERSION)) {
                                    new AlertDialog.Builder(CheckForUpdates.this)
                                            .setTitle(pm4WUser.language.INFO)
                                            .setMessage(pm4WUser.language.UPDATE_AVAILABLE)
                                            .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            finish();
                                        }
                                    }).setPositiveButton(pm4WUser.language.UPDATE, new DialogInterface.OnClickListener() {

                                        @Override
                                        public void onClick(DialogInterface dialog, int which) {
                                            pDialog = new ProgressDialog(CheckForUpdates.this);
                                            pDialog.setMessage(pm4WUser.language.DOWNLOADING_UPDATE);
                                            pDialog.setIndeterminate(true);
                                            pDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
                                            pDialog.setCancelable(true);

                                            final DownloadTask downloadTask = new DownloadTask(CheckForUpdates.this);

                                            downloadTask.execute(url, name);

                                            pDialog.setOnCancelListener(new OnCancelListener() {
                                                @Override
                                                public void onCancel(DialogInterface dialog) {
                                                    new AlertDialog.Builder(CheckForUpdates.this)
                                                            .setTitle(pm4WUser.language.INFO)
                                                            .setMessage(pm4WUser.language.CONFIRM_CANCEL_DOWNLOAD)
                                                            .setIcon(android.R.drawable.ic_dialog_alert).setPositiveButton(pm4WUser.language.CANCEL_DOWNLOAD, new DialogInterface.OnClickListener() {

                                                        @Override
                                                        public void onClick(DialogInterface dialog, int which) {
                                                            downloadTask.cancel(true);
                                                            deleteDownloadedFile(name);
                                                            finish();

                                                        }
                                                    }).setNegativeButton(pm4WUser.language.FINISH_DOWNLOADING, new DialogInterface.OnClickListener() {

                                                        @Override
                                                        public void onClick(DialogInterface dialog, int which) {
                                                            pDialog.show();

                                                        }
                                                    }).setOnCancelListener(new OnCancelListener() {

                                                        @Override
                                                        public void onCancel(DialogInterface dialog) {
                                                            pDialog.show();

                                                        }
                                                    }).show();

                                                }
                                            });
                                        }
                                    }).show();

                                } else {
                                    new AlertDialog.Builder(CheckForUpdates.this)
                                            .setTitle(pm4WUser.language.INFO)
                                            .setMessage(pm4WUser.language.NO_UPDATE_AVAILABLE)
                                            .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new OnCancelListener() {

                                        @Override
                                        public void onCancel(DialogInterface dialog) {
                                            finish();
                                        }
                                    }).show();
                                }

                            } else {
                                new AlertDialog.Builder(CheckForUpdates.this)
                                        .setTitle(pm4WUser.language.INFO)
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
                        e.printStackTrace();
                        new AlertDialog.Builder(CheckForUpdates.this)
                                .setTitle(pm4WUser.language.ERROR)
                                .setMessage(pm4WUser.language.ERROR_READING_FROM_SERVER)
                                //.setMessage(e.toString())
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

    class DownloadTask extends AsyncTask<String, Integer, String> {

        private Context context;
        private PowerManager.WakeLock mWakeLock;

        public DownloadTask(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            PowerManager pm = (PowerManager) context.getSystemService(Context.POWER_SERVICE);
            mWakeLock = pm.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK,
                    getClass().getName());
            mWakeLock.acquire();
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... params) {
            String name = params[1];
            InputStream input = null;
            OutputStream output = null;
            HttpURLConnection connection = null;
            try {
                URL url = new URL(params[0]);
                connection = (HttpURLConnection) url.openConnection();
                connection.connect();
                if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                    return "Server returned HTTP " + connection.getResponseCode()
                            + " " + connection.getResponseMessage();
                }

                int fileLength = connection.getContentLength();

                input = connection.getInputStream();
                output = new FileOutputStream(PATH + name);

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
                e.printStackTrace();
                dialogMsg = e.toString();
                return null;
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
            for (int i = 0; i < myFiles.length; i++) {
                File myFile = new File(pm4w_directory, myFiles[i]);
                if (!myFile.getName().equals(name)) {
                    myFile.delete();
                }
            }

            return name;
        }

        @Override
        protected void onProgressUpdate(Integer... progress) {
            super.onProgressUpdate(progress);
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
                Toast.makeText(context, "Download error: " + dialogMsg, Toast.LENGTH_LONG).show();
                dialogMsg = null;
                finish();
            } else {
                Intent intent = new Intent(Intent.ACTION_VIEW);
                intent.setDataAndType(Uri.fromFile(new File(PATH + name)), "application/vnd.android.package-archive");
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivityForResult(intent, Constants.SUCCESS_STATUS_CODE);
            }
        }

    }
}