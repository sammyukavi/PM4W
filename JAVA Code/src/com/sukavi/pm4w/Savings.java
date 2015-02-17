package com.sukavi.pm4w;

import com.sukavi.pm4w.user.Permissions;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;


public class Savings extends Activity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.savings);

	Button btn_commitee_treasurer = (Button) findViewById(R.id.btn_commitee_treasurer);

	btn_commitee_treasurer.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {

		Intent listsavingsforsubmissions = new Intent(getApplicationContext(), ListSavingsForSubmissions.class);
		listsavingsforsubmissions.putExtra("request", "attendants-submissions");
		startActivity(listsavingsforsubmissions);
	    }
	});

	btn_commitee_treasurer.setVisibility(View.GONE);

	if(Permissions.CAN_SUBMIT_ATTENDANT_DAILY_SALES||Permissions.CAN_CANCEL_ATTENDANT_DAILY_SALES) {
	    btn_commitee_treasurer.setVisibility(View.VISIBLE);
	}

	Button btn_waterboard_treasurer = (Button) findViewById(R.id.btn_waterboard_treasurer);

	btn_waterboard_treasurer.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {
		Intent listsavingsforsubmissions = new Intent(getApplicationContext(), ListSavingsForSubmissions.class);
		listsavingsforsubmissions.putExtra("request", "treasurers-submissions");
		startActivity(listsavingsforsubmissions);		
	    }
	});

	btn_waterboard_treasurer.setVisibility(View.GONE);

	if(Permissions.CAN_APPROVE_ATTENDANTS_SUBMISSIONS||Permissions.CAN_CANCEL_ATTENDANTS_SUBMISSIONS) {
	    btn_waterboard_treasurer.setVisibility(View.VISIBLE);
	}	

	Button btn_water_source_savings = (Button) findViewById(R.id.btn_watersource_savings);

	btn_water_source_savings.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {
		Intent listWaterSources = new Intent(getApplicationContext(), ListWaterSources.class);			
		startActivity(listWaterSources);
	    }
	});

	btn_water_source_savings.setVisibility(View.GONE);

	if(Permissions.CAN_VIEW_WATER_SOURCE_SAVINGS) {
	    btn_water_source_savings.setVisibility(View.VISIBLE);
	}

    }

}
