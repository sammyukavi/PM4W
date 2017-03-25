package com.eyeeza.apps.pm4w.main.auth.expenditures;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

/**
 * Created by sammy-n-ukavi-jr on 7/28/15.
 */
public class ExpendituresActivity extends Activity {

    private Pm4wUser pm4WUser;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_expenditures);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.EXPENDITURES);

        Button btnAddExpenditure = (Button) findViewById(R.id.btnAddExpenditure);
        btnAddExpenditure.setText(pm4WUser.language.ADD_EXPENSE);
        btnAddExpenditure.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent i = new Intent(getApplicationContext(), AddExpenditureActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_ADD_EXPENSES) {
            btnAddExpenditure.setVisibility(View.VISIBLE);
        }

        Button btnViewExpenditure = (Button) findViewById(R.id.btnViewExpenditure);
        btnViewExpenditure.setText(pm4WUser.language.VIEW_EXPENDITURES);
        btnViewExpenditure.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent i = new Intent(getApplicationContext(), ListWaterSourcesActivity.class);
                startActivity(i);
            }
        });

        if ( pm4WUser.CAN_EDIT_EXPENSES || pm4WUser.CAN_DELETE_EXPENSES || pm4WUser.CAN_VIEW_EXPENSES) {
            btnViewExpenditure.setVisibility(View.VISIBLE);
        }


    }
}
