package com.eyeeza.apps.pm4w.sync;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;

/**
 * Created by sammy-n-ukavi-jr on 8/17/15.
 */
public class AuthenticatorService extends Service {

    // Instance field that stores the authenticator object
    private Authenticator mAuthenticator;

    @Override
    public void onCreate() {
        // Create a new authenticator object
        mAuthenticator = new Authenticator(this);
    }

    /*
     * When the system binds to this Service to make the RPC call
     * return the authenticator's IBinder.
     */
    @Override
    public IBinder onBind(Intent intent) {
        return mAuthenticator.getIBinder();
    }
}