package com.eyeeza.apps.pm4w.sync;

/**
 * Created by sammy-n-ukavi-jr on 8/17/15.
 */

import android.accounts.Account;
import android.content.AbstractThreadedSyncAdapter;
import android.content.ContentProviderClient;
import android.content.ContentResolver;
import android.content.Context;
import android.content.SyncResult;
import android.os.Bundle;

import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.networking.NetworkFunctions;
import com.eyeeza.apps.pm4w.user.Pm4wUser;


/**
 * Handle the transfer of data between a server and an
 * app, using the Android sync adapter framework.
 */
public class SyncAdapter extends AbstractThreadedSyncAdapter {
    // ...
    // Global variables
    // Define a variable to contain a content resolver instance
    ContentResolver mContentResolver;
    Context context;

    /**
     * Set up the sync adapter
     */
    public SyncAdapter(Context context, boolean autoInitialize) {
        super(context, autoInitialize);
        /*
         * If your app uses a content resolver, get an instance of it
         * from the incoming Context
         */
        mContentResolver = context.getContentResolver();
        this.context = context;
    }
    //...

    /*
     * Specify the code you want to run in the sync adapter. The entire
     * sync adapter runs in a background thread, so you don't have to set
     * up your own background processing.
     */
    @Override
    public void onPerformSync(
            Account account,
            Bundle extras,
            String authority,
            ContentProviderClient provider,
            SyncResult syncResult) {
        PerformSync performSync = new PerformSync(context);

        if (NetworkFunctions.isOnline(context)) {
            try {
                performSync.Sync();
            } catch (Exception e) {
                Pm4wUser pm4wUser = new Pm4wUser(context);
                pm4wUser.logEvent(EventLogs.SYNC_UNCOMPLETE);
                e.printStackTrace();
            }
        }


    }
}
