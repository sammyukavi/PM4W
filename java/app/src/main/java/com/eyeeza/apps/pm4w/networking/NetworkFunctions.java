package com.eyeeza.apps.pm4w.networking;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

/**
 * Created by sammy-n-ukavi-jr on 7/26/15.
 */
public class NetworkFunctions {
    /**
     * Checks to see if user is connected to wifi or 3g
     *
     * @return
     */
    public static boolean isOnline(Context context) {
        ConnectivityManager connectivityManager = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = null;
        if (connectivityManager != null) {
            networkInfo = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
            if (!networkInfo.isAvailable()) {
                networkInfo = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
            }
        }
        return networkInfo == null ? false : networkInfo.isConnected();
    }
}
