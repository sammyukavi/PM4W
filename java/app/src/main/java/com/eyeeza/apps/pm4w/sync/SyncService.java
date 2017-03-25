package com.eyeeza.apps.pm4w.sync;

/**
 * Created by sammy-n-ukavi-jr on 8/17/15.
 */

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;

/**
 * Define a Service that returns an IBinder for the
 * sync adapter class, allowing the sync adapter framework to call
 * onPerformSync().
 */
public class SyncService extends Service {

    private static SyncAdapter sSyncAdapter = null;

    private static final Object sSyncAdapterLock = new Object();

    public SyncService() {
        super();
    }

    @Override
    public void onCreate() {
        // synchronized (sSyncAdapterLock) {
        if (sSyncAdapter == null) {
            sSyncAdapter = new SyncAdapter(getApplicationContext(), true);
        }
        //  }
    }


    @Override
    public IBinder onBind(Intent intent) {
        return sSyncAdapter.getSyncAdapterBinder();
    }
}