package com.eyeeza.apps.pm4w.main.auth.sales;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.SimpleAdapter;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Sales;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class AddDailySaleActivity extends Activity {


    private Caretakers caretaker;
    private Pm4wUser pm4WUser;
    private Spinner waterSourceSpinner;
    private TextView amountSold, amountSoldTextview, waterSourcePromptTextview;
    private int waterSourceId = 0;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_adddailysale);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.ADD_DAILY_SALE);

        waterSourcePromptTextview = (TextView) findViewById(R.id.waterSourcePromptTextview);
        waterSourcePromptTextview.setText(pm4WUser.language.WATER_SOURCE_PROMPT);

        amountSoldTextview = (TextView) findViewById(R.id.amountSoldTextview);
        amountSoldTextview.setText(pm4WUser.language.AMOUNT_SOLD);


        amountSold = (TextView) findViewById(R.id.amountSold);

        caretaker = new Caretakers(this);
        ArrayList<HashMap<String, String>> WaterSourcesList = new ArrayList<HashMap<String, String>>();
        WaterSourcesList = caretaker.getAllWaterSources();

        SimpleAdapter adapter = new SimpleAdapter(
                this, WaterSourcesList,
                R.layout.listitem_watersources, new String[]{Constants.ID_WATER_SOURCE_TAG,
                Constants.WATER_SOURCE_NAME_TAG, Constants.WATER_SOURCE_MONTHLY_CHARGES_TAG},
                new int[]{R.id.waterSourceIdRenderer, R.id.waterSourceNameRenderer});


        waterSourceSpinner = (Spinner) findViewById(R.id.waterSourceSpinner);
        waterSourceSpinner.setPrompt(pm4WUser.language.WATER_SOURCE_PROMPT);
        waterSourceSpinner.setAdapter(adapter);
        waterSourceSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {

            @Override
            public void onItemSelected(AdapterView<?> arg0, View view, int arg2, long arg3) {
                TextView id_water_source_text_view = (TextView) view.findViewById(R.id.waterSourceIdRenderer);
                waterSourceId = Integer.parseInt(id_water_source_text_view.getText().toString());
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {
                waterSourceId = 0;
            }
        });

        Button add_sale_button = (Button) findViewById(R.id.addSaleButton);
        add_sale_button.setText(pm4WUser.language.SAVE);


    }

    public void addSale(View v) {
        waterSourceSpinner = (Spinner) findViewById(R.id.waterSourceSpinner);
        amountSold = (TextView) findViewById(R.id.amountSold);
        if (amountSold.getText().toString().trim().length() == 0 || Double.parseDouble(amountSold.getText().toString()) == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.AMOUNT_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (waterSourceSpinner.getSelectedItem() == null) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.WATERSOURCE_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        caretaker.getWaterSource(waterSourceId);

        Sales sale = new Sales(this);
        sale.setWaterSourceID(waterSourceId);
        sale.setSoldBy(pm4WUser.getIdu());
        sale.setSoldTo(0);
        sale.setSaleUgx(Double.parseDouble(amountSold.getText().toString()));
        sale.setSaleDate(Utils.getMySQLDate());
        sale.setPercentageSaved(caretaker.getWaterSourcePercentageSaved());
        sale.setSubmittedToTreasurer(0);
        sale.setSubmittedBy(0);
        sale.setSubmissionToTreasuerDate(Constants.DEFAULT_DATETIME);
        sale.setTreasurerApprovalStatus(0);
        sale.setReviewedBy(0);
        sale.setDateReviewed(Constants.DEFAULT_DATETIME);
        sale.setMarkedForDelete(0);
        sale.setDateCreated(Utils.getMySQLDate());
        sale.setLastUpdated(Utils.getMySQLDate());

        long id = sale.saveSale();

        pm4WUser.logEvent(EventLogs.EVENT_ADDED_DAILY_SALE, id);

        new AlertDialog.Builder(AddDailySaleActivity.this)
                .setTitle(pm4WUser.language.SUCCESS)
                .setMessage(pm4WUser.language.SALE_ADDED)
                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

            @Override
            public void onCancel(DialogInterface dialog) {
                finish();
            }
        }).setNegativeButton(pm4WUser.language.FINISH, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                finish();
            }
        }).setNeutralButton(pm4WUser.language.ANOTHER_SALE, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                amountSold.setText(null);
            }
        }).show();

    }

}
