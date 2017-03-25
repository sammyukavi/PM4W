package com.eyeeza.apps.pm4w.main.auth.sales;


import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Sales;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.text.NumberFormat;
import java.text.ParseException;
import java.util.Locale;

public class AddMonthlySale extends Activity {

    private TextView customerNameTextview, waterSourceNameTextView, amountSoldTextview;
    private WaterUsers waterUser;
    private TextView amountSold;
    private Pm4wUser pm4WUser;
    private String idUser;
    private Caretakers caretaker;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_addmonthlysale);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.ADD_MONTHLY_SALE);

        customerNameTextview = (TextView) findViewById(R.id.customerNameTextview);
        customerNameTextview.setText(pm4WUser.language.CUSTOMER_NAME);

        waterSourceNameTextView = (TextView) findViewById(R.id.waterSourceNameTextView);
        waterSourceNameTextView.setText(pm4WUser.language.WATER_SOURCE_NAME);

        amountSoldTextview = (TextView) findViewById(R.id.amountSoldTextview);
        amountSoldTextview.setText(pm4WUser.language.AMOUNT_SOLD);

        idUser = getIntent().getStringExtra(Constants.WATER_USER_ID_TAG);

        waterUser = new WaterUsers(this);
        caretaker = new Caretakers(this);

        waterUser.getWaterUser(Long.parseLong(idUser));
        caretaker.getWaterSource(waterUser.getWaterSourceId());

        TextView waterUserName = (TextView) findViewById(R.id.waterUserName);
        waterUserName.setText(waterUser.getName());

        TextView waterSourceName = (TextView) findViewById(R.id.waterSourceName);
        waterSourceName.setText(caretaker.getWaterSourceName());


        amountSold = (TextView) findViewById(R.id.amountSold);
        amountSold.setText(String.valueOf(Utils.numberFormat(caretaker.getWaterSourceMonthlyCharges())));

        Button addSaleButton = (Button) findViewById(R.id.addSaleButton);
        addSaleButton.setText(pm4WUser.language.SAVE);

    }


    public void addSale(View v) {

        Double amount = 0.0;
        amountSold.getText().toString();

        try {
            NumberFormat ukFormat = NumberFormat.getNumberInstance(Locale.UK);
            amount = ukFormat.parse(amountSold.getText().toString()).doubleValue();
        } catch (ParseException e) {
            //Handle exception
        }

        amountSold = (TextView) findViewById(R.id.amountSold);
        if (amount == 0) {
            new AlertDialog.Builder(this)
                    .setTitle(pm4WUser.language.ERROR)
                    .setMessage(pm4WUser.language.AMOUNT_REQUIRED_ERROR)
                    .setIcon(android.R.drawable.ic_dialog_alert)
                    .show();
            return;
        }

        Sales sale = new Sales(this);
        sale.setWaterSourceID(waterUser.getWaterSourceId());
        sale.setSoldBy(pm4WUser.getIdu());
        sale.setSoldTo(Long.parseLong(waterUser.getIdUser() + ""));
        sale.setSaleUgx(amount);
        sale.setSaleDate(Utils.getMySQLDate());
        sale.setPercentageSaved(caretaker.getWaterSourcePercentageSaved());
        sale.setSubmittedToTreasurer(0);
        sale.setSubmittedBy(0);
        sale.setSubmissionToTreasuerDate(Constants.DEFAULT_DATETIME);
        sale.setTreasurerApprovalStatus(0);
        sale.setReviewedBy(0);
        sale.setDateReviewed(Constants.DEFAULT_DATETIME);
        sale.setMarkedForDelete(0);
        sale.setDateCreated(Utils.getMySQLDate());
        sale.setLastUpdated(Utils.getMySQLDate());

        long id = sale.saveSale();

        pm4WUser.logEvent(EventLogs.EVENT_ADDED_MONTHLY_SALE, id);


        new AlertDialog.Builder(AddMonthlySale.this)
                .setTitle(pm4WUser.language.SUCCESS)
                .setMessage(pm4WUser.language.SALE_ADDED)
                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                finish();
            }
        }).setNegativeButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                finish();

            }
        }).show();


    }


}
