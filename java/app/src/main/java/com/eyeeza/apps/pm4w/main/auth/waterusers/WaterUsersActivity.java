package com.eyeeza.apps.pm4w.main.auth.waterusers;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

public class WaterUsersActivity extends Activity {

    private Pm4wUser pm4WUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_waterusers);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.WATER_USERS);

        Button btn_add_water_user = (Button) findViewById(R.id.btn_add_water_sources);
        btn_add_water_user.setText(pm4WUser.language.ADD_WATER_USER);

        btn_add_water_user.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent addWaterUser = new Intent(getApplicationContext(), AddWaterUserActivity.class);
                startActivity(addWaterUser);
            }
        });

        if (pm4WUser.CAN_ADD_WATER_USERS) {
            btn_add_water_user.setVisibility(View.VISIBLE);
        }

        Button btn_list_water_users = (Button) findViewById(R.id.btn_show_water_users);
        btn_list_water_users.setText(pm4WUser.language.SHOW_WATER_USERS);
        btn_list_water_users.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent listwaterusers = new Intent(getApplicationContext(), ListWaterUsers.class);
                startActivity(listwaterusers);
            }
        });

        btn_list_water_users.setVisibility(View.GONE);

        if (pm4WUser.CAN_EDIT_WATER_USERS || pm4WUser.CAN_DELETE_WATER_USERS || pm4WUser.CAN_VIEW_WATER_USERS) {
            btn_list_water_users.setVisibility(View.VISIBLE);
        }

    }

}
