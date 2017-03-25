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
import com.eyeeza.apps.pm4w.dbtables.Caretakers;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Treasurers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class ListCareTakersCollectionsActivity extends Activity {


    private ArrayList<HashMap<String, String>> careTakerCollections = new ArrayList<HashMap<String, String>>();
    private ListView water_users_list;
    private TextView users_count;
    private Pm4wUser pm4WUser;
    private CareTakersAdapter careTakersAdapter = null;
    private ArrayList<String> selectedCaretakers = new ArrayList<>();
    private Caretakers caretaker;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listcaretakercollections);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.SUBMIT_CARETAKER_SALES);

        water_users_list = (ListView) findViewById(R.id.waterUsersList);
        users_count = (TextView) findViewById(R.id.usersCount);

        pm4WUser.logEvent(EventLogs.EVENT_LISTED_CARETAKER_SALES);

        caretaker = new Caretakers(ListCareTakersCollectionsActivity.this);
        new FetchCollections().execute();

        Button submit_savings_button = (Button) findViewById(R.id.submit_savings_button);
        submit_savings_button.setText(pm4WUser.language.SUBMIT_SALES);

        if (pm4WUser.CAN_APPROVE_ATTENDANTS_SUBMISSIONS) {
            submit_savings_button.setVisibility(View.VISIBLE);
        }

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new FetchCollections().execute();
    }

    public void markSubmitted(View v) {
        int submittedBy = pm4WUser.getIdu();
        Treasurers treasurers = new Treasurers(ListCareTakersCollectionsActivity.this);
        for (int index = 0; index < selectedCaretakers.size(); index++) {
            pm4WUser.logEvent(EventLogs.EVENT_SUBMITTED_CARETAKER_SALES, Long.parseLong(selectedCaretakers.get(index)));
            treasurers.markSubmittedByCommiteeTreasurer(selectedCaretakers.get(index), submittedBy);
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

    class FetchCollections extends AsyncTask<String, String, String> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(ListCareTakersCollectionsActivity.this);
            pDialog.setTitle(pm4WUser.language.PLEASE_WAIT);
            pDialog.setMessage(pm4WUser.language.SENDING_DATA);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListCareTakersCollectionsActivity.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... args) {
            careTakerCollections = caretaker.fetchCareTakerCollections();
            return null;
        }

        @Override
        protected void onPostExecute(String s) {
            pDialog.dismiss();
            careTakersAdapter = new CareTakersAdapter(careTakerCollections);
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    users_count.setText(careTakerCollections.size() + "");

                    careTakersAdapter.notifyDataSetChanged();

                    if (careTakerCollections.size() > 0) {

                        water_users_list.setAdapter(careTakersAdapter);
                    } else {
                        new AlertDialog.Builder(ListCareTakersCollectionsActivity.this)
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

    private class CareTakersAdapter extends BaseAdapter {

        private final ArrayList<HashMap<String, String>> mData;

        public CareTakersAdapter(ArrayList<HashMap<String, String>> customersList) {
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
                convertView = vi.inflate(R.layout.listitem_caretakers_checkbox, null);

                holder = new ViewHolder();
                holder.idu = (CheckBox) convertView.findViewById(R.id.idu);
                holder.water_source_name = (TextView) convertView.findViewById(R.id.waterSourceName);
                holder.savings = (TextView) convertView.findViewById(R.id.savings);
                convertView.setTag(holder);

                holder.idu.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {
                        CheckBox cb = (CheckBox) v;
                        String id_user = cb.getTag().toString();
                        if (cb.isChecked() && !selectedCaretakers.contains(id_user)) {
                            selectedCaretakers.add(id_user);
                        } else {
                            selectedCaretakers.remove(id_user);
                        }
                        RelativeLayout footer = (RelativeLayout) findViewById(R.id.footer);
                        if (selectedCaretakers.size() > 0) {
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
