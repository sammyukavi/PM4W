package com.eyeeza.apps.pm4w.main.auth.waterusers;

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
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class AddWaterUserActivity extends Activity {


    private Pm4wUser pm4WUser;
    private TextView fnameTextview, lnameTextview, pnumberTextview, waterSourcePromptTextview, fName, lName, pNumber;
    private Spinner waterSourceSpinner;
    private int waterSourceId;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_addupdatewateruser);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.ADD_WATER_USER);

        fnameTextview = (TextView) findViewById(R.id.fnameTextview);
        fnameTextview.setText(pm4WUser.language.FIRST_NAME);

        lnameTextview = (TextView) findViewById(R.id.lnameTextview);
        lnameTextview.setText(pm4WUser.language.LAST_NAME);

        pnumberTextview = (TextView) findViewById(R.id.pnumberTextview);
        pnumberTextview.setText(pm4WUser.language.PNUMBER);

        waterSourcePromptTextview = (TextView) findViewById(R.id.waterSourcePromptTextview);
        waterSourcePromptTextview.setText(pm4WUser.language.WATER_SOURCE_PROMPT);

        Caretakers caretaker = new Caretakers(this);
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

        Button add_water_user_button = (Button) findViewById(R.id.addWaterUserButton);
        add_water_user_button.setText(pm4WUser.language.ADD_USER);

    }

    public void saveUser(View v) {

        fName = (TextView) findViewById(R.id.fName);
        lName = (TextView) findViewById(R.id.lName);
        pNumber = (TextView) findViewById(R.id.pNumber);

        if (fName.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.FIRST_NAME_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (lName.getText().toString().trim().length() == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.LAST_NAME_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        if (pNumber.getText().toString().trim().length() > 0) {
            //String pNumber = this.pNumber.getText().toString();
            //pNumber = pNumber.replaceAll("\\d", "#");
        }

        WaterUsers water_user = new WaterUsers(this);
        water_user.setfName(fName.getText().toString().trim());
        water_user.setlName(lName.getText().toString().trim());
        water_user.setpNumber(pNumber.getText().toString().trim());
        water_user.setWaterSourceId(waterSourceId);
        water_user.setDateAdded(Utils.getMySQLDate());
        water_user.setAddedBy(pm4WUser.getIdu());
        water_user.setReportedDefaulter(0);
        water_user.setMarkedForDelete(0);
        water_user.setLastUpdated(Utils.getMySQLDate());
        long id = water_user.saveWaterUser();

        pm4WUser.logEvent(EventLogs.EVENT_ADDED_WATER_USER, id);

        new AlertDialog.Builder(this)
                .setMessage(pm4WUser.language.WATER_USER_SUCCESSFULLY_ADDED)
                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

            @Override
            public void onCancel(DialogInterface dialog) {
                finish();
            }
        }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                finish();
            }
        }).show();

    }


}
