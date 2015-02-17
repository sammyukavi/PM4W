package com.sukavi.pm4w;

import com.sukavi.pm4w.config.Config;

import android.app.Activity;
import android.os.Bundle;
import android.widget.TextView;

public class About extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);	
	setContentView(R.layout.about);
	TextView app_version = (TextView) findViewById(R.id.app_version);
	app_version.setText("V "+Config.APP_VERSION);
    }

}
