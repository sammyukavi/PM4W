package com.eyeeza.apps.pm4w.main.auth.accounts;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;


import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

public class AccountBalanceActivity extends Activity {

    private Pm4wUser pm4WUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_account);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.ACCOUNT);

        Button btn_my_account = (Button) findViewById(R.id.btn_my_account);
        btn_my_account.setText(pm4WUser.language.MY_ACCOUNT_BALANCE);
        btn_my_account.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent nextActivity = new Intent(getApplicationContext(), ListWaterSourcesActivity.class);
                nextActivity.putExtra("nextActivity", "accountType");
                startActivity(nextActivity);
            }
        });

        Button btn_mini_statement = (Button) findViewById(R.id.btn_mini_statement);
        btn_mini_statement.setText(pm4WUser.language.MINI_STATEMENT);

        btn_mini_statement.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent nextActivity = new Intent(getApplicationContext(), ListWaterSourcesActivity.class);
                nextActivity.putExtra("nextActivity", "miniStatement");
                startActivity(nextActivity);
            }
        });

    }

}
