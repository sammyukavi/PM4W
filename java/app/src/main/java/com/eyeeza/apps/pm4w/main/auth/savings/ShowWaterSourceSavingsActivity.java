package com.eyeeza.apps.pm4w.main.auth.savings;

import android.app.Activity;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.networking.JSONParser;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

import org.apache.http.NameValuePair;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class ShowWaterSourceSavingsActivity extends Activity {

    private String waterSourceId;
    private ProgressDialog pDialog;
    private String api_url;
    private JSONParser jsonp = new JSONParser();
    private JSONObject json;
    private List<NameValuePair> params = new ArrayList<NameValuePair>();

    private Pm4wUser pm4WUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_showwatersourcesavings);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.WATER_SOURCE_SAVINGS_DATA);

        waterSourceId = getIntent().getStringExtra(Constants.ID_WATER_SOURCE_TAG);

        Treasurers treasurers = new Treasurers(this);

        try {
            treasurers.getWaterSource(Integer.parseInt(waterSourceId));
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        TextView waterSourceNameTextView = (TextView) findViewById(R.id.waterSourceNameTextView);
        waterSourceNameTextView.setText(pm4WUser.language.WATER_SOURCE_NAME);

        TextView waterSourceName = (TextView) findViewById(R.id.waterSourceName);
        waterSourceName.setText(treasurers.getWaterSourceName());

        TextView waterSourceLocationTextView = (TextView) findViewById(R.id.waterSourceLocationTextView);
        waterSourceLocationTextView.setText(pm4WUser.language.WATER_SOURCE_LOCATION);

        TextView waterSourceLocation = (TextView) findViewById(R.id.waterSourceLocation);
        waterSourceLocation.setText(treasurers.getWaterSourceLocation());

        TextView userCountTextView = (TextView) findViewById(R.id.userCountTextView);
        userCountTextView.setText(pm4WUser.language.MONTHLY_WATER_USER);

        TextView usersCount = (TextView) findViewById(R.id.usersCount);
        usersCount.setText(String.valueOf(treasurers.getMonthlyBilledUsers()));


        TextView verifiedTransactionsTextView = (TextView) findViewById(R.id.verifiedTransactionsTextView);
        verifiedTransactionsTextView.setText(pm4WUser.language.VERIFIED_TRANSACTIONS);

        TextView transactions = (TextView) findViewById(R.id.transactions);
        transactions.setText(String.valueOf(treasurers.getVerifiedTransactions()));


        TextView availableBalanceTextview = (TextView) findViewById(R.id.availableBalanceTextview);
        availableBalanceTextview.setText(pm4WUser.language.AVAILABLE_BALANCE);

        TextView availableBalance = (TextView) findViewById(R.id.availableBalance);
        availableBalance.setText(String.valueOf(treasurers.getAvailableSavings()));

        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_WATER_SOURCE_SAVINGS);

    }

}

