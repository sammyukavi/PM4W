package com.eyeeza.apps.pm4w.main.auth;


import android.accounts.AccountManager;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.NotificationManager;
import android.content.ContentResolver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.main.auth.accounts.AccountBalanceActivity;
import com.eyeeza.apps.pm4w.main.auth.expenditures.ExpendituresActivity;
import com.eyeeza.apps.pm4w.main.auth.sales.SalesActivity;
import com.eyeeza.apps.pm4w.main.auth.savings.SavingsActivity;
import com.eyeeza.apps.pm4w.main.auth.waterusers.WaterUsersActivity;
import com.eyeeza.apps.pm4w.main.unauth.About;
import com.eyeeza.apps.pm4w.main.unauth.Help;
import com.eyeeza.apps.pm4w.main.unauth.Login;
import com.eyeeza.apps.pm4w.networking.NetworkFunctions;
import com.eyeeza.apps.pm4w.user.Pm4wUser;

public class Dashboard extends Activity {
    private static final String PREF_SETUP_COMPLETE = "setup_complete";
    private android.accounts.Account mAccount;
    private Pm4wUser pm4WUser;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        NotificationManager mNotificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        mNotificationManager.cancel(Constants.NOTIFICATION_ID);

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.DASHBOARD);

        //water users
        Button btnWaterUsers = (Button) findViewById(R.id.btnWaterUsers);
        btnWaterUsers.setText(pm4WUser.language.WATER_USERS);
        btnWaterUsers.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), WaterUsersActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_ADD_WATER_USERS || pm4WUser.CAN_EDIT_WATER_USERS || pm4WUser.CAN_DELETE_WATER_USERS || pm4WUser.CAN_VIEW_WATER_USERS) {
            btnWaterUsers.setVisibility(View.VISIBLE);
        }

        //water sales
        Button btn_sales = (Button) findViewById(R.id.btn_sales);
        btn_sales.setText(pm4WUser.language.SALES);
        btn_sales.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), SalesActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_ADD_SALES || pm4WUser.CAN_EDIT_SALES || pm4WUser.CAN_DELETE_SALES || pm4WUser.CAN_VIEW_SALES) {
            btn_sales.setVisibility(View.VISIBLE);
        }


        //savings
        Button btnSavings = (Button) findViewById(R.id.btnSavings);
        btnSavings.setText(pm4WUser.language.SAVINGS);
        btnSavings.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), SavingsActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_SUBMIT_ATTENDANT_DAILY_SALES || pm4WUser.CAN_CANCEL_ATTENDANT_DAILY_SALES ||
                pm4WUser.CAN_APPROVE_ATTENDANTS_SUBMISSIONS || pm4WUser.CAN_CANCEL_ATTENDANTS_SUBMISSIONS ||
                pm4WUser.CAN_APPROVE_TREASURERS_SUBMISSIONS || pm4WUser.CAN_CANCEL_TREASURERS_SUBMISSIONS
                || pm4WUser.CAN_VIEW_WATER_SOURCE_SAVINGS) {
            btnSavings.setVisibility(View.VISIBLE);
        }


        //ExpendituresActivity

        Button btn_expenditures = (Button) findViewById(R.id.btn_expenditures);
        btn_expenditures.setText(pm4WUser.language.EXPENDITURES);
        btn_expenditures.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent i = new Intent(getApplicationContext(), ExpendituresActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_ADD_EXPENSES || pm4WUser.CAN_EDIT_EXPENSES || pm4WUser.CAN_DELETE_EXPENSES || pm4WUser.CAN_VIEW_EXPENSES) {
            btn_expenditures.setVisibility(View.VISIBLE);
        }

        //My account

        Button btn_account = (Button) findViewById(R.id.btn_account);
        btn_account.setText(pm4WUser.language.ACCOUNT);
        btn_account.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {

                Intent i = new Intent(getApplicationContext(), AccountBalanceActivity.class);
                startActivity(i);
            }
        });

        if (pm4WUser.CAN_VIEW_PERSONAL_SAVINGS) {
            btn_account.setVisibility(View.VISIBLE);
        }

        mAccount = CreateSyncAccount(this);

        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_DASHBOARD);

    }

    public android.accounts.Account CreateSyncAccount(Context context) {
        boolean isNewAccount = false;
        boolean setupComplete = PreferenceManager.getDefaultSharedPreferences(context).getBoolean(PREF_SETUP_COMPLETE, false);
        android.accounts.Account newAccount = new android.accounts.Account(Constants.ACCOUNT_NAME, Constants.ACCOUNT_TYPE);
        AccountManager accountManager = (AccountManager) context.getSystemService(ACCOUNT_SERVICE);
        if (accountManager.addAccountExplicitly(newAccount, null, null)) {
            ContentResolver.setIsSyncable(newAccount, Constants.AUTHORITY, 1);
            ContentResolver.setSyncAutomatically(newAccount, Constants.AUTHORITY, true);
            ContentResolver.addPeriodicSync(newAccount, Constants.AUTHORITY, new Bundle(), Config.SYNC_FREQUENCY);
            isNewAccount = true;
        } else {
            /*
             * The account exists or some other error occurred. Log this, report it,
             * or handle it internally.
             */
        }

        if (isNewAccount || !setupComplete) {
            //performSync();
            PreferenceManager.getDefaultSharedPreferences(context).edit().putBoolean(PREF_SETUP_COMPLETE, true).commit();
        }
        return newAccount;
    }

    public void RemoveSyncAccount() {
        AccountManager accountManager = (AccountManager) this.getSystemService(ACCOUNT_SERVICE);
        android.accounts.Account[] accounts = accountManager.getAccounts();
        for (int index = 0; index < accounts.length; index++) {
            if (accounts[index].type.intern() == Constants.AUTHORITY) {
                accountManager.removeAccount(accounts[index], null, null);
            }
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.dashboard, menu);
        menu.getItem(0).setTitle(pm4WUser.language.SEND_DATA);
        menu.getItem(1).setTitle(pm4WUser.language.SELECT_LANGUAGE);
        menu.getItem(2).setTitle(pm4WUser.language.CHECK_FOR_UPDATES);
        menu.getItem(3).setTitle(pm4WUser.language.LOGOUT);
        menu.getItem(4).setTitle(pm4WUser.language.ABOUT);
        menu.getItem(5).setTitle(pm4WUser.language.HELP);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == R.id.action_sync) {
            if (NetworkFunctions.isOnline(this)) {
                new AlertDialog.Builder(Dashboard.this)
                        .setTitle(pm4WUser.language.INFO)
                        .setMessage(pm4WUser.language.SENDING_DATA)
                        .setCancelable(false)
                        .setIcon(android.R.drawable.ic_dialog_alert)
                        .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialogInterface, int i) {

                            }
                        })
                        .show();
                performSync();
            } else {
                new AlertDialog.Builder(this)
                        .setTitle(pm4WUser.language.NO_INTERNET_TITLE)
                        .setMessage(pm4WUser.language.NO_INTERNET_MSG)
                        .setIcon(android.R.drawable.ic_dialog_alert)
                        .setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialogInterface, int i) {

                            }
                        })
                        .show();
            }
        } else if (item.getItemId() == R.id.action_change_language) {
            finish();
            pm4WUser.setAppPreferredLanguage("");
            pm4WUser.saveSessionAccount();
            Intent chooseLanguage = new Intent(Dashboard.this, ChooseLanguage.class);
            startActivity(chooseLanguage);
        } else if (item.getItemId() == R.id.action_check_update) {
            Intent aCheckForUpdates = new Intent(getApplicationContext(), CheckForUpdates.class);
            startActivity(aCheckForUpdates);
        } else if (item.getItemId() == R.id.action_logout) {
            if (Constants.SyncInProgress) {
                new AlertDialog.Builder(Dashboard.this)
                        .setTitle(pm4WUser.language.INFO)
                        .setMessage(pm4WUser.language.SYNC_IN_PROGRESS)
                        .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                    @Override
                    public void onCancel(DialogInterface dialog) {
                        return;
                    }
                }).show();
            } else {
                RemoveSyncAccount();
                pm4WUser.logEvent(EventLogs.EVENT_ATTEMPTED_LOGOUT);
                pm4WUser.logOut(true);
                finish();
                Intent login = new Intent(getApplicationContext(), Login.class);
                startActivity(login);

            }

        } else if (item.getItemId() == R.id.action_about) {
            Intent about = new Intent(getApplicationContext(), About.class);
            startActivity(about);
        } else if (item.getItemId() == R.id.action_help) {
            Intent help = new Intent(getApplicationContext(), Help.class);
            startActivity(help);
        }
        return false;
    }

    private void performSync() {
        Bundle bundle = new Bundle();
        bundle.putBoolean(ContentResolver.SYNC_EXTRAS_MANUAL, true);
        bundle.putBoolean(ContentResolver.SYNC_EXTRAS_EXPEDITED, true);
        ContentResolver.requestSync(mAccount, Constants.AUTHORITY, bundle);
    }

    @Override
    protected void onPause() {
        super.onPause();
        //ContentResolver.removeStatusChangeListener(mContentProviderHandle);
    }


}