package com.turtleplayer;

import java.util.ArrayList;
import java.util.Vector;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;

public class SurroundSoundRetrieve extends AsyncTask<String, Void, Boolean>{
	
	Context mContext = null;
	String queryKey = null;
	
	//Result data
	Vector<JSONObject> trackData = new Vector<JSONObject>();
	
	Exception exception = null;
	
	/* Valid strings for listToRetrieve
	 * 		GetCurrentPlaying
	 * 		GetTop10
	 */
	SurroundSoundRetrieve(Context context, String listToRetrieve){
		mContext = context;
		queryKey = listToRetrieve;
	}

	@Override
	protected Boolean doInBackground(String... arg0) {

		try{
			//Setup the parameters
			ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
			nameValuePairs.add(new BasicNameValuePair(queryKey, ""));

			//Create the HTTP request
			HttpParams httpParameters = new BasicHttpParams();

			//Setup timeouts
			HttpConnectionParams.setConnectionTimeout(httpParameters, 15000);
			HttpConnectionParams.setSoTimeout(httpParameters, 15000);

			HttpClient httpclient = new DefaultHttpClient(httpParameters);
			//IMPORTANT: This needs to be changed based on the IPV4 address of the database
			HttpPost httppost = new HttpPost("http://206.12.52.167/SurroundSound/SurroundSound.php");
			httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));        
			HttpResponse response = httpclient.execute(httppost);
			HttpEntity entity = response.getEntity();

			String result = EntityUtils.toString(entity);

			JSONArray array = new JSONArray(result);
			for(int i = 0; i < array.length(); i++) {
				trackData.add(array.getJSONObject(i));
			}
			
			for(int i = 0; i < trackData.size(); i++) {
				Log.d("ssdb", "Artist: " + trackData.get(i).getString("Artist"));
				Log.d("ssdb", "Track: " + trackData.get(i).getString("Track"));
			}
		}catch (Exception e){
			Log.e("SurroundSoundRetrieve", "Error:", e);
			exception = e;
		}
		return true;
	}

	@Override
	protected void onPostExecute(Boolean valid){
		//Update the UI

	}
}