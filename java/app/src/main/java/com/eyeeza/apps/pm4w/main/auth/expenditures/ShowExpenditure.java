package com.eyeeza.apps.pm4w.main.auth.expenditures;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.eyeeza.apps.pm4w.R;
import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbtables.EventLogs;
import com.eyeeza.apps.pm4w.dbtables.Expenditures;
import com.eyeeza.apps.pm4w.user.Pm4wUser;
import com.eyeeza.apps.pm4w.utils.Utils;

/**
 * Created by Sammy N Ukavi Jr on 5/9/2016.
 */
public class ShowExpenditure extends Activity {
    private Pm4wUser pm4WUser;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_showexpenditure);

        pm4WUser = new Pm4wUser(this);
        pm4WUser.getSesssionAccount(this);
        pm4WUser.getAccountPermissions(pm4WUser.getGroupId());
        pm4WUser.loadLanguage(pm4WUser.getAppPreferredLanguage());

        TextView formName = (TextView) findViewById(R.id.formName);
        formName.setText(pm4WUser.language.EXPENDITURES);

        TextView waterSourcePromptTextview = (TextView) findViewById(R.id.waterSourcePromptTextview);
        waterSourcePromptTextview.setText(pm4WUser.language.WATER_SOURCE);

        TextView repairTypesPromptTextview = (TextView) findViewById(R.id.repairTypesPromptTextview);
        repairTypesPromptTextview.setText(pm4WUser.language.REPAIR_TYPE);

        TextView expenditureCostTextview = (TextView) findViewById(R.id.expenditureCostTextview);
        expenditureCostTextview.setText(pm4WUser.language.EXPENDITURE_COST);

        TextView benefactorTextview = (TextView) findViewById(R.id.benefactorTextview);
        benefactorTextview.setText(pm4WUser.language.BENEFACTOR);

        TextView descriptionTextview = (TextView) findViewById(R.id.descriptionTextview);
        descriptionTextview.setText(pm4WUser.language.DESCRIPTION);

        TextView addedByTextview = (TextView) findViewById(R.id.addedByTextview);
        addedByTextview.setText(pm4WUser.language.ADDED_BY);

        TextView dateAddedTextview = (TextView) findViewById(R.id.dateAddedTextview);
        dateAddedTextview.setText(pm4WUser.language.DATE_ADDED);

        Button editExpenditure = (Button) findViewById(R.id.editExpenditure);
        editExpenditure.setText(pm4WUser.language.EDIT);
        Button deleteExpenditure = (Button) findViewById(R.id.deleteExpenditure);
        deleteExpenditure.setText(pm4WUser.language.DELETE);

        if (pm4WUser.CAN_EDIT_EXPENSES) {
            //editExpenditure.setVisibility(View.VISIBLE);
        }

        if (pm4WUser.CAN_DELETE_EXPENSES) {
            //deleteExpenditure.setVisibility(View.VISIBLE);
        }

        Expenditures expenditure = new Expenditures(this);
        Long expenditureId = Long.parseLong(getIntent().getStringExtra(Constants.ID_EXPENDITURE_TAG));
        expenditure.getExpenditure(expenditureId);

        TextView waterSource = (TextView) findViewById(R.id.waterSource);
        waterSource.setText(expenditure.getWaterSourceName());

        TextView repairType = (TextView) findViewById(R.id.repairType);
        repairType.setText(expenditure.getRepairType());

        TextView expenditureCost = (TextView) findViewById(R.id.expenditureCost);
        expenditureCost.setText(Utils.numberFormat(expenditure.getExpenditureCost()));

        TextView benefactor = (TextView) findViewById(R.id.benefactor);
        benefactor.setText(expenditure.getBenefactor());

        TextView description = (TextView) findViewById(R.id.description);
        description.setText(expenditure.getDescription());

        TextView addedBy = (TextView) findViewById(R.id.addedBy);
        addedBy.setText(expenditure.getAddedBy());

        TextView dateAdded = (TextView) findViewById(R.id.dateAdded);
        dateAdded.setText(Utils.formatDate(expenditure.getExpenditureDate()));

        pm4WUser.logEvent(EventLogs.EVENT_VIEWED_EXPENSE, expenditureId);

    }

    public void editExpenditure(View view) {

    }

    public void deleteExpenditure(View view) {

    }
}
