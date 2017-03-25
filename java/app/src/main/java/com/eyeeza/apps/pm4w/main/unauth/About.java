package com.eyeeza.apps.pm4w.main.unauth;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.config.Config;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.user.Pm4wUser;


public class About extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about);
        TextView app_version = (TextView) findViewById(R.id.app_version);
        app_version.setText("V " + Config.APP_VERSION);
        Pm4wUser pm4WUser = new Pm4wUser(this);
        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_ABOUT);
    }

    @Override
    public void onBackPressed() {
        //	super.onBackPressed();
        finish();
        Intent i = new Intent(getApplicationContext(), Login.class);
        startActivity(i);
    }

}
