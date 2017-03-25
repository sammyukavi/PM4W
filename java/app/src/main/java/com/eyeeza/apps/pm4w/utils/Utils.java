package com.eyeeza.apps.pm4w.utils;

import android.content.Context;
import android.util.Log;

import com.eyeeza.apps.pm4w.dbmanager.Constants;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.text.DateFormat;
import java.text.DecimalFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * Created by Sammy N Ukavi Jr on 4/27/2016.
 */
public class Utils {

    private static String fileName = "data.json";

    public Utils() {
    }

    public static void var_dump(Object obj) {
        Log.e("PM4W", "STARTING DUMPING OF OBJECT");
        System.out.println(obj);
        Log.e("PM4W", "FINISHED DUMPING OF OBJECT");
    }

    public static void var_dump(Object obj, Object obj1) {
        Log.e("PM4W", "STARTING DUMPING OF OBJECT");
        System.out.println(obj);
        System.out.println(obj1);
        Log.e("PM4W", "FINISHED DUMPING OF OBJECT");
    }

    public static void var_dump(Object obj, Object obj1, Object obj2) {
        Log.e("PM4W", "STARTING DUMPING OF OBJECT");
        System.out.println(obj);
        System.out.println(obj1);
        System.out.println(obj2);
        Log.e("PM4W", "FINISHED DUMPING OF OBJECT");
    }


    public static String getStringFromFile(Context context) {
        try {
            File f = new File(context.getFilesDir().getPath() + "/" + fileName);
            //check whether file exists
            FileInputStream is = new FileInputStream(f);
            int size = is.available();
            byte[] buffer = new byte[size];
            is.read(buffer);
            is.close();
            return new String(buffer);
        } catch (IOException e) {
            Log.e("TAG", "Error in Reading: " + e.getLocalizedMessage());
            return null;
        }
    }

    public static String getStringFromFile(Context context, String fileName) {
        try {
            File f = new File(context.getFilesDir().getPath() + "/" + fileName);
            //check whether file exists
            FileInputStream is = new FileInputStream(f);
            int size = is.available();
            byte[] buffer = new byte[size];
            is.read(buffer);
            is.close();
            return new String(buffer);
        } catch (IOException e) {
            Log.e("TAG", "Error in Reading: " + e.getLocalizedMessage());
            return null;
        }
    }

    public static void saveStringToFile(Context context, String string, String fileName) {
        try {
            FileWriter file = new FileWriter(context.getFilesDir().getPath() + "/" + fileName);
            file.write(string);
            file.flush();
            file.close();
        } catch (IOException e) {
            Log.e("TAG", "Error in Writing: " + e.getLocalizedMessage());
        }
    }

    public static String getMySQLDate() {
        java.util.Date dt = new java.util.Date();
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat(Constants.DATE_TIME_FORMAT);
        return sdf.format(dt);
    }

    public static boolean timeIsRelativelyValid(Context context) {
        boolean timeIsValid = false;
        try {
            SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
            String serverTimeString = Utils.getStringFromFile(context, Constants.SERVER_TIME + ".json");
            Date serverTime = formatter.parse(serverTimeString);
            Date currentTime = formatter.parse(getMySQLDate());
            if (serverTime.before(currentTime) || serverTime.equals(currentTime)) {
                timeIsValid = true;
            }
        } catch (Exception e1) {
            e1.printStackTrace();
        }
        return timeIsValid;
    }

    public static String numberFormat(double value) {
        DecimalFormat moneyFormat = new DecimalFormat("#,##0.00");
        return moneyFormat.format(value);
    }

    public static double numberFormat(double value, int places) {
        if (places < 0) throw new IllegalArgumentException();
        BigDecimal bd = new BigDecimal(value);
        bd = bd.setScale(places, RoundingMode.HALF_UP);
        return bd.doubleValue();
    }

    public static String formatDate(String date) {
        DateFormat mSDF = new SimpleDateFormat(Constants.DATE_TIME_FORMAT_3);
        SimpleDateFormat formatter = new SimpleDateFormat(Constants.DATE_TIME_FORMAT);
        try {
            return mSDF.format(formatter.parse(date));
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return "";
    }
}
