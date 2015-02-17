package com.sukavi.pm4w;

import com.sukavi.pm4w.user.Permissions;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;


public class Sales extends Activity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.sales);

	Button btn_daily_sale = (Button) findViewById(R.id.btn_daily_sale);

	btn_daily_sale.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {

		Intent addDailySale = new Intent(getApplicationContext(), AddDailySale.class);
		startActivity(addDailySale);
	    }
	});

	btn_daily_sale.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_SALES) {
	    btn_daily_sale.setVisibility(View.VISIBLE);
	}	

	Button btn_monthly_billing = (Button) findViewById(R.id.btn_monthly_billing);

	btn_monthly_billing.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {

		Intent list_customers = new Intent(getApplicationContext(), ListWaterUsers.class);
		list_customers.putExtra("nextActivity","add-monthly-sale");		
		startActivity(list_customers);
	    }
	});

	btn_monthly_billing.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_SALES) {
	    btn_monthly_billing.setVisibility(View.VISIBLE);
	}


	Button btn_followup = (Button) findViewById(R.id.btn_followup);

	btn_followup.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View view) {		
		Intent followup = new Intent(getApplicationContext(), FollowUp.class);
		followup.putExtra("nextActivity","add-follow-up");		
		startActivity(followup);
	    }
	});

	btn_followup.setVisibility(View.GONE);

	if(Permissions.CAN_ADD_SALES) {
	    btn_followup.setVisibility(View.VISIBLE);
	}

	
    }

}
