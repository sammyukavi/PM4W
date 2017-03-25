package com.eyeeza.apps.pm4w.main.auth.waterusers;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.WaterUsers;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

import java.util.ArrayList;
import java.util.HashMap;


public class ListWaterUsers extends Activity {

    private int userId = 0;
    private RelativeLayout footer;
    private TextView users_count;
    private Pm4wUser pm4WUser;
    private ArrayList<HashMap<String, String>> customersList = new ArrayList<HashMap<String, String>>();
    private ListView waterUsersList;
    private WaterUsersAdapter waterUsersAdapter = null;
    private WaterUsers waterUsers;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listwaterusers);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());
        waterUsers = new WaterUsers(this);


        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.MONTHLY_WATER_USER);

        if (pm4WUser.CAN_EDIT_WATER_USERS) {
            Button edit_button = (Button) findViewById(R.id.edit_button);
            edit_button.setText(pm4WUser.language.EDIT);
            edit_button.setVisibility(View.VISIBLE);
        }
        if (pm4WUser.CAN_DELETE_WATER_USERS) {
            Button delete_button = (Button) findViewById(R.id.delete_button);
            delete_button.setText(pm4WUser.language.DELETE);
            delete_button.setVisibility(View.VISIBLE);
        }

        users_count = (TextView) findViewById(R.id.usersCount);
        waterUsersList = (ListView) findViewById(R.id.waterUsersList);
        footer = (RelativeLayout) findViewById(R.id.footer);

        pm4WUser.logEvent(EventLogs.EVENT_LISTED_WATER_USERS);

        new fetchWaterUsers().execute();

    }

    @Override
    protected void onRestart() {
        super.onRestart();
        new fetchWaterUsers().execute();
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());
    }

    @Override
    protected void onResume() {
        super.onResume();
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());
    }

    public void launchEditor(View view) {

        if (userId == 0) {
            new AlertDialog.Builder(ListWaterUsers.this)
                    .setMessage(pm4WUser.language.SELECT_WATER_USER_TO_EDIT)
                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

                @Override
                public void onCancel(DialogInterface dialog) {

                }
            }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {

                }
            }).show();
            return;
        }

        if (!pm4WUser.CAN_EDIT_WATER_USERS) {
            new AlertDialog.Builder(ListWaterUsers.this)
                    .setTitle(pm4WUser.language.INFO)
                    .setMessage(pm4WUser.language.ACTION_DISABLED)
                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

                @Override
                public void onCancel(DialogInterface dialog) {
                    finish();
                }
            }).show();
        } else {
            Intent editwateruser = new Intent(getApplicationContext(), EditWaterUser.class);
            editwateruser.putExtra(Constants.WATER_USER_ID_TAG, userId);
            startActivity(editwateruser);
        }
    }

    public void markDeleted(View view) {
        if (userId == 0) {
            new AlertDialog.Builder(this)
                    .setMessage(pm4WUser.language.SELECT_WATER_USER_TO_DELETE)
                    .setIcon(android.R.drawable.ic_dialog_info).setOnCancelListener(new DialogInterface.OnCancelListener() {

                @Override
                public void onCancel(DialogInterface dialog) {
                    return;
                }
            }).setPositiveButton(pm4WUser.language.OK, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {
                    return;
                }
            }).show();
        } else {
            DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    switch (which) {
                        case DialogInterface.BUTTON_POSITIVE:
                            //Yes button clicked
                            WaterUsers waterUser = new WaterUsers(ListWaterUsers.this);
                            waterUser.getWaterUser(userId);
                            waterUser.setMarkedForDelete(1);
                            waterUser.setLastUpdated(Utils.getMySQLDate());
                            long id = waterUser.saveWaterUser();
                            pm4WUser.logEvent(EventLogs.EVENT_DELETED_WATER_USER,waterUser.getIdUser());
                            new fetchWaterUsers().execute();
                            break;

                        case DialogInterface.BUTTON_NEGATIVE:
                            //No button clicked
                            break;
                    }
                }
            };

            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setMessage(pm4WUser.language.DELETE_WATER_USER_MSG)
                    .setPositiveButton(pm4WUser.language.DELETE, dialogClickListener)
                    .setNegativeButton(pm4WUser.language.CANCEL, dialogClickListener)
                    .show();
        }
    }

    class fetchWaterUsers extends AsyncTask<String, String, String> {
        ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            pDialog = new ProgressDialog(ListWaterUsers.this);
            pDialog.setMessage(pm4WUser.language.PLEASE_WAIT);
            pDialog.setIndeterminate(true);
            pDialog.setCancelable(false);
            pDialog.setButton(pm4WUser.language.CANCEL, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    ListWaterUsers.this.finish();
                }
            });
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... strings) {
            customersList = waterUsers.getAllWaterUsers();
            return null;
        }

        @Override
        protected void onPostExecute(String file_url) {
            userId = 0;
            pDialog.dismiss();
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    users_count.setText(customersList.size() + "");
                    if (customersList.size() > 0) {
                        waterUsersAdapter = new WaterUsersAdapter(customersList);
                        waterUsersList.setAdapter(waterUsersAdapter);
                    } else {
                        new AlertDialog.Builder(ListWaterUsers.this)
                                .setMessage(pm4WUser.language.NO_CUSTOMERS_ON_MONTHLY_BILLING)
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


    private class WaterUsersAdapter extends BaseAdapter {

        private final ArrayList<HashMap<String, String>> mData;
        private int mSelectedPosition = -1;
        private RadioButton mSelectedRB;

        public WaterUsersAdapter(ArrayList<HashMap<String, String>> customersList) {
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
        public View getView(final int index, View convertView, final ViewGroup viewGroup) {
            ViewHolder holder = null;
            if (convertView == null) {
                LayoutInflater vi = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                convertView = vi.inflate(R.layout.listitem_waterusers_radio, null);

                holder = new ViewHolder();
                holder.user_id = (RadioButton) convertView.findViewById(R.id.userId);
                holder.user_name = (TextView) convertView.findViewById(R.id.username);
                convertView.setTag(holder);

                holder.user_id.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {
                        RadioButton cb = (RadioButton) v;
                        String id_user = cb.getTag().toString();
                        userId = Integer.parseInt(id_user);
                        if (index != mSelectedPosition && mSelectedRB != null) {
                            mSelectedRB.setChecked(false);
                        }

                        mSelectedPosition = index;
                        mSelectedRB = (RadioButton) v;
                    }
                });

                if (mSelectedPosition != index) {
                    holder.user_id.setChecked(false);
                } else {
                    holder.user_id.setChecked(true);
                    if (mSelectedRB != null && holder.user_id != mSelectedRB) {
                        mSelectedRB = holder.user_id;
                    }
                }
            } else {
                holder = (ViewHolder) convertView.getTag();
            }

            HashMap<String, String> water_user = mData.get(index);
            holder.user_id.setTag(water_user.get(Constants.WATER_USER_ID_TAG));
            holder.user_id.setText(water_user.get(Constants.COMBINED_FNAME_LNAME_TAG));
            holder.user_id.setChecked(false);
            return convertView;
        }

        private class ViewHolder {
            RadioButton user_id;
            TextView user_name;
        }
    }
}
