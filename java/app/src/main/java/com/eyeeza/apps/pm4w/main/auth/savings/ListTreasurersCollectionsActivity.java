package com.eyeeza.apps.pm4w.main.auth.savings;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class ListTreasurersCollectionsActivity extends Activity {


    private ArrayList<HashMap<String, String>> treasurerCollectionsArrayList = new ArrayList<HashMap<String, String>>();
    private ListView waterUsersList;
    private TextView usersCount;
    private Pm4wUser pm4WUser;
    private TreasurersBaseAdapter treasurersBaseAdapter = null;
    private ArrayList<String> selectedTreasurerArrayList = new ArrayList<>();
    private Treasurers treasurer;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listtreasurercollections);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.SUBMIT_TREASURER_COLLECTIONS);

        waterUsersList = (ListView) findViewById(R.id.waterUsersList);
        usersCount = (TextView) findViewById(R.id.usersCount);

        pm4WUser.logEvent(EventLogs.EVENT_LISTED_TREASURER_COLLECTIONS);

        treasurer = new Treasurers(ListTreasurersCollectionsActivity.this);
        new FetchCollections().execute();

        Button approve_sales_button = (Button) findViewById(R.id.approve_sales_button);
        approve_sales_button.setText(pm4WUser.language.APPROVE_SALES);
        if (pm4WUser.CAN_APPROVE_TREASURERS_SUBMISSIONS) {
            approve_sales_button.setVisibility(View.VISIBLE);
        }

        Button cancel_sales_button = (Button) findViewById(R.id.cancel_sales_button);
        cancel_sales_button.setText(pm4WUser.language.CANCEL_SALES);
        if (pm4WUser.CAN_CANCEL_TREASURERS_SUBMISSIONS) {
            cancel_sales_button.setVisibility(View.VISIBLE);
        }

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchCollections().execute();
    }

    public void approveSubmitted(View v) {
        int submittedBy = pm4WUser.getIdu();
        Treasurers treasurers = new Treasurers(ListTreasurersCollectionsActivity.this);

        for (int index = 0; index < selectedTreasurerArrayList.size(); index++) {
            pm4WUser.logEvent(EventLogs.EVENT_APPROVED_TREASURER_COLLECTIONS, Long.parseLong(selectedTreasurerArrayList.get(index)));
            treasurers.approveSubmittedByCommiteeTreasurer(selectedTreasurerArrayList.get(index), submittedBy);
        }
        new AlertDialog.Builder(this)
                .setMessage(pm4WUser.language.SUBMISSION_SUCCESSFUL)
                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

            @Override
            public void onCancel(DialogInterface dialog) {
                new FetchCollections().execute();
            }
        }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                new FetchCollections().execute();
            }
        }).show();
    }

    public void denySubmitted(View v) {
        DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                switch (which) {
                    case DialogInterface.BUTTON_POSITIVE:
                        //Yes button clicked

                        int submittedBy = pm4WUser.getIdu();
                        Treasurers treasurers = new Treasurers(ListTreasurersCollectionsActivity.this);

                        for (int index = 0; index < selectedTreasurerArrayList.size(); index++) {
                            pm4WUser.logEvent(EventLogs.EVENT_DENIED_TREASURER_COLLECTIONS, Long.parseLong(selectedTreasurerArrayList.get(index)));
                            treasurers.denySubmittedByCommiteeTreasurer(selectedTreasurerArrayList.get(index), submittedBy);
                        }
                        new AlertDialog.Builder(ListTreasurersCollectionsActivity.this)
                                .setMessage(pm4WUser.language.SUBMISSION_CANCEL_SUCCESSFUL)
                                .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                new FetchCollections().execute();
                            }
                        }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialogInterface, int i) {
                                new FetchCollections().execute();
                            }
                        }).show();

                        break;

                    case DialogInterface.BUTTON_NEGATIVE:
                        //No button clicked
                        break;
                }
            }
        };

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage(pm4WUser.language.CANCEL_SUBMISSION_MESSAGE)
                .setPositiveButton(pm4WUser.language.YES, dialogClickListener)
                .setNegativeButton(pm4WUser.language.NO, dialogClickListener)
                .show();
    }


    class FetchCollections extends AsyncTask<String, String, String> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(ListTreasurersCollectionsActivity.this);
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListTreasurersCollectionsActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            try {
                treasurerCollectionsArrayList = treasurer.fetchTresurerCollections();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String s) {
            pDialog.dismiss();

            treasurersBaseAdapter = new TreasurersBaseAdapter(treasurerCollectionsArrayList);


            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    usersCount.setText(treasurerCollectionsArrayList.size() + "");
                    treasurersBaseAdapter.notifyDataSetChanged();
                    if (treasurerCollectionsArrayList.size() > 0) {
                        waterUsersList.setAdapter(treasurersBaseAdapter);
                    } else {
                        new AlertDialog.Builder(ListTreasurersCollectionsActivity.this)
                                .setMessage(pm4WUser.language.NO_SALES_AVAILABLE)
                                .setIcon(android.R.drawable.ic_dialog_alert).setOnCancelListener(new DialogInterface.OnCancelListener() {

                            @Override
                            public void onCancel(DialogInterface dialog) {
                                finish();
                            }
                        }).show();
                    }
                }

            });
        }

    }

    private class TreasurersBaseAdapter extends BaseAdapter {

        private final ArrayList<HashMap<String, String>> mData;

        public TreasurersBaseAdapter(ArrayList<HashMap<String, String>> customersList) {
            this.mData = customersList;
        }

        @Override
        public int getCount() {
            return mData.size();
        }

        @Override
        public Object getItem(int i) {
            return null;
        }

        @Override
        public long getItemId(int i) {
            return 0;
        }

        @Override
        public boolean isEnabled(int position) {
            return false;
        }

        @Override
        public View getView(int index, View convertView, ViewGroup viewGroup) {
            ViewHolder holder = null;
            if (convertView == null) {
                LayoutInflater vi = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                convertView = vi.inflate(R.layout.listitem_treasurers_checkbox, null);

                holder = new ViewHolder();
                holder.idu = (CheckBox) convertView.findViewById(R.id.idu);
                holder.water_source_name = (TextView) convertView.findViewById(R.id.waterSourceName);
                holder.savings = (TextView) convertView.findViewById(R.id.savings);
                convertView.setTag(holder);

                holder.idu.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {
                        CheckBox cb = (CheckBox) v;
                        String id_user = cb.getTag().toString();
                        if (cb.isChecked() && !selectedTreasurerArrayList.contains(id_user)) {
                            selectedTreasurerArrayList.add(id_user);
                        } else {
                            selectedTreasurerArrayList.remove(id_user);
                        }
                        RelativeLayout footer = (RelativeLayout) findViewById(R.id.footer);
                        if (selectedTreasurerArrayList.size() > 0) {
                            footer.setVisibility(View.VISIBLE);
                        } else {
                            footer.setVisibility(View.INVISIBLE);
                        }
                    }
                });
            } else {
                holder = (ViewHolder) convertView.getTag();
            }

            HashMap<String, String> water_user = mData.get(index);
            holder.idu.setTag(water_user.get(Constants.USER_IDU_TAG));
            holder.idu.setText(water_user.get(Constants.COMBINED_FNAME_LNAME_TAG));
            holder.idu.setChecked(false);
            holder.water_source_name.setText(water_user.get(Constants.WATER_SOURCE_NAME_TAG));
            double amount = Double.parseDouble(water_user.get(Constants.SAVINGS_TAG));
            holder.savings.setText(pm4WUser.language.CURRENCY_SYMBOL + " " + Utils.numberFormat(amount));
            return convertView;
        }

        private class ViewHolder {
            CheckBox idu;
            TextView water_source_name;
            TextView savings;
        }
    }

}
