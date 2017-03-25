package com.eyeeza.apps.pm4w.main.auth;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.user.Pm4wUser;


public class InvalidDate extends Activity {
    Pm4wUser pm4WUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_invaliddate);
        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.INVALID_DATE);

        TextView invalid_date_prompt = (TextView) findViewById(R.id.invalid_date_prompt);
        invalid_date_prompt.setText(pm4WUser.language.INVALID_DATE_PROMPT_TEXT);

        TextView invalid_date_desc = (TextView) findViewById(R.id.invalid_date_desc);
        invalid_date_desc.setText(pm4WUser.language.INVALID_DATE_DESCRIPTION);

        java.util.Date dt = new java.util.Date();
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat(Constants.DATE_TIME_FORMAT_2);

        TextView current_date = (TextView) findViewById(R.id.current_date);
        current_date.setText(sdf.format(dt));

        Button adjust_date_button = (Button) findViewById(R.id.adjust_date_button);
        adjust_date_button.setText(pm4WUser.language.ADJUST_DATE);

    }

    public void adjust_date(View v) {
        Intent dateIntent = new Intent(android.provider.Settings.ACTION_DATE_SETTINGS);
        startActivity(dateIntent);
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        checkTime();
    }

    @Override
    public void onBackPressed() {
        finish();
    }

    private void checkTime() {
        Intent chooseLanguage = new Intent(getApplicationContext(), ChooseLanguage.class);
        startActivity(chooseLanguage);
        finish();
    }

}
