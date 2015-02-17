package com.sukavi.pm4w;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class Account extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);	
	setContentView(R.layout.account);

	Button btn_my_account = (Button) findViewById(R.id.btn_my_account);

	btn_my_account.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent myaccount = new Intent(getApplicationContext(), MyAccount.class);
		startActivity(myaccount);
	    }
	});
	
	Button btn_mini_statement = (Button) findViewById(R.id.btn_mini_statement);

	btn_mini_statement.setOnClickListener(new View.OnClickListener() {

	    @Override
	    public void onClick(View v) {
		Intent ministatement = new Intent(getApplicationContext(), MiniStatement.class);
		startActivity(ministatement);
	    }
	});

    }

}
