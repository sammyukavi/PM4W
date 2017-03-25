package com.eyeeza.apps.pm4w.main.auth.expenditures;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Expenditures;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Sammy N Ukavi Jr on 5/2/2016.
 */
public class ListWaterSourceExpendituresActivity extends Activity {

    private ArrayList<HashMap<String, String>> expensesArrayList;
    private TextView expensesCount;
    private Pm4wUser pm4WUser;
    private Expenditures expenditures;
    private String waterSourceId;
    private ListView expendituresListView;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listwatersourceexpenses);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.EXPENDITURES);

        expensesCount = (TextView) findViewById(R.id.expensesCount);


        expenditures = new Expenditures(ListWaterSourceExpendituresActivity.this);


        expendituresListView = (ListView) findViewById(R.id.expendituresList);
        expendituresListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                TextView expenditureIdRenderer = (TextView) view.findViewById(R.id.expenditureIdRenderer);
                Intent showExpendituresActivity = new Intent(getApplicationContext(), ShowExpenditure.class);
                showExpendituresActivity.putExtra(Constants.ID_EXPENDITURE_TAG, expenditureIdRenderer.getText());
                startActivity(showExpendituresActivity);
            }

        });

        waterSourceId = getIntent().getStringExtra(Constants.ID_WATER_SOURCE_TAG);

        pm4WUser.logEvent(EventLogs.EVENT_LISTED_EXPENSES, Long.parseLong(waterSourceId));

        new FetchExpenditures().execute();

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchExpenditures().execute();
    }

    class FetchExpenditures extends AsyncTask<String, String, String> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(ListWaterSourceExpendituresActivity.this);
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListWaterSourceExpendituresActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            try {
                expensesArrayList = expenditures.fetchExpenditures(waterSourceId);
            } catch (Exception ex) {
                ex.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String s) {
            pDialog.dismiss();

            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    expensesCount.setText(expensesArrayList.size() + "");
                    if (expensesArrayList.size() > 0) {

                        SimpleAdapter adapter = new SimpleAdapter(
                                ListWaterSourceExpendituresActivity.this, expensesArrayList,
                                R.layout.listitem_expenses, new String[]{Constants.ID_EXPENDITURE_TAG,
                                Constants.EXPENDITURE_DATE_TAG, Constants.BENEFACTOR_TAG, Constants.EXPENDITURE_COST_TAG},
                                new int[]{R.id.expenditureIdRenderer, R.id.expenditureDate, R.id.benefactor, R.id.expenditureCost});

                        expendituresListView.setAdapter(adapter);

                    } else {
                        new AlertDialog.Builder(ListWaterSourceExpendituresActivity.this)
                                .setMessage(pm4WUser.language.NO_EXPENSES)
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
