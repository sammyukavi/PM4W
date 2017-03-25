package com.eyeeza.apps.pm4w.main.unauth;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.eyeeza.apps.pm4w.config.Config;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;

public class RunOnStartupActivity extends BroadcastReceiver {

    @Override
    public void onReceive(Context context, Intent intent) {
        if (intent.getAction().equals(Intent.ACTION_BOOT_COMPLETED)) {
           /* int tryTimes = 2000;
            int counter = 0;
            boolean isConnected = false;
            while (!isConnected && counter < tryTimes) {
                isConnected = hasActiveInternetConnection(context);
                counter += 1;
            }

            if (isConnected) {
                Intent i = new Intent(context, Login.class);
                i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                context.startActivity(i);
            }*/

            Intent i = new Intent(context, Login.class);
            i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(i);
        }
    }

    public boolean hasActiveInternetConnection(Context context) {
        if (isNetworkAvailable(context)) {
            try {
                HttpURLConnection urlc = (HttpURLConnection) (new URL(Config.API_URL).openConnection());
                urlc.setRequestProperty("PM4W-Agent", "pm4w-mobile-client");
                urlc.setRequestProperty("Connection", "close");
                urlc.setConnectTimeout(1500);
                urlc.connect();
                return (urlc.getResponseCode() == 200);
            } catch (IOException e) {

            }
        } else {

        }
        return false;
    }

    private boolean isNetworkAvailable(Context context) {
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo netInfo = cm.getActiveNetworkInfo();
        return netInfo != null && netInfo.isConnectedOrConnecting();
    }
}
