package com.eyeeza.apps.pm4w.main.auth.savings;

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
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import java.util.ArrayList;
import java.util.HashMap;

public class ListWaterSourcesActivity extends Activity {
    private ProgressDialog pDialog;
    private Pm4wUser pm4WUser;
    private ArrayList<HashMap<String, String>> waterSourcesArrayList = new ArrayList<HashMap<String, String>>();
    private ListView waterSourcesListView;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listwatersources);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.WATER_SOURCES);


        waterSourcesListView = (ListView) findViewById(R.id.watersourcesList);
        waterSourcesListView.setOnItemClickListener(new OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                TextView waterSourceIdRenderer = (TextView) view.findViewById(R.id.waterSourceIdRenderer);
                Intent showWaterSourceSavingsActivity = new Intent(getApplicationContext(), ShowWaterSourceSavingsActivity.class);
                showWaterSourceSavingsActivity.putExtra(Constants.ID_WATER_SOURCE_TAG, waterSourceIdRenderer.getText());
                startActivity(showWaterSourceSavingsActivity);
            }

        });

        new FetchWaterSources().execute();

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchWaterSources().execute();
    }

    class FetchWaterSources extends AsyncTask<String, String, String> {

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(ListWaterSourcesActivity.this);
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListWaterSourcesActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            Treasurers treasurer = new Treasurers(ListWaterSourcesActivity.this);
            waterSourcesArrayList = treasurer.getAllWaterSources();
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            pDialog.dismiss();

            if (waterSourcesArrayList.size() > 0) {
                SimpleAdapter adapter = new SimpleAdapter(
                        ListWaterSourcesActivity.this, waterSourcesArrayList,
                        R.layout.listitem_watersources, new String[]{Constants.ID_WATER_SOURCE_TAG,
                        Constants.WATER_SOURCE_NAME_TAG},
                        new int[]{R.id.waterSourceIdRenderer, R.id.waterSourceNameRenderer});
                waterSourcesListView.setAdapter(adapter);
            } else {
                new AlertDialog.Builder(ListWaterSourcesActivity.this)
                        .setTitle(pm4WUser.language.INFO)
                        .setMessage(pm4WUser.language.NO_TRANSACTIONS)
                        .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                    @Override
                    public void onCancel(DialogInterface dialog) {
                        finish();
                    }
                }).show();
            }
        }

    }

}
