package com.eyeeza.apps.pm4w.main.auth.expenditures;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.SimpleAdapter;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Expenditures;
import com.eyeeza.apps.pm4w.dbtables.RepairTypes;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class AddExpenditureActivity extends Activity {

    private Pm4wUser pm4WUser;
    private TextView waterSourcePromptTextview, repairTypesTextview, expenditureCostTextview, descriptionTextview, benefactorTextview;
    private Spinner waterSourceSpinner, repairtypesSpinner;
    private int waterSourceId = 0, repairTypeId = 0;

   /* String id_customer;
    ProgressDialog pDialog;
    JSONObject json;
    int Current_stage = 0;
    List<NameValuePair> params = new ArrayList<NameValuePair>();
    String api_url;
    Spinner water_source_spinner, repair_types_spinner;
    private ArrayList<HashMap<String, String>> WaterSourcesList = new ArrayList<HashMap<String, String>>();
    private ArrayList<HashMap<String, String>> RepairTypesList = new ArrayList<HashMap<String, String>>();
    private int water_source_id, repair_type_id = 0;*/


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_addexpenditure);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.ADD_EXPENSE);

        waterSourcePromptTextview = (TextView) findViewById(R.id.waterSourcePromptTextview);
        waterSourcePromptTextview.setText(pm4WUser.language.WATER_SOURCE_PROMPT);

        waterSourceSpinner = (Spinner) findViewById(R.id.waterSourceSpinner);
        waterSourceSpinner.setPrompt(pm4WUser.language.WATER_SOURCE_PROMPT);

        repairTypesTextview = (TextView) findViewById(R.id.repairTypesPromptTextview);
        repairTypesTextview.setText(pm4WUser.language.TYPE_OF_EXPENSE);

        repairtypesSpinner = (Spinner) findViewById(R.id.repairtypesSpinner);
        repairtypesSpinner.setPrompt(pm4WUser.language.TYPE_OF_EXPENSE);

        expenditureCostTextview = (TextView) findViewById(R.id.expenditureCostTextview);
        expenditureCostTextview.setText(pm4WUser.language.EXPENDITURE_COST);

        benefactorTextview = (TextView) findViewById(R.id.benefactorTextview);
        benefactorTextview.setText(pm4WUser.language.BENEFACTOR);

        descriptionTextview = (TextView) findViewById(R.id.descriptionTextview);
        descriptionTextview.setText(pm4WUser.language.DESCRIPTION);

        Button add_expense_button = (Button) findViewById(R.id.add_expense_button);
        add_expense_button.setText(pm4WUser.language.ADD_EXPENSE);


        Treasurers treasurer = new Treasurers(this);
        ArrayList<HashMap<String, String>> WaterSourcesList = treasurer.getAllWaterSources();

        SimpleAdapter waterSourcesAdapter = new SimpleAdapter(
                this, WaterSourcesList,
                R.layout.listitem_watersources, new String[]{Constants.ID_WATER_SOURCE_TAG,
                Constants.WATER_SOURCE_NAME_TAG, Constants.WATER_SOURCE_MONTHLY_CHARGES_TAG},
                new int[]{R.id.waterSourceIdRenderer, R.id.waterSourceNameRenderer});

        waterSourceSpinner.setAdapter(waterSourcesAdapter);
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

        RepairTypes repairType = new RepairTypes(this);

        ArrayList<HashMap<String, String>> repairTypesList = repairType.getAllRepairTypes();

        SimpleAdapter repairTypesAdapter = new SimpleAdapter(
                this, repairTypesList,
                R.layout.list_repairtypes, new String[]{Constants.ID_REPAIR_TYPE_TAG,
                Constants.REPAIR_TYPE_TAG},
                new int[]{R.id.repairTypeIdRenderer, R.id.repairTypeRenderer});

        repairtypesSpinner.setAdapter(repairTypesAdapter);
        repairtypesSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {

            @Override
            public void onItemSelected(AdapterView<?> arg0, View view, int arg2, long arg3) {
                TextView repairTypeIdRenderer = (TextView) view.findViewById(R.id.repairTypeIdRenderer);
                repairTypeId = Integer.parseInt(repairTypeIdRenderer.getText().toString());
            }

            @Override
            public void onNothingSelected(AdapterView<?> arg0) {
                repairTypeId = 0;
            }
        });

    }

    public void addExpense(View v) {
        if (waterSourceSpinner.getSelectedItem() == null) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.WATERSOURCE_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }
        EditText expenditureCost = (EditText) findViewById(R.id.expenditureCost);
        EditText benefactor = (EditText) findViewById(R.id.benefactor);
        EditText description = (EditText) findViewById(R.id.description);

        if (waterSourceSpinner.getSelectedItem() == null) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.WATERSOURCE_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (expenditureCost.getText().toString().trim().length() == 0 || Double.parseDouble(expenditureCost.getText().toString()) == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.EXPENDITURE_COST_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (benefactor.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.EXPENDITURE_BENEFACTOR_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (description.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.EXPENDITURE_DESCRIPTION_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        Expenditures expenditure = new Expenditures(this);

        expenditure.setWaterSourceId(waterSourceId);
        expenditure.setRepairTypeId(repairTypeId);
        expenditure.setExpenditureDate(Utils.getMySQLDate());
        expenditure.setExpenditureCost(Double.parseDouble(expenditureCost.getText().toString().trim()));
        expenditure.setBenefactor(benefactor.getText().toString());
        expenditure.setDescription(description.getText().toString().trim());
        expenditure.setLoggedBy(pm4WUser.getIdu());
        expenditure.setMarkedForDelete(0);
        expenditure.setDateCreated(Utils.getMySQLDate());
        expenditure.setLastUpdated(Utils.getMySQLDate());

        long expenditure_id = expenditure.saveExpenditure();

        pm4WUser.logEvent(EventLogs.EVENT_LOGGED_EXPENSE, expenditure_id);

        String msg = "";
        if (expenditure_id != 0) {
            msg = pm4WUser.language.EXPENDITURE_SUCCESSFULLY_ADDED;
        } else {
            msg = pm4WUser.language.ERROR_ADDING_EXPENDITURE;
        }
        new AlertDialog.Builder(this)
                .setMessage(msg)
                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

            @Override
            public void onCancel(DialogInterface dialog) {
                finish();
            }
        }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                finish();
            }
        }).show();

    }


}
