package com.eyeeza.apps.pm4w.main.auth.sales;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

public class SalesActivity extends Activity {

    private Pm4wUser pm4WUser;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sales);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.SALES);


        Button btn_daily_sale = (Button) findViewById(R.id.btn_daily_sale);

        btn_daily_sale.setText(pm4WUser.language.DAILY_SALE);
        btn_daily_sale.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {

                Intent addDailySale = new Intent(getApplicationContext(), AddDailySaleActivity.class);
                startActivity(addDailySale);
            }
        });

        if (pm4WUser.CAN_ADD_SALES) {
            btn_daily_sale.setVisibility(View.VISIBLE);
        }

        Button btn_monthly_billing = (Button) findViewById(R.id.btn_monthly_billing);
        btn_monthly_billing.setText(pm4WUser.language.MONTHLY_SALES);

        btn_monthly_billing.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent listWaterUsers = new Intent(getApplicationContext(), ListMonthlyWaterUsers.class);
                startActivity(listWaterUsers);
            }
        });


        if (pm4WUser.CAN_ADD_SALES) {
            btn_monthly_billing.setVisibility(View.VISIBLE);
        }


        Button btn_followup = (Button) findViewById(R.id.btn_followup);
        btn_followup.setText(pm4WUser.language.FOLLOW_UP);

        btn_followup.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent followUp = new Intent(getApplicationContext(), FollowUpActivity.class);
                followUp.putExtra("action", "followUp");
                startActivity(followUp);
            }
        });

        if (pm4WUser.CAN_ADD_SALES) {
            btn_followup.setVisibility(View.VISIBLE);
        }

        Button btn_user_payments = (Button) findViewById(R.id.btn_user_payments);
        btn_user_payments.setText(pm4WUser.language.USER_PAYMENTS);

        btn_user_payments.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent followUp = new Intent(getApplicationContext(), FollowUpActivity.class);
                followUp.putExtra("action", "viewPayments");
                startActivity(followUp);
            }
        });

        if (pm4WUser.CAN_VIEW_SALES) {
            btn_user_payments.setVisibility(View.VISIBLE);
        }


    }

}
