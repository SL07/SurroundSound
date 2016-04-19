package com.turtleplayer;

import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;

import com.turtleplayer.model.Track;

public class SurroundSoundUpdate extends AsyncTask<String, Void, Boolean>{

	Context mContext = null;
	Track trackToBeUpdated = null;
	String queryType = null;
	
	Exception exception = null;
	
	SurroundSoundUpdate(Context context, Track trackInput, String addOrRemove){
		mContext = context;
		trackToBeUpdated = trackInput;
		queryType = addOrRemove;
	}

	@Override
	protected Boolean doInBackground(String... arg0) {

		try{
			String trackName = trackToBeUpdated.getSongName();
			String artistName = trackToBeUpdated.getArtistName();
			String trackKey = null;
			String artistKey = null;
			if(queryType == "add") {
				trackKey = "Add_Track";
				artistKey = "Add_Artist";
			} else if(queryType == "remove") {
				trackKey = "Remove_Track";
				artistKey = "Remove_Artist";
			} else {
				Log.e("SurroundSoundUpdate", "Error: Invalid queryType");
			}
			
			//Setup the parameters
			ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
			nameValuePairs.add(new BasicNameValuePair(trackKey, trackName));
			nameValuePairs.add(new BasicNameValuePair(artistKey, artistName));

			//Create the HTTP request
			HttpParams httpParameters = new BasicHttpParams();

			//Setup timeouts
			HttpConnectionParams.setConnectionTimeout(httpParameters, 15000);
			HttpConnectionParams.setSoTimeout(httpParameters, 15000);

			HttpClient httpclient = new DefaultHttpClient(httpParameters);
			//IMPORTANT: This needs to be changed based on the IPV4 address of the database
			HttpPost httppost = new HttpPost("http://206.12.52.167/SurroundSound/SurroundSound.php");
			httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));        
			httpclient.execute(httppost);
		}catch (Exception e){
			Log.e("SurroundSoundUpdate", "Error:", e);
			exception = e;
		}
		return true;
	}
}