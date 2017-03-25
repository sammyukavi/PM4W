package com.eyeeza.apps.pm4w.dbtables;

import android.content.ContentValues;
import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

import com.eyeeza.apps.pm4w.dbmanager.Constants;
import com.eyeeza.apps.pm4w.dbmanager.DBoperations;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by sammy-n-ukavi-jr on 7/27/15.
 */
public class Sales extends DBoperations {
    private Context context;
    private int idSale = 0;
    private int waterSourceID;
    private int soldBy;
    private long soldTo;
    private double saleUgx;
    private String saleDate;
    private double percentageSaved;
    private int submittedToTreasurer;
    private int submittedBy;
    private String submissionToTreasuerDate;
    private int treasurerApprovalStatus;
    private int reviewedBy;
    private String dateReviewed;
    private int markedForDelete;
    private String dateCreated;
    private String lastUpdated;

    public Sales(Context context) {
        super(context);
        this.context = context;
    }

    public int getIdSale() {
        return idSale;
    }

    public void setIdSale(int idSale) {
        this.idSale = idSale;
    }

    public int getWaterSourceID() {
        return waterSourceID;
    }

    public void setWaterSourceID(int waterSourceID) {
        this.waterSourceID = waterSourceID;
    }

    public long getSoldBy() {
        return soldBy;
    }

    public void setSoldBy(int soldBy) {
        this.soldBy = soldBy;
    }

    public long getSoldTo() {
        return soldTo;
    }

    public void setSoldTo(long soldTo) {
        this.soldTo = soldTo;
    }

    public double getSaleUgx() {
        return saleUgx;
    }

    public void setSaleUgx(double saleUgx) {
        this.saleUgx = saleUgx;
    }

    public String getSaleDate() {
        return saleDate;
    }

    public void setSaleDate(String saleDate) {
        this.saleDate = saleDate;
    }

    public double getPercentageSaved() {
        return percentageSaved;
    }

    public void setPercentageSaved(double percentageSaved) {
        this.percentageSaved = percentageSaved;
    }

    public int getSubmittedToTreasurer() {
        return submittedToTreasurer;
    }

    public void setSubmittedToTreasurer(int submittedToTreasurer) {
        this.submittedToTreasurer = submittedToTreasurer;
    }

    public int getSubmittedBy() {
        return submittedBy;
    }

    public void setSubmittedBy(int submittedBy) {
        this.submittedBy = submittedBy;
    }

    public String getSubmissionToTreasuerDate() {
        return submissionToTreasuerDate;
    }

    public void setSubmissionToTreasuerDate(String submissionToTreasuerDate) {
        this.submissionToTreasuerDate = submissionToTreasuerDate;
    }

    public int getTreasurerApprovalStatus() {
        return treasurerApprovalStatus;
    }

    public void setTreasurerApprovalStatus(int treasurerApprovalStatus) {
        this.treasurerApprovalStatus = treasurerApprovalStatus;
    }

    public int getReviewedBy() {
        return reviewedBy;
    }

    public void setReviewedBy(int reviewedBy) {
        this.reviewedBy = reviewedBy;
    }

    public String getDateReviewed() {
        return dateReviewed;
    }

    public void setDateReviewed(String dateReviewed) {
        this.dateReviewed = dateReviewed;
    }

    public int getMarkedForDelete() {
        return markedForDelete;
    }

    public void setMarkedForDelete(int markedForDelete) {
        this.markedForDelete = markedForDelete;
    }

    public String getDateCreated() {
        return dateCreated;
    }

    public void setDateCreated(String dateCreated) {
        this.dateCreated = dateCreated;
    }

    public String getLastUpdated() {
        return lastUpdated;
    }

    public void setLastUpdated(String lastUpdated) {
        this.lastUpdated = lastUpdated;
    }

    public long saveSale(JSONObject sale) throws JSONException {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(Constants.ID_SALE_TAG, sale.getInt(Constants.ID_SALE_TAG));
        values.put(Constants.SALE_WATER_SOURCE_ID_TAG, sale.getString(Constants.SALE_WATER_SOURCE_ID_TAG));
        values.put(Constants.SOLD_BY_TAG, sale.getString(Constants.SOLD_BY_TAG));
        values.put(Constants.SOLD_TO_TAG, sale.getString(Constants.SOLD_TO_TAG));
        values.put(Constants.SALE_UGX_TAG, sale.getString(Constants.SALE_UGX_TAG));
        values.put(Constants.SALE_DATE_TAG, sale.getString(Constants.SALE_DATE_TAG));
        values.put(Constants.PERCENTAGE_SAVED_TAG, sale.getString(Constants.PERCENTAGE_SAVED_TAG));
        values.put(Constants.SUBMITTED_TO_TREASURER_TAG, sale.getString(Constants.SUBMITTED_TO_TREASURER_TAG));
        values.put(Constants.SUBMITTED_BY_TAG, sale.getString(Constants.SUBMITTED_BY_TAG));
        values.put(Constants.SUBMISSION_TO_TREASURER_DATE_TAG, sale.getString(Constants.SUBMISSION_TO_TREASURER_DATE_TAG));
        values.put(Constants.TREASURERER_APPROVAL_STATUS_TAG, sale.getString(Constants.TREASURERER_APPROVAL_STATUS_TAG));
        values.put(Constants.REVIEWED_BY_TAG, sale.getString(Constants.REVIEWED_BY_TAG));
        values.put(Constants.DATE_REVIEWED_TAG, sale.getString(Constants.DATE_REVIEWED_TAG));
        values.put(Constants.SALE_MARKED_FOR_DELETE_TAG, sale.getString(Constants.SALE_MARKED_FOR_DELETE_TAG));
        values.put(Constants.DATE_CREATED_TAG, sale.getString(Constants.DATE_CREATED_TAG));
        values.put(Constants.LAST_UPDATED_TAG, sale.getString(Constants.LAST_UPDATED_TAG));

        long no = db.insertWithOnConflict(Constants.SALES_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }

    public long saveSale() {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        if (idSale != 0) {
            values.put(Constants.ID_SALE_TAG, idSale);
        }
        values.put(Constants.SALE_WATER_SOURCE_ID_TAG, waterSourceID);
        values.put(Constants.SOLD_BY_TAG, soldBy);
        values.put(Constants.SOLD_TO_TAG, soldTo);
        values.put(Constants.SALE_UGX_TAG, saleUgx);
        values.put(Constants.SALE_DATE_TAG, saleDate);
        values.put(Constants.PERCENTAGE_SAVED_TAG, percentageSaved);
        values.put(Constants.SUBMITTED_TO_TREASURER_TAG, submittedToTreasurer);
        values.put(Constants.SUBMITTED_BY_TAG, submittedBy);
        values.put(Constants.SUBMISSION_TO_TREASURER_DATE_TAG, submissionToTreasuerDate);
        values.put(Constants.TREASURERER_APPROVAL_STATUS_TAG, treasurerApprovalStatus);
        values.put(Constants.REVIEWED_BY_TAG, reviewedBy);
        values.put(Constants.DATE_REVIEWED_TAG, dateReviewed);
        values.put(Constants.SALE_MARKED_FOR_DELETE_TAG, markedForDelete);
        values.put(Constants.DATE_CREATED_TAG, dateCreated);
        values.put(Constants.LAST_UPDATED_TAG, lastUpdated);

        long no = db.insertWithOnConflict(Constants.SALES_TABLENAME, "", values, SQLiteDatabase.CONFLICT_REPLACE);
        db.close();
        return no;
    }


}