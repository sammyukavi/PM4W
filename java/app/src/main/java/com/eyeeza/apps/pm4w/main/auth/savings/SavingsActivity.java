package com.eyeeza.apps.pm4w.main.auth.savings;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.user.Pm4wUser;


public class SavingsActivity extends Activity {

    private Pm4wUser pm4WUser;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_savings);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.SAVINGS);

        Button btn_commitee_treasurer = (Button) findViewById(R.id.btn_commitee_treasurer);
        btn_commitee_treasurer.setText(pm4WUser.language.COMMITEE_TREASURER);

        btn_commitee_treasurer.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent listCareTakersCollections = new Intent(getApplicationContext(), ListCareTakersCollectionsActivity.class);
                startActivity(listCareTakersCollections);
            }
        });

        if (pm4WUser.CAN_SUBMIT_ATTENDANT_DAILY_SALES || pm4WUser.CAN_CANCEL_ATTENDANT_DAILY_SALES) {
            btn_commitee_treasurer.setVisibility(View.VISIBLE);
        }

        Button btn_waterboard_treasurer = (Button) findViewById(R.id.btn_waterboard_treasurer);

        btn_waterboard_treasurer.setText(pm4WUser.language.WATER_BOARD_TREASURER);
        btn_waterboard_treasurer.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent listTreasurersCollections = new Intent(getApplicationContext(), ListTreasurersCollectionsActivity.class);
                startActivity(listTreasurersCollections);
            }
        });

        if (pm4WUser.CAN_APPROVE_ATTENDANTS_SUBMISSIONS || pm4WUser.CAN_CANCEL_ATTENDANTS_SUBMISSIONS) {
            btn_waterboard_treasurer.setVisibility(View.VISIBLE);
        }

        Button btn_water_source_savings = (Button) findViewById(R.id.btn_watersource_savings);
        btn_water_source_savings.setText(pm4WUser.language.WATER_SOURCE_SAVINGS);

        btn_water_source_savings.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent listWaterSources = new Intent(getApplicationContext(), ListWaterSourcesActivity.class);
                startActivity(listWaterSources);
            }
        });

        if (pm4WUser.CAN_VIEW_WATER_SOURCE_SAVINGS) {
            btn_water_source_savings.setVisibility(View.VISIBLE);
        }

    }

}
