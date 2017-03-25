package com.eyeeza.apps.pm4w.main.auth;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;


public class ChooseLanguage extends Activity {
    private Pm4wUser pm4WUser;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chooselanguage);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());

        final ProgressDialog progressDialog = new ProgressDialog(this);
        progressDialog.setCancelable(false);
        progressDialog.setIndeterminate(true);

        pm4WUser.getAvailableLanguages();
        final String[] languages = pm4WUser.getAvailableLanguages();

        if (pm4WUser.getAppPreferredLanguage().length() == 0) {
            pm4WUser.setAppPreferredLanguage(languages[0]);
            AlertDialog.Builder builder = new AlertDialog.Builder(ChooseLanguage.this);
            builder.setCancelable(false);
            builder.setTitle(getResources().getString(R.string.choose_language));
            builder.setSingleChoiceItems(languages, 0,
                    new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int item) {
                            pm4WUser.setAppPreferredLanguage(languages[item]);
                        }
                    });

            builder.setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    pm4WUser.logEvent(EventLogs.EVENT_CHOSE_LANGUAGE, pm4WUser.getAppPreferredLanguage());
                    pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());
                    pm4WUser.saveSessionAccount();
                    proceed();
                }
            });

            AlertDialog alert = builder.create();
            alert.show();
        } else {
            proceed();
        }


    }

    private void proceed() {
        final Intent dashboard = new Intent(getApplicationContext(), Dashboard.class);
        final Intent invalidDate = new Intent(getApplicationContext(), InvalidDate.class);
        if (Utils.timeIsRelativelyValid(ChooseLanguage.this)) {
            startActivity(dashboard);
        } else {
            startActivity(invalidDate);
        }
    }

}
