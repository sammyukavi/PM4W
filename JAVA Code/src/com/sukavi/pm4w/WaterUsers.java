package com.sukavi.pm4w;

import com.sukavi.pm4w.user.Permissions;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class WaterUsers extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);	
	setContentView(R.layout.water_users);

	Button btn_list_water_users = (Button) findViewById(R.id.btn_show_water_users);

	btn_list_water_users.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent listwaterusers = new Intent(getApplicationContext(), ListWaterUsers.class);		
		startActivity(listwaterusers);
	    }
	});

	btn_list_water_users.setVisibility(View.GONE);

	if(Permissions.CAN_EDIT_WATER_USERS||Permissions.CAN_DELETE_WATER_USERS||Permissions.CAN_VIEW_WATER_USERS) {
	    btn_list_water_users.setVisibility(View.VISIBLE);
	}

	Button btn_add_water_user = (Button) findViewById(R.id.btn_add_water_sources);

	btn_add_water_user.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent addwateruser = new Intent(getApplicationContext(), AddWaterUser.class);		
		startActivity(addwateruser);
	    }
	});

	btn_add_water_user.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_WATER_USERS) {
	    btn_add_water_user.setVisibility(View.VISIBLE);
	}


    }

}
