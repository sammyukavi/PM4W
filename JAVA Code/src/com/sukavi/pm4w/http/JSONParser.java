package com.sukavi.pm4w.http;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import com.sukavi.pm4w.config.Config;

import android.util.Log;

public class JSONParser {

    public final String API_URL = Config.API_URL;
    static InputStream is = null;
    static JSONObject jObj = null;
    static String json = ""; 
    public JSONParser() {

    } 
    public JSONObject makeHttpRequest(String url, String method,List<NameValuePair> params) { 
	String requestUrl = API_URL+url;		
	try { 
	    if(method == "POST"){             
		DefaultHttpClient httpClient = new DefaultHttpClient();
		HttpPost httpPost = new HttpPost(requestUrl);
		httpPost.setHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
		httpPost.setEntity(new UrlEncodedFormEntity(params));
		HttpResponse httpResponse = httpClient.execute(httpPost);
		HttpEntity httpEntity = httpResponse.getEntity();
		is = httpEntity.getContent(); 
	    }else if(method == "GET"){             
		DefaultHttpClient httpClient = new DefaultHttpClient();
		String paramString = URLEncodedUtils.format(params, "utf-8");
		requestUrl += "?" + paramString;
		HttpGet httpGet = new HttpGet(requestUrl); 
		HttpResponse httpResponse = httpClient.execute(httpGet);
		HttpEntity httpEntity = httpResponse.getEntity();
		is = httpEntity.getContent();
	    }           
	    System.out.println("URL is: "+requestUrl);
	} catch (UnsupportedEncodingException e) {
	    e.printStackTrace();
	} catch (ClientProtocolException e) {
	    e.printStackTrace();
	} catch (IOException e) {
	    e.printStackTrace();
	}

	try {
	    BufferedReader reader = new BufferedReader(new InputStreamReader(is, "iso-8859-1"), 8);
	    StringBuilder sb = new StringBuilder();
	    String line = null;
	    while ((line = reader.readLine()) != null) {
		sb.append(line + "\n");
	    }
	    is.close();
	    json = sb.toString();
	} catch (Exception e) {
	    Log.e("Buffer ERROR", "ERROR converting result " + e.toString());
	}


	try {
	    jObj = new JSONObject(json);
	} catch (JSONException e) {
	    Log.e("JSON Parser", "ERROR parsing data " + e.toString());
	}


	return jObj;

    }
}